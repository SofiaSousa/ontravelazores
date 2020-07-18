<?php

/******************************************************************************/
/******************************************************************************/

class CRBSVehicleAttribute
{
	/**************************************************************************/
	
    function __construct()
    {
        $this->attributeType=array
        (
            '1'                                                                 =>  array(__('Text Value','car-rental-booking-system')),
            '2'                                                                 =>  array(__('Single Choice','car-rental-booking-system')),
            '3'                                                                 =>  array(__('Multi Choice','car-rental-booking-system'))
        );
    }
    
    /**************************************************************************/
    
    public function init()
    {
        $this->registerCPT();
    }
    
	/**************************************************************************/

    public static function getCPTName()
    {
        return(PLUGIN_CRBS_CONTEXT.'_vehicle_attr');
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
					'name'														=>	__('Vehicle Attributes','car-rental-booking-system'),
					'singular_name'												=>	__('Vehicle Attribute','car-rental-booking-system'),
					'add_new'													=>	__('Add New','car-rental-booking-system'),
					'add_new_item'												=>	__('Add New Vehicle Attribute','car-rental-booking-system'),
					'edit_item'													=>	__('Edit Vehicle Attribute','car-rental-booking-system'),
					'new_item'													=>	__('New Vehicle Attribute','car-rental-booking-system'),
					'all_items'													=>	__('Vehicle Attributes','car-rental-booking-system'),
					'view_item'													=>	__('View Vehicle Attribute','car-rental-booking-system'),
					'search_items'												=>	__('Search Vehicle Attributes','car-rental-booking-system'),
					'not_found'													=>	__('No Vehicle Attributes Found','car-rental-booking-system'),
					'not_found_in_trash'										=>	__('No Vehicle Attributes Found in Trash','car-rental-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('Vehicle Attributes','car-rental-booking-system')
				),	
				'public'														=>	false,  
				'show_ui'														=>	true, 
				'show_in_menu'													=>	'edit.php?post_type='.CRBSBooking::getCPTName(),
				'capability_type'												=>	'post',
				'menu_position'													=>	2,
				'hierarchical'													=>	false,  
				'rewrite'														=>	false,  
				'supports'														=>	array('title','page-attributes','thumbnail')  
			)
		);
        
        add_action('save_post',array($this,'savePost'));
        add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
        add_filter('postbox_classes_'.self::getCPTName().'_crbs_meta_box_vehicle_attribute',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }

    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CRBS_CONTEXT.'_meta_box_vehicle_attribute',__('Main','car-rental-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
        
		$data=array();
        
        $data['meta']=CRBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CRBSHelper::createNonceField(PLUGIN_CRBS_CONTEXT.'_meta_box_vehicle_attribute');
        
        $data['dictionary']['attribute_type']=$this->getAttributeType();

		$Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'admin/meta_box_vehicle_attribute.php');
		echo $Template->output();	        
    }
    
     /**************************************************************************/
    
    function adminCreateMetaBoxClass($class) 
    {
        array_push($class,'to-postbox-1');
        return($class);
    }
    
	/**************************************************************************/
    
    function getAttributeType()
    {
        return($this->attributeType);
    }
    
    /**************************************************************************/
    
    function isAttributeType($attributeType)
    {
        return(array_key_exists($attributeType,$this->getAttributeType()));
    }
    
	/**************************************************************************/
	
	function setPostMetaDefault(&$meta)
	{
		CRBSHelper::setDefault($meta,'attribute_type','1');
	}
    
    /**************************************************************************/
    
    function savePost($postId)
    {      
        if(!$_POST) return(false);
        
        if(CRBSHelper::checkSavePost($postId,PLUGIN_CRBS_CONTEXT.'_meta_box_vehicle_attribute_noncename','savePost')===false) return(false);
        
		$meta=array();

        $Validation=new CRBSValidation();
        
		$this->setPostMetaDefault($meta);
        
        /***/
        
        if(CRBSHelper::getPostValue('is_edit_mode'))
        {
            $postMeta=CRBSPostMeta::getPostMeta($postId);
            $meta['attribute_type']=$postMeta['attribute_type'];
        }
        else
        {
            $meta['attribute_type']=CRBSHelper::getPostValue('attribute_type');
        }
        
        if(!$this->isAttributeType($meta['attribute_type']))
            $meta['attribute_type']=1;      
        
        if($meta['attribute_type']!=1)
        {
            if(array_key_exists(PLUGIN_CRBS_CONTEXT.'_attribute_value',$_POST))
            {
                $data=CRBSHelper::getPostValue('attribute_value');
                if(!is_array($data)) $data=array();

                foreach($data as $index=>$value)
                {
                    if($Validation->isNotEmpty($value))
                       $meta['attribute_value'][]=array('id'=>$index,'value'=>$value);
                }
            }
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
			'vehicle_attribute_id' 												=>	0
		);
		
		$attribute=shortcode_atts($default,$attr);
		CRBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'															=>	self::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1,
			'orderby'															=>	array('menu_order'=>'asc','title'=>'asc')
		);
		
		if($attribute['vehicle_attribute_id'])
			$argument['p']=$attribute['vehicle_attribute_id'];

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
            'title'                                                             =>  __('Title','car-rental-booking-system'),
            'attribute_type'                                                    =>  __('Type','car-rental-booking-system'),
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
			case 'attribute_type':
				
                echo esc_html($this->attributeType[$meta['attribute_type']][0]);
                
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