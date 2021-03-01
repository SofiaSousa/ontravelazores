<?php

/******************************************************************************/
/******************************************************************************/

class CRBSEmailAccount
{
	/**************************************************************************/
	
    function __construct()
    {
		$this->secureConnectionType=array
		(
			'none'																=>	array(__('- None -','car-rental-booking-system')),
			'ssl'																=>	array(__('SSL','car-rental-booking-system')),
			'tls'																=>	array(__('TLS','car-rental-booking-system')),
		);
    }
	
	/**************************************************************************/
	
	function isSecureConnectionType($name)
	{
		return(array_key_exists($name,$this->getSecureConnectionType()) ? true : false);
	}
	
	/**************************************************************************/
	
	function getSecureConnectionType()
	{
		return($this->secureConnectionType);
	}
    
    /**************************************************************************/
    
    public function init()
    {
        $this->registerCPT();
    }
    
	/**************************************************************************/

    public static function getCPTName()
    {
        return(PLUGIN_CRBS_CONTEXT.'_email_account');
    }
        
    /**************************************************************************/
    
    private function registerCPT()
    {
		register_post_type
		(
			self::getCPTName(),
			array
			(
				'labels'														=>	array
				(
					'name'														=>	__('E-mail Accounts','car-rental-booking-system'),
					'singular_name'												=>	__('E-mail Accounts','car-rental-booking-system'),
					'add_new'													=>	__('Add New','car-rental-booking-system'),
					'add_new_item'												=>	__('Add New E-mail Account','car-rental-booking-system'),
					'edit_item'													=>	__('Edit E-mail Account','car-rental-booking-system'),
					'new_item'													=>	__('New E-mail Account','car-rental-booking-system'),
					'all_items'													=>	__('E-mail Accounts','car-rental-booking-system'),
					'view_item'													=>	__('View E-mail Account','car-rental-booking-system'),
					'search_items'												=>	__('Search E-mail Accounts','car-rental-booking-system'),
					'not_found'													=>	__('No E-mail Accounts Found','car-rental-booking-system'),
					'not_found_in_trash'										=>	__('No E-mail Accounts in Trash','car-rental-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('E-mail Accounts','car-rental-booking-system')
				),	
				'public'														=>	false,  
				'show_ui'														=>	true, 
				'show_in_menu'													=>	'edit.php?post_type='.CRBSBooking::getCPTName(),
				'capability_type'												=>	'post',
				'menu_position'													=>	2,
				'hierarchical'													=>	false,  
				'rewrite'														=>	false,  
				'supports'														=>	array('title')  
			)
		);
        
        add_action('save_post',array($this,'savePost'));
        add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
        add_filter('postbox_classes_'.self::getCPTName().'_crbs_meta_box_email_account',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }

    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CRBS_CONTEXT.'_meta_box_email_account',__('Main','car-rental-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
        
		$data=array();
        
        $data['meta']=CRBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CRBSHelper::createNonceField(PLUGIN_CRBS_CONTEXT.'_meta_box_email_account');
        
        $data['dictionary']['secure_connection_type']=$this->secureConnectionType;
        
		$Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'admin/meta_box_email_account.php');
		echo $Template->output();	        
    }
    
     /**************************************************************************/
    
    function adminCreateMetaBoxClass($class) 
    {
        array_push($class,'to-postbox-1');
        return($class);
    }

	/**************************************************************************/
	
	function setPostMetaDefault(&$meta)
	{
        CRBSHelper::setDefault($meta,'sender_name','');
        CRBSHelper::setDefault($meta,'sender_email_address','');
        
        CRBSHelper::setDefault($meta,'smtp_auth_enable','0');
        CRBSHelper::setDefault($meta,'smtp_auth_username','');
        CRBSHelper::setDefault($meta,'smtp_auth_password','');
        CRBSHelper::setDefault($meta,'smtp_auth_host','');
        CRBSHelper::setDefault($meta,'smtp_auth_port','');
        CRBSHelper::setDefault($meta,'smtp_auth_secure_connection_type','none');
        CRBSHelper::setDefault($meta,'smtp_auth_debug_enable','0');
	}
    
    /**************************************************************************/
    
    function savePost($postId)
    {      
        if(!$_POST) return(false);
        
        if(CRBSHelper::checkSavePost($postId,PLUGIN_CRBS_CONTEXT.'_meta_box_email_account_noncename','savePost')===false) return(false);
        
        $Validation=new CRBSValidation();
        
        $option=CRBSHelper::getPostOption();

		if(!$Validation->isBool($option['smtp_auth_enable']))
			$option['smtp_auth_enable']=0;
		
		if(!$this->isSecureConnectionType($option['smtp_auth_secure_connection_type']))
			$option['smtp_auth_secure_connection_type']='none';
		
		if(!$Validation->isBool($option['smtp_auth_debug_enable']))
			$option['smtp_auth_debug_enable']=0;
		
		$field=array
		(
			'sender_name',
			'sender_email_address',
			'smtp_auth_enable',
			'smtp_auth_username',
			'smtp_auth_password',
			'smtp_auth_host',
			'smtp_auth_port',
			'smtp_auth_secure_connection_type',
			'smtp_auth_debug_enable'
		);

		foreach($field as $value)
			CRBSPostMeta::updatePostMeta($postId,$value,$option[$value]);
    }
    
    /**************************************************************************/
    
    function getDictionary($attr=array())
    {
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'email_account_id'    												=>	0
		);
		
		$attribute=shortcode_atts($default,$attr);
		CRBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'															=>	self::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1,
			'orderby'															=>	array('title'=>'asc')
		);
		
		if($attribute['email_account_id'])
			$argument['p']=$attribute['email_account_id'];

		$query=new WP_Query($argument);
		if($query===false) return($dictionary);
		
		while($query->have_posts())
		{
			$query->the_post();
			$dictionary[$post->ID]['post']=$post;
			$dictionary[$post->ID]['meta']=CRBSPostMeta::getPostMeta($post);
		}
		
		CRBSHelper::preservePost($post,$bPost,0);	
		
		return($dictionary);        
    }
    
    /**************************************************************************/
    
    function manageEditColumns($column)
    {
        $column=array
        (
            'cb'                                                                =>  $column['cb'],
            'title'                                                             =>  $column['title'],
            'sender'                                                            =>  __('Sender','car-rental-booking-system'),
            'date'                                                              =>  $column['date']
        );
   
		return($column);          
    }
    
    /**************************************************************************/
    
    function managePostsCustomColumn($column)
    {
		global $post;
		
		$meta=CRBSPostMeta::getPostMeta($post);
        
        $Validation=new CRBSValidation();
        
		switch($column) 
		{
			case 'sender':
				
                $html=null;
                
                if($Validation->isNotEmpty($meta['sender_name']))
                    $html.=esc_html($meta['sender_name']);
                
                if($Validation->isNotEmpty($meta['sender_email_address']))
                    $html.=' <a href="mailto:'.esc_attr($meta['sender_email_address']).'">&lt;'.$meta['sender_email_address'].'&gt;</a>';
                
                echo trim($html);
                
			break;
        }
    }
    
    /**************************************************************************/
    
    function manageEditSortableColumns($column)
    {
		return($column);       
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/