<?php

/******************************************************************************/
/******************************************************************************/

class CRBSTaxRate
{
	/**************************************************************************/
	
    function __construct()
    {

    }
    
    /**************************************************************************/
    
    public function init()
    {
        $this->registerCPT();
    }
    
	/**************************************************************************/

    public static function getCPTName()
    {
        return(PLUGIN_CRBS_CONTEXT.'_tax_rate');
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
					'name'														=>	__('Tax Rates','car-rental-booking-system'),
					'singular_name'												=>	__('Tax Rates','car-rental-booking-system'),
					'add_new'													=>	__('Add New','car-rental-booking-system'),
					'add_new_item'												=>	__('Add New Tax Rate','car-rental-booking-system'),
					'edit_item'													=>	__('Edit Tax Rate','car-rental-booking-system'),
					'new_item'													=>	__('New Tax Rate','car-rental-booking-system'),
					'all_items'													=>	__('Tax Rates','car-rental-booking-system'),
					'view_item'													=>	__('View Tax Rate','car-rental-booking-system'),
					'search_items'												=>	__('Search Tax Rates','car-rental-booking-system'),
					'not_found'													=>	__('No Tax Rates Found','car-rental-booking-system'),
					'not_found_in_trash'										=>	__('No Tax Rates in Trash','car-rental-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('Tax Rates','car-rental-booking-system')
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
        add_filter('postbox_classes_'.self::getCPTName().'_crbs_meta_box_tax_rate',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }

    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CRBS_CONTEXT.'_meta_box_tax_rate',__('Main','car-rental-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
        
		$data=array();
        
        $data['meta']=CRBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CRBSHelper::createNonceField(PLUGIN_CRBS_CONTEXT.'_meta_box_tax_rate');
        
		$Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'admin/meta_box_tax_rate.php');
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
		CRBSHelper::setDefault($meta,'tax_rate_value','23.00');
        CRBSHelper::setDefault($meta,'tax_rate_default','0');
	}
    
    /**************************************************************************/
    
    function savePost($postId)
    {      
        if(!$_POST) return(false);
        
        if(CRBSHelper::checkSavePost($postId,PLUGIN_CRBS_CONTEXT.'_meta_box_tax_rate_noncename','savePost')===false) return(false);
        
		$meta=array();

        $Validation=new CRBSValidation();
        
		$this->setPostMetaDefault($meta);
        
        $meta['tax_rate_value']=CRBSHelper::getPostValue('tax_rate_value');
        if(!$Validation->isFloat($meta['tax_rate_value'],0,100))
            $meta['tax_rate_value']=23.00;
		
		$meta['tax_rate_value']=CRBSPrice::formatToSave($meta['tax_rate_value']);
        
        $meta['tax_rate_default']=CRBSHelper::getPostValue('tax_rate_default');
        if(!$Validation->isBool($meta['tax_rate_default']))
            $meta['tax_rate_default']=0;
        
        /***/
        
        if($meta['tax_rate_default']==1)
        {
            $id=$this->getDefaultTaxPostId();
            if($id!=0) CRBSPostMeta::updatePostMeta($id,'tax_rate_default',0);
        }
        
        /***/
        
		foreach($meta as $index=>$value)
			CRBSPostMeta::updatePostMeta($postId,$index,$value);
    }
    
    /**************************************************************************/
    
    function getDictionary($attr=array())
    {
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'tax_rate_id'         												=>	0
		);
		
		$attribute=shortcode_atts($default,$attr);
		CRBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'															=>  self::getCPTName(),
			'post_status'														=>  'publish',
			'posts_per_page'													=>  -1,
            'meta_key'                                                          =>  PLUGIN_CRBS_CONTEXT.'_tax_rate_value',
            'orderby'                                                           =>  'meta_value_num',
            'order'                                                             =>  'asc'
		);
		
		if($attribute['tax_rate_id'])
			$argument['p']=$attribute['tax_rate_id'];

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
    
    function getDefaultTaxPostId($taxRate=null)
    {
        if(is_null($taxRate))
            $taxRate=CRBSGlobalData::setGlobalData('tax_rate_dictionary',array(new CRBSTaxRate(),'getDictionary'));
        
        foreach($taxRate as $index=>$value)
        {
            if($value['meta']['tax_rate_default']==1)
                return($index);
        }
        
        return(0);
    }
    
    /**************************************************************************/
    
    function getTaxRateValue($taxRateId,$taxRate)
    {
        if(!isset($taxRate[$taxRateId])) return(0);
        return($taxRate[$taxRateId]['meta']['tax_rate_value']);
    }
    
    /**************************************************************************/
    
    function isTaxRate($taxRateId)
    {
        $dictionary=$this->getDictionary();
        return(array_key_exists($taxRateId,$dictionary));
    }
    
    /**************************************************************************/
    
    function manageEditColumns($column)
    {
        $column=array
        (
            'cb'                                                                =>  $column['cb'],
            'title'                                                             =>  __('Title','car-rental-booking-system'),
            'value'                                                             =>  __('Value','car-rental-booking-system'),
            'date'                                                              =>  $column['date']
        );
   
		return($column);          
    }
    
    /**************************************************************************/
    
    function managePostsCustomColumn($column)
    {
		global $post;
		
		$meta=CRBSPostMeta::getPostMeta($post);
        
		switch($column) 
		{
			case 'value':
				
                echo esc_html($meta['tax_rate_value']).'%';
                
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