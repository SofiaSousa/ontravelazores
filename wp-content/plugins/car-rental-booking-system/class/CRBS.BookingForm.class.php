<?php

/******************************************************************************/
/******************************************************************************/

class CRBSBookingForm
{
	/**************************************************************************/
	
    function __construct()
    {
		$this->fieldMandatory=array
		(
			'client_contact_detail_phone_number'								=>	array
			(
				'label'															=>	__('Phone number','car-rental-booking-system'),
				'mandatory'														=>	0
			),
			'client_billing_detail_company_name'								=>	array
			(
				'label'															=>	__('Company registered name','car-rental-booking-system'),
				'mandatory'														=>	0
			),
			'client_billing_detail_tax_number'									=>	array
			(
				'label'															=>	__('Tax number','car-rental-booking-system'),
				'mandatory'														=>	0
			),
			'client_billing_detail_street_name'									=>	array
			(
				'label'															=>	__('Street name','car-rental-booking-system'),
				'mandatory'														=>	1
			),
			'client_billing_detail_street_number'								=>	array
			(
				'label'															=>	__('Street number','car-rental-booking-system'),
				'mandatory'														=>	0
			),
			'client_billing_detail_city'										=>	array
			(
				'label'															=>	__('City','car-rental-booking-system'),
				'mandatory'														=>	1
			),
			'client_billing_detail_state'										=>	array
			(
				'label'															=>	__('State','car-rental-booking-system'),
				'mandatory'														=>	0
			),
			'client_billing_detail_postal_code'									=>	array
			(
				'label'															=>	__('Postal code','car-rental-booking-system'),
				'mandatory'														=>	1
			),
			'client_billing_detail_country_code'								=>	array
			(
				'label'															=>	__('Country','car-rental-booking-system'),
				'mandatory'														=>	1
			)
		);
		
        $this->vehicleSortingType=array
        (
            1                                                                   =>  array(__('Price ascending','car-rental-booking-system')),
            2                                                                   =>  array(__('Price descending','car-rental-booking-system')),
            3                                                                   =>  array(__('Vehicle number ascending','car-rental-booking-system')),
            4                                                                   =>  array(__('Vehicle number descending','car-rental-booking-system')),
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
        return(PLUGIN_CRBS_CONTEXT.'_booking_form');
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
					'name'														=>	__('Booking Forms','car-rental-booking-system'),
					'singular_name'												=>	__('Booking Form','car-rental-booking-system'),
					'add_new'													=>	__('Add New','car-rental-booking-system'),
					'add_new_item'												=>	__('Add New Booking Form','car-rental-booking-system'),
					'edit_item'													=>	__('Edit Booking Form','car-rental-booking-system'),
					'new_item'													=>	__('New Booking Form','car-rental-booking-system'),
					'all_items'													=>	__('Booking Forms','car-rental-booking-system'),
					'view_item'													=>	__('View Booking Form','car-rental-booking-system'),
					'search_items'												=>	__('Search Booking Forms','car-rental-booking-system'),
					'not_found'													=>	__('No Booking Forms Found','car-rental-booking-system'),
					'not_found_in_trash'										=>	__('No Booking Forms Found in Trash','car-rental-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('Booking Forms','car-rental-booking-system')
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
        add_filter('postbox_classes_'.self::getCPTName().'_crbs_meta_box_booking_form',array($this,'adminCreateMetaBoxClass'));
        
        add_shortcode(PLUGIN_CRBS_CONTEXT.'_booking_form',array($this,'createBookingForm'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }
    
    /**************************************************************************/
    
    static function getShortcodeName()
    {
        return(PLUGIN_CRBS_CONTEXT.'_booking_form');
    }
    
    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CRBS_CONTEXT.'_meta_box_booking_form',__('Main','car-rental-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
        
		$Coupon=new CRBSCoupon();
        $Country=new CRBSCountry();
        $Location=new CRBSLocation();
        $Currency=new CRBSCurrency();
        $GoogleMap=new CRBSGoogleMap();
        $BookingStatus=new CRBSBookingStatus();
        $BookingFormStyle=new CRBSBookingFormStyle();
        $BookingFormElement=new CRBSBookingFormElement();
        
		$data=array();
        
        $data['meta']=CRBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CRBSHelper::createNonceField(PLUGIN_CRBS_CONTEXT.'_meta_box_booking_form');
        
        $data['dictionary']['color']=$BookingFormStyle->getColor();
        
        $data['dictionary']['location']=$Location->getDictionary();
        
        $data['dictionary']['country']=$Country->getCountry();
		
		$data['dictionary']['coupon']=$Coupon->getDictionary();
		
		$data['dictionary']['currency']=$Currency->getCurrency();
		
        $data['dictionary']['booking_status']=$BookingStatus->getBookingStatus();
		
        $data['dictionary']['form_element_panel']=$BookingFormElement->getPanel($data['meta']);
        
        $data['dictionary']['google_map']['position']=$GoogleMap->getPosition();
        $data['dictionary']['google_map']['map_type_control_id']=$GoogleMap->getMapTypeControlId();
        $data['dictionary']['google_map']['map_type_control_style']=$GoogleMap->getMapTypeControlStyle();
		
		$data['dictionary']['field_type']=$BookingFormElement->getFieldType();
		
		$data['dictionary']['field_mandatory']=$this->fieldMandatory;
		$data['dictionary']['vehicle_sorting_type']=$this->vehicleSortingType;
		
		$Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'admin/meta_box_booking_form.php');
		echo $Template->output();	        
    }
    
    /**************************************************************************/
    
    function adminCreateMetaBoxClass($class) 
    {
        array_push($class,'to-postbox-1');
        return($class);
    }
    
    /**************************************************************************/
    
    function savePost($postId)
    {      
        if(!$_POST) return(false);
        
        if(CRBSHelper::checkSavePost($postId,PLUGIN_CRBS_CONTEXT.'_meta_box_booking_form_noncename','savePost')===false) return(false);
        
        $Date=new CRBSDate();
		$Coupon=new CRBSCoupon();
        $Country=new CRBSCountry();
        $Location=new CRBSLocation();
		$Currency=new CRBSCurrency();
        $GoogleMap=new CRBSGoogleMap();
        $Validation=new CRBSValidation();
        $BookingStatus=new CRBSBookingStatus();
        $BookingFormStyle=new CRBSBookingFormStyle();
        
        /***/
        /***/
        
        $option=CRBSHelper::getPostOption();

        $locationDictionary=$Location->getDictionary();
		if(is_array($option['location_id']))
		{
			foreach($option['location_id'] as $index=>$value)
			{
				if(!array_key_exists($value,$locationDictionary))
					unset($option['location_id'][$index]);
			}
		}
		
		if(is_array($option['location_id']))
		{
			if(!count($option['location_id']))
				$option['location_id']=array(-1); 
		}
		else $option['location_id']=array();
		
		if((!array_key_exists($option['location_pickup_default_id'],$locationDictionary)) && ($option['location_pickup_default_id']!=-1))
			$option['location_pickup_default_id']=0;
		
		if((!array_key_exists($option['location_return_default_id'],$locationDictionary)) && ($option['location_return_default_id']!=-1))
			$option['location_return_default_id']=0;		
		
        if(!$Validation->isBool($option['location_single_display_enable']))
            $option['location_single_display_enable']=0;  		
        if(!$Validation->isBool($option['location_the_same_enable']))
            $option['location_the_same_enable']=0; 	
        if(!$Validation->isBool($option['location_customer_only_enable']))
            $option['location_customer_only_enable']=0; 			
		
        $option['currency']=(array)$option['currency'];
        if(in_array(-1,$option['currency']))
        {
            $option['currency']=array(-1);
        }
        else
        {
            foreach($Currency->getCurrency() as $index=>$value)
            {
                if(!$Currency->isCurrency($index))
                    unset($option['currency'][$index]);
            }
        }
		
        if(!count($option['currency']))
            $option['currency']=array(-1); 
        
		/***/
		
        $couponDictionary=$Coupon->getDictionary();
		if(!array_key_exists($option['coupon_id'],$couponDictionary))
			$option['coupon_id']=-1;
		
		/***/
		
        if(!$Validation->isPrice($option['order_value_minimum']))
            $option['order_value_minimum']=0.00;   
		
		$option['order_value_minimum']=CRBSPrice::formatToSave($option['order_value_minimum']);
     
        if(!$Validation->isNumber($option['timepicker_step'],1,9999))
            $option['timepicker_step']=30;   
		
        if(!in_array($option['timepicker_today_start_time_type'],array(1,2)))
            $option['timepicker_today_start_time_type']=1;		
        
        if(!$BookingStatus->isBookingStatus($option['booking_status_id_default']))
            $option['booking_status_id_default']=1;
        
        if(!array_key_exists('geolocation_enable',$option))
            $option['geolocation_enable']=array();
            
        foreach($option['geolocation_enable'] as $index=>$value)
        {
            if(!in_array($value,array(1,2)))
                unset($option['geolocation_enable'][$index]);
        }

        if(!is_array($option['geolocation_enable']))
            $option['geolocation_enable']=array();
            
        if(!$Validation->isBool($option['vehicle_count_enable']))
            $option['vehicle_count_enable']=0;
        
        if(!$Validation->isBool($option['summary_sidebar_sticky_enable']))
            $option['summary_sidebar_sticky_enable']=0;
        
        if(!in_array($option['summary_bill_type'],array(1,2)))
            $option['summary_bill_type']=0;
        
        if(!$Validation->isBool($option['vehicle_filter_bar_enable']))
            $option['vehicle_filter_bar_enable']=0; 

        if(!$Validation->isBool($option['vehicle_price_per_day_enable']))
            $option['vehicle_price_per_day_enable']=0; 
        
        if(!$Validation->isBool($option['scroll_to_booking_extra_after_select_vehicle_enable']))
            $option['scroll_to_booking_extra_after_select_vehicle_enable']=0; 

        if(!$Validation->isBool($option['driver_age_enable']))
            $option['driver_age_enable']=0; 
        
        if(!$Validation->isBool($option['coupon_enable']))
            $option['coupon_enable']=0; 
        
        if(!$Validation->isBool($option['woocommerce_enable']))
            $option['woocommerce_enable']=0; 
		
		if(!in_array($option['woocommerce_account_enable_type'],array(0,1,2)))
			$option['woocommerce_account_enable_type']=1;		
        
        if(!$Validation->isBool($option['booking_summary_hide_fee']))
            $option['booking_summary_hide_fee']=0;         
        
        if(!$Validation->isBool($option['booking_summary_display_net_price']))
            $option['booking_summary_display_net_price']=0;             
        
        $option['booking_extra_day_time']=$Date->formatTimeToStandard($option['booking_extra_day_time']);
        if(!$Validation->isBool($option['booking_extra_day_enable']))
            $option['booking_extra_day_enable']=0;    
        if(!$Validation->isTime($option['booking_extra_day_time']))
            $option['booking_extra_day_time']=''; 
        if(!$Validation->isNumber($option['booking_extra_day_number'],0,999))
            $option['booking_extra_day_number']=0;   
        
        if(!array_key_exists($option['vehicle_sorting_type'],$this->vehicleSortingType))
            $option['vehicle_sorting_type']=1;
		
		if(!$Validation->isBool($option['form_preloader_enable']))
            $option['form_preloader_enable']=0;             
		if(!$Validation->isBool($option['navigation_top_enable']))
            $option['navigation_top_enable']=0;
		if(!$Validation->isBool($option['step_1_right_panel_visibility']))
            $option['step_1_right_panel_visibility']=0;
			
		$option['field_mandatory']=(array)$option['field_mandatory'];
		foreach($option['field_mandatory'] as $index=>$value)
		{
			if(!array_key_exists($value,$this->fieldMandatory))
				unset($option['field_mandatory'][$index]);
		}
		
        /***/
        
        if(!$Validation->isBool($option['customer_pickup_location_enable']))
            $option['customer_pickup_location_enable']=0; 
        
        if((int)$option['customer_pickup_location_enable']===0)
            $option['customer_pickup_location_id']=0;
		
        if(!array_key_exists($option['customer_pickup_location_id'],$locationDictionary))
            $option['customer_pickup_location_id']=count($locationDictionary) ? (int)key($locationDictionary) : 0;
     
        if(!$Validation->isNumber($option['customer_pickup_location_restriction_radius'],0,99999))
            $option['customer_pickup_location_restriction_radius']=50;   
        
        if(!array_key_exists('customer_pickup_location_restriction_country',$option))
            $option['customer_pickup_location_restriction_country']=array();
        
        $option['customer_pickup_location_restriction_country']=(array)$option['customer_pickup_location_restriction_country'];
        foreach($option['customer_pickup_location_restriction_country'] as $index=>$value)
        {
            if($value==-1)
            {
                $option['customer_pickup_location_restriction_country']=array();
                break;
            }
            
            if(!$Country->isCountry($value))
                unset($option['customer_pickup_location_restriction_country'][$index]);
        }
        
        /***/
        
        if(!$Validation->isBool($option['customer_return_location_enable']))
            $option['customer_return_location_enable']=0; 
        
        if((int)$option['customer_return_location_enable']===0)
            $option['customer_return_location_id']=0;

        if(!array_key_exists($option['customer_return_location_id'],$locationDictionary))
            $option['customer_return_location_id']=0;        
        
        if(!$Validation->isNumber($option['customer_return_location_restriction_radius'],0,99999))
            $option['customer_return_location_restriction_radius']=50;
        
        if(!array_key_exists('customer_return_location_restriction_country',$option))
            $option['customer_return_location_restriction_country']=array();
        
        $option['customer_return_location_restriction_country']=(array)$option['customer_return_location_restriction_country'];
        foreach($option['customer_return_location_restriction_country'] as $index=>$value)
        {
            if($value==-1)
            {
                $option['customer_return_location_restriction_country']=array();
                break;
            }
            
            if(!$Country->isCountry($value))
                unset($option['customer_return_location_restriction_country'][$index]);
        }
		
        if(!$Validation->isBool($option['thank_you_page_enable']))
            $option['thank_you_page_enable']=0;		
        
        if(!in_array($option['billing_detail_state'],array(1,2,3,4)))
            $option['billing_detail_state']=1;   		
		
		/***/
		
		$option['vehicle_attribute_enable']=(array)$option['vehicle_attribute_enable'];
		foreach($option['vehicle_attribute_enable'] as $index=>$value)
		{
			if(!in_array($value,array(1,2,3,4)))
				unset($option['vehicle_attribute_enable'][$index]);
		}
		
		if(!is_array($option['vehicle_attribute_enable']))
			$option['vehicle_attribute_enable']=array();
			
        /***/
        
        foreach($option['style_color'] as $index=>$value)
        {
            if(!$BookingFormStyle->isColor($index))
            {
                unset($option['style_color'][$index]);
                continue;
            }
            
            if(!$Validation->isColor($value,true))
                $option['style_color'][$index]='';
        }
        
        /***/

        $FormElement=new CRBSBookingFormElement();
        $FormElement->save($postId);
        
        /***/
        
        if(!$Validation->isBool($option['google_map_draggable_enable']))
            $option['google_map_draggable_enable']=1;        
        if(!$Validation->isBool($option['google_map_scrollwheel_enable']))
            $option['google_map_scrollwheel_enable']=1;             

        if(!$Validation->isBool($option['google_map_map_type_control_enable']))
            $option['google_map_map_type_control_enable']=0;   
        if(!array_key_exists($option['google_map_map_type_control_id'],$GoogleMap->getMapTypeControlId()))
            $option['google_map_map_type_control_id']='SATELLITE';        
        if(!array_key_exists($option['google_map_map_type_control_style'],$GoogleMap->getMapTypeControlStyle()))
            $option['google_map_map_type_control_style']='DEFAULT';         
        if(!array_key_exists($option['google_map_map_type_control_position'],$GoogleMap->getPosition()))
            $option['google_map_map_type_control_position']='TOP_CENTER';
        
        if(!$Validation->isBool($option['google_map_zoom_control_enable']))
            $option['google_map_zoom_control_enable']=0;   
        if(!array_key_exists($option['google_map_zoom_control_position'],$GoogleMap->getPosition()))
            $option['google_map_zoom_control_position']='TOP_CENTER';        
        if(!$Validation->isNumber($option['google_map_zoom_control_level'],1,21))
            $option['google_map_zoom_control_position']=6;   
        
        /***/
        
        $key=array
        (
            'location_id',
			'location_single_display_enable',
			'location_the_same_enable',
			'location_customer_only_enable',
			'location_pickup_default_id',
			'location_return_default_id',
			'currency',
			'coupon_enable',
			'coupon_id',
            'order_value_minimum',
            'timepicker_step',
			'timepicker_today_start_time_type',
            'booking_status_id_default',
            'geolocation_enable',
            'vehicle_count_enable',
            'summary_sidebar_sticky_enable',
            'summary_bill_type',
            'vehicle_filter_bar_enable',
            'vehicle_price_per_day_enable',
            'scroll_to_booking_extra_after_select_vehicle_enable',
            'driver_age_enable',
            'woocommerce_enable',
			'woocommerce_account_enable_type',
            'booking_summary_hide_fee',
            'booking_summary_display_net_price',
            'booking_extra_day_enable',
            'booking_extra_day_time',
            'booking_extra_day_number',
			'vehicle_sorting_type',
            'form_preloader_enable',
			'navigation_top_enable',
			'step_1_right_panel_visibility',
			'field_mandatory',
            'customer_pickup_location_enable',
            'customer_pickup_location_id',
            'customer_pickup_location_restriction_name',
            'customer_pickup_location_restriction_radius',
            'customer_pickup_location_restriction_coordinate_lat',
            'customer_pickup_location_restriction_coordinate_lng',
            'customer_pickup_location_restriction_country',
            'customer_return_location_enable',
            'customer_return_location_id',
            'customer_return_location_restriction_name',
            'customer_return_location_restriction_radius',
            'customer_return_location_restriction_coordinate_lat',
            'customer_return_location_restriction_coordinate_lng',
            'customer_return_location_restriction_country',
			'billing_detail_state',
			'vehicle_attribute_enable',
			'thank_you_page_enable',
			'thank_you_page_button_back_to_home_label',
			'thank_you_page_button_back_to_home_url_address',
            'style_color',
            'google_map_draggable_enable',
            'google_map_scrollwheel_enable',
            'google_map_map_type_control_enable',
            'google_map_map_type_control_id',
            'google_map_map_type_control_style',
            'google_map_map_type_control_position',
            'google_map_zoom_control_enable',
            'google_map_zoom_control_position',
            'google_map_zoom_control_level',
            'google_map_style'
        );

		foreach($key as $value)
			CRBSPostMeta::updatePostMeta($postId,$value,$option[$value]);
        
        $BookingFormStyle->createCSSFile();
    }
    
	/**************************************************************************/
	
	function setPostMetaDefault(&$meta)
	{
        CRBSHelper::setDefault($meta,'location_id',array());
		
        CRBSHelper::setDefault($meta,'location_single_display_enable',1);
		CRBSHelper::setDefault($meta,'location_the_same_enable',0);
		CRBSHelper::setDefault($meta,'location_customer_only_enable',0);
		
		CRBSHelper::setDefault($meta,'location_pickup_default_id',0);
		CRBSHelper::setDefault($meta,'location_return_default_id',0);
		
		CRBSHelper::setDefault($meta,'currency',array(-1));
		
		CRBSHelper::setDefault($meta,'coupon_id',-1);
        
        CRBSHelper::setDefault($meta,'order_value_minimum',0.00);
        
        CRBSHelper::setDefault($meta,'booking_status_id_default',1);
        
        CRBSHelper::setDefault($meta,'timepicker_step',30);
		CRBSHelper::setDefault($meta,'timepicker_today_start_time_type',1);
		
        CRBSHelper::setDefault($meta,'geolocation_enable',array());
        
        CRBSHelper::setDefault($meta,'vehicle_count_enable',1);
        CRBSHelper::setDefault($meta,'summary_sidebar_sticky_enable',0);
        CRBSHelper::setDefault($meta,'summary_bill_type',1);
        CRBSHelper::setDefault($meta,'vehicle_filter_bar_enable',1);
        CRBSHelper::setDefault($meta,'vehicle_price_per_day_enable',1);
        CRBSHelper::setDefault($meta,'scroll_to_booking_extra_after_select_vehicle_enable',0);
        CRBSHelper::setDefault($meta,'driver_age_enable',1);
        CRBSHelper::setDefault($meta,'woocommerce_enable',0);
		CRBSHelper::setDefault($meta,'woocommerce_account_enable_type',1);
        CRBSHelper::setDefault($meta,'coupon_enable',0);
		
		CRBSHelper::setDefault($meta,'vehicle_sorting_type',1); 
		CRBSHelper::setDefault($meta,'geolocation_server_side_enable',array()); 
        
        CRBSHelper::setDefault($meta,'booking_summary_hide_fee',0); 
        CRBSHelper::setDefault($meta,'booking_summary_display_net_price',0); 
        
        CRBSHelper::setDefault($meta,'booking_extra_day_enable',0); 
        CRBSHelper::setDefault($meta,'booking_extra_day_time',''); 
        CRBSHelper::setDefault($meta,'booking_extra_day_number',0); 
        
        CRBSHelper::setDefault($meta,'form_preloader_enable',1); 
        CRBSHelper::setDefault($meta,'navigation_top_enable',1);
        CRBSHelper::setDefault($meta,'step_1_right_panel_visibility',1);
        
        CRBSHelper::setDefault($meta,'customer_pickup_location_id',0);
        CRBSHelper::setDefault($meta,'customer_pickup_location_enable',0);
        CRBSHelper::setDefault($meta,'customer_pickup_location_restriction_name','');
        CRBSHelper::setDefault($meta,'customer_pickup_location_restriction_radius',50);
        CRBSHelper::setDefault($meta,'customer_pickup_location_restriction_coordinate_lat','');
        CRBSHelper::setDefault($meta,'customer_pickup_location_restriction_coordinate_lng','');
        CRBSHelper::setDefault($meta,'customer_pickup_location_restriction_country','-1');
        
        CRBSHelper::setDefault($meta,'customer_return_location_id',0);
        CRBSHelper::setDefault($meta,'customer_return_location_enable',0);
        CRBSHelper::setDefault($meta,'customer_return_location_restriction_name','');
        CRBSHelper::setDefault($meta,'customer_return_location_restriction_radius',50);
        CRBSHelper::setDefault($meta,'customer_return_location_restriction_coordinate_lat','');
        CRBSHelper::setDefault($meta,'customer_return_location_restriction_coordinate_lng','');
        CRBSHelper::setDefault($meta,'customer_return_location_restriction_country','-1');
        
		CRBSHelper::setDefault($meta,'billing_detail_state',1);
		
		CRBSHelper::setDefault($meta,'thank_you_page_enable',1);
		CRBSHelper::setDefault($meta,'thank_you_page_button_back_to_home_label',__('Back To Home','car-rental-booking-system'));
		CRBSHelper::setDefault($meta,'thank_you_page_button_back_to_home_url_address','');
		
		$fieldMandatory=array();
		foreach($this->fieldMandatory as $index=>$value)
		{
			if((int)$value['mandatory']===1)
				$fieldMandatory[]=$index;
		}	
		
		CRBSHelper::setDefault($meta,'field_mandatory',$fieldMandatory);
		
		CRBSHelper::setDefault($meta,'vehicle_attribute_enable',array(1,2,3,4));
		
        $BookingFormStyle=new CRBSBookingFormStyle();
        CRBSHelper::setDefault($meta,'style_color',array_fill(1,count($BookingFormStyle->getColor()),'')); 
        
        CRBSHelper::setDefault($meta,'google_map_draggable_enable',1);
        CRBSHelper::setDefault($meta,'google_map_scrollwheel_enable',1);
        
        CRBSHelper::setDefault($meta,'google_map_map_type_control_enable',0);
        CRBSHelper::setDefault($meta,'google_map_map_type_control_id','SATELLITE');
        CRBSHelper::setDefault($meta,'google_map_map_type_control_style','DEFAULT');
        CRBSHelper::setDefault($meta,'google_map_map_type_control_position','TOP_CENTER');
        
        CRBSHelper::setDefault($meta,'google_map_zoom_control_enable',0);
        CRBSHelper::setDefault($meta,'google_map_zoom_control_style','DEFAULT');
        CRBSHelper::setDefault($meta,'google_map_zoom_control_position','TOP_CENTER');
        CRBSHelper::setDefault($meta,'google_map_zoom_control_level',6);
        
        CRBSHelper::setDefault($meta,'google_map_pan_control_enable',0);
        CRBSHelper::setDefault($meta,'google_map_pan_control_position','TOP_CENTER');        

        CRBSHelper::setDefault($meta,'google_map_scale_control_enable',0);
        CRBSHelper::setDefault($meta,'google_map_scale_control_position','TOP_CENTER');        
        
        CRBSHelper::setDefault($meta,'google_map_street_view_enable',0);
        CRBSHelper::setDefault($meta,'google_map_street_view_postion','TOP_CENTER');  
        
        CRBSHelper::setDefault($meta,'google_map_style','');
	}
    
    /**************************************************************************/
    
    function getDictionary($attr=array())
    {
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'booking_form_id'   												=>	0,
			'suppress_filters'													=>	false
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
		
		if(array_key_exists('booking_form_id',$attr))
        {
			$argument['p']=$attribute['booking_form_id'];
            if((int)$argument['p']<=0) return($dictionary);
        }
		if(array_key_exists('suppress_filters',$attr))
        {
			$argument['suppress_filters']=$attribute['suppress_filters'];
        }	
		
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
		return($column);          
    }
    
    /**************************************************************************/
    
    function managePostsCustomColumn($column)
    {

    }
    
    /**************************************************************************/
    
    function manageEditSortableColumns($column)
    {
		return($column);       
    }
    
    /**************************************************************************/
    
    function createBookingForm($attr)
    {
        $TaxRate=new CRBSTaxRate();
        $Location=new CRBSLocation();
        $Validation=new CRBSValidation();
		$PaymentPaypal=new CRBSPaymentPaypal();
        $BookingFormStyle=new CRBSBookingFormStyle();
		
		$action=CRBSHelper::getGetValue('action',false);
		if($action==='ipn')
		{
			$PaymentPaypal->handleIPN();
			return(null);
		}
                
		$default=array
		(
			'booking_form_id'   												=>	0,
			'currency'                                                          =>  '',
            'widget_mode'                                                       =>  0,
            'widget_booking_form_url'                                           =>  '',
            'widget_booking_form_style_id'                                      =>  1
		);
		
        $data=array();
        
		$attribute=shortcode_atts($default,$attr);               
        
        if(!is_array($data=$this->checkBookingForm($attribute['booking_form_id'],$attribute['currency'],true))) return;         
		
        $data['ajax_url']=admin_url('admin-ajax.php');
        
        $data['booking_form_post_id']=$attribute['booking_form_id'];
        $data['booking_form_html_id']=CRBSHelper::createId('crbs_booking_form');
       
        $data['dictionary']['tax_rate']=$TaxRate->getDictionary();
       
        $data['vehicle_bag_count_range']=$this->getVehicleBagCountRange($data['dictionary']['vehicle']);
        $data['vehicle_passenger_count_range']=$this->getVehiclePassengerCountRange($data['dictionary']['vehicle']);
        
        $data['location_info']=$Location->getLocationInfo();
        
        $color=$BookingFormStyle->getColor();
        
        foreach($color as $index=>$value)
        {
            $data['booking_form_color'][$index]=$data['meta']['style_color'][$index];
            if($Validation->isEmpty($data['booking_form_color'][$index])) $data['booking_form_color'][$index]=$value['color'];
        }
        
        $data['widget_mode']=(int)$attribute['widget_mode'];
        $data['widget_booking_form_url']=$attribute['widget_booking_form_url'];
        $data['widget_booking_form_style_id']=$attribute['widget_booking_form_style_id'];
        
        $Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'public/public.php');
        return($Template->output());
    }
    
    /**************************************************************************/
    
    function bookingFormDisplayError($message,$displayError)
    {
        if(!$displayError) return;
        echo '<div class="crbs-booking-form-error">'.esc_html($message).'</div>';
    }
    
    /**************************************************************************/
    
    function checkBookingForm($bookingFormId,$currency=null,$displayError=false)
    {
        $data=array();      
        
		$Validation=new CRBSValidation();
        $GeoLocation=new CRBSGeoLocation();
        $WooCommerce=new CRBSWooCommerce();
       
        $bookingForm=$this->getDictionary(array('booking_form_id'=>$bookingFormId));
        if(!count($bookingForm)) 
        {
            $this->bookingFormDisplayError(__('Booking form with provided ID doesn\'t exist.','car-rental-booking-form'),$displayError);
            return(-1);
        }
		
        $data['post']=$bookingForm[$bookingFormId]['post'];
        $data['meta']=$bookingForm[$bookingFormId]['meta'];
       
        /****/        

        $data['dictionary']['location']=$this->getBookingFormLocation($data['meta']);
        if(!count($data['dictionary']['location'])) 
        {
            $this->bookingFormDisplayError(__('Plugin cannot find location. Please make sure that you created at least one location and set coordinates for it.','car-rental-booking-form'),$displayError);
            return(-2);
        }
       
        list($pickupLocationId)=$this->getBookingFormPickupLocation($data);

        $data['dictionary']['vehicle']=$this->getBookingFormVehicle($data);
        if(!count($data['dictionary']['vehicle'])) 
        {
            $this->bookingFormDisplayError(__('Plugin cannot find at least one vehicle. Please make sure created at least one vehicle and assigned it to the at least one location.','car-rental-booking-form'),$displayError);
            return(-3);
        }

        $data['dictionary']['payment']=array();
        $data['dictionary']['payment_woocommerce']=array();
        
        if($WooCommerce->isEnable($data['meta']))
        {
			if((int)$data['dictionary']['location'][$pickupLocationId]['meta']['payment_woocommerce_step_3_enable']===1)
				$data['dictionary']['payment_woocommerce']=$WooCommerce->getPaymentDictionary();
        }
        else 
        {
            $data['dictionary']['payment']=$this->getBookingFormPayment($data['dictionary']['location'][$pickupLocationId]['meta']);
        }
        
		if($Validation->isEmpty($currency))
            $currency=CRBSHelper::getGetValue('currency',false);
        
        if(in_array($currency,$data['meta']['currency']))
            $data['currency']=$currency;
        else $data['currency']=CRBSOption::getOption('currency');
		
        $data['dictionary']['booking_extra']=$this->getBookingFormExtra($pickupLocationId);
        $data['dictionary']['vehicle_category']=$this->getBookingFormVehicleCategory();
        		
		/***/
		
        if(in_array(2,$data['meta']['geolocation_enable']))
            $data['client_coordinate']=$GeoLocation->getCoordinate();
        else $data['client_coordinate']=array('lat'=>0,'lng'=>0);
				
        /****/
        
        if($data['meta']['customer_pickup_location_enable']===1)
        {
            if(!array_key_exists($data['meta']['customer_pickup_location_id'],$data['dictionary']['location']))
            {
                $data['meta']['customer_pickup_location_enable']=0;
                $data['meta']['customer_pickup_location_id']=0;
            }
        }
        
        if($data['meta']['customer_return_location_enable']===1)
        {
            if(!array_key_exists($data['meta']['customer_return_location_id'],$data['dictionary']['location']))
            {
                $data['meta']['customer_return_location_enable']=0;
                $data['meta']['customer_return_location_id']=0;
            }
        }      
	
        /****/

		$Date=new CRBSDate();
        $TaxRate=new CRBSTaxRate();
        $Country=new CRBSCountry();
		$Geofence=new CRBSGeofence();
        $PriceRule=new CRBSPriceRule();
		
        $data['dictionary']['country']=$Country->getCountry();
        $data['dictionary']['geofence']=$Geofence->getDictionary();
        $data['dictionary']['tax_rate']=$TaxRate->getDictionary();
        $data['dictionary']['price_rule']=$PriceRule->getDictionary();
         
        $vehicleDictionaryDriverAge=$this->getBookingFormVehicle($data,false);
        
		$countryCode=$GeoLocation->getCountryCode();
		
        foreach($data['dictionary']['location'] as $index=>$value)
        {
			foreach($value['meta']['business_hour'] as $businessHourIndex=>$businessHourValue)
			{
				if($Validation->isTime($businessHourValue['default']))
				{
					$date=date_i18n('d-m-Y G:i',strtotime('01-01-1970 '.$businessHourValue['default'].'+'.(int)$data['meta']['timepicker_step'].' minutes'));
					$value['meta']['business_hour'][$businessHourIndex]['default_timepicker']=date_i18n('H:i',strtotime($date));
					
					if($Date->compareDate(date_i18n('d-m-Y',strtotime($date)),date_i18n('d-m-Y',strtotime('01-01-1970')))===1)
						$value['meta']['business_hour'][$businessHourIndex]['default_timepicker']=$businessHourValue['default'];
				}
				
				if((is_array($businessHourValue['break']) && (count($businessHourValue['break']))))
				{
					foreach($businessHourValue['break'] as $breakIndex=>$breakValue)
						$value['meta']['business_hour'][$businessHourIndex]['break'][$breakIndex]['stop']=date_i18n('H:i',strtotime('01-01-1970 '.$breakValue['stop'].' + 1 minutes')); 
				}
			}
			
            $data['location_vehicle_id_default'][$index]=$value['meta']['vehicle_id_default'];
            
            $data['location_date_exclude'][$index]=$value['meta']['date_exclude'];
            $data['location_business_hour'][$index]=$value['meta']['business_hour'];
            
            $data['location_pickup_period'][$index]=$this->getBookingFormPickupPeriod($value['meta'],$data['meta']['timepicker_step'],$data['meta']['timepicker_today_start_time_type']);
        
			$data['location_pickup_period_format'][$index]['min']=date(CRBSOption::getOption('date_format'),strtotime($data['location_pickup_period'][$index]['min']));
			
			if(is_null($data['location_pickup_period'][$index]['max'])) $data['location_pickup_period_format'][$index]['max']=null;
			else $data['location_pickup_period_format'][$index]['max']=date(CRBSOption::getOption('date_format'),strtotime($data['location_pickup_period'][$index]['max']));
			
            $data['location_driver_age'][$index]=$this->getBookingFormDriverAge($index,$vehicleDictionaryDriverAge);
            
			$data['location_after_business_hour_pickup_enable'][$index]=$value['meta']['after_business_hour_pickup_enable'];
            $data['location_after_business_hour_return_enable'][$index]=$value['meta']['after_business_hour_return_enable'];
            
			$data['location_payment_paypal_redirect_duration'][$index]=$value['meta']['payment_paypal_redirect_duration'];
			
            if(($Validation->isNotEmpty($value['meta']['coordinate_latitude'])) && ($Validation->isNotEmpty($value['meta']['coordinate_longitude'])))
                $data['location_coordinate'][$index]=array('lat'=>$value['meta']['coordinate_latitude'],'lng'=>$value['meta']['coordinate_longitude']);
			
			if($value['meta']['country_default']=='-1')
			{
				if((int)$data['meta']['geolocation_server_side_enable']===1)
					$data['location_client_country_default'][$index]=$countryCode;
			}
			else $data['location_client_country_default'][$index]=$value['meta']['country_default'];
        }
        
        /****/
        
        $data['step']=array();
        
        $data['step']['dictionary']=array
        (
            1                                                                   =>  array
            (
                'navigation'                                                    =>  array
                (
                    'number'                                                    =>  __('1','car-rental-booking-system'),
                    'label'                                                     =>  __('Date & Location','car-rental-booking-system'),
                ),
                'button'                                                        =>  array
                (
                    'next'                                                      =>  __('Search for Car','car-rental-booking-system')
                )
            ),
            2                                                                   =>  array
            (
                'navigation'                                                    =>  array
                (                
                    'number'                                                    =>  __('2','car-rental-booking-system'),
                    'label'                                                     =>  __('Search for Car','car-rental-booking-system')
                ),
                'button'                                                        =>  array
                (
                    'prev'                                                      =>  __('Date & Location','car-rental-booking-system'),
                    'next'                                                      =>  __('Driver Details','car-rental-booking-system')
                )
            ),
            3                                                                   =>  array
            (
                'navigation'                                                    =>  array
                (
                    'number'                                                    =>  __('3','car-rental-booking-system'),
                    'label'                                                     =>  __('Driver Details','car-rental-booking-system')
                ),
                'button'                                                        =>  array
                (
                    'prev'                                                      =>  __('Search for Car','car-rental-booking-system'),
                    'next'                                                      =>  __('Booking Summary','car-rental-booking-system')
                )
            ),
            4                                                                   =>  array
            (
                'navigation'                                                    =>  array
                (
                    'number'                                                    =>  __('4','car-rental-booking-system'),
                    'label'                                                     =>  __('Booking Summary','car-rental-booking-system')
                ),
                'button'                                                        =>  array
                (
                    'prev'                                                      =>  __('Driver Details','car-rental-booking-system'),
                    'next'                                                      =>  __('Book now','car-rental-booking-system')
                )
            )            
        );
		
		list(,,$pickupLocationIdSelect)=$this->getBookingFormPickupLocation($data);
		list(,,$returnLocationIdSelect)=$this->getBookingFormReturnLocation($data);
		
		$data['pickup_location_id_select']=$pickupLocationIdSelect;
        $data['return_location_id_select']=$returnLocationIdSelect;
		
		$data['pickup_location_id']=$pickupLocationId;
		
        /***/
		
        return($data);
    }
    
    /**************************************************************************/
    
   function getBookingFormVehicleCategory()
    {
        $Vehicle=new CRBSVehicle();
        $dictionary=$Vehicle->getCategory();
     
        return($dictionary);
    }
	
	/**************************************************************************/
	
    function getBookingFormLocation($meta)
    {
        $Location=new CRBSLocation();
        $Validation=new CRBSValidation();
		
        $dictionary=$Location->getDictionary();
        
        foreach($dictionary as $index=>$value)
        {
            if(!in_array($index,$meta['location_id']))
				unset($dictionary[$index]);
			
			if(array_key_exists($index,$dictionary))
			{
				if(($Validation->isEmpty($dictionary[$index]['meta']['coordinate_latitude'])) || ($Validation->isEmpty($dictionary[$index]['meta']['coordinate_longitude'])))
				{
					unset($dictionary[$index]);
				}
			}
        }
        
       return($dictionary);
    }
       
    /**************************************************************************/
    
    function getBookingFormPickupLocation($bookingForm)
    {
        $option=array_merge(CRBSHelper::getGetOption(null,false),CRBSHelper::getPostOption());
	
        $locationId=0;
		$locationIdSelect=0;
        $locationCustomer=false;
        
        if(array_key_exists('pickup_location_id',$option))
		{
            $locationId=$option['pickup_location_id'];
			$locationIdSelect=$locationId;
		}
		else
		{
            $locationId=(int)$bookingForm['meta']['location_pickup_default_id'];
			
			if($locationId===-1)
				$locationId=-1*$bookingForm['meta']['customer_pickup_location_id'];
			
			$locationIdSelect=$locationId;
		}
		
        if(($locationId<-1) && ($bookingForm['meta']['customer_pickup_location_enable']==1))
        {
            $locationId=abs($bookingForm['meta']['customer_pickup_location_id']);
			
			if(array_key_exists('pickup_location_address_data',$option))
				$locationCustomer=json_decode($option['pickup_location_address_data']);
        }
        
        if(!array_key_exists($locationId,$bookingForm['dictionary']['location']))
		{
            $locationId=key($bookingForm['dictionary']['location']);
			$locationIdSelect=$locationId;
		}
		
        return([$locationId,$locationCustomer,$locationIdSelect]);
    }
    
    /**************************************************************************/
    
    function getBookingFormReturnLocation($bookingForm)
    {
        $option=array_merge(CRBSHelper::getGetOption(null,false),CRBSHelper::getPostOption());
        
		CRBSHelper::removeUIndex($option,'return_location_address_data');
		
        $locationId=0;
		$locationIdSelect=0;
        $locationCustomer=false;
        
        if(array_key_exists('return_location_id',$option))
		{
            $locationId=$option['return_location_id'];
			$locationIdSelect=$locationId;
		}
		else
		{
            $locationId=(int)$bookingForm['meta']['location_return_default_id'];
			
			if($locationId===-1)
				$locationId=-1*$bookingForm['meta']['customer_return_location_id'];
			
			$locationIdSelect=$locationId;				
		}
			
        if($locationId==-1)
            list($locationId,$locationCustomer)=$this->getBookingFormPickupLocation($bookingForm);
        
        if(($locationId<-1) && ($bookingForm['meta']['customer_return_location_enable']==1))
        {
            $locationId=abs($bookingForm['meta']['customer_return_location_id']);
            $locationCustomer=json_decode($option['return_location_address_data']);
        }
        
        if(!array_key_exists($locationId,$bookingForm['dictionary']['location']))
		{
            $locationId=key($bookingForm['dictionary']['location']);
			$locationIdSelect=$locationId;
		}
		
        return([$locationId,$locationCustomer,$locationIdSelect]);
    }
    
    /**************************************************************************/
    
    function getBookingFormExtra($pickupLocationId)
    {
        $BookingExtra=new CRBSBookingExtra();
        
        $dictionary=$BookingExtra->getDictionary();
        
        foreach($dictionary as $index=>$value)
        {
            if(is_array($value['meta']['location_id']))
            {
                if(!in_array($pickupLocationId,$value['meta']['location_id']))
                    unset($dictionary[$index]);
            }
        }
   
        return($dictionary);        
    }
    
    /**************************************************************************/
    
    function getBookingFormDriverAge($locationId,$vehicleDictionary)
    {
        $age=array('min'=>100,'max'=>0);
        
        foreach($vehicleDictionary as $value)
        {
			if(is_array($value['meta']['location_id']))
			{
                if(in_array($locationId,$value['meta']['location_id']))
                {
                    $age['min']=$value['meta']['driver_age_min']<$age['min'] ? $value['meta']['driver_age_min'] : $age['min'];
                    $age['max']=$value['meta']['driver_age_max']>$age['max'] ? $value['meta']['driver_age_max'] : $age['max'];
                }
            }
        }
        
        return($age);
    }  
    
    /**************************************************************************/
    
    function getBookingFormVehicle($bookingForm,$checkPickupLocation=true)
    {
        $Date=new CRBSDate();
        $Vehicle=new CRBSVehicle();
        $Validation=new CRBSValidation();
        
        $data=CRBSHelper::getPostOption();
        
        CRBSHelper::removeUIndex($data,'driver_age','pickup_location_id','pickup_date','pickup_time','','return_date','return_time');
        
        $driverAge=(int)$data['driver_age'];
        list($pickupLocationId)=$this->getBookingFormPickupLocation($bookingForm);
		
        $vehicle=$Vehicle->getDictionary(array(),$bookingForm['meta']['vehicle_sorting_type']);
        
        foreach($vehicle as $index=>$value)
        {
            if($checkPickupLocation)
            {
                if($pickupLocationId>0)
                {
                    if(is_array($value['meta']['location_id']))
                    {
                        if(!in_array($pickupLocationId,$value['meta']['location_id']))
                            unset($vehicle[$index]);
                    }
                }
            }
            
            if($bookingForm['meta']['driver_age_enable']==1)
            {
                if($driverAge>0)
                {
                    if(!(($driverAge>=$value['meta']['driver_age_min']) && ($driverAge<=$value['meta']['driver_age_max'])))
                        unset($vehicle[$index]);
                }
            }
        }           
        
        $pickupDate=$Date->formatDateToStandard($data['pickup_date']);
        $pickupTime=$Date->formatTimeToStandard($data['pickup_time']);        
        $returnDate=$Date->formatDateToStandard($data['return_date']);
        $returnTime=$Date->formatTimeToStandard($data['return_time']); 
        
        $vehicle=$Vehicle->checkAvailability($vehicle,$pickupDate,$pickupTime,$returnDate,$returnTime,$pickupLocationId);
        
        /***/
        
        $groupCode=array();
        
        foreach($vehicle as $index=>$value)
        {
            $code=$value['meta']['group_code'];
            
            if($Validation->isNotEmpty($code))
            {
                if(!isset($groupCode[$code])) $groupCode[$code]=array();
                else array_push($groupCode[$code],$index); 
            }
        }  
          
        foreach($groupCode as $index=>$value)
        {
            foreach($value as $vehicleId)
                unset($vehicle[$vehicleId]);
        }
        
        /***/
        
        $Vehicle->getVehicleAttribute($vehicle);
        
        return($vehicle);
    }
       
    /**************************************************************************/
    
    function getBookingFormPayment($meta)
    {
        $Payment=new CRBSPayment();
                
        $payment=$Payment->getPayment();
        
        foreach($payment as $index=>$value)
        {
            if(!in_array($index,$meta['payment_id']))
               unset($payment[$index]);
        }
        
        return($payment);
    }

    /**************************************************************************/

    function goToStep()
    {         
        $response=array();
        
        $Date=new CRBSDate();
        $User=new CRBSUser();
        $Payment=new CRBSPayment();
        $Country=new CRBSCountry();
        $Validation=new CRBSValidation();
        $WooCommerce=new CRBSWooCommerce();
        $BookingFormElement=new CRBSBookingFormElement();
       
        $data=CRBSHelper::getPostOption();
     
        if(!is_array($bookingForm=$this->checkBookingForm($data['booking_form_id'])))
        {
            if($bookingForm===-3)
            {
                $response['step']=1;
                $this->setErrorGlobal($response,__('Cannot find at least one vehicle available in selected time period.','car-rental-booking-system'));
                CRBSHelper::createJSONResponse($response);
            }
        }
       
        if((!in_array($data['step_request'],array(2,3,4,5))) || (!in_array($data['step'],array(1,2,3,4))))
        {
            $response['step']=1;
            CRBSHelper::createJSONResponse($response);            
        }
        
        /***/
        /***/
		
        if($data['step_request']>1)
        {
            list($pickupLocationId,$pickupLocationCustomerAddress,$pickupLocationIdSelect)=$this->getBookingFormPickupLocation($bookingForm);
            list($returnLocationId,$returnLocationCustomerAddress,$returnLocationIdSelect)=$this->getBookingFormReturnLocation($bookingForm);

            if($pickupLocationCustomerAddress!==false)
            {
                if(($Validation->isEmpty($pickupLocationCustomerAddress->{'formatted_address'})) || ($Validation->isEmpty($pickupLocationCustomerAddress->{'lat'})) || ($Validation->isEmpty($pickupLocationCustomerAddress->{'lng'}))) 
                    $this->setErrorLocal($response,CRBSHelper::getFormName('pickup_location_address',false),__('Enter a valid location.','car-rental-booking-system')); 
            }
            else
            {
                if(!array_key_exists($pickupLocationId,$bookingForm['dictionary']['location']))
                    $this->setErrorLocal($response,CRBSHelper::getFormName('pickup_location_id',false),__('Enter a valid location.','car-rental-booking-system'));                
            }
            
            if($returnLocationCustomerAddress!==false)
            {
                if(($Validation->isEmpty($returnLocationCustomerAddress->{'formatted_address'})) || ($Validation->isEmpty($returnLocationCustomerAddress->{'lat'})) || ($Validation->isEmpty($returnLocationCustomerAddress->{'lng'}))) 
                    $this->setErrorLocal($response,CRBSHelper::getFormName('return_location_address',false),__('Enter a valid location.','car-rental-booking-system')); 
            }
            else
            {
                if(!array_key_exists($returnLocationId,$bookingForm['dictionary']['location']))
                    $this->setErrorLocal($response,CRBSHelper::getFormName('return_location_id',false),__('Enter a valid location.','car-rental-booking-system'));
            }
			
			if(!isset($response['error']))
			{
				if((int)$bookingForm['meta']['location_the_same_enable']===1)
				{
					if(($pickupLocationIdSelect>0) && ($returnLocationIdSelect>=-1))
					{
						if(($pickupLocationIdSelect!==$returnLocationIdSelect) && ($returnLocationIdSelect!=-1))
						{
							$this->setErrorLocal($response,CRBSHelper::getFormName('return_location_id',false),__('Select the same pickup and return location.','car-rental-booking-system'));
						}
					}
				}
			}
			
            if(!isset($response['error']))
            {
                $dateTimeError=false;

                $data=CRBSBookingHelper::formatDateTimeToStandard($data);

                /***/
				
                if(!$Validation->isDate($data['pickup_date']))
                {
                    $dateTimeError=true;
                    $this->setErrorLocal($response,CRBSHelper::getFormName('pickup_date',false),__('Enter a valid date.','car-rental-booking-system'));
                }
                if(!$Validation->isTime($data['pickup_time']))
                {   
                    $dateTimeError=true;
                    $this->setErrorLocal($response,CRBSHelper::getFormName('pickup_time',false),__('Enter a valid time.','car-rental-booking-system'));
                }
                if(!$Validation->isDate($data['return_date']))
                {
                    $dateTimeError=true;
                    $this->setErrorLocal($response,CRBSHelper::getFormName('return_date',false),__('Enter a valid date.','car-rental-booking-system'));
                }
                if(!$Validation->isTime($data['return_time']))
                {   
                    $dateTimeError=true;
                    $this->setErrorLocal($response,CRBSHelper::getFormName('return_time',false),__('Enter a valid time.','car-rental-booking-system'));
                }  

                /***/
				
                if(!$dateTimeError)
                {
					$currentDate=date_i18n('d-m-Y G:i');
					
                    if(in_array($Date->compareDate($data['pickup_date'].' '.$data['pickup_time'],$currentDate),array(2)))
                    {
                        $dateTimeError=true;
                        $this->setErrorLocal($response,CRBSHelper::getFormName('pickup_date',false),__('Pickup date has to be later than current date.','car-rental-booking-system'));                    
                    }
                }

                if(!$dateTimeError)
                {
                    if(in_array($Date->compareDate($data['pickup_date'].' '.$data['pickup_time'],$data['return_date'].' '.$data['return_time']),array(0,1)))
                    {
                        $dateTimeError=true;
                        $this->setErrorLocal($response,CRBSHelper::getFormName('return_date',false),__('Return date and time has to be later than pickup date.','car-rental-booking-system'));                    
                    }
                }

                /***/

				if(!$dateTimeError)
				{
					$bookingPeriodFrom=$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['pickup_period_from'];
					if(!$Validation->isNumber($bookingPeriodFrom,0,9999))
						$bookingPeriodFrom=0;

					list($date1,$date2)=$this->getDatePickupPeriod($data,$bookingForm['dictionary']['location'][$pickupLocationId],'pickup',$bookingPeriodFrom);
					if($Date->compareDate($date1,$date2)===2)
					{
						$this->setErrorLocal($response,CRBSHelper::getFormName('pickup_date',false),__('Enter a valid date.','car-rental-booking-system'));
						$dateTimeError=true;                    
					}       

					if(!$dateTimeError)
					{
						$bookingPeriodTo=$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['pickup_period_to'];
						if($Validation->isNumber($bookingPeriodTo,0,9999))
						{
							$bookingPeriodTo+=$bookingPeriodFrom;

							list($date1,$date2)=$this->getDatePickupPeriod($data,$bookingForm['dictionary']['location'][$pickupLocationId],'pickup',$bookingPeriodTo);    
							if($Date->compareDate($date1,$date2)===1)
							{
								$this->setErrorLocal($response,CRBSHelper::getFormName('pickup_date',false),__('Enter a valid date.','car-rental-booking-system'));
								$dateTimeError=true;                    
							}                               
						}
					}
				}
           
                /***/

                if(!$dateTimeError)
                {
                    if(is_array($bookingForm['dictionary']['location'][$pickupLocationId]['meta']['date_exclude']))
                    {
                        foreach($bookingForm['dictionary']['location'][$pickupLocationId]['meta']['date_exclude'] as $index=>$value)
                        {
                            if($Date->dateInRange($data['pickup_date'],$value['start'],$value['stop']))
                            {
                                $this->setErrorLocal($response,CRBSHelper::getFormName('pickup_date',false),__('Enter a valid date.','car-rental-booking-system'));
                                $dateTimeError=true;
                                break;
                            }
                        }
                    }
                }

                /***/

                if(!$dateTimeError)
                {
                    $number=$Date->getDayNumberOfWeek($data['pickup_date']);
                    if(isset($bookingForm['dictionary']['location'][$pickupLocationId]['meta']['business_hour'][$number]))
                    {
                        $start=$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['business_hour'][$number]['start'];
                        $stop=$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['business_hour'][$number]['stop'];
                        
                        if($bookingForm['dictionary']['location'][$pickupLocationId]['meta']['after_business_hour_pickup_enable']==1)
                        {
							if($number!=$Date->getDayNumberOfWeek(date_i18n('d-m-Y'))) $start='00:00';
                            $stop='23:59';
                        }
						
                        if(($Validation->isNotEmpty($start)) && ($Validation->isNotEmpty($stop)))
                        {
                            if(!$Date->timeInRange($data['pickup_time'],$start,$stop))
                            {
                                $this->setErrorLocal($response,CRBSHelper::getFormName('pickup_time',false),__('Enter a valid time.','car-rental-booking-system'));
                                $dateTimeError=true;
                            }
                        }
                        else
                        {
                            $this->setErrorLocal($response,CRBSHelper::getFormName('pickup_date',false),__('Enter a valid date.','car-rental-booking-system'));
                            $dateTimeError=true;                        
                        }
						
						if(!$dateTimeError)
						{
							$breakHour=$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['business_hour'][$number]['break'];
							if((is_array($breakHour)) && (count($breakHour)))
							{
								foreach($breakHour as $breakHourValue)
								{
									if($Date->timeInRange($data['pickup_time'],$breakHourValue['start'],$breakHourValue['stop']))
									{
										$this->setErrorLocal($response,CRBSHelper::getFormName('pickup_time',false),__('Enter a valid time.','car-rental-booking-system'));
										$dateTimeError=true;										
										break;
									}
								}
							}
						}
                    }

                    $number=$Date->getDayNumberOfWeek($data['return_date']);
                    if(isset($bookingForm['dictionary']['location'][$returnLocationId]['meta']['business_hour'][$number]))
                    {
                        $start=$bookingForm['dictionary']['location'][$returnLocationId]['meta']['business_hour'][$number]['start'];
                        $stop=$bookingForm['dictionary']['location'][$returnLocationId]['meta']['business_hour'][$number]['stop'];
                     
                        if($bookingForm['dictionary']['location'][$returnLocationId]['meta']['after_business_hour_return_enable']==1)
                        {
                            $start='00:00';
                            $stop='23:59';
                        }
                        
                        if(($Validation->isNotEmpty($start)) && ($Validation->isNotEmpty($stop)))
                        {
                            if(!$Date->timeInRange($data['return_time'],$start,$stop))
                            {
                                $this->setErrorLocal($response,CRBSHelper::getFormName('return_time',false),__('Enter a valid time.','car-rental-booking-system'));
                                $dateTimeError=true;
                            }
                        }
                        else
                        {
                            $this->setErrorLocal($response,CRBSHelper::getFormName('return_date',false),__('Enter a valid date.','car-rental-booking-system'));
                            $dateTimeError=true;                        
                        }
						
						if(!$dateTimeError)
						{
							$breakHour=$bookingForm['dictionary']['location'][$returnLocationId]['meta']['business_hour'][$number]['break'];
							if((is_array($breakHour)) && (count($breakHour)))
							{
								foreach($breakHour as $breakHourValue)
								{
									if($Date->timeInRange($data['return_time'],$breakHourValue['start'],$breakHourValue['stop']))
									{
										$this->setErrorLocal($response,CRBSHelper::getFormName('return_time',false),__('Enter a valid time.','car-rental-booking-system'));
										$dateTimeError=true;										
										break;
									}
								}
							}
						}
                    }
                }
                
                /***/
                
                if(!$dateTimeError)
                {
                    if((int)CRBSOption::getOption('billing_type')===2)
                    {
						$meta=$bookingForm['dictionary']['location'][$pickupLocationId]['meta'];

						$rentalDayCount=CRBSBookingHelper::calculateRentalDayCount($data['pickup_date'],$data['pickup_time'],$data['return_date'],$data['return_time']);
						
						if(isset($meta['vehicle_rent_date']))
						{
							if(count($meta['vehicle_rent_date']))
							{
								foreach($meta['vehicle_rent_date'] as $index=>$value)
								{
									if($Date->dateInRange($data['pickup_date'],$value['start'],$value['stop']))
									{
										if($Validation->isNotEmpty($value['day_count_min']))
										{
											if($value['day_count_min']>$rentalDayCount)
											{
												$this->setErrorLocal($response,CRBSHelper::getFormName('return_date',false),sprintf(__('Minimum number of days to rent a car is %s.','car-rental-booking-system'),$value['day_count_min']));
												$dateTimeError=true;
											}
										}
										
										if(!$dateTimeError)
										{
											if($Validation->isNotEmpty($value['day_count_max']))
											{
												if($value['day_count_max']<$rentalDayCount)
												{
													$this->setErrorLocal($response,CRBSHelper::getFormName('return_date',false),sprintf(__('Maximum number of days to rent a car is %s.','car-rental-booking-system'),$value['day_count_max']));
													$dateTimeError=true;
												}
											}											
										}
									}
								}
							}
						}
						
						if(!$dateTimeError)
						{
							if(($Validation->isNotEmpty($meta['vehicle_rent_day_count_min'])) || ($Validation->isNotEmpty($meta['vehicle_rent_day_count_max'])))
							{
								if($Validation->isNotEmpty($meta['vehicle_rent_day_count_min']))
								{
									if($meta['vehicle_rent_day_count_min']>$rentalDayCount)
										$this->setErrorLocal($response,CRBSHelper::getFormName('return_date',false),sprintf(__('Minimum number of days to rent a car is %s.','car-rental-booking-system'),$meta['vehicle_rent_day_count_min']));
								}
								if($Validation->isNotEmpty($meta['vehicle_rent_day_count_max']))
								{
									if($meta['vehicle_rent_day_count_max']<$rentalDayCount)
										$this->setErrorLocal($response,CRBSHelper::getFormName('return_date',false),sprintf(__('Maximum number of days to rent a car is %s.','car-rental-booking-system'),$meta['vehicle_rent_day_count_max']));                            
								}
							}
						}
                    }
                }
            }
            
            /***/
            
            if($bookingForm['meta']['driver_age_enable']==1)
            {
                $age=$bookingForm['location_driver_age'][$pickupLocationId];
                if(!(($data['driver_age']>=$age['min']) && ($data['driver_age']<=$age['max'])))
                {
                    $this->setErrorLocal($response,CRBSHelper::getFormName('driver_age',false),__('Enter a valid driver\'s age.','car-rental-booking-system'));
                }
            }
            
            /***/
            
            if(isset($response['error']))
            {
                $response['step']=1;
                CRBSHelper::createJSONResponse($response);
            }
        }  
		
        /***/
               
        if($data['step_request']>2)
        {
            $error=false;
            
            if(!array_key_exists($data['vehicle_id'],$bookingForm['dictionary']['vehicle']))
            {
                $error=true;
                $this->setErrorGlobal($response,__('Select a vehicle.','car-rental-booking-system'));
            }
            
            if(!$error)
            {
                if($bookingForm['meta']['order_value_minimum']>0)
                {
                    $Booking=new CRBSBooking();

                    $data['booking_form']=$bookingForm;

                    if(($price=$Booking->calculatePrice($data))!==false)      
                    {
						$orderValueMinimum=number_format($bookingForm['meta']['order_value_minimum']*CRBSCurrency::getExchangeRate(),2,'.','');
						
                        if($orderValueMinimum>$price['total']['sum']['gross']['value'])
                        {
                            $this->setErrorGlobal($response,sprintf(__('Minimum value of order is %s.','car-rental-booking-system'),CRBSPrice::format($orderValueMinimum,CRBSCurrency::getFormCurrency())));
                        }
                    }
                }
            }
            
            if(isset($response['error']))
				$data['step']=$data['step_request']=$response['step']=2;
        }
		
        /***/
                
        if(!isset($response['error']))
        {
            if($data['step_request']>3)
            {
                $error=false;
                
                if($WooCommerce->isEnable($bookingForm['meta']))
                {
                    if(!$User->isSignIn())
                    {
                        if(((int)$data['client_account']===0) && ((int)$bookingForm['meta']['woocommerce_account_enable_type']===2))
                        {
                            $this->setErrorGlobal($response,__('Login to your account or provide all needed details.','car-rental-booking-system'));   
                        }
                    }
                }                
                
                if(!$error)
                {                
                    if($Validation->isEmpty($data['client_contact_detail_first_name']))
                        $this->setErrorLocal($response,CRBSHelper::getFormName('client_contact_detail_first_name',false),__('Enter your first name','car-rental-booking-system'));
                    if($Validation->isEmpty($data['client_contact_detail_last_name']))
                        $this->setErrorLocal($response,CRBSHelper::getFormName('client_contact_detail_last_name',false),__('Enter your last name','car-rental-booking-system'));
                    if(!$Validation->isEmailAddress($data['client_contact_detail_email_address']))
                        $this->setErrorLocal($response,CRBSHelper::getFormName('client_contact_detail_email_address',false),__('Enter valid e-mail address','car-rental-booking-system'));
					if(in_array('client_contact_detail_phone_number',$bookingForm['meta']['field_mandatory']))
					{
						if($Validation->isEmpty($data['client_contact_detail_phone_number']))
							$this->setErrorLocal($response,CRBSHelper::getFormName('client_contact_detail_phone_number',false),__('Please enter valid phone number.','car-rental-booking-system'));
					}
					
                    if((int)$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['driver_license_attach_enable']===1)
                    {
                        if($Validation->isEmpty($data['driver_license_file_tmp_name']))
                            $this->setErrorLocal($response,CRBSHelper::getFormName('driver_license_file_tmp_name',false),__('Attach driver license file.','car-rental-booking-system'));
                    }
                    
					if((int)$bookingForm['meta']['billing_detail_state']!==4)
					{
						if(((int)$data['client_billing_detail_enable']===1) || ((int)$bookingForm['meta']['billing_detail_state']===3))
						{
							if(in_array('client_billing_detail_company_name',$bookingForm['meta']['field_mandatory']))
							{							
								if($Validation->isEmpty($data['client_billing_detail_company_name']))
									$this->setErrorLocal($response,CRBSHelper::getFormName('client_billing_detail_company_name',false),__('Enter valid company name.','car-rental-booking-system'));               
							}
							if(in_array('client_billing_detail_tax_number',$bookingForm['meta']['field_mandatory']))
							{							
								if($Validation->isEmpty($data['client_billing_detail_tax_number']))
									$this->setErrorLocal($response,CRBSHelper::getFormName('client_billing_detail_tax_number',false),__('Enter valid tax number.','car-rental-booking-system'));               
							}						
							if(in_array('client_billing_detail_street_name',$bookingForm['meta']['field_mandatory']))
							{
								if($Validation->isEmpty($data['client_billing_detail_street_name']))
									$this->setErrorLocal($response,CRBSHelper::getFormName('client_billing_detail_street_name',false),__('Enter valid street name.','car-rental-booking-system'));               
							}
							if(in_array('client_billing_detail_street_number',$bookingForm['meta']['field_mandatory']))
							{
								if($Validation->isEmpty($data['client_billing_detail_street_number']))
									$this->setErrorLocal($response,CRBSHelper::getFormName('client_billing_detail_street_number',false),__('Enter valid street number.','car-rental-booking-system'));               
							}						
							if(in_array('client_billing_detail_city',$bookingForm['meta']['field_mandatory']))
							{
								if($Validation->isEmpty($data['client_billing_detail_city']))
									$this->setErrorLocal($response,CRBSHelper::getFormName('client_billing_detail_city',false),__('Enter valid city name.','car-rental-booking-system'));                 
							}
							if(in_array('client_billing_detail_state',$bookingForm['meta']['field_mandatory']))
							{
								if($Validation->isEmpty($data['client_billing_detail_state']))
									$this->setErrorLocal($response,CRBSHelper::getFormName('client_billing_detail_state',false),__('Enter valid state name.','car-rental-booking-system'));                
							}
							if(in_array('client_billing_detail_postal_code',$bookingForm['meta']['field_mandatory']))	
							{
								if($Validation->isEmpty($data['client_billing_detail_postal_code']))
									$this->setErrorLocal($response,CRBSHelper::getFormName('client_billing_detail_postal_code',false),__('Enter valid postal code.','car-rental-booking-system'));                  
							}
							if(in_array('client_billing_detail_country_code',$bookingForm['meta']['field_mandatory']))
							{
								if(!$Country->isCountry($data['client_billing_detail_country_code']))
									$this->setErrorLocal($response,CRBSHelper::getFormName('client_billing_detail_country_code',false),__('Enter valid country name.','car-rental-booking-system')); 
							}
						}
					}
					
                    if($WooCommerce->isEnable($bookingForm['meta']))
                    {
                        if(!$User->isSignIn())
                        {
							if(((int)$data['client_sign_up_enable']===1) || ((int)$bookingForm['meta']['woocommerce_account_enable_type']===2))
                            {
                                $validationResult=$User->validateCreateUser($data['client_contact_detail_email_address'],$data['client_sign_up_login'],$data['client_sign_up_password'],$data['client_sign_up_password_retype']);

                                if(in_array('EMAIL_INVALID',$validationResult))
                                    $this->setErrorLocal($response,CRBSHelper::getFormName('client_contact_detail_email_address',false),__('E-mail address is invalid.','car-rental-booking-system')); 
                                if(in_array('EMAIL_EXISTS',$validationResult))
                                    $this->setErrorLocal($response,CRBSHelper::getFormName('client_contact_detail_email_address',false),__('E-mail address already exists','car-rental-booking-system'));                             

                                if(in_array('LOGIN_INVALID',$validationResult))
                                    $this->setErrorLocal($response,CRBSHelper::getFormName('client_sign_up_login',false),__('Login cannot be empty.','car-rental-booking-system'));                             
                                if(in_array('LOGIN_EXISTS',$validationResult))
                                    $this->setErrorLocal($response,CRBSHelper::getFormName('client_sign_up_login',false),__('Login already exists.','car-rental-booking-system'));                               

                                if(in_array('PASSWORD1_INVALID',$validationResult))
                                    $this->setErrorLocal($response,CRBSHelper::getFormName('client_sign_up_password',false),__('Password cannot be empty.','car-rental-booking-system'));                               
                                if(in_array('PASSWORD2_INVALID',$validationResult))
                                    $this->setErrorLocal($response,CRBSHelper::getFormName('client_sign_up_password_retype',false),__('Password cannot be empty.','car-rental-booking-system'));                             
                                if(in_array('PASSWORD_MISMATCH',$validationResult))
                                    $this->setErrorLocal($response,CRBSHelper::getFormName('client_sign_up_password_retype',false),__('Passwords are not the same.','car-rental-booking-system'));                              
                            }
                        }
                    }
                
                    $error=$BookingFormElement->validateField($bookingForm['meta'],$data);
                    foreach($error as $errorValue)
                        $this->setErrorLocal($response,$errorValue['name'],$errorValue['message_error']); 

                    if(!CRBSBookingHelper::isPayment($data['payment_id'],$bookingForm['meta'],$bookingForm['dictionary']['location'][$pickupLocationId]['meta']))
                        $this->setErrorGlobal($response,__('Select a payment method.','car-rental-booking-system'));

                    $error=$BookingFormElement->validateAgreement($bookingForm['meta'],$data);
                    if($error)
                        $this->setErrorGlobal($response,__('Approve all agreements.','car-rental-booking-system'));              
                }
                
                if(isset($response['error']))
                {
                    $data['step']=$data['step_request']=$response['step']=3;
                } 
            }
        }
        
        /***/
        
        if(!isset($response['error']))
        {
            if($data['step_request']>4)
            {
                $Booking=new CRBSBooking();
                $WooCommerce=new CRBSWooCommerce();
                
                $bookingId=$Booking->sendBooking($data,$bookingForm);
               
				if((int)$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['payment_processing_enable']===1)
				{
					$response['step']=5;
					
					if(!$WooCommerce->isEnable($bookingForm['meta']))
					{
						if(!$Payment->isPayment($data['payment_id']))
							$data['payment_id']=0;
						
						if($data['payment_id']!=0)
						{
							$payment=$Payment->getPayment($data['payment_id']);

							$response['payment_info']=esc_html($bookingForm['dictionary']['location'][$pickupLocationId]['meta']['payment_'.$payment[1].'_info']);

							$response['button_back_to_home_label']=esc_html($bookingForm['meta']['thank_you_page_button_back_to_home_label']);
							$response['button_back_to_home_url_address']=esc_url($bookingForm['meta']['thank_you_page_button_back_to_home_url_address']);

							$response['payment_prefix']=$payment[1];
						}
							
						$response['payment_id']=$data['payment_id'];  

						if(in_array($data['payment_id'],array(2,3)))
						{
							$booking=$Booking->getBooking($bookingId);
							$bookingBilling=$Booking->createBilling($bookingId);              
						}

						if($data['payment_id']==3)
						{
							$response['form']['item_name']=$booking['post']->post_title;
							$response['form']['item_number']=$booking['post']->ID;
							
							$response['form']['currency_code']=$booking['meta']['currency_id'];

							$response['form']['amount']=$bookingBilling['summary']['pay'];
						}
						elseif($data['payment_id']==2)
						{
							$PaymentStripe=new CRBSPaymentStripe();
							
							$sessionId=$PaymentStripe->createSession($booking,$bookingBilling,$bookingForm);
							
							$response['stripe_session_id']=$sessionId;
							$response['stripe_redirect_duration']=$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['payment_stripe_redirect_duration'];
							$response['stripe_publishable_key']=$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['payment_stripe_api_key_publishable'];
						}
					}
					else
					{
						$response['payment_url']=$WooCommerce->sendBooking($bookingId,$bookingForm,$data);

						if($Validation->isNotEmpty($response['payment_url']))
							$response['thank_you_page_enable']=$bookingForm['meta']['thank_you_page_enable'];
                        else $response['thank_you_page_enable']=1;
						
						$response['payment_id']=-1;
					}
				}
				else
                {
					$response['step']=5;
                    $response['payment_id']=-2;       
					
					$response['button_back_to_home_label']=$bookingForm['meta']['thank_you_page_button_back_to_home_label'];
					$response['button_back_to_home_url_address']=$bookingForm['meta']['thank_you_page_button_back_to_home_url_address'];
				}
            }
        }
	              
        /***/
        /***/        

        if($data['step_request']==2)
        {
            if(($vehicleHtml=$this->vehicleFilter(false,$bookingForm))!==false);
                $response['vehicle']=$vehicleHtml;
            
            $response['booking_extra']=$this->createBookingExtra($data,$bookingForm);
        }   
		
        /***/
        
        if($data['step_request']==3)
        {
            $userData=array();
            
            $User=new CRBSUser();
            $WooCommerce=new CRBSWooCommerce();
            
            if(($WooCommerce->isEnable($bookingForm['meta'])) && ($User->isSignIn()))
            {
                if(!array_key_exists('client_contact_detail_first_name',$data))
                    $userData=$WooCommerce->getUserData();
            }
            
            if(!array_key_exists('client_contact_detail_first_name',$data))
            {
                $userData['client_billing_detail_country_code']=$bookingForm['location_client_country_default'][$pickupLocationId];
            }
            
            $response['client_form_sign_id']=$this->createClientFormSignIn($bookingForm);
            $response['client_form_sign_up']=$this->createClientFormSignUp($bookingForm,$userData,$pickupLocationId);
        }
        
        /***/
        
        if(!isset($response['error']))
        {
            $response['step']=$data['step_request'];
            $data['step']=$response['step'];
        }
        else $data['step_request']=$data['step'];
                       
        $response['summary']=$this->createSummary($data,$bookingForm);

        $response['payment']=$this->createPayment($bookingForm['dictionary']['payment'],$bookingForm['dictionary']['payment_woocommerce'],$data['payment_id'],$bookingForm['dictionary']['location'][$pickupLocationId]['meta']);
        
        CRBSHelper::createJSONResponse($response);
        
        /***/
    }

    /**************************************************************************/
    
    function setErrorLocal(&$response,$field,$message)
    {
        if(!isset($response['error']))
        {
            $response['error']['local']=array();
            $response['error']['global']=array();
        }
        
        array_push($response['error']['local'],array('field'=>$field,'message'=>$message));
    }
    
    /**************************************************************************/
    
    function setErrorGlobal(&$response,$message)
    {
        if(!isset($response['error']))
        {
            $response['error']['local']=array();
            $response['error']['global']=array();
        }
        
        array_push($response['error']['global'],array('message'=>$message));
    }
    
    /**************************************************************************/
    
    function createSummaryPriceElementAjax()
    {
        $response=array();
        
        $data=CRBSHelper::getPostOption();
        $data=CRBSBookingHelper::formatDateTimeToStandard($data);
        
        if(!is_array($bookingForm=$this->checkBookingForm($data['booking_form_id'])))
        {
            $response['html']=null;
            CRBSHelper::createJSONResponse($response);
        }
        
        $response['html']=$this->createSummaryPriceElement($data,$bookingForm);
        
        CRBSHelper::createJSONResponse($response);
    }
    
    /**************************************************************************/
    
    function createSummaryPriceElement($data,$bookingForm)
    {
        $html=null;
        $Booking=new CRBSBooking();
        
        $data['booking_form']=$bookingForm;
        
        if(($price=$Booking->calculatePrice($data,null,$bookingForm['meta']['booking_summary_hide_fee']))===false) return(null);

        $netGross=(int)$bookingForm['meta']['booking_summary_display_net_price']===1 ? 'net' : 'gross';
        
        if(CRBSOption::getOption('billing_type')==2)
            $rentalDayCount=CRBSBookingHelper::calculateRentalDayCount($data['pickup_date'],$data['pickup_time'],$data['return_date'],$data['return_time']);

        if($price['vehicle']['sum'][$netGross]['value']!=0)
        {
            if(($bookingForm['meta']['summary_bill_type']==2) && (CRBSOption::getOption('billing_type')==2))
                $label=sprintf(__('%s days x Selected Vehicle','car-rental-booking-system'),$rentalDayCount);
            else $label=__('Selected vehicle','car-rental-booking-system');
                
            $html.=
            '
                <div>
                    <span>'.$label.'</span>
                    <span>'.$price['vehicle']['sum'][$netGross]['format'].'</span>
                </div>
            ';
        }
 
        if((int)$data['booking_form']['meta']['booking_summary_hide_fee']===0)
        {		
            if($price['deposit']['sum'][$netGross]['value']!=0)
            {
                $html.=
                '
                    <div>
                        <span>'.__('Deposit','car-rental-booking-system').'</span>
                        <span>'.$price['deposit']['sum'][$netGross]['format'].'</span>
                    </div>
                ';
            }

            if($price['initial']['sum'][$netGross]['value']!=0)
            {
                $html.=
                '
                    <div>
                        <span>'.__('Initial fee','car-rental-booking-system').'</span>
                        <span>'.$price['initial']['sum'][$netGross]['format'].'</span>
                    </div>
                ';
            }
			
            if($price['one_way']['sum'][$netGross]['value']!=0)
            {
                $html.=
                '
                    <div>
                        <span>'.__('One way fee','car-rental-booking-system').'</span>
                        <span>'.$price['one_way']['sum'][$netGross]['format'].'</span>
                    </div>
                ';
            }
			
            if($price['after_business_hour_pickup']['sum'][$netGross]['value']!=0)
            {
                $html.=
                '
                    <div>
                        <span>'.__('After hours pickup fee','car-rental-booking-system').'</span>
                        <span>'.$price['after_business_hour_pickup']['sum'][$netGross]['format'].'</span>
                    </div>
                ';
            }

            if($price['after_business_hour_return']['sum'][$netGross]['value']!=0)
            {
                $html.=
                '
                    <div>
                        <span>'.__('After hours return fee','car-rental-booking-system').'</span>
                        <span>'.$price['after_business_hour_return']['sum'][$netGross]['format'].'</span>
                    </div>
                ';
            }

            if($price['delivery']['sum'][$netGross]['value']!=0)
            {
                $html.=
                '
                    <div>
                        <span>'.__('Delivery fee','car-rental-booking-system').'</span>
                        <span>'.$price['delivery']['sum'][$netGross]['format'].'</span>
                    </div>
                ';
            }

            if($price['delivery_return']['sum'][$netGross]['value']!=0)
            {
                $html.=
                '
                    <div>
                        <span>'.__('Delivery return fee','car-rental-booking-system').'</span>
                        <span>'.$price['delivery_return']['sum'][$netGross]['format'].'</span>
                    </div>
                ';
            }
			
            if($price['customer_pickup_location']['sum'][$netGross]['value']!=0)
            {
                $html.=
                '
                    <div>
                        <span>'.__('Customer pickup location fee','car-rental-booking-system').'</span>
                        <span>'.$price['customer_pickup_location']['sum'][$netGross]['format'].'</span>
                    </div>
                ';
            }
			
            if($price['customer_return_location']['sum'][$netGross]['value']!=0)
            {
                $html.=
                '
                    <div>
                        <span>'.__('Customer return location fee','car-rental-booking-system').'</span>
                        <span>'.$price['customer_return_location']['sum'][$netGross]['format'].'</span>
                    </div>
                ';
            }
        }
        
        if($price['booking_extra']['sum'][$netGross]['value']!=0)
        {        
            if(($bookingForm['meta']['summary_bill_type']==2) && (CRBSOption::getOption('billing_type')==2))
            {
                $BookingExtra=new CRBSBookingExtra();
                
                $bookingExtra=$BookingExtra->validate($data,$bookingForm,$taxRateDictionary);
        
                $htmlBookingExtra=null;
                
                foreach($bookingExtra as $value)
                {
                    if((int)$value['quantity']!==1)
                        $htmlBookingExtra=__(sprintf('%s x %s',$value['quantity'],$value['name']),'car-rental-booking-system');
                    else $htmlBookingExtra=$value['name'];
                            
                    $htmlBookingExtra=sprintf(__('%s days x %s','car-rental-booking-system'),$rentalDayCount,$htmlBookingExtra);
                  
                    $html.=
                    '
                        <div>
                            <span>'.$htmlBookingExtra.'</span>
                            <span>'.CRBSPrice::format($value['sum_'.$netGross],CRBSOption::getOption('currency')).'</span>
                        </div>
                    ';                       
                }
            }
            else
            {
                $html.=
                '
                    <div>
                        <span>'.__('Extra options','car-rental-booking-system').'</span>
                        <span>'.$price['booking_extra']['sum'][$netGross]['format'].'</span>
                    </div>
                ';
            }
        }
        
        if(($price['total']['tax']['value']!=0) && ((int)$bookingForm['meta']['booking_summary_display_net_price']===1))
        {
            $html.=
            '
                <div>
                    <span>'.__('Tax','car-rental-booking-system').'</span>
                    <span>'.$price['total']['tax']['format'].'</span>
                </div>
            ';              
        }
		
        $html.=
        '
            <div class="crbs-summary-price-element-total">
                <span>'.__('Total','car-rental-booking-system').'</span>
                <span>'.$price['total']['sum']['gross']['format'].'</span>
            </div>
        ';

        if(CRBSBookingFormHelper::isPaymentDepositEnable($bookingForm))
        {
            $html.=
            '
                <div class="crbs-summary-price-element-pay">
                    <span>'.sprintf(__('To pay (deposit)','car-rental-booking-system')).'</span>
                    <span>'.$price['pay']['sum']['gross']['format'].'</span>
                </div>
                <div class="crbs-summary-price-element-balance">
                    <span>'.sprintf(__('Balance','car-rental-booking-system')).'</span>
                    <span>'.$price['balance']['sum']['gross']['format'].'</span>
                </div>
            ';
        }
		
        $html=
        '
            <div class="crbs-summary-price-element">
                '.$html.'
            </div>
        ';

        return($html);
    }
    
    /**************************************************************************/
    
    function createSummary($data,$bookingForm)
    {
        $response=array(null,null,null);
        
        $Date=new CRBSDate();
        $User=new CRBSUser();
        $TaxRate=new CRBSTaxRate();
        $Location=new CRBSLocation();
        $Validation=new CRBSValidation();
        $WooCommerce=new CRBSWooCommerce();
        $BookingExtra=new CRBSBookingExtra();
        $BookingFormSummary=new CRBSBookingFormSummary();
        
        /***/
        
        $taxRateDictionary=$TaxRate->getDictionary();
        
        /***/
                
        $priceHtml=$this->createSummaryPriceElement($data,$bookingForm);
   
        /***/
                
        $bookingExtraHtml=array();
        $bookingExtra=$BookingExtra->validate($data,$bookingForm,$taxRateDictionary);
        
        foreach($bookingExtra as $value)
        {
            array_push($bookingExtraHtml,$value['quantity'].' x '.$value['name'].' - '.CRBSPrice::format($value['sum_gross'],CRBSOption::getOption('currency')));
        }

        /***/
        
        $pickupDate=$Date->formatDateToDisplay($data['pickup_date']);
        $pickupTime=$Date->formatTimeToDisplay($data['pickup_time']);

        $returnDate=$Date->formatDateToDisplay($data['return_date']);
        $returnTime=$Date->formatTimeToDisplay($data['return_time']);
        
        /***/
        
        $rentalPeriodHtml=null;
        $rentalPeriod=CRBSBookingHelper::calculateRentalPeriod($data['pickup_date'],$data['pickup_time'],$data['return_date'],$data['return_time'],$bookingForm['meta']);
        
        switch(CRBSOption::getOption('billing_type'))
        {
            case 1:
               
                $rentalPeriodHtml=sprintf(__('%s hours','car-rental-booking-system'),$rentalPeriod['hour']);
                
            break;
        
            case 2:
                
                $rentalPeriodHtml=sprintf(__('%s days','car-rental-booking-system'),$rentalPeriod['day']);
                
            break;
        
            case 3:
                
				if($rentalPeriod['hour']===0)
					$rentalPeriodHtml=sprintf(__('%s days','car-rental-booking-system'),$rentalPeriod['day']);
                else $rentalPeriodHtml=sprintf(__('%s days, %s hours','car-rental-booking-system'),$rentalPeriod['day'],$rentalPeriod['hour']);
                
            break;
        }
        
        /***/
        
        $userHtml=null;
        if($WooCommerce->isEnable($bookingForm['meta']))
        {
            if($User->isSignIn())
            {
                $userData=$User->getCurrentUserData();
                $userHtml=$userData->data->display_name;
            }
        }
        
        /***/
        
        $pickupLocationAddress=null;
        $returnLocationAddress=null;
        
        list($pickupLocationId,$pickupLocationCustomerAddress)=$this->getBookingFormPickupLocation($bookingForm);
        list($returnLocationId,$returnLocationCustomerAddress)=$this->getBookingFormReturnLocation($bookingForm);
                
        if($pickupLocationCustomerAddress===false)
           $pickupLocationAddress=$Location->displayAddress($pickupLocationId); 
        else $pickupLocationAddress=$pickupLocationCustomerAddress->{'formatted_address'};
        
        if($returnLocationCustomerAddress===false)
        {
            if((int)$data['return_location_id']===-1)
                $returnLocationAddress=__('Return to the same location','car-rental-booking-system');
            else $returnLocationAddress=$Location->displayAddress($returnLocationId);  
        }
        else $returnLocationAddress=$returnLocationCustomerAddress->{'formatted_address'};
        
        /***/
        
        switch($data['step_request'])
        {
            case 2:
                
                if(!is_null($userHtml))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Logged as','car-rental-booking-system'),
                            $userHtml
                        )
                    );                    
                }
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Pickup location','car-rental-booking-system'),
                        $pickupLocationAddress
                    ),
                    1,
                    true
                );
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Pickup date, time','car-rental-booking-system'),
                        $pickupDate.', '.$pickupTime
                    )
                );
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Return location','car-rental-booking-system'),
                        $returnLocationAddress
                    ),
                    1,
                    true
                );
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Return date, time','car-rental-booking-system'),
                        $returnDate.', '.$returnTime
                    )
                ); 
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Rental period','car-rental-booking-system'),
                        $rentalPeriodHtml
                    )
                ); 
                
                $response[0]=$BookingFormSummary->create(__('Booking details','car-rental-booking-system')).$priceHtml;
                
            break;
        
            case 3:
                
                if(!is_null($userHtml))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Logged as','car-rental-booking-system'),
                            $userHtml
                        )
                    );                    
                }
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Pickup location','car-rental-booking-system'),
                        $pickupLocationAddress
                    ),
                    1,
                    true
                );
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Pickup date, time','car-rental-booking-system'),
                        $pickupDate.', '.$pickupTime
                    )
                );
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Return location','car-rental-booking-system'),
                        $returnLocationAddress
                    ),
                    1,
                    true
                );
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Return date, time','car-rental-booking-system'),
                        $returnDate.', '.$returnTime
                    )
                ); 
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Rental period','car-rental-booking-system'),
                        $rentalPeriodHtml
                    )
                ); 
                
                $response[0]=$BookingFormSummary->create(__('Booking details','car-rental-booking-system')).$priceHtml;
                
            break;
        
            case 4:
                
                if(!is_null($userHtml))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Logged as','car-rental-booking-system'),
                            $userHtml
                        )
                    );                    
                }
                
                $BookingFormSummary->add
                (
                    array
                    (
                        array
                        (
                            __('First name','car-rental-booking-system'),
                            $data['client_contact_detail_first_name']
                        ),
                        array
                        (
                            __('Last name','car-rental-booking-system'),
                            $data['client_contact_detail_last_name']
                        )
                    ),
                    2
                );
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('E-mail address','car-rental-booking-system'),
                        $data['client_contact_detail_email_address']
                    )
                );       
                
                
                if($Validation->isNotEmpty($data['client_contact_detail_phone_number']))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Phone number','car-rental-booking-system'),
                            $data['client_contact_detail_phone_number']
                        )
                    );
                }
                
                if(($data['client_billing_detail_enable']==1) && ((int)$bookingForm['meta']['billing_detail_enable']!==4))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            array
                            (
                                __('Company name','car-rental-booking-system'),
                                $data['client_billing_detail_company_name']
                            ),
                            array
                            (
                                __('Tax number','car-rental-booking-system'),
                                $data['client_billing_detail_tax_number']
                            )
                        ),
                        2
                    );
                    
                    $dataAddress=array
                    (
                        'street'                                                =>  $data['client_billing_detail_street_name'],
                        'street_number'                                         =>  $data['client_billing_detail_street_number'],
                        'postcode'                                              =>  $data['client_billing_detail_postal_code'],
                        'city'                                                  =>  $data['client_billing_detail_city'],
                        'state'                                                 =>  $data['client_billing_detail_state'],
                        'country'                                               =>  $data['client_billing_detail_country_code']
                    );
                    
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Billing address','car-rental-booking-system'),
                            CRBSHelper::displayAddress($dataAddress)
                        ),
                        1,
                        true
                    );
                }
                
                if($Validation->isNotEmpty($data['comment']))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Comments','car-rental-booking-system'),
                            $data['comment']
                        )
                    );
                }
                
                $response[0].=$BookingFormSummary->create(__('Driver Details','car-rental-booking-system'),3);
                
                /***/
               
                $paymentName=CRBSBookingHelper::getPaymentName($data['payment_id'],-1,$bookingForm['meta']);
                
                if(!is_null($paymentName))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Your choice','car-rental-booking-system'),
                            $paymentName
                        )
                    );  

                    $response[0].=$BookingFormSummary->create(__('Payment Method','car-rental-booking-system'),3);
                }
                
                /***/

                $BookingFormSummary->add
                (
                    array
                    (
                        __('Pickup location','car-rental-booking-system'),
                        $pickupLocationAddress
                    ),
                    1,
                    true
                );
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Pickup date, time','car-rental-booking-system'),
                        $pickupDate.', '.$pickupTime
                    )
                );
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Return location','car-rental-booking-system'),
                        $returnLocationAddress
                    ),
                    1,
                    true
                );
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Return date, time','car-rental-booking-system'),
                        $returnDate.', '.$returnTime
                    )
                ); 
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Rental period','car-rental-booking-system'),
                        $rentalPeriodHtml
                    )
                ); 
                
                $response[1]='<div class="crbs-google-map-summary"></div>'.$BookingFormSummary->create(__('Booking details','car-rental-booking-system'));

                /***/
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Vehicle','car-rental-booking-system'),
                        $bookingForm['dictionary']['vehicle'][$data['vehicle_id']]['post']->post_title
                    )
                );     
                
                if(count($bookingExtraHtml))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Extra options','car-rental-booking-system'),
                            $bookingExtraHtml
                        ),
                        3
                    );
                }
                
                $couponHtml=null;
                if((int)$bookingForm['meta']['coupon_enable']===1)
                {
                    $couponHtml=
                    '
                        <div class="crbs-clear-fix crbs-coupon-code-section">
                            <div class="crbs-form-field">
                                <label>'.__('Do you have a discount code?','car-rental-booking-system').'</label>
                                <input maxlength="12" name="'.CRBSHelper::getFormName('coupon_code',false).'" value="" type="text">
                            </div>
                            <a href="#" class="crbs-button crbs-button-style-2">
                                '.__('Apply code','car-rental-booking-system').'
                                <span class="crbs-meta-icon-arrow-horizontal"></span>
                            </a>
                        </div>
                    ';
                }
                
                $thumbnailHtml=null;
                $thumbnail=get_the_post_thumbnail_url($data['vehicle_id'],PLUGIN_CRBS_CONTEXT.'_vehicle');
        
                if($thumbnail!==false)
                    $thumbnailHtml='<div><img src="'.esc_url($thumbnail).'" alt=""/></div>';
                
                $response[2]=$thumbnailHtml.$BookingFormSummary->create(__('Vehicle info','car-rental-booking-system'),2).$couponHtml.$priceHtml;
                
            break;
        }
        
        return($response);
    }
    
    /**************************************************************************/ 
    
    function createVehicle($data,$bookingForm,&$priceToSort)
    {
        $html=array(null);
        
        $htmlThumbnail=null;
        $htmlDescription=null;
        $htmlDescriptionButton=null;
        
        $Vehicle=new CRBSVehicle();
        $Validation=new CRBSValidation();
        
        $argument=array
        (
            'booking_form_id'                                                   =>  $data['booking_form_id'],
            'vehicle_id'                                                        =>  $data['vehicle_id'],
            'pickup_location_id'                                                =>  $data['pickup_location_id'],
            'pickup_date'                                                       =>  $data['pickup_date'],
            'pickup_time'                                                       =>  $data['pickup_time'],
            'return_location_id'                                                =>  $data['return_location_id'],
            'return_date'                                                       =>  $data['return_date'],
            'return_time'                                                       =>  $data['return_time'],
            'driver_age'                                                        =>  $data['driver_age']
        );
               
        $price=$Vehicle->calculatePrice($argument,$bookingForm);
        
        /***/
        
        $thumbnail=get_the_post_thumbnail_url($data['vehicle_id'],PLUGIN_CRBS_CONTEXT.'_vehicle');
        if($thumbnail!==false)
            $htmlThumbnail='<div class="crbs-vehicle-image"><img src="'.esc_url($thumbnail).'" alt=""/></div>';
            
        /***/
                
        if($Validation->isNotEmpty($data['vehicle']['post']->post_content))
            $htmlDescription='<p>'.$data['vehicle']['post']->post_content.'</p>';

        if((array_key_exists('attribute',$data['vehicle'])) && (is_array($data['vehicle']['attribute'])))
        {
            $i=0;
            $htmlAttribute=array(null,null);
            $count=ceil(count($data['vehicle']['attribute'])/2);
            
            foreach($data['vehicle']['attribute'] as $value)
            {
                $index=($i++)<$count ? 0 : 1;
                $htmlAttribute[$index].=
                '
                    <li class="crbs-clear-fix">
                        <div>'.esc_html($value['name']).'</div>
                        <div>'.esc_html($value['value']).'</div>
                    </li>
                ';
            }
            
            if($Validation->isNotEmpty($htmlAttribute[0]))
                $htmlAttribute[0]='<ul class="crbs-list-reset">'.$htmlAttribute[0].'</ul>';
            if($Validation->isNotEmpty($htmlAttribute[1]))
                $htmlAttribute[1]='<ul class="crbs-list-reset">'.$htmlAttribute[1].'</ul>';                
            
            $htmlDescription.=
            '
                <div class="crbs-vehicle-attribute crbs-clear-fix">
                    '.$htmlAttribute[0].'
                    '.$htmlAttribute[1].'    
                </div>
            ';
        }
        
        if($Validation->isNotEmpty($htmlDescription))
            $htmlDescription='<div class="crbs-vehicle-description crbs-clear-fix"><div>'.$htmlDescription.'</div></div>';

        /***/
        
        if($Validation->isNotEmpty($htmlDescription))
        {
            $htmlDescriptionButton=
            '
                <a href="#" class="crbs-vehicle-description-button crbs-clear-fix">
                    <span>'.esc_html__('View Vehicle Info','car-rental-booking-system').'</span>
                    <span>'.esc_html__('Close Vehicle Info','car-rental-booking-system').'</span>
                </a> 
            ';
        }
        
        /***/
        
        $htmlPricePerDay=null;
       
        if((int)CRBSOption::getOption('billing_type')===2)
        {
            if($bookingForm['meta']['vehicle_price_per_day_enable']==1)
                $htmlPricePerDay='<div>'.$price['price']['price_per_day']['gross']['format'].esc_html__(' /day','car-rental-booking-system').'</div>';
        }
        
        /***/
        
        $htmlOrSimilar=null;
        if($data['vehicle']['meta']['booking_vehicle_similar_enable']==1)
            $htmlOrSimilar='<span>'.__('or&nbsp;similar','car-rental-booking-system').'</span>';
        
        /***/

		$htmlMeta=null;
		
		if(($Validation->isNotEmpty($data['vehicle']['meta']['passenger_count'])) && (in_array(1,$bookingForm['meta']['vehicle_attribute_enable'])))
		{
			$htmlMeta.=
			'
				<li>
					<span class="crbs-meta-icon-seat"></span>
					<span>'.esc_html($data['vehicle']['meta']['passenger_count']).'</span>
				</li>				
			';
		}
		if(($Validation->isNotEmpty($data['vehicle']['meta']['bag_count'])) && (in_array(2,$bookingForm['meta']['vehicle_attribute_enable'])))
		{
			$htmlMeta.=
			'
				<li>
					<span class="crbs-meta-icon-bag"></span>
					<span>'.esc_html($data['vehicle']['meta']['bag_count']).'</span>
				</li>			
			';
		}
		if(($Validation->isNotEmpty($data['vehicle']['meta']['gearbox_type'])) && (in_array(3,$bookingForm['meta']['vehicle_attribute_enable'])))
		{
			$htmlMeta.=
			'
				<li>
					<span class="crbs-meta-icon-transmission"></span>
					<span>'.esc_html($data['vehicle']['meta']['gearbox_type']).'</span>
				</li>		
			';
		}
		if(($Validation->isNotEmpty($data['vehicle']['meta']['fuel_state'])) && (in_array(4,$bookingForm['meta']['vehicle_attribute_enable'])))
		{
			$htmlMeta.=
			'
				<li>
					<span class="crbs-meta-icon-fuel"></span>
					<span>'.esc_html($data['vehicle']['meta']['fuel_state']).'</span>
				</li>		
			';
		}		
		
		if($Validation->isNotEmpty($htmlMeta))
		{
			$htmlMeta=
			'
			   <div class="crbs-vehicle-meta crbs-clear-fix">
					<ul>
						'.$htmlMeta.'
					</ul>
				</div>	
			';
		}
		
		$priceHtml=$price['price']['sum']['gross']['format'];
		
		if(isset($price['price_before_coupon']))
		{
			$priceHtml='<span>'.$price['price_before_coupon']['price']['sum']['gross']['format'].'</span>'.$priceHtml;
		}
	
        $html=
        '
            <div class="crbs-vehicle crbs-clear-fix" data-id="'.esc_attr($data['vehicle_id']).'">

                '.$htmlThumbnail.'

                <div class="crbs-vehicle-content">

                    <div class="crbs-vehicle-header crbs-clear-fix"> 
                        <div>'.get_the_title($data['vehicle_id']).'</div> '.$htmlOrSimilar.'
                    </div>

					'.$htmlMeta.'

                    '.$htmlDescriptionButton.'

                </div>

                <div class="crbs-vehicle-price">

                    <div>'.$priceHtml.'</div>
                        
                    '.$htmlPricePerDay.'

                    <a href="#" class="crbs-button crbs-button-style-2 '.($data['vehicle_selected_id']==$data['vehicle_id'] ? 'crbs-state-selected' : null).'">
                        '.esc_html__('Select','car-rental-booking-system').'
                        <span class="crbs-meta-icon-tick"></span>
                    </a>

                </div>
                
                '.$htmlDescription.'

            </div>
        ';
        
        $priceToSort=$price['price']['sum']['gross']['value'];
        
        return($html);
    }
    
    /**************************************************************************/
    
    function createBookingExtra($data,$bookingForm)
    {
        $html=null;

        if(count($bookingForm['dictionary']['booking_extra']))
        {
            $BookingExtra=new CRBSBookingExtra();

            foreach($bookingForm['dictionary']['booking_extra'] as $index=>$value)
            {
                $price=$BookingExtra->calculatePrice($value,$bookingForm['dictionary']['tax_rate']);
                
                $class=array();
                if($value['meta']['quantity_enable']==1)
                    array_push($class,'crbs-booking-extra-list-item-quantity-enable');
                
                /***/
                
                $vehiclePrice=array(0=>$price['price']['gross']['format']);
                if(isset($value['meta']['vehicle_price']))
                {
                    foreach($value['meta']['vehicle_price'] as $vehiclePriceIndex=>$vehiclePriceData)
                    {
                        if((int)$vehiclePriceData['status']!=1) continue;
                        
                        $vehiclePrice[$vehiclePriceIndex]=CRBSPrice::calculateGross($vehiclePriceData['price'],$vehiclePriceData['tax_rate_id']);
                        $vehiclePrice[$vehiclePriceIndex]=CRBSPrice::format($vehiclePrice[$vehiclePriceIndex],CRBSOption::getOption('currency'));
                    }
                }
               
                /***/
                
                $html.=
                '
                    <li'.CRBSHelper::createCSSClassAttribute($class).' data-vehicle_price="'.esc_attr(json_encode($vehiclePrice)).'">
                        <div class="crbs-column-1">
                            <span class="booking-form-extra-name">
                                '.get_the_title($index).'
                            </span>
                            <span class="booking-form-extra-price">
                               <span>'.(array_key_exists($data['vehicle_id'],$vehiclePrice) ? $vehiclePrice[$data['vehicle_id']] : $price['price']['gross']['format']).'</span>'.$price['suffix'].'
                            </span>
                            <span class="booking-form-extra-description">
                                '.esc_html($value['meta']['description']).'
                            </span>
                        </div>
                ';

                if($value['meta']['quantity_enable']==1)
                {
                    $html.=
                    '
                        <div class="crbs-column-2">
                            <div class="crbs-form-field">
                                <label>'.__('Number','car-rental-booking-system').'</label>
                                <input type="text" name="'.CRBSHelper::getFormName('booking_extra_'.$index.'_quantity',false).'" value="'.(array_key_exists('booking_extra_'.$index.'_quantity',$data) ? (int)$data['booking_extra_'.$index.'_quantity'] : $value['meta']['quantity_default']).'" data-quantity-max="'.(int)$value['meta']['quantity_max'].'"/>
                            </div>  
                        </div>
                    ';
                }
                else
                {
                    $html.=
                    '
                        <div class="crbs-column-2">
							<div class="crbs-form-field">
								<label>'.__('Number','car-rental-booking-system').'</label>
								<input type="text" name="'.CRBSHelper::getFormName('booking_extra_'.$index.'_quantity',false).'" value="1" data-quantity-max="1" readonly="readonly"/>
							</div>
						</div>
                    ';
                }
                
                $class=array('crbs-button','crbs-button-style-2');
                $selected=array_map('intval',explode(',',$data['booking_extra_id']));
                
                if(in_array($index,$selected))
                    array_push($class,'crbs-state-selected');
				else
				{
					if(!in_array('booking_extra_'.$index.'_quantity',$data))
					{
						if((int)$value['meta']['button_select_default_state']===1)
							array_push($class,'crbs-state-selected');
					}		
				}
				
				if((int)$value['meta']['button_select_default_state']===2)
					array_push($class,'crbs-state-selected','crbs-state-selected-mandatory');
                
                $html.=
                '
                        <div class="crbs-column-3">
                            <a href="#" data-id="'.esc_attr($index).'"'.CRBSHelper::createCSSClassAttribute($class).'>
                                '.__('Select','car-rental-booking-system').'
                                <span class="crbs-meta-icon-tick"></span>
                            </a>
                        </div>
                    </li>
                ';
            }
        }
        
        if(!is_null($html))
        {
            $html=
            '
                <h4 class="crbs-header">
                    '.__('Add-on options','car-rental-booking-system').'
                </h4>    
                <div class="crbs-booking-extra-list">
                    <ul class="crbs-list-reset">
                        '.$html.'
                    </ul>
                </div>
            ';
        }
        
        return($html);
    }
    
    /**************************************************************************/
    
    function createPayment($payment,$paymentWooCommerce,$paymentIdSelected,$pickupLocationMeta)
    {
        $html=null;
		
		$Payment=new CRBSPayment();
		$Validation=new CRBSValidation();
		
        if(count($paymentWooCommerce))
        {
            foreach($paymentWooCommerce as $index=>$value)
            {
                $class=array('crbs-payment-type-woocommerce-'.esc_attr($index));

                if($paymentIdSelected==$index)
                    array_push($class,'crbs-state-selected');

                $html.=
                '
                    <li>
                        <a href="#"'.CRBSHelper::createCSSClassAttribute($class).'data-payment-id="'.esc_attr($value->{'id'}).'">               
                            <span class="crbs-payment-name">'.esc_html($value->{'title'}).'</span>
                            <span class="crbs-meta-icon-tick"></span>
                        </a>
                    </li>                       
                ';
            }
        }
        else if(count($payment))
        {
			if(!$Payment->isPayment($paymentIdSelected))
				$paymentIdSelected=$pickupLocationMeta['payment_default_id'];
			
            foreach($payment as $index=>$value)
            {
				$style=array();
                $class=array('crbs-payment-type-'.esc_attr($index));

                if($paymentIdSelected==$index)
                    array_push($class,'crbs-state-selected');
				
				if($Validation->isNotEmpty($pickupLocationMeta['payment_'.$value[1].'_logo_src']))
				{
					$class[]='crbs-payment-background-image';
                    $style['background-image']='url(\''.$pickupLocationMeta['payment_'.$value[1].'_logo_src'].'\')';
                }
			
                $html.=
                '
                    <li>
                        <a href="#"'.CRBSHelper::createCSSClassAttribute($class).CRBSHelper::createStyleAttribute($style).'data-payment-id="'.esc_attr($index).'">
                ';

                if($index==1)
                {
                    $html.=
                    '
                            <span class="crbs-meta-icon-wallet"></span>
                            <span class="crbs-payment-name">'.esc_html($Payment->getPaymentName($index)).'</span>
                    ';
                }
                else if($index==4)
                {
                    $html.=
                    '
                            <span class="crbs-meta-icon-bank"></span>
                            <span class="crbs-payment-name">'.esc_html($Payment->getPaymentName($index)).'</span>
                    ';
                }
                else if($index==5)
                {
                    $html.=
                    '
                            <span class="crbs-meta-icon-bank"></span>
                            <span class="crbs-payment-name">'.esc_html($Payment->getPaymentName($index)).'</span>
                    ';
                }

                $html.=
                '
                            <span class="crbs-meta-icon-tick"></span>
                        </a>
                    </li>  
                ';
            }
        }
        else return($html);
        
        $html=
        '
            <h4 class="crbs-header">
                '.esc_html__('Payment Method','car-rental-booking-system').'
            </h4>
                
            <ul class="crbs-payment crbs-list-reset">
                '.$html.'
            </ul>
        ';
        
        return($html);
    }
    
    /**************************************************************************/ 
    
    function getVehiclePassengerCountRange($vehicle)
    {
        $count=array();
        foreach($vehicle as $value)
            array_push($count,$value['meta']['passenger_count']);
            
        $count=array('min'=>1,'max'=>max($count));
        
        return($count);
    }
    
     /**************************************************************************/ 
    
    function getVehicleBagCountRange($vehicle)
    {
        $count=array();
        foreach($vehicle as $value)
            array_push($count,$value['meta']['bag_count']);
            
        $count=array('min'=>1,'max'=>max($count));
        
        return($count);      
    }
    
    /**************************************************************************/
    
    function vehicleFilter($ajax=true,$bookingForm=null)
    {           
        if(!is_bool($ajax)) $ajax=true;
        
        $html=null;
        $response=array();
        
        $Validation=new CRBSValidation();
        
        $data=CRBSHelper::getPostOption();
        $data=CRBSBookingHelper::formatDateTimeToStandard($data);
		
		CRBSHelper::removeUIndex($data,'driver_age');
        
        if(is_null($bookingForm))
        {
            if(!is_array($bookingForm=$this->checkBookingForm($data['booking_form_id'])))
            {
                if(!$ajax) return(false);

                $this->setErrorGlobal($response,__('There are no vehicles which match your filter criteria.','car-rental-booking-system'));
                CRBSHelper::createJSONResponse($response);
            }
        }
        
        list($data['pickup_location_id'])=$this->getBookingFormPickupLocation($bookingForm);
        list($data['return_location_id'])=$this->getBookingFormReturnLocation($bookingForm);
        
        if(!$Validation->isNumber($data['vehicle_bag_count'],1,99)) $data['vehicle_bag_count']=1;
        if(!$Validation->isNumber($data['vehicle_passenger_count'],1,99)) $data['vehicle_passenger_count']=1;        
        
        /***/
        
        $vehicleHtml=array();
        $vehiclePrice=array();
        
        $categoryId=(int)$data['vehicle_category'];
   
		foreach($bookingForm['dictionary']['vehicle'] as $index=>$value)
        {
            if($categoryId>0)
            {
                if(!has_term($categoryId,CRBSVehicle::getCPTCategoryName(),$index)) continue;
            }
            
            if(!(($value['meta']['passenger_count']>=$data['vehicle_passenger_count']) && ($value['meta']['bag_count']>=$data['vehicle_bag_count']))) continue;
            
            $argument=array
            (
                'booking_form_id'                                               =>  $bookingForm['post']->ID,
                'vehicle'                                                       =>  $value,
                'vehicle_id'                                                    =>  $value['post']->ID,
                'vehicle_selected_id'                                           =>  $data['vehicle_id'],
                'pickup_location_id'                                            =>  $data['pickup_location_id'],
                'pickup_date'                                                   =>  $data['pickup_date'],
                'pickup_time'                                                   =>  $data['pickup_time'],
                'return_location_id'                                            =>  $data['return_location_id'],
                'return_date'                                                   =>  $data['return_date'],
                'return_time'                                                   =>  $data['return_time'],
                'driver_age'                                                    =>  $data['driver_age']
            );
            
            $price=0;
            
            $vehicleHtml[$index]=$this->createVehicle($argument,$bookingForm,$price);
            $vehiclePrice[$index]=$price;
        }
		
        if(in_array((int)$bookingForm['meta']['vehicle_sorting_type'],array(1,2)))
        {
            asort($vehiclePrice);         
            if((int)$bookingForm['meta']['vehicle_sorting_type']===2)
                $vehiclePrice=array_reverse($vehiclePrice,true);
        }
		
        foreach($vehiclePrice as $index=>$value)
            $html.='<li>'.$vehicleHtml[$index].'</li>';
        
        $response['html']=$html;
        
        if($Validation->isEmpty($html))
        {
            if($ajax)
            {
                $this->setErrorGlobal($response,__('There are no vehicles which match your filter criteria.','car-rental-booking-system'));
                CRBSHelper::createJSONResponse($response);
            }
        }
        
        if(!$ajax) return($html);
        
        CRBSHelper::createJSONResponse($response);
    }
    
    /**************************************************************************/
    
    function checkCouponCode()
    {        
        $response=array();
        
        $data=CRBSHelper::getPostOption();
        $data=CRBSBookingHelper::formatDateTimeToStandard($data);
        
        if(!is_array($bookingForm=$this->checkBookingForm($data['booking_form_id'])))
        {
            $response['html']=null;
            CRBSHelper::createJSONResponse($response);
        }
        
        $response['html']=$this->createSummaryPriceElement($data,$bookingForm);
        
		$couponCodeSourceType=0;
		
        $Coupon=new CRBSCoupon();
        $coupon=$Coupon->checkCode($bookingForm,$couponCodeSourceType);
        
        $response['error']=$coupon===false ? 1 : 0;
        
        if($response['error']===1)
           $response['message']=__('Provided coupon is invalid.','car-rental-booking-system'); 
        else 
            $response['message']=__('Provided coupon is valid. Discount has been granted.','car-rental-booking-system');
        
        CRBSHelper::createJSONResponse($response);
    }
    
    /**************************************************************************/   
   
    function createClientFormSignIn($bookingForm)
    {
        $User=new CRBSUser();
        $WooCommerce=new CRBSWooCommerce();
        
        if(!$WooCommerce->isEnable($bookingForm['meta'])) return;
        if($User->isSignIn()) return;
        
		if((int)$bookingForm['meta']['woocommerce_account_enable_type']===0) return;
		
        $data=CRBSHelper::getPostOption();
        
        $html=
        '
            <div class="crbs-client-form-sign-in">

                <div class="crbs-form-panel">

                    <label class="crbs-form-panel-label">'.esc_html__('Sign In','car-rental-booking-system').'</label>

                    <div class="crbs-form-panel-content crbs-clear-fix">                    

                        <div class="crbs-clear-fix">
                            <div class="crbs-form-field crbs-form-field-width-50">
                                <label>'.esc_html__('Login *','car-rental-booking-system').'</label>
                                <input type="text" name="'.CRBSHelper::getFormName('client_sign_in_login',false).'" value=""/>
                            </div>
                            <div class="crbs-form-field crbs-form-field-width-50">
                                <label>'.esc_html__('Password *','car-rental-booking-system').'</label>
                                <input type="password" name="'.CRBSHelper::getFormName('client_sign_in_password',false).'" value=""/>
                            </div>
                        </div>

                    </div>
                </div>
            
                <div class="crbs-clear-fix crbs-float-right">

                   <a href="#" class="crbs-button crbs-button-style-2 crbs-button-sign-up">
                        '.esc_html__('Don\'t Have an Account?','car-rental-booking-system').'
                   </a> 

                   <a href="#" class="crbs-button crbs-button-style-1 crbs-button-sign-in">
                       '.esc_html__('Sign In','car-rental-booking-system').'
                   </a> 

                   <input type="hidden" name="'.CRBSHelper::getFormName('client_account',false).'" value="'.(int)$data['client_account'].'"/> 

                </div>

            </div>
        ';
        
        return($html);
    }
    
    /**************************************************************************/
    
    function createClientFormSignUp($bookingForm,$userData=array(),$pickupLocationId=-1)
    {
        $User=new CRBSUser();
        $WooCommerce=new CRBSWooCommerce();
        $BookingFormElement=new CRBSBookingFormElement();
        
        /***/
        
        $data=CRBSHelper::getPostOption();
        if(count($userData)) $data=$userData;

        /***/
        
        $html=null;
        $htmlElement=array(null,null,null,null,null,null);
        
        $htmlElementCountry=null;
        
		$countryAvailable=array(-1);
		if(array_key_exists($pickupLocationId,$bookingForm['dictionary']['location']))
			$countryAvailable=$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['country_available'];
		
        foreach($bookingForm['dictionary']['country'] as $index=>$value)
		{	
			if((in_array(-1,$countryAvailable)) || (in_array($index,$countryAvailable)))
				$htmlElementCountry.='<option value="'.esc_attr($index).'" '.($data['client_billing_detail_country_code']==$index ? 'selected' : null).'>'.esc_html($value[0]).'</option>';
		}
		
        $htmlElement[1]=$BookingFormElement->createField(1,$bookingForm['meta']);
        
        $htmlElement[2]=$BookingFormElement->createField(2,$bookingForm['meta']);
        
        $panel=$BookingFormElement->getPanel($bookingForm['meta']);
        foreach($panel as $index=>$value)
        {
            if(in_array($value['id'],array(1,2))) continue;
            $htmlElement[3].=$BookingFormElement->createField($value['id'],$bookingForm['meta']);
        }
        
        if($WooCommerce->isEnable($bookingForm['meta']))
        {
            if(!$User->isSignIn())
            {
                if(in_array((int)$bookingForm['meta']['woocommerce_account_enable_type'],array(1,2)))
                {
					$class=array(array('crbs-form-checkbox'),array('crbs-disable-section'));
                    
                    if(in_array((int)$bookingForm['meta']['woocommerce_account_enable_type'],array(2)))
                    {
                        
                    }
                    else
                    {
                        if((int)$data['client_sign_up_enable']===0)
                        {
                            array_push($class[1],'crbs-hidden');
                        }
                        else
                        {
                            array_push($class[0],'crbs-state-selected');
                        }
                    }			
					
					$htmlElement[4].=
					'
						<div class="crbs-form-panel">
							<label class="crbs-form-panel-label">
					';
					
                    if(in_array((int)$bookingForm['meta']['woocommerce_account_enable_type'],array(2)))
                    {
						unset($class[1][0]);
                        $htmlElement[4].=esc_html__('New account','car-rental-booking-system');
                    }
                    else
                    {
                        $htmlElement[4].=
                        '
								<span'.CRBSHelper::createCSSClassAttribute($class[0]).'>
									<span class="crbs-meta-icon-tick"></span>
								</span>
								<span>'.esc_html__('Create an account?','car-rental-booking-system').'</span>
								<input type="hidden" name="'.CRBSHelper::getFormName('client_sign_up_enable',false).'" value="'.esc_attr($data['client_sign_up_enable']).'"/> 
                        ';                        
                    }					
	
					$htmlElement[4].=
					'
							</label>

							<div class="crbs-form-panel-content crbs-clear-fix">               

								<div>

									<div class="crbs-clear-fix">
										<div class="crbs-form-field crbs-form-field-width-33">
											<label>'.esc_html__('Login','car-rental-booking-system').'</label>
											<input type="text" name="'.CRBSHelper::getFormName('client_sign_up_login',false).'"/>
										</div>
										<div class="crbs-form-field crbs-form-field-width-33">
											<label>
												'.esc_html__('Password','car-rental-booking-system').'
												&nbsp;
												<a href="#" class="crbs-sign-up-password-generate">'.esc_html__('Generate','car-rental-booking-system').'</a>
												<a href="#" class="crbs-sign-up-password-show">'.esc_html__('Show','car-rental-booking-system').'</a>
											</label>
											<input type="password" name="'.CRBSHelper::getFormName('client_sign_up_password',false).'"/>
										</div>
										<div class="crbs-form-field crbs-form-field-width-33">
											<label>'.esc_html__('Re-type password','car-rental-booking-system').'</label>
											<input type="password" name="'.CRBSHelper::getFormName('client_sign_up_password_retype',false).'"/>
										</div>
									</div>

								</div>

								<div'.CRBSHelper::createCSSClassAttribute($class[1]).'></div>

							</div>

						</div>
					';
				}
            }
        }
		        
        /***/
        
        $class=array(array('crbs-client-form-sign-up','crbs-hidden'),array('crbs-form-checkbox'),array('crbs-disable-section'));
        
        if($WooCommerce->isEnable($bookingForm['meta']))
        {
            if(($User->isSignIn()) || ((int)$data['client_account']===1) || ((int)$bookingForm['meta']['woocommerce_account_enable_type']===0)) unset($class[0][1]);
        }  
        else unset($class[0][1]);
        
		if((int)$bookingForm['meta']['billing_detail_state']===3)
		{
			$data['client_billing_detail_enable']=1;
			array_push($class[1],'crbs-state-selected-mandatory');
		}
		elseif((int)$bookingForm['meta']['billing_detail_state']===2)
		{
			if(!array_key_exists('client_billing_detail_enable',$data))
				$data['client_billing_detail_enable']=1;
		}
		
        if((int)$data['client_billing_detail_enable']===1)
        {
            array_push($class[1],'crbs-state-selected');
            array_push($class[2],'crbs-hidden');
        }
        
        list($pickupLocationId)=$this->getBookingFormPickupLocation($bookingForm);
        
        if(in_array((int)$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['driver_license_attach_enable'],array(1,2)))
        {
			$Validation=new CRBSValidation();
			
			$classButton=array(array('crbs-file-upload','crbs-button','crbs-button-style-3'),array('crbs-file-remove'));
			
			$fileName=null;
			
			if($Validation->isEmpty($data['driver_license_file_name']))
				array_push($classButton[1],'crbs-hidden');
			else 
			{
				$fileName=$data['driver_license_file_name'];
				array_push($classButton[0],'crbs-hidden');
			}
			
            $htmlElement[5]=
            '
                <div'.CRBSHelper::createCSSClassAttribute(array('crbs-clear-fix')).'>
                    <div'.CRBSHelper::createCSSClassAttribute(array('crbs-form-field')).'>
                        <label>'.esc_html__('Driver license','car-rental-booking-system').((int)$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['driver_license_attach_enable']===1 ? ' *' : '').'</label>
                        <div'.CRBSHelper::createCSSClassAttribute($classButton[0]).'>
							<span>'.esc_html__('Upload a file','car-rental-booking-system').'</span>
							<input type="file" name="'.CRBSHelper::getFormName('driver_license_file',false).'"></input>							
						</div>
						<div'.CRBSHelper::createCSSClassAttribute($classButton[1]).'>
							<span>'.esc_html__('Uploaded file:','car-rental-booking-system').'<span>'.esc_html($fileName).'</span></span>
							<span'.CRBSHelper::createCSSClassAttribute(array('crbs-button','crbs-button-style-3')).'>'.esc_html__('Remove file','car-rental-booking-system').'</span>
						</div>
						<input type="hidden" name="'.CRBSHelper::getFormName('driver_license_file_name',false).'"/>
						<input type="hidden" name="'.CRBSHelper::getFormName('driver_license_file_type',false).'"/>
						<input type="hidden" name="'.CRBSHelper::getFormName('driver_license_file_tmp_name',false).'"/>	
                    </div>
                </div>                
            ';
        }
		
        $html=
        '
            <div'.CRBSHelper::createCSSClassAttribute($class[0]).'>

                <div class="crbs-form-panel">
 
                    <label class="crbs-form-panel-label">'.esc_html__('Driver details','car-rental-booking-system').'</label>

                    <div class="crbs-form-panel-content crbs-clear-fix">

                        <div class="crbs-clear-fix">
                            <div class="crbs-form-field crbs-form-field-width-50">
                                <label>'.esc_html__('First name *','car-rental-booking-system').'</label>
                                <input type="text" name="'.CRBSHelper::getFormName('client_contact_detail_first_name',false).'" value="'.esc_attr($data['client_contact_detail_first_name']).'"/>
                            </div>
                            <div class="crbs-form-field crbs-form-field-width-50">
                                <label>'.esc_html__('Last name *','car-rental-booking-system').'</label>
                                <input type="text" name="'.CRBSHelper::getFormName('client_contact_detail_last_name',false).'" value="'.esc_attr($data['client_contact_detail_last_name']).'"/>
                            </div>
                        </div>

                        <div class="crbs-clear-fix">
                            <div class="crbs-form-field crbs-form-field-width-50">
                                <label>'.esc_html__('E-mail address *','car-rental-booking-system').'</label>
                                <input type="text" name="'.CRBSHelper::getFormName('client_contact_detail_email_address',false).'"  value="'.esc_attr($data['client_contact_detail_email_address']).'"/>
                            </div>
                            <div class="crbs-form-field crbs-form-field-width-50">
                                <label>'.esc_html__('Phone number','car-rental-booking-system').(in_array('client_contact_detail_phone_number',$bookingForm['meta']['field_mandatory']) ? ' *' : '').'</label>
                                <input type="text" name="'.CRBSHelper::getFormName('client_contact_detail_phone_number',false).'"  value="'.esc_attr($data['client_contact_detail_phone_number']).'"/>
                            </div>
                        </div>

                        '.$htmlElement[5].'

                        <div class="crbs-clear-fix">
                            <div class="crbs-form-field">
                                <label>'.esc_html__('Comments','car-rental-booking-system').'</label>
                                <textarea name="'.CRBSHelper::getFormName('comment',false).'"></textarea>
                            </div>
                        </div>

                        '.$htmlElement[1].'
                                                      
                    </div>
                    
                </div>
                
                '.$htmlElement[4].'
		';
		
		/***/
		
		if((int)$bookingForm['meta']['billing_detail_state']===4) return($html.$htmlElement[3].'</div>');
		
		/***/
		
		$html.=
		'       
                <div class="crbs-form-panel">
 
                    <label class="crbs-form-panel-label">
                        <span'.CRBSHelper::createCSSClassAttribute($class[1]).'>
                            <span class="crbs-meta-icon-tick"></span>
                        </span>
                        <span>'.esc_html__('Billing details','car-rental-booking-system').'</span>
                        <input type="hidden" name="'.CRBSHelper::getFormName('client_billing_detail_enable',false).'" value="'.esc_attr($data['client_billing_detail_enable']).'"/> 
                    </label>

                    <div class="crbs-form-panel-content crbs-clear-fix">

                        <div class="crbs-clear-fix">
                            <div class="crbs-form-field crbs-form-field-width-50">
                                <label>'.esc_html__('Company registered name','car-rental-booking-system').(in_array('client_billing_detail_company_name',$bookingForm['meta']['field_mandatory']) ? ' *' : '').'</label>
                                <input type="text" name="'.CRBSHelper::getFormName('client_billing_detail_company_name',false).'" value="'.esc_attr($data['client_billing_detail_company_name']).'"/>
                            </div>
                            <div class="crbs-form-field crbs-form-field-width-50">
                                <label>'.esc_html__('Tax number','car-rental-booking-system').(in_array('client_billing_detail_tax_number',$bookingForm['meta']['field_mandatory']) ? ' *' : '').'</label>
                                <input type="text" name="'.CRBSHelper::getFormName('client_billing_detail_tax_number',false).'" value="'.esc_attr($data['client_billing_detail_tax_number']).'"/>
                            </div>
                        </div>

                        <div class="crbs-clear-fix">
                            <div class="crbs-form-field crbs-form-field-width-33">
                                <label>'.esc_html__('Street','car-rental-booking-system').(in_array('client_billing_detail_street_name',$bookingForm['meta']['field_mandatory']) ? ' *' : '').'</label>
                                <input type="text" name="'.CRBSHelper::getFormName('client_billing_detail_street_name',false).'" value="'.esc_attr($data['client_billing_detail_street_name']).'"/>
                            </div>
                            <div class="crbs-form-field crbs-form-field-width-33">
                                <label>'.esc_html__('Street number','car-rental-booking-system').(in_array('client_billing_detail_street_number',$bookingForm['meta']['field_mandatory']) ? ' *' : '').'</label>
                                <input type="text" name="'.CRBSHelper::getFormName('client_billing_detail_street_number',false).'" value="'.esc_attr($data['client_billing_detail_street_number']).'"/>
                            </div>
                            <div class="crbs-form-field crbs-form-field-width-33">
                                <label>'.esc_html__('City','car-rental-booking-system').(in_array('client_billing_detail_city',$bookingForm['meta']['field_mandatory']) ? ' *' : '').'</label>
                                <input type="text" name="'.CRBSHelper::getFormName('client_billing_detail_city',false).'" value="'.esc_attr($data['client_billing_detail_city']).'"/>
                            </div>                    
                        </div>

                        <div class="crbs-clear-fix">
                            <div class="crbs-form-field crbs-form-field-width-33">
                                <label>'.esc_html__('State','car-rental-booking-system').(in_array('client_billing_detail_state',$bookingForm['meta']['field_mandatory']) ? ' *' : '').'</label>
                                <input type="text" name="'.CRBSHelper::getFormName('client_billing_detail_state',false).'" value="'.esc_attr($data['client_billing_detail_state']).'"/>
                            </div>
                            <div class="crbs-form-field crbs-form-field-width-33">
                                <label>'.esc_html__('Postal code','car-rental-booking-system').(in_array('client_billing_detail_postal_code',$bookingForm['meta']['field_mandatory']) ? ' *' : '').'</label>
                                <input type="text" name="'.CRBSHelper::getFormName('client_billing_detail_postal_code',false).'" value="'.esc_attr($data['client_billing_detail_postal_code']).'"/>
                            </div>
                            <div class="crbs-form-field crbs-form-field-width-33">
                                <label>'.esc_html__('Country','car-rental-booking-system').(in_array('client_billing_detail_country_code',$bookingForm['meta']['field_mandatory']) ? ' *' : '').'</label>
                                <select name="'.CRBSHelper::getFormName('client_billing_detail_country_code',false).'" value="'.esc_attr($data['client_billing_detail_country_code']).'">
                                '.$htmlElementCountry.'
                                </select>
                            </div>                    
                        </div>  

                        '.$htmlElement[2].'
                            
                        <div'.CRBSHelper::createCSSClassAttribute($class[2]).'></div>
                    
                    </div>
                    
                </div>
                
                '.$htmlElement[3].'
                
            </div>
        ';
        
        return($html);
    }
    
    /**************************************************************************/
    
    function userSignIn()
    {
        $data=CRBSHelper::getPostOption();
        $data=CRBSBookingHelper::formatDateTimeToStandard($data);
        
        $response=array('user_sign_in'=>0);
        
        if(!is_array($bookingForm=$this->checkBookingForm($data['booking_form_id'])))
        {
            $this->setErrorGlobal($response,__('Login error.','car-rental-booking-system'));
            CRBSHelper::createJSONResponse($response);
        }
        
        $User=new CRBSUser();
        $WooCommerce=new CRBSWooCommerce();
        
        if(!$User->signIn($data['client_sign_in_login'],$data['client_sign_in_password']))
            $this->setErrorGlobal($response,__('Login error.','car-rental-booking-system'));
        else 
        {
            $userData=$WooCommerce->getUserData();
            
            $response['user_sign_in']=1;  
            
            $response['summary']=$this->createSummary($data,$bookingForm);
            $response['client_form_sign_up']=$this->createClientFormSignUp($bookingForm,$userData);
        }
        
        CRBSHelper::createJSONResponse($response);
    }
    
    /**************************************************************************/
    
    function fileUpload()
    {            
        $response=array();
		
        if(!is_array($_FILES))
            CRBSHelper::createJSONResponse($response);
   
		$name=key($_FILES);
		
        if(!is_array($_FILES[$name]))
            CRBSHelper::createJSONResponse($response);
      
        $fileName=CRBSHelper::createId();
        
        move_uploaded_file($_FILES[$name]['tmp_name'],dirname($_FILES[$name]['tmp_name']).'/'.$fileName);
        
        $response['name']=$_FILES[$name]['name'];
        $response['type']=$_FILES[$name]['type'];
        
        $response['tmp_name']=$fileName;
        
        CRBSHelper::createJSONResponse($response);
    }
	
	/**************************************************************************/
	
    function getBookingFormPickupPeriod($meta,$interval,$intervalType)
    {
        $date=array();
        
        $Validation=new CRBSValidation();
        
        $type=array(1=>'days',2=>'hours',3=>'minutes');
        
        /***/
		
		$dateStart=date_i18n('d-m-Y G:i');
		
		if((int)$intervalType===2)
		{
			if($interval<=0) $interval=1;

			$i=0;
			while(1)
			{
				$dateTemp=strtotime($dateStart.' '.$i.' minute');
				$minute=date('i',$dateTemp);
				if($minute%$interval==0) break;
				$i++;
			}
		}
		else $dateTemp=strtotime($dateStart);
		
        $dateStart=strtotime('+ '.(int)$meta['pickup_period_from'].' '.$type[(int)$meta['pickup_period_type']],$dateTemp);
        		
        $offset=(int)$meta['pickup_period_from'];
        
        if((int)$meta['pickup_period_type']===1)
           $offset*=24;
        if((int)$meta['pickup_period_type']===3)
            $offset*=3600;       
        
        /***/
        
        if($Validation->isEmpty($meta['pickup_period_to'])) $dateStop=null;
        else $dateStop=strtotime('+ '.(int)$meta['pickup_period_to'].' '.$type[(int)$meta['pickup_period_type']],$dateStart);
     
        /***/
        
		$date['min']=date_i18n('d-m-Y H:i:s',$dateStart);
		$date['max']=is_null($dateStop) ? null : date_i18n('d-m-Y H:i:s',$dateStop);

        return($date);
    }
	
	/**************************************************************************/
	
    function getDatePickupPeriod($data,$location,$type,$delta)
    {
        $date=array();
        
        if((int)$location['meta']['pickup_period_type']===1)
        {
            $date[0]=$data[$type.'_date'];
            $date[1]=date_i18n('d-m-Y',CRBSDate::strtotime('+'.$delta.' days'));
        }
        elseif((int)$location['meta']['pickup_period_type']===2)
        {
            $date[0]=$data[$type.'_date'].' '.$data[$type.'_time'];
            $date[1]=date_i18n('d-m-Y H:i',CRBSDate::strtotime('+'.$delta.' hours'));                            
        }
        elseif((int)$location['meta']['pickup_period_type']===3)
        {
            $date[0]=$data[$type.'_date'].' '.$data[$type.'_time'];
            $date[1]=date_i18n('d-m-Y H:i',CRBSDate::strtotime('+'.$delta.' minutes'));                            
        } 
     
        return($date);
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/