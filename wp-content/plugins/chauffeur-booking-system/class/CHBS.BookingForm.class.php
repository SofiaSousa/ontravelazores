<?php

/******************************************************************************/
/******************************************************************************/

class CHBSBookingForm
{
	/**************************************************************************/
	
    function __construct()
    {
        $this->vehicleSortingType=array
        (
            1                                                                   =>  array(__('Price ascending')),
            2                                                                   =>  array(__('Price descending')),
            3                                                                   =>  array(__('Vehicle number ascending')),
            4                                                                   =>  array(__('Vehicle number descending')),
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
        return(PLUGIN_CHBS_CONTEXT.'_booking_form');
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
					'name'														=>	__('Booking Forms','chauffeur-booking-system'),
					'singular_name'												=>	__('Booking Form','chauffeur-booking-system'),
					'add_new'													=>	__('Add New','chauffeur-booking-system'),
					'add_new_item'												=>	__('Add New Booking Form','chauffeur-booking-system'),
					'edit_item'													=>	__('Edit Booking Form','chauffeur-booking-system'),
					'new_item'													=>	__('New Booking Form','chauffeur-booking-system'),
					'all_items'													=>	__('Booking Forms','chauffeur-booking-system'),
					'view_item'													=>	__('View Booking Form','chauffeur-booking-system'),
					'search_items'												=>	__('Search Booking Forms','chauffeur-booking-system'),
					'not_found'													=>	__('No Booking Forms Found','chauffeur-booking-system'),
					'not_found_in_trash'										=>	__('No Booking Forms Found in Trash','chauffeur-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('Booking Forms','chauffeur-booking-system')
				),	
				'public'														=>	false,  
				'show_ui'														=>	true, 
				'show_in_menu'													=>	'edit.php?post_type='.CHBSBooking::getCPTName(),
				'capability_type'												=>	'post',
				'menu_position'													=>	2,
				'hierarchical'													=>	false,  
				'rewrite'														=>	false,  
				'supports'														=>	array('title','page-attributes','thumbnail')  
			)
		);
        
        add_action('save_post',array($this,'savePost'));
        add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
        add_filter('postbox_classes_'.self::getCPTName().'_chbs_meta_box_booking_form',array($this,'adminCreateMetaBoxClass'));
        
        add_shortcode(PLUGIN_CHBS_CONTEXT.'_booking_form',array($this,'createBookingForm'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }
    
    /**************************************************************************/
    
    static function getShortcodeName()
    {
        return(PLUGIN_CHBS_CONTEXT.'_booking_form');
    }
    
    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CHBS_CONTEXT.'_meta_box_booking_form',__('Main','chauffeur-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
               
        $Route=new CHBSRoute();
        $Driver=new CHBSDriver();
        $Payment=new CHBSPayment();
        $Country=new CHBSCountry();
        $Vehicle=new CHBSVehicle();
        $Location=new CHBSLocation();
        $Currency=new CHBSCurrency();
        $GoogleMap=new CHBSGoogleMap();
        $ServiceType=new CHBSServiceType();
        $EmailAccount=new CHBSEmailAccount();
        $BookingExtra=new CHBSBookingExtra();
        $BookingStatus=new CHBSBookingStatus();
        $BookingFormStyle=new CHBSBookingFormStyle();
        $BookingFormElement=new CHBSBookingFormElement();
        
		$data=array();
        
        $data['meta']=CHBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CHBSHelper::createNonceField(PLUGIN_CHBS_CONTEXT.'_meta_box_booking_form');
        
        $data['dictionary']['route']=$Route->getDictionary();
        $data['dictionary']['payment']=$Payment->getPayment();
        
        $data['dictionary']['color']=$BookingFormStyle->getColor();
        
        $data['dictionary']['vehicle']=$Vehicle->getDictionary();
        $data['dictionary']['vehicle_category']=$Vehicle->getCategory();
        
        $data['dictionary']['service_type']=$ServiceType->getServiceType();
        $data['dictionary']['email_account']=$EmailAccount->getDictionary();
        $data['dictionary']['booking_extra_category']=$BookingExtra->getCategory();
  
        $data['dictionary']['country']=$Country->getCountry();
        
        $data['dictionary']['booking_status']=$BookingStatus->getBookingStatus();
        
        $data['dictionary']['google_map']['position']=$GoogleMap->getPosition();
        $data['dictionary']['google_map']['route_avoid']=$GoogleMap->getRouteAvoid();
        $data['dictionary']['google_map']['map_type_control_id']=$GoogleMap->getMapTypeControlId();
        $data['dictionary']['google_map']['map_type_control_style']=$GoogleMap->getMapTypeControlStyle();
        
        $data['dictionary']['form_element_panel']=$BookingFormElement->getPanel($data['meta']);
        
        $data['dictionary']['driver']=$Driver->getDictionary();
        
        $data['dictionary']['location']=$Location->getDictionary();
        
        $data['dictionary']['currency']=$Currency->getCurrency();
        
        $data['dictionary']['vehicle_sorting_type']=$this->vehicleSortingType;
        
		$Template=new CHBSTemplate($data,PLUGIN_CHBS_TEMPLATE_PATH.'admin/meta_box_booking_form.php');
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
        
        if(CHBSHelper::checkSavePost($postId,PLUGIN_CHBS_CONTEXT.'_meta_box_booking_form_noncename','savePost')===false) return(false);
        
		$meta=array();
        
        $Date=new CHBSDate();
        $Route=new CHBSRoute();
        $Length=new CHBSLength();
        $Driver=new CHBSDriver();
        $Vehicle=new CHBSVehicle();
        $Payment=new CHBSPayment();
        $Country=new CHBSCountry();
        $Currency=new CHBSCurrency();
        $Location=new CHBSLocation();
        $Validation=new CHBSValidation();
        $ServiceType=new CHBSServiceType();
        $EmailAccount=new CHBSEmailAccount();
        $BookingExtra=new CHBSBookingExtra();
        $BookingStatus=new CHBSBookingStatus();
        $BookingFormStyle=new CHBSBookingFormStyle();
        
		$this->setPostMetaDefault($meta);
        
        /***/
        /***/
        
        $meta['service_type_id']=(array)CHBSHelper::getPostValue('service_type_id');
        foreach($meta['service_type_id'] as $index=>$value)
        {
            if(!$ServiceType->isServiceType($value))
                unset($meta['service_type_id'][$index]);
        }
        
        if(!count($meta['service_type_id']))
            $meta['service_type_id']=array(1,2,3);
        
        $meta['service_type_id_default']=(int)CHBSHelper::getPostValue('service_type_id_default');
        
        /***/
        
        $field=array('transfer_type_enable_1','transfer_type_enable_3');
        
        foreach($field as $fieldIndex)
        {
            $meta[$fieldIndex]=(array)CHBSHelper::getPostValue($fieldIndex);
            foreach($meta[$fieldIndex] as $index=>$value)
            {
                if(!$ServiceType->isServiceType($value))
                    unset($meta[$fieldIndex][$index]);
            }        
            if(!count($meta[$fieldIndex]))
                $meta[$fieldIndex]=array(); 
        }
        
        /***/
        
        $meta['vehicle_category_id']=(array)CHBSHelper::getPostValue('vehicle_category_id');
        if(in_array(-1,$meta['vehicle_category_id']))
        {
            $meta['vehicle_category_id']=array(-1);
        }
        else
        {
            $category=$Vehicle->getCategory();
            foreach($meta['vehicle_category_id'] as $index=>$value)
            {
                if(!isset($category[$value]))
                    unset($category[$value]);                
            }
        }
        
        if(!count($meta['vehicle_category_id']))
            $meta['vehicle_category_id']=array(-1);
            
        $meta['vehicle_id_default']=(int)CHBSHelper::getPostValue('vehicle_id_default');
        
        /***/
        
        $meta['vehicle_filter_enable']=(array)CHBSHelper::getPostValue('vehicle_filter_enable');
        foreach($meta['vehicle_filter_enable'] as $index=>$value)
        {
            if(!in_array($value,array(1,2,3,4)))
                unset($meta['vehicle_filter_enable'][$index]);
        }
        
        /***/  
        
        $meta['vehicle_sorting_type']=CHBSHelper::getPostValue('vehicle_sorting_type');
        if(!array_key_exists($meta['vehicle_sorting_type'],$this->vehicleSortingType))
            $meta['vehicle_sorting_type']=1;
        
        /***/    
            
        $meta['vehicle_pagination_vehicle_per_page']=(int)CHBSHelper::getPostValue('vehicle_pagination_vehicle_per_page');
        if(!$Validation->isNumber($meta['vehicle_pagination_vehicle_per_page'],1,99))
            $meta['vehicle_pagination_vehicle_per_page']=0;
 
        /***/    
            
        $meta['vehicle_limit']=(int)CHBSHelper::getPostValue('vehicle_limit');
        if(!$Validation->isNumber($meta['vehicle_limit'],1,99))
            $meta['vehicle_limit']=0;
        
        /***/
                
        $meta['route_id']=(array)CHBSHelper::getPostValue('route_id');
        if(in_array(-1,$meta['route_id']))
        {
            $meta['route_id']=array(-1);
        }
        else
        {
            $directory=$Route->getDictionary();
            foreach($meta['route_id'] as $index=>$value)
            {
                if(!isset($directory[$value]))
                    unset($directory[$value]);                
            }
        }
        
        if(!count($meta['route_id']))
            $meta['route_id']=array(-1);        
        
        $meta['route_list_item_empty_enable']=CHBSHelper::getPostValue('route_list_item_empty_enable');
        if(!$Validation->isBool($meta['route_list_item_empty_enable']))
            $meta['route_list_item_empty_enable']=0;        
        
        $meta['route_list_item_empty_text']=CHBSHelper::getPostValue('route_list_item_empty_text'); 
        
        /***/
        
        $meta['booking_extra_category_id']=(array)CHBSHelper::getPostValue('booking_extra_category_id');
        if(in_array(-1,$meta['booking_extra_category_id']))
        {
            $meta['booking_extra_category_id']=array(-1);
        }
        else
        {
            $category=$BookingExtra->getCategory();
            foreach($meta['booking_extra_category_id'] as $index=>$value)
            {
                if(!isset($category[$value]))
                    unset($meta['booking_extra_category_id'][$index]);                
            }
        }
        
        if(!count($meta['booking_extra_category_id']))
            $meta['booking_extra_category_id']=array(-1);          
        
        /***/
        
        $meta['booking_extra_category_display_enable']=CHBSHelper::getPostValue('booking_extra_category_display_enable');
        if(!$Validation->isBool($meta['booking_extra_category_display_enable']))
            $meta['booking_extra_category_display_enable']=0;
        
        /***/
        
        $meta['currency']=(array)CHBSHelper::getPostValue('currency');
        if(in_array(-1,$meta['currency']))
        {
            $meta['currency']=array(-1);
        }
        else
        {
            foreach($Currency->getCurrency() as $index=>$value)
            {
                if(!$Currency->isCurrency($index))
                {
                    unset($meta['currency'][$index]);
                }
            }
        }
        
        if(!count($meta['currency']))
            $meta['currency']=array(-1);         

        /***/

        $meta['extra_time_step']=CHBSHelper::getPostValue('extra_time_step');
        $meta['extra_time_enable']=CHBSHelper::getPostValue('extra_time_enable');
        $meta['extra_time_range_min']=CHBSHelper::getPostValue('extra_time_range_min');
        $meta['extra_time_range_max']=CHBSHelper::getPostValue('extra_time_range_max');
        $meta['extra_time_unit']=CHBSHelper::getPostValue('extra_time_unit');
        
        if(!$Validation->isBool($meta['extra_time_enable']))
            $meta['extra_time_enable']=1;
        if(!$Validation->isNumber($meta['extra_time_range_min'],0,9999))
            $meta['extra_time_range_min']=0;    
        if(!$Validation->isNumber($meta['extra_time_range_max'],1,9999))
            $meta['extra_time_range_max']=24;            
        if(!$Validation->isNumber($meta['extra_time_step'],1,9999))
            $meta['extra_time_step']=1; 
        if(!in_array($meta['extra_time_unit'],array(1,2)))
            $meta['extra_time_unit']=2;
        
        if(($meta['extra_time_range_min']>=$meta['extra_time_range_max']) || (!count(array_intersect(array(1,3),$meta['service_type_id']))))
        {
            $meta['extra_time_step']=1;
            $meta['extra_time_range_min']=0;
            $meta['extra_time_range_max']=24;
        }

        /***/
        
        $meta['duration_min']=CHBSHelper::getPostValue('duration_min');
        $meta['duration_max']=CHBSHelper::getPostValue('duration_max');        
        $meta['duration_step']=CHBSHelper::getPostValue('duration_step');     

        if(!$Validation->isNumber($meta['duration_min'],1,9999))
            $meta['duration_min']=1;    
        if(!$Validation->isNumber($meta['duration_max'],1,9999))
            $meta['duration_max']=24;            
        if(!$Validation->isNumber($meta['duration_step'],1,9999))
            $meta['duration_step']=1;       
        
        if(($meta['duration_min']>=$meta['duration_max']) || (!count(array_intersect(array(2),$meta['service_type_id']))))
        {
            $meta['duration_min']=1;
            $meta['duration_max']=24;
            $meta['duration_step']=1; 
        }     
        
        /***/
        
        $meta['booking_period_from']=CHBSHelper::getPostValue('booking_period_from');
        if(!$Validation->isNumber($meta['booking_period_from'],0,9999))
            $meta['booking_period_from']='';          
        $meta['booking_period_to']=CHBSHelper::getPostValue('booking_period_to');
        if(!$Validation->isNumber($meta['booking_period_to'],0,9999))
            $meta['booking_period_to']='';  
        $meta['booking_period_type']=CHBSHelper::getPostValue('booking_period_type');
        if(!in_array($meta['booking_period_type'],array(1,2,3)))
            $meta['booking_period_type']=1;        
        
        /***/
        
        $meta['booking_vehicle_interval']=CHBSHelper::getPostValue('booking_vehicle_interval');
        if(!$Validation->isNumber($meta['booking_vehicle_interval'],0,9999))
            $meta['booking_vehicle_interval']=0;              
        
        /***/
        
        $meta['price_hide']=CHBSHelper::getPostValue('price_hide');
        if(!$Validation->isBool($meta['price_hide']))
            $meta['price_hide']=0;   
        
        /***/
        
        $meta['order_sum_split']=CHBSHelper::getPostValue('order_sum_split');
        if(!$Validation->isBool($meta['order_sum_split']))
            $meta['order_sum_split']=0;  
        
        $meta['show_net_price_hide_tax']=CHBSHelper::getPostValue('show_net_price_hide_tax');
        if(!$Validation->isBool($meta['show_net_price_hide_tax']))
            $meta['show_net_price_hide_tax']=0; 
        
        /***/
        
        $meta['booking_summary_hide_fee']=CHBSHelper::getPostValue('booking_summary_hide_fee');
        if(!$Validation->isBool($meta['booking_summary_hide_fee']))
            $meta['booking_summary_hide_fee']=0;         
        
        /***/
        
        $meta['prevent_double_vehicle_booking_enable']=CHBSHelper::getPostValue('prevent_double_vehicle_booking_enable');
        if(!$Validation->isBool($meta['prevent_double_vehicle_booking_enable']))
            $meta['prevent_double_vehicle_booking_enable']=0;       
        
        /***/
        
        $meta['vehicle_in_the_same_booking_passenger_sum_enable']=CHBSHelper::getPostValue('vehicle_in_the_same_booking_passenger_sum_enable');
        if(!$Validation->isBool($meta['vehicle_in_the_same_booking_passenger_sum_enable']))
            $meta['vehicle_in_the_same_booking_passenger_sum_enable']=0;       
        
        /***/        
        
        $meta['step_second_enable']=CHBSHelper::getPostValue('step_second_enable');
        if(!$Validation->isBool($meta['step_second_enable']))
            $meta['step_second_enable']=1;
        
        /***/        
        
        $meta['thank_you_page_enable']=CHBSHelper::getPostValue('thank_you_page_enable');
        if(!$Validation->isBool($meta['thank_you_page_enable']))
            $meta['thank_you_page_enable']=1;        
        
        $meta['thank_you_page_button_back_to_home_label']=CHBSHelper::getPostValue('thank_you_page_button_back_to_home_label');
        $meta['thank_you_page_button_back_to_home_url_address']=CHBSHelper::getPostValue('thank_you_page_button_back_to_home_url_address');
        
        /***/
        
        $meta['distance_minimum']=CHBSHelper::getPostValue('distance_minimum');        
        if(!$Validation->isNumber($meta['distance_minimum'],0,99999))
            $meta['distance_minimum']=0;      
        if(CHBSOption::getOption('length_unit')==2)
            $meta['distance_minimum']=$Length->convertUnit($meta['distance_minimum'],2,1);
        
        $meta['duration_minimum']=CHBSHelper::getPostValue('duration_minimum');        
        if(!$Validation->isNumber($meta['duration_minimum'],0,999999999))
            $meta['duration_minimum']=0;     
             
        $meta['order_value_minimum']=CHBSHelper::getPostValue('order_value_minimum');        
        if(!$Validation->isPrice($meta['order_value_minimum']))
            $meta['order_value_minimum']=0.00;   

        /***/
        
        $meta['timepicker_step']=CHBSHelper::getPostValue('timepicker_step');
        if(!$Validation->isNumber($meta['timepicker_step'],1,9999))
            $meta['timepicker_step']=30;           
        
        $meta['timepicker_dropdown_list_enable']=CHBSHelper::getPostValue('timepicker_dropdown_list_enable');
        if(!$Validation->isBool($meta['timepicker_dropdown_list_enable']))
            $meta['timepicker_dropdown_list_enable']=0;          
        
        $meta['form_preloader_enable']=CHBSHelper::getPostValue('form_preloader_enable');
        if(!$Validation->isBool($meta['form_preloader_enable']))
            $meta['form_preloader_enable']=0;           

        $meta['form_preloader_image_src']=CHBSHelper::getPostValue('form_preloader_image_src');
        
        $meta['form_preloader_background_opacity']=CHBSHelper::getPostValue('form_preloader_background_opacity');
        if(!$Validation->isNumber($meta['form_preloader_background_opacity'],0,100))
            $meta['form_preloader_background_opacity']=20;
        
        $meta['form_preloader_background_color']=CHBSHelper::getPostValue('form_preloader_background_color');
        if(!$Validation->isColor($meta['form_preloader_background_color']))
            $meta['form_preloader_background_color']='FFFFFF';
            
        /***/
        
        $meta['billing_detail_state']=CHBSHelper::getPostValue('billing_detail_state');
        if(!in_array($meta['billing_detail_state'],array(1,2,3)))
            $meta['billing_detail_state']=1;   
        
        $meta['billing_detail_list_state']=CHBSHelper::getPostValue('billing_detail_list_state');
        
        /***/   
        
        $meta['booking_status_default_id']=CHBSHelper::getPostValue('booking_status_default_id');
        if(!$BookingStatus->isBookingStatus($meta['booking_status_default_id']))
            $meta['booking_status_default_id']=1;

        /***/
        
        $driverDictionary=$Driver->getDictionary();
        $meta['driver_default_id']=CHBSHelper::getPostValue('driver_default_id');
        if(!array_key_exists($meta['driver_default_id'],$driverDictionary))
            $meta['driver_default_id']=-1;

        /***/
        
        $meta['geolocation_server_side_enable']=CHBSHelper::getPostValue('geolocation_server_side_enable');
        if(!$Validation->isBool($meta['geolocation_server_side_enable']))
            $meta['geolocation_server_side_enable']=0;        
        
        /***/ 
       
        $meta['summary_sidebar_sticky_enable']=CHBSHelper::getPostValue('summary_sidebar_sticky_enable');
        if(!$Validation->isBool($meta['summary_sidebar_sticky_enable']))
            $meta['summary_sidebar_sticky_enable']=0;
        
        /***/
        
        $meta['scroll_to_booking_extra_after_select_vehicle_enable']=CHBSHelper::getPostValue('scroll_to_booking_extra_after_select_vehicle_enable');
        if(!$Validation->isBool($meta['scroll_to_booking_extra_after_select_vehicle_enable']))
            $meta['scroll_to_booking_extra_after_select_vehicle_enable']=0;        
 
        /***/
        
        $meta['woocommerce_enable']=CHBSHelper::getPostValue('woocommerce_enable');
        if(!$Validation->isBool($meta['woocommerce_enable']))
            $meta['woocommerce_enable']=0;       
      
        /***/
        
        $meta['woocommerce_account_enable_type']=CHBSHelper::getPostValue('woocommerce_account_enable_type');
        if(!in_array($meta['woocommerce_account_enable_type'],array(0,1,2)))
            $meta['woocommerce_account_enable_type']=0;       
        
        /***/
        
        $meta['coupon_enable']=CHBSHelper::getPostValue('coupon_enable');
        if(!$Validation->isBool($meta['coupon_enable']))
            $meta['coupon_enable']=0;    
        
        /***/
        
        $meta['passenger_adult_enable_service_type_1']=CHBSHelper::getPostValue('passenger_adult_enable_service_type_1');
        if(!$Validation->isBool($meta['passenger_adult_enable_service_type_1']))
            $meta['passenger_adult_enable_service_type_1']=0; 
        
        $meta['passenger_children_enable_service_type_1']=CHBSHelper::getPostValue('passenger_children_enable_service_type_1');
        if(!$Validation->isBool($meta['passenger_children_enable_service_type_1']))
            $meta['passenger_children_enable_service_type_1']=0; 
        
        $meta['passenger_adult_enable_service_type_2']=CHBSHelper::getPostValue('passenger_adult_enable_service_type_2');
        if(!$Validation->isBool($meta['passenger_adult_enable_service_type_2']))
            $meta['passenger_adult_enable_service_type_2']=0; 
        
        $meta['passenger_children_enable_service_type_2']=CHBSHelper::getPostValue('passenger_children_enable_service_type_2');
        if(!$Validation->isBool($meta['passenger_children_enable_service_type_2']))
            $meta['passenger_children_enable_service_type_2']=0; 
        
        $meta['passenger_adult_enable_service_type_3']=CHBSHelper::getPostValue('passenger_adult_enable_service_type_3');
        if(!$Validation->isBool($meta['passenger_adult_enable_service_type_3']))
            $meta['passenger_adult_enable_service_type_3']=0; 
        
        $meta['passenger_children_enable_service_type_3']=CHBSHelper::getPostValue('passenger_children_enable_service_type_3');
        if(!$Validation->isBool($meta['passenger_children_enable_service_type_3']))
            $meta['passenger_children_enable_service_type_3']=0;         
        
        $meta['passenger_adult_default_number']=CHBSHelper::getPostValue('passenger_adult_default_number');
        if(!$Validation->isNumber($meta['passenger_adult_default_number'],0,99,true))
            $meta['passenger_adult_default_number']=0;
        $meta['passenger_children_default_number']=CHBSHelper::getPostValue('passenger_children_default_number'); 
        if(!$Validation->isNumber($meta['passenger_children_default_number'],0,99,true))
            $meta['passenger_children_default_number']=0;             
  
        $meta['show_price_per_single_passenger']=CHBSHelper::getPostValue('show_price_per_single_passenger');
        if(!$Validation->isBool($meta['show_price_per_single_passenger']))
            $meta['show_price_per_single_passenger']=0;         
            
        /***/
        
        $meta['calculation_method_service_type_1']=CHBSHelper::getPostValue('calculation_method_service_type_1');
        if(!in_array($meta['calculation_method_service_type_1'],array(1,2)))
            $meta['calculation_method_service_type_1']=1;
        
        $meta['calculation_method_service_type_3']=CHBSHelper::getPostValue('calculation_method_service_type_3');
        if(!in_array($meta['calculation_method_service_type_3'],array(1,2)))
            $meta['calculation_method_service_type_3']=1;   
        
        /***/
        
        $meta['base_location']=CHBSHelper::getPostValue('base_location');
        $meta['base_location_coordinate_lat']=CHBSHelper::getPostValue('base_location_coordinate_lat');
        $meta['base_location_coordinate_lng']=CHBSHelper::getPostValue('base_location_coordinate_lng');
        
        if($Validation->isEmpty($meta['base_location']))
        {
            $meta['base_location_coordinate_lat']='';
            $meta['base_location_coordinate_lng']='';
        }
        
        $meta['waypoint_enable']=CHBSHelper::getPostValue('waypoint_enable');
        if(!$Validation->isBool($meta['waypoint_enable']))
            $meta['waypoint_enable']=0;          
        
        $locationDictionary=$Location->getDictionary();
        
        $field=array('location_fixed_pickup_service_type_1','location_fixed_dropoff_service_type_1','location_fixed_pickup_service_type_2','location_fixed_dropoff_service_type_2');
        
        foreach($field as $fieldName)
        {
            $meta[$fieldName]=(array)CHBSHelper::getPostValue($fieldName);
            foreach($meta[$fieldName] as $index=>$value)
            {
                if($value==-1)
                {
                    $meta[$fieldName]=array(-1);
                    break;
                }

                if(!array_key_exists($value,$locationDictionary))                        
                    unset($meta[$fieldName][$index]);
            }
        }       
        
        $meta['location_fixed_list_item_empty_enable']=CHBSHelper::getPostValue('location_fixed_list_item_empty_enable');
        if(!$Validation->isBool($meta['location_fixed_list_item_empty_enable']))
            $meta['location_fixed_list_item_empty_enable']=0;           
        
        $meta['location_fixed_list_item_empty_text']=CHBSHelper::getPostValue('location_fixed_list_item_empty_text');
        
        $meta['location_fixed_autocomplete_enable']=CHBSHelper::getPostValue('location_fixed_autocomplete_enable');
        if(!$Validation->isBool($meta['location_fixed_autocomplete_enable']))
            $meta['location_fixed_autocomplete_enable']=0;                
        
        $meta['ride_time_multiplier']=CHBSHelper::getPostValue('ride_time_multiplier');
        if(!$Validation->isFloat($meta['ride_time_multiplier'],0,99.99))
            $meta['ride_time_multiplier']=1.00;  
        else $meta['ride_time_multiplier']=number_format(preg_replace('/,/','.',$meta['ride_time_multiplier']),2,'.','');
            
        $meta['google_autosugestion_address_type']=CHBSHelper::getPostValue('google_autosugestion_address_type');
        if(!in_array($meta['google_autosugestion_address_type'],array(1,2)))
            $meta['google_autosugestion_address_type']=2;         
        
        $meta['icon_field_enable']=CHBSHelper::getPostValue('icon_field_enable');
        if(!$Validation->isBool($meta['icon_field_enable']))
            $meta['icon_field_enable']=0;         
        
        $meta['navigation_top_enable']=CHBSHelper::getPostValue('navigation_top_enable');
        if(!$Validation->isBool($meta['navigation_top_enable']))
            $meta['navigation_top_enable']=0;        
        
        $meta['step_1_right_panel_visibility']=CHBSHelper::getPostValue('step_1_right_panel_visibility');
        if(!$Validation->isBool($meta['step_1_right_panel_visibility']))
            $meta['step_1_right_panel_visibility']=0;   
        
        $meta['vehicle_more_info_default_show']=CHBSHelper::getPostValue('vehicle_more_info_default_show');
        if(!$Validation->isBool($meta['vehicle_more_info_default_show']))
            $meta['vehicle_more_info_default_show']=0;                 
        
        $meta['booking_title']=CHBSHelper::getPostValue('booking_title');

        /***/
        /***/
        
		$businessHour=array();
        $businessHourPost=CHBSHelper::getPostValue('business_hour');
        
		foreach(array_keys($Date->day) as $index)
		{
			$businessHour[$index]=array('start'=>null,'stop'=>null);
			
            $businessHourPost[$index][0]=$Date->formatTimeToStandard($businessHourPost[$index][0]);
            $businessHourPost[$index][1]=$Date->formatTimeToStandard($businessHourPost[$index][1]);
            
            if((isset($businessHourPost[$index][0])) && (isset($businessHourPost[$index][1])))
            {
                if(($Validation->isTime($businessHourPost[$index][0],false)) && ($Validation->isTime($businessHourPost[$index][1],false)))
                {
                    $result=$Date->compareTime($businessHourPost[$index][0],$businessHourPost[$index][1]);

                    if($result==2)
                    {
                        $businessHour[$index]['start']=$businessHourPost[$index][0];
                        $businessHour[$index]['stop']=$businessHourPost[$index][1];
                    }
                }
            }
		}
 
		$meta['business_hour']=$businessHour;
        
        /***/
        
		$dateExclude=array();
        $dateExcludePost=array();
        
        $dateExcludePostStart=CHBSHelper::getPostValue('date_exclude_start');
        $dateExcludePostStop=CHBSHelper::getPostValue('date_exclude_stop');
        
        foreach($dateExcludePostStart as $index=>$value)
        {
            if(isset($dateExcludePostStop[$index]))
                $dateExcludePost[]=array($dateExcludePostStart[$index],$dateExcludePostStop[$index]);
        }
      
		foreach($dateExcludePost as $index=>$value)
		{
            $value[0]=$Date->formatDateToStandard($value[0]);
            $value[1]=$Date->formatDateToStandard($value[1]);
            
			if(!$Validation->isDate($value[0],true)) continue;
			if(!$Validation->isDate($value[1],true)) continue;

			if($Date->compareDate($value[0],$value[1])==1) continue;
			if($Date->compareDate(date_i18n('d-m-Y'),$value[1])==1) continue;
			
			$dateExclude[]=array('start'=>$value[0],'stop'=>$value[1]);
		}
        
		$meta['date_exclude']=$dateExclude;
        
        /***/
        /***/
        
        $meta['payment_mandatory_enable']=CHBSHelper::getPostValue('payment_mandatory_enable');
        if(!$Validation->isBool($meta['payment_mandatory_enable']))
            $meta['payment_mandatory_enable']=0; 
        
        $meta['payment_processing_enable']=CHBSHelper::getPostValue('payment_processing_enable');
        if(!$Validation->isBool($meta['payment_processing_enable']))
            $meta['payment_processing_enable']=1;   
        
        $meta['payment_woocommerce_step_3_enable']=CHBSHelper::getPostValue('payment_woocommerce_step_3_enable');
        if(!$Validation->isBool($meta['payment_woocommerce_step_3_enable']))
            $meta['payment_woocommerce_step_3_enable']=1;              
                
        $meta['payment_deposit_enable']=CHBSHelper::getPostValue('payment_deposit_enable');
        if(!$Validation->isBool($meta['payment_deposit_enable']))
            $meta['payment_deposit_enable']=0;         
        
        $meta['payment_deposit_value']=CHBSHelper::getPostValue('payment_deposit_value');
        if(!$Validation->isNumber($meta['payment_deposit_value'],0,100))
            $meta['payment_deposit_value']=30;             
        
        if($meta['payment_deposit_enable']==0)
            $meta['payment_deposit_value']=30;
        
        /***/
        
        $meta['payment_id']=(array)CHBSHelper::getPostValue('payment_id');
        foreach($meta['payment_id'] as $index=>$value)
        {
            if(!$Payment->isPayment($value))
                unset($meta['payment_id'][$value]);
        }
        
        $meta['payment_cash_logo_src']=CHBSHelper::getPostValue('payment_cash_logo_src');
        
        $meta['payment_stripe_api_key_secret']=CHBSHelper::getPostValue('payment_stripe_api_key_secret');
        $meta['payment_stripe_api_key_publishable']=CHBSHelper::getPostValue('payment_stripe_api_key_publishable');
        $meta['payment_stripe_redirect_url_address']=CHBSHelper::getPostValue('payment_stripe_redirect_url_address');
        $meta['payment_stripe_logo_src']=CHBSHelper::getPostValue('payment_stripe_logo_src');
    
        $meta['payment_paypal_email_address']=CHBSHelper::getPostValue('payment_paypal_email_address');
        $meta['payment_paypal_sandbox_mode_enable']=CHBSHelper::getPostValue('payment_paypal_sandbox_mode_enable');
        if(!$Validation->isBool($meta['payment_paypal_sandbox_mode_enable']))
            $meta['payment_paypal_sandbox_mode_enable']=0;
        $meta['payment_paypal_logo_src']=CHBSHelper::getPostValue('payment_paypal_logo_src');
        
        $meta['payment_wire_transfer_info']=CHBSHelper::getPostValue('payment_wire_transfer_info');
        $meta['payment_wire_transfer_logo_src']=CHBSHelper::getPostValue('payment_wire_transfer_logo_src');
        
        /***/
        /***/
        
        $field=array('pickup','dropoff');
        
        foreach($field as $fieldName)
        {
            $meta['driving_zone_restriction_'.$fieldName.'_location_enable']=CHBSHelper::getPostValue('driving_zone_restriction_'.$fieldName.'_location_enable');
            if(!$Validation->isBool($meta['driving_zone_restriction_'.$fieldName.'_location_enable']))
                $meta['driving_zone_restriction_'.$fieldName.'_location_enable']=0;            
        
            $meta['driving_zone_restriction_'.$fieldName.'_location_country']=(array)CHBSHelper::getPostValue('driving_zone_restriction_'.$fieldName.'_location_country');
            foreach($meta['driving_zone_restriction_'.$fieldName.'_location_country'] as $index=>$value)
            {
                if($value==-1)
                {
                    $meta['driving_zone_restriction_'.$fieldName.'_location_country']=array(-1);
                    break;
                }

                if(!$Country->isCountry($value))
                    unset($meta['driving_zone_restriction_'.$fieldName.'_location_country'][$index]);
            }
            
            if(!count($meta['driving_zone_restriction_'.$fieldName.'_location_country']))
                $meta['driving_zone_restriction_'.$fieldName.'_location_country']=array(-1);
            
            $meta['driving_zone_restriction_'.$fieldName.'_location_area_radius']=CHBSHelper::getPostValue('driving_zone_restriction_'.$fieldName.'_location_area_radius');
            if(!$Validation->isNumber($meta['driving_zone_restriction_'.$fieldName.'_location_area_radius'],0,99999))
                $meta['driving_zone_restriction_'.$fieldName.'_location_area_radius']=50;
            
            $meta['driving_zone_restriction_'.$fieldName.'_location_area']=CHBSHelper::getPostValue('driving_zone_restriction_'.$fieldName.'_location_area');
            $meta['driving_zone_restriction_'.$fieldName.'_location_area_coordinate_lat']=CHBSHelper::getPostValue('driving_zone_restriction_'.$fieldName.'_location_area_coordinate_lat');
            $meta['driving_zone_restriction_'.$fieldName.'_location_area_coordinate_lng']=CHBSHelper::getPostValue('driving_zone_restriction_'.$fieldName.'_location_area_coordinate_lng');
        }
             
        /***/
        /***/
        
        $FormElement=new CHBSBookingFormElement();
        $FormElement->save($postId);        
        
        /***/
        /***/
        
        $dictionary=$EmailAccount->getDictionary();
        $meta['booking_new_sender_email_account_id']=CHBSHelper::getPostValue('booking_new_sender_email_account_id');
        
        if(!array_key_exists($meta['booking_new_sender_email_account_id'],$dictionary))
            $meta['booking_new_sender_email_account_id']=-1;
        
        $meta['booking_new_recipient_email_address']='';
        $recipient=preg_split('/;/',CHBSHelper::getPostValue('booking_new_recipient_email_address'));
        
        foreach($recipient as $index=>$value)
        {
            if($Validation->isEmailAddress($value))
            {
                if($Validation->isNotEmpty($meta['booking_new_recipient_email_address'])) $meta['booking_new_recipient_email_address'].=';';
                $meta['booking_new_recipient_email_address'].=$value;
            }
        } 
        
        /***/
        
        $meta['nexmo_sms_enable']=CHBSHelper::getPostValue('nexmo_sms_enable');
        if(!$Validation->isBool($meta['nexmo_sms_enable']))
            $meta['nexmo_sms_enable']=0;
        
        $meta['nexmo_sms_api_key']=CHBSHelper::getPostValue('nexmo_sms_api_key');
        $meta['nexmo_sms_api_key_secret']=CHBSHelper::getPostValue('nexmo_sms_api_key_secret');
        
        $meta['nexmo_sms_sender_name']=CHBSHelper::getPostValue('nexmo_sms_sender_name');
        $meta['nexmo_sms_recipient_phone_number']=CHBSHelper::getPostValue('nexmo_sms_recipient_phone_number');
        
        $meta['nexmo_sms_message']=CHBSHelper::getPostValue('nexmo_sms_message');
        
        /***/
        
        $meta['twilio_sms_enable']=CHBSHelper::getPostValue('twilio_sms_enable');
        if(!$Validation->isBool($meta['twilio_sms_enable']))
            $meta['twilio_sms_enable']=0;
        
        $meta['twilio_sms_api_sid']=CHBSHelper::getPostValue('twilio_sms_api_sid');
        $meta['twilio_sms_api_token']=CHBSHelper::getPostValue('twilio_sms_api_token');
        
        $meta['twilio_sms_sender_phone_number']=CHBSHelper::getPostValue('twilio_sms_sender_phone_number');
        $meta['twilio_sms_recipient_phone_number']=CHBSHelper::getPostValue('twilio_sms_recipient_phone_number');
        
        $meta['twilio_sms_message']=CHBSHelper::getPostValue('twilio_sms_message');
        
        /***/
        
        $meta['telegram_enable']=CHBSHelper::getPostValue('telegram_enable');
        if(!$Validation->isBool($meta['telegram_enable']))
            $meta['telegram_enable']=0;
        
        $meta['telegram_token']=CHBSHelper::getPostValue('telegram_token');
        $meta['telegram_group_id']=CHBSHelper::getPostValue('telegram_group_id');
        $meta['telegram_message']=CHBSHelper::getPostValue('telegram_message');
                
        /***/
        /***/
        
        $GoogleMap=new CHBSGoogleMap();
                
        $meta['google_map_default_location_type']=CHBSHelper::getPostValue('google_map_default_location_type');
        if(!in_array($meta['google_map_default_location_type'],array(1,2)))
            $meta['google_map_default_location_type']=1;       
        
        $meta['google_map_default_location_fixed']=CHBSHelper::getPostValue('google_map_default_location_fixed');
        $meta['google_map_default_location_fixed_coordinate_lat']=CHBSHelper::getPostValue('google_map_default_location_fixed_coordinate_lat');
        $meta['google_map_default_location_fixed_coordinate_lng']=CHBSHelper::getPostValue('google_map_default_location_fixed_coordinate_lng');
        
        $meta['google_map_route_avoid']=(array)CHBSHelper::getPostValue('google_map_route_avoid');
        if(in_array(-1,$meta['google_map_route_avoid']))
        {
            $meta['google_map_route_avoid']=array(-1);
        }
        else
        {
            $avoid=$GoogleMap->getRouteAvoid();
            foreach($meta['google_map_route_avoid'] as $index=>$value)
            {
                if(!isset($avoid[$value]))
                    unset($meta['google_map_route_avoid'][$value]);                
            }
        }
        
        $meta['google_map_traffic_layer_enable']=CHBSHelper::getPostValue('google_map_traffic_layer_enable');  
        $meta['google_map_draggable_enable']=CHBSHelper::getPostValue('google_map_draggable_enable');  
        $meta['google_map_scrollwheel_enable']=CHBSHelper::getPostValue('google_map_scrollwheel_enable');  
        
        if(!$Validation->isBool($meta['google_map_traffic_layer_enable']))
            $meta['google_map_traffic_layer_enable']=0;     
        if(!$Validation->isBool($meta['google_map_draggable_enable']))
            $meta['google_map_draggable_enable']=1;        
        if(!$Validation->isBool($meta['google_map_scrollwheel_enable']))
            $meta['google_map_scrollwheel_enable']=1;             

        /***/
        
        $meta['google_map_draggable_location_enable']=CHBSHelper::getPostValue('google_map_draggable_location_enable'); 
        if(!$Validation->isBool($meta['google_map_draggable_location_enable']))
            $meta['google_map_draggable_location_enable']=0;              
        
        /***/
        
        $meta['google_map_map_type_control_enable']=CHBSHelper::getPostValue('google_map_map_type_control_enable');  
        $meta['google_map_map_type_control_id']=CHBSHelper::getPostValue('google_map_map_type_control_id'); 
        $meta['google_map_map_type_control_style']=CHBSHelper::getPostValue('google_map_map_type_control_style'); 
        $meta['google_map_map_type_control_position']=CHBSHelper::getPostValue('google_map_map_type_control_position');  
        
        if(!$Validation->isBool($meta['google_map_map_type_control_enable']))
            $meta['google_map_map_type_control_enable']=0;   
        if(!array_key_exists($meta['google_map_map_type_control_id'],$GoogleMap->getMapTypeControlId()))
            $meta['google_map_map_type_control_id']='SATELLITE';        
        if(!array_key_exists($meta['google_map_map_type_control_style'],$GoogleMap->getMapTypeControlStyle()))
            $meta['google_map_map_type_control_style']='DEFAULT';         
        if(!array_key_exists($meta['google_map_map_type_control_position'],$GoogleMap->getPosition()))
            $meta['google_map_map_type_control_position']='TOP_CENTER';
        
        /***/
        
        $meta['google_map_zoom_control_enable']=CHBSHelper::getPostValue('google_map_zoom_control_enable');  
        $meta['google_map_zoom_control_position']=CHBSHelper::getPostValue('google_map_zoom_control_position');  
        $meta['google_map_zoom_control_level']=CHBSHelper::getPostValue('google_map_zoom_control_level'); 
        
        if(!$Validation->isBool($meta['google_map_zoom_control_enable']))
            $meta['google_map_zoom_control_enable']=0;   
        if(!array_key_exists($meta['google_map_zoom_control_position'],$GoogleMap->getPosition()))
            $meta['google_map_zoom_control_position']='TOP_CENTER';        
        if(!$Validation->isNumber($meta['google_map_zoom_control_level'],1,21))
            $meta['google_map_zoom_control_position']=6;   

        /***/
        /***/
        
        $meta['google_calendar_enable']=CHBSHelper::getPostValue('google_calendar_enable');  
        $meta['google_calendar_id']=CHBSHelper::getPostValue('google_calendar_id');  
        $meta['google_calendar_settings']=CHBSHelper::getPostValue('google_calendar_settings');  
        
        if(!$Validation->isBool($meta['google_calendar_enable']))
            $meta['google_calendar_enable']=0;           
        
        /***/
        
        $meta['style_color']=(array)CHBSHelper::getPostValue('style_color');   
        foreach($meta['style_color'] as $index=>$value)
        {
            if(!$BookingFormStyle->isColor($index))
            {
                unset($meta['style_color'][$index]);
                continue;
            }
            
            if(!$Validation->isColor($value,true))
                $meta['style_color'][$index]='';
        }
        
        /***/
        /***/

        foreach($meta as $index=>$value)
            CHBSPostMeta::updatePostMeta($postId,$index,$value);    
        
        $BookingFormStyle->createCSSFile();
    }
    
	/**************************************************************************/
	
	function setPostMetaDefault(&$meta)
	{
        $BookingFormStyle=new CHBSBookingFormStyle();
        
        CHBSHelper::setDefault($meta,'service_type_id',array(1,2,3));
        CHBSHelper::setDefault($meta,'service_type_id_default',1);
        
        CHBSHelper::setDefault($meta,'transfer_type_enable_1',array());
        CHBSHelper::setDefault($meta,'transfer_type_enable_3',array(1,2,3));
        
        CHBSHelper::setDefault($meta,'vehicle_category_id',array(-1));
        CHBSHelper::setDefault($meta,'vehicle_id_default',-1);        
        CHBSHelper::setDefault($meta,'vehicle_filter_enable',array(1,2,4));
        CHBSHelper::setDefault($meta,'vehicle_sorting_type',0);
        CHBSHelper::setDefault($meta,'vehicle_pagination_vehicle_per_page',0);
        CHBSHelper::setDefault($meta,'vehicle_limit',0);
        
        CHBSHelper::setDefault($meta,'route_id',array(-1));
        CHBSHelper::setDefault($meta,'route_list_item_empty_enable',0);
        CHBSHelper::setDefault($meta,'route_list_item_empty_text','');
        
        CHBSHelper::setDefault($meta,'booking_extra_category_id',array(-1));
        
        CHBSHelper::setDefault($meta,'booking_extra_category_display_enable',0);

        CHBSHelper::setDefault($meta,'currency',array(-1));
        
        CHBSHelper::setDefault($meta,'extra_time_enable',1);
        CHBSHelper::setDefault($meta,'extra_time_range_min',0);
        CHBSHelper::setDefault($meta,'extra_time_range_max',24);
        CHBSHelper::setDefault($meta,'extra_time_step',1);
        CHBSHelper::setDefault($meta,'extra_time_unit',2);
        
        CHBSHelper::setDefault($meta,'duration_min',1);
        CHBSHelper::setDefault($meta,'duration_max',24);
        CHBSHelper::setDefault($meta,'duration_step',1);
        
        CHBSHelper::setDefault($meta,'booking_period_from','');
        CHBSHelper::setDefault($meta,'booking_period_to','');
        CHBSHelper::setDefault($meta,'booking_period_type',1);
        
        CHBSHelper::setDefault($meta,'booking_vehicle_interval',0);
        
        CHBSHelper::setDefault($meta,'booking_summary_hide_fee',0);
        CHBSHelper::setDefault($meta,'price_hide',0);       
        CHBSHelper::setDefault($meta,'order_sum_split',0);       
        CHBSHelper::setDefault($meta,'show_net_price_hide_tax',0);       
        
        CHBSHelper::setDefault($meta,'prevent_double_vehicle_booking_enable',0);
        CHBSHelper::setDefault($meta,'vehicle_in_the_same_booking_passenger_sum_enable',0);
        
        CHBSHelper::setDefault($meta,'step_second_enable',1);
        CHBSHelper::setDefault($meta,'thank_you_page_enable',1);
        CHBSHelper::setDefault($meta,'thank_you_page_button_back_to_home_label',__('Back To Home','chauffeur-booking-system'));
        CHBSHelper::setDefault($meta,'thank_you_page_button_back_to_home_url_address','');
        
        CHBSHelper::setDefault($meta,'distance_minimum',0);
        CHBSHelper::setDefault($meta,'duration_minimum',0);
        CHBSHelper::setDefault($meta,'order_value_minimum',0.00);
        CHBSHelper::setDefault($meta,'timepicker_step',30);
        
        CHBSHelper::setDefault($meta,'timepicker_dropdown_list_enable',1);
        
        CHBSHelper::setDefault($meta,'form_preloader_enable',1);
        CHBSHelper::setDefault($meta,'form_preloader_image_src','');
        CHBSHelper::setDefault($meta,'form_preloader_background_opacity',20);
        CHBSHelper::setDefault($meta,'form_preloader_background_color','FFFFFF');
        
        CHBSHelper::setDefault($meta,'billing_detail_state',1);
        CHBSHelper::setDefault($meta,'billing_detail_list_state','');       
        
        CHBSHelper::setDefault($meta,'booking_status_default_id',1);
        
        CHBSHelper::setDefault($meta,'driver_default_id',-1);
        
        CHBSHelper::setDefault($meta,'geolocation_server_side_enable',1);
        
        CHBSHelper::setDefault($meta,'summary_sidebar_sticky_enable',0);
        
        CHBSHelper::setDefault($meta,'scroll_to_booking_extra_after_select_vehicle_enable',1);
        
        CHBSHelper::setDefault($meta,'woocommerce_enable',0);
        CHBSHelper::setDefault($meta,'woocommerce_account_enable_type',1);
        
        CHBSHelper::setDefault($meta,'coupon_enable',0);
        
        CHBSHelper::setDefault($meta,'passenger_adult_enable_service_type_1',0);
        CHBSHelper::setDefault($meta,'passenger_children_enable_service_type_1',0);
        CHBSHelper::setDefault($meta,'passenger_adult_enable_service_type_2',0);
        CHBSHelper::setDefault($meta,'passenger_children_enable_service_type_2',0);
        CHBSHelper::setDefault($meta,'passenger_adult_enable_service_type_3',0);
        CHBSHelper::setDefault($meta,'passenger_children_enable_service_type_3',0);
        
        CHBSHelper::setDefault($meta,'passenger_adult_default_number','');
        CHBSHelper::setDefault($meta,'passenger_children_default_number','');
        
        CHBSHelper::setDefault($meta,'show_price_per_single_passenger',0);
        
        CHBSHelper::setDefault($meta,'calculation_method_service_type_1',1);
        CHBSHelper::setDefault($meta,'calculation_method_service_type_3',1);        
        
        CHBSHelper::setDefault($meta,'base_location','');
        CHBSHelper::setDefault($meta,'base_location_coordinate_lat','');
        CHBSHelper::setDefault($meta,'base_location_coordinate_lng','');        
        
        CHBSHelper::setDefault($meta,'waypoint_enable',1);

        CHBSHelper::setDefault($meta,'location_fixed_autocomplete_enable',0);
        
        CHBSHelper::setDefault($meta,'location_fixed_pickup_service_type_1',array(-1));
        CHBSHelper::setDefault($meta,'location_fixed_dropoff_service_type_1',array(-1));
        CHBSHelper::setDefault($meta,'location_fixed_pickup_service_type_2',array(-1));
        CHBSHelper::setDefault($meta,'location_fixed_dropoff_service_type_2',array(-1));

        CHBSHelper::setDefault($meta,'location_fixed_list_item_empty_enable',0);
        CHBSHelper::setDefault($meta,'location_fixed_list_item_empty_text','');
        
        CHBSHelper::setDefault($meta,'ride_time_multiplier','1.00');
        
        CHBSHelper::setDefault($meta,'google_autosugestion_address_type',2);
        CHBSHelper::setDefault($meta,'icon_field_enable',0);
        
        CHBSHelper::setDefault($meta,'navigation_top_enable',1);
        CHBSHelper::setDefault($meta,'step_1_right_panel_visibility',1);
        CHBSHelper::setDefault($meta,'vehicle_more_info_default_show',0);
        
        CHBSHelper::setDefault($meta,'booking_title',__('Booking %s','chauffeur-booking-system'));
        
        /***/
        
		for($i=1;$i<8;$i++)
		{
			if(!isset($meta['business_hour'][$i]))
                $meta['business_hour'][$i]=array('start'=>null,'stop'=>null);
		}	

		if(!array_key_exists('date_exclude',$meta))
			$meta['date_exclude']=array();        
        
        /***/
        
        CHBSHelper::setDefault($meta,'payment_mandatory_enable',0);
        CHBSHelper::setDefault($meta,'payment_processing_enable',1);
        CHBSHelper::setDefault($meta,'payment_woocommerce_step_3_enable',1);        
        
        CHBSHelper::setDefault($meta,'payment_deposit_enable',0);
        CHBSHelper::setDefault($meta,'payment_deposit_value',30);
        
        CHBSHelper::setDefault($meta,'payment_id',array(1));
        
        CHBSHelper::setDefault($meta,'payment_paypal_email_address','');
        CHBSHelper::setDefault($meta,'payment_paypal_sandbox_mode_enable','0');
        CHBSHelper::setDefault($meta,'payment_paypal_logo_src','');
        
        CHBSHelper::setDefault($meta,'payment_stripe_api_key_secret','');
        CHBSHelper::setDefault($meta,'payment_stripe_api_key_publishable','');
        CHBSHelper::setDefault($meta,'payment_stripe_redirect_url_address','');
        CHBSHelper::setDefault($meta,'payment_stripe_logo_src','');
        
        CHBSHelper::setDefault($meta,'payment_wire_transfer_info','');
        CHBSHelper::setDefault($meta,'payment_wire_transfer_logo_src','');
        
        CHBSHelper::setDefault($meta,'payment_cash_logo_src','');
        
        /***/
     
        CHBSHelper::setDefault($meta,'driving_zone_restriction_pickup_location_enable','0');
        CHBSHelper::setDefault($meta,'driving_zone_restriction_dropoff_location_enable','0');

        CHBSHelper::setDefault($meta,'driving_zone_restriction_pickup_location_country','-1');
        CHBSHelper::setDefault($meta,'driving_zone_restriction_dropoff_location_country','-1');

        CHBSHelper::setDefault($meta,'driving_zone_restriction_pickup_location_area','');
        CHBSHelper::setDefault($meta,'driving_zone_restriction_pickup_location_area_radius','50');
        CHBSHelper::setDefault($meta,'driving_zone_restriction_pickup_location_area_coordinate_lat','');
        CHBSHelper::setDefault($meta,'driving_zone_restriction_pickup_location_area_coordinate_lng','');

        CHBSHelper::setDefault($meta,'driving_zone_restriction_dropoff_location_area','');
        CHBSHelper::setDefault($meta,'driving_zone_restriction_dropoff_location_area_radius','50');
        CHBSHelper::setDefault($meta,'driving_zone_restriction_dropoff_location_area_coordinate_lat','');
        CHBSHelper::setDefault($meta,'driving_zone_restriction_dropoff_location_area_coordinate_lng','');
              
        /***/
        
        CHBSHelper::setDefault($meta,'booking_new_sender_email_account_id',-1);
        CHBSHelper::setDefault($meta,'booking_new_recipient_email_address','');
        
        CHBSHelper::setDefault($meta,'nexmo_sms_enable',0);
        CHBSHelper::setDefault($meta,'nexmo_sms_api_key','');
        CHBSHelper::setDefault($meta,'nexmo_sms_api_key_secret','');
        CHBSHelper::setDefault($meta,'nexmo_sms_sender_name','');
        CHBSHelper::setDefault($meta,'nexmo_sms_recipient_phone_number','');
        CHBSHelper::setDefault($meta,'nexmo_sms_message',__('New booking has been received.','chauffeur-booking-system'));
     
        CHBSHelper::setDefault($meta,'twilio_sms_enable',0);
        CHBSHelper::setDefault($meta,'twilio_sms_api_sid','');
        CHBSHelper::setDefault($meta,'twilio_sms_api_token','');
        CHBSHelper::setDefault($meta,'twilio_sms_sender_phone_number','');
        CHBSHelper::setDefault($meta,'twilio_sms_recipient_phone_number','');
        CHBSHelper::setDefault($meta,'twilio_sms_message',__('New booking has been received.','chauffeur-booking-system'));
        
        CHBSHelper::setDefault($meta,'telegram_enable',0);
        CHBSHelper::setDefault($meta,'telegram_token','');
        CHBSHelper::setDefault($meta,'telegram_group_id','');
        CHBSHelper::setDefault($meta,'telegram_message',__('New booking has been received.','chauffeur-booking-system'));
        
        /***/
                
        CHBSHelper::setDefault($meta,'google_map_default_location_type',1);
        CHBSHelper::setDefault($meta,'google_map_default_location_fixed','');
        CHBSHelper::setDefault($meta,'google_map_default_location_fixed_coordinate_lat','');
        CHBSHelper::setDefault($meta,'google_map_default_location_fixed_coordinate_lng','');
        
        CHBSHelper::setDefault($meta,'google_map_route_avoid',-1);
        
        CHBSHelper::setDefault($meta,'google_map_draggable_enable',1);
        CHBSHelper::setDefault($meta,'google_map_scrollwheel_enable',1);
        CHBSHelper::setDefault($meta,'google_map_traffic_layer_enable',0);
        
        CHBSHelper::setDefault($meta,'google_map_draggable_location_enable',0);
        
        CHBSHelper::setDefault($meta,'google_map_map_type_control_enable',0);
        CHBSHelper::setDefault($meta,'google_map_map_type_control_id','SATELLITE');
        CHBSHelper::setDefault($meta,'google_map_map_type_control_style','DEFAULT');
        CHBSHelper::setDefault($meta,'google_map_map_type_control_position','TOP_CENTER');
        
        CHBSHelper::setDefault($meta,'google_map_zoom_control_enable',0);
        CHBSHelper::setDefault($meta,'google_map_zoom_control_style','DEFAULT');
        CHBSHelper::setDefault($meta,'google_map_zoom_control_position','TOP_CENTER');
        CHBSHelper::setDefault($meta,'google_map_zoom_control_level',6);
        
        CHBSHelper::setDefault($meta,'google_map_pan_control_enable',0);
        CHBSHelper::setDefault($meta,'google_map_pan_control_position','TOP_CENTER');        

        CHBSHelper::setDefault($meta,'google_map_scale_control_enable',0);
        CHBSHelper::setDefault($meta,'google_map_scale_control_position','TOP_CENTER');        
        
        CHBSHelper::setDefault($meta,'google_map_street_view_enable',0);
        CHBSHelper::setDefault($meta,'google_map_street_view_postion','TOP_CENTER');        
        
        /***/
        
        CHBSHelper::setDefault($meta,'google_calendar_enable',0);
        CHBSHelper::setDefault($meta,'google_calendar_id','');
        CHBSHelper::setDefault($meta,'google_calendar_settings','');
        
        /***/
        
        CHBSHelper::setDefault($meta,'style_color',array_fill(1,count($BookingFormStyle->getColor()),''));   
	}
    
    /**************************************************************************/
    
    function getDictionary($attr=array())
    {
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'booking_form_id'   												=>	0
		);
		
		$attribute=shortcode_atts($default,$attr);
		
		CHBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'															=>	self::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1,
			'orderby'															=>	array('menu_order'=>'asc','title'=>'asc'),
            'suppress_filters'                                                  =>  true
		);
		
		if(array_key_exists('booking_form_id',$attr))
        {
			$argument['p']=$attribute['booking_form_id'];
            if((int)$argument['p']<=0) return($dictionary);
        }
        
		$query=new WP_Query($argument);
		if($query===false) return($dictionary);
		
		while($query->have_posts())
		{
			$query->the_post();
            if(is_null($post)) continue;
            
			$dictionary[$post->ID]['post']=$post;
			$dictionary[$post->ID]['meta']=CHBSPostMeta::getPostMeta($post);
		}

		CHBSHelper::preservePost($post,$bPost,0);	
        
		return($dictionary);        
    }
    
    /**************************************************************************/
    
    function createBookingForm($attr)
    {
        $Length=new CHBSLength();
        $TaxRate=new CHBSTaxRate();
        $TransferType=new CHBSTransferType();
        
		$action=CHBSHelper::getGetValue('action',false);
		if($action==='ipn')
		{
			$PaymentPaypal=new CHBSPaymentPaypal();
			$PaymentPaypal->handleIPN();
			return(null);
		}
                
		$default=array
		(
			'booking_form_id'   												=>	0,
            'currency'                                                          =>  '',
            'widget_mode'                                                       =>  0,
            'widget_service_type_id'                                            =>  1,
            'widget_booking_form_url'                                           =>  '',
            'widget_booking_form_new_window'                                    =>  0,
		);
		
        $data=array();
        
		$attribute=shortcode_atts($default,$attr);               

        if(!is_array($data=$this->checkBookingForm($attribute['booking_form_id'],$attribute['currency'],true))) return;
             
        $data['ajax_url']=admin_url('admin-ajax.php');
        
        $data['booking_form_post_id']=$attribute['booking_form_id'];
        $data['booking_form_html_id']=CHBSHelper::createId('chbs_booking_form');
        
        $data['dictionary']['transfer_type']=$TransferType->getTransferType();

        $data['dictionary']['tax_rate']=$TaxRate->getDictionary();
                
        $dictionary=$Length->getUnit();
        $data['length_unit']=$dictionary[CHBSOption::getOption('length_unit')];
        $data['length_unit_id']=CHBSOption::getOption('length_unit');
       
        if($attribute['widget_mode']==1)
        {
            if(!in_array($attribute['widget_service_type_id'],$data['meta']['service_type_id']))
            {
                $attribute['widget_service_type_id']=$data['meta']['service_type_id'][0];
            }
        }
        
        $data['widget_mode']=$attribute['widget_mode'];
        $data['widget_service_type_id']=$attribute['widget_service_type_id'];
        $data['widget_booking_form_url']=$attribute['widget_booking_form_url'];
        $data['widget_booking_form_new_window']=$attribute['widget_booking_form_new_window'];
        
        $data['date_available']=$this->getBookingFormDateAvailable($data['meta']);
        
        $Template=new CHBSTemplate($data,PLUGIN_CHBS_TEMPLATE_PATH.'public/public.php');
        return($Template->output());
    }
    
    /**************************************************************************/
    
    function bookingFormDisplayError($message,$displayError)
    {
        if(!$displayError) return;
        echo '<div class="chbs-booking-form-error">'.esc_html($message).'</div>';
    }
    
    /**************************************************************************/
    
    function checkBookingForm($bookingFormId,$currency=null,$displayError=false)
    {
        $data=array();
        
        $Validation=new CHBSValidation();
        $WooCommerce=new CHBSWooCommerce();
        $BookingExtra=new CHBSBookingExtra();
        
        $bookingForm=$this->getDictionary(array('booking_form_id'=>$bookingFormId));
        if(!count($bookingForm)) 
        {
            $this->bookingFormDisplayError(__('Booking form with provided ID doesn\'t exist.','chauffeur-booking-form'),$displayError);
            return(-1);
        }
        
        $data['post']=$bookingForm[$bookingFormId]['post'];
        $data['meta']=$bookingForm[$bookingFormId]['meta'];
       
        if(in_array(3,$data['meta']['service_type_id']))
        {
            $data['dictionary']['route']=$this->getBookingFormRoute($data['meta']);
            if(!count($data['dictionary']['route'])) 
            {
                $this->bookingFormDisplayError(__('There are not assigned routes for flat rate service type. Please create at least one route or disable "Flat rate" service type in booking form settings.','chauffeur-booking-form'),$displayError);
                return(-2);
            }
        }   
        
        $data['dictionary']['vehicle']=$this->getBookingFormVehicle($data['meta']);
        
        if(!count($data['dictionary']['vehicle'])) 
        { 
            $this->bookingFormDisplayError(__('Plugin cannot find at least one vehicle.','chauffeur-booking-form'),$displayError);
            return(-3);
        }
        
        if($Validation->isEmpty($currency))
            $currency=CHBSHelper::getGetValue('currency',false);
        
        if(in_array($currency,$data['meta']['currency']))
            $data['currency']=$currency;
        else $data['currency']=CHBSOption::getOption('currency');
        
        if($WooCommerce->isEnable($data['meta']))
        {
            $data['dictionary']['payment_woocommerce']=$WooCommerce->getPaymentDictionary();
        }
        else 
        {
            $data['dictionary']['payment']=$this->getBookingFormPayment($data['meta']);
        }
        
        $data['dictionary']['booking_extra']=$this->getBookingFormExtra($data['meta']);
              
        $data['dictionary']['booking_extra_category']=$BookingExtra->getCategory();
        
        $data['dictionary']['vehicle_category']=$this->getBookingFormVehicleCategory($data['meta']);
  
        $data['vehicle_bag_count_range']=$this->getVehicleBagCountRange($data['dictionary']['vehicle']);
        $data['vehicle_passenger_count_range']=$this->getVehiclePassengerCountRange($data);
        
        /****/
        
        $TaxRate=new CHBSTaxRate();
        $Country=new CHBSCountry();
        $Geofence=new CHBSGeofence();
        $PriceRule=new CHBSPriceRule();
        
        $data['dictionary']['country']=$Country->getCountry();
        $data['dictionary']['geofence']=$Geofence->getDictionary();
        $data['dictionary']['tax_rate']=$TaxRate->getDictionary();
        $data['dictionary']['price_rule']=$PriceRule->getDictionary();
        
        /****/
        
        $data['step']=array();
        $data['step']['disable']=array();
        
        if(($data['meta']['step_second_enable']!=1) && (count($data['dictionary']['vehicle'])==1))
        {
            $data['step']['disable']=array(2);
        }
        
        $data['step']['dictionary']=array
        (
            1                                                                   =>  array
            (
                'navigation'                                                    =>  array
                (
                    'number'                                                    =>  __('1','chauffeur-booking-system'),
                    'label'                                                     =>  __('Enter Ride Details','chauffeur-booking-system'),
                ),
                'button'                                                        =>  array
                (
                    'next'                                                      =>  __('Choose a vehicle','chauffeur-booking-system')
                )
            ),
            2                                                                   =>  array
            (
                'navigation'                                                    =>  array
                (                
                    'number'                                                    =>  __('2','chauffeur-booking-system'),
                    'label'                                                     =>  __('Choose a Vehicle','chauffeur-booking-system')
                ),
                'button'                                                        =>  array
                (
                    'prev'                                                      =>  __('Choose ride details','chauffeur-booking-system'),
                    'next'                                                      =>  __('Enter contact details','chauffeur-booking-system')
                )
            ),
            3                                                                   =>  array
            (
                'navigation'                                                    =>  array
                (
                    'number'                                                    =>  __('3','chauffeur-booking-system'),
                    'label'                                                     =>  __('Enter Contact Details','chauffeur-booking-system')
                ),
                'button'                                                        =>  array
                (
                    'prev'                                                      =>  __('Choose a vehicle','chauffeur-booking-system'),
                    'next'                                                      =>  __('Booking summary','chauffeur-booking-system')
                )
            ),
            4                                                                   =>  array
            (
                'navigation'                                                    =>  array
                (
                    'number'                                                    =>  __('4','chauffeur-booking-system'),
                    'label'                                                     =>  __('Booking Summary','chauffeur-booking-system')
                ),
                'button'                                                        =>  array
                (
                    'prev'                                                      =>  __('Enter contact details','chauffeur-booking-system'),
                    'next'                                                      =>  ((int)$data['meta']['price_hide']===1 ? __('Send now','chauffeur-booking-system') : __('Book now','chauffeur-booking-system'))
                )
            )            
        );
        
        if(in_array(2,$data['step']['disable']))
        {
            $data['step']['dictionary'][4]['navigation']['number']=$data['step']['dictionary'][3]['navigation']['number'];
            $data['step']['dictionary'][3]['navigation']['number']=$data['step']['dictionary'][2]['navigation']['number'];
            
            $data['step']['dictionary'][1]['button']['next']=$data['step']['dictionary'][2]['button']['next'];
            $data['step']['dictionary'][3]['button']['prev']=$data['step']['dictionary'][2]['button']['prev'];
        }
       
        $data['vehicle_id_default']=0;
        if(in_array(2,$data['step']['disable']))
        {
            reset($data['dictionary']['vehicle']);
            $data['vehicle_id_default']=key($data['dictionary']['vehicle']);
        }
        else $data['vehicle_id_default']=$data['meta']['vehicle_id_default'];
        
        foreach($data['step']['disable'] as $value)
            unset($data['step']['dictionary'][$value]);
        
        /***/
           
        $GeoLocation=new CHBSGeoLocation();
        
        if((int)$data['meta']['geolocation_server_side_enable']===1)
            $data['client_country_code']=$GeoLocation->getCountryCode();
        else $data['client_country_code']='';
        
        /***/
        
        $Location=new CHBSLocation();
        
        $data['dictionary']['location']=$Location->getDictionary();
        
        $field=array('location_fixed_pickup_service_type_1','location_fixed_dropoff_service_type_1','location_fixed_pickup_service_type_2','location_fixed_dropoff_service_type_2');

        foreach($field as $fieldName)
        {
            foreach($data['meta'][$fieldName] as $index=>$value)
            {
                if($value==-1)
                {
                    $data['meta'][$fieldName]=array();
                    break;
                }

                if(array_key_exists($value,$data['dictionary']['location']))                        
                {
                    $location=$data['dictionary']['location'][$value];
                    $data['meta'][$fieldName][$value]=array('id'=>$value,'address'=>$location['meta']['location_name'],'formatted_address'=>$location['post']->post_title,'lat'=>$location['meta']['location_name_coordinate_lat'],'lng'=>$location['meta']['location_name_coordinate_lng'],'dropoff_disable'=>array());
                }
                
                if($fieldName==='location_fixed_pickup_service_type_1')
                {
                    $t=$data['dictionary']['location'][$value]['meta']['location_dropoff_disable_service_type_1'];
                    
                    if(is_array($t))
                    {
                        if(array_key_exists($bookingFormId,$t))
                        {
                            if(!in_array(-1,$t[$bookingFormId]))
                                $data['meta'][$fieldName][$value]['dropoff_disable']=$t[$bookingFormId];
                        }
                    }
                }
                
                if($fieldName==='location_fixed_pickup_service_type_2')
                {
                    $t=$data['dictionary']['location'][$value]['meta']['location_dropoff_disable_service_type_2'];
                    
                    if(is_array($t))
                    {
                        if(array_key_exists($bookingFormId,$t))
                        {
                            if(!in_array(-1,$t[$bookingFormId]))
                                $data['meta'][$fieldName][$value]['dropoff_disable']=$t[$bookingFormId];
                        }
                    }
                }
            }
            
			foreach($data['meta'][$fieldName] as $index=>$value)
			{
				if(!is_array($value)) unset($data['meta'][$fieldName][$index]);
			}		
        }
        
        $data['meta']['waypoint_enable']=($data['meta']['waypoint_enable']==1) && (!count($data['meta']['location_fixed_pickup_service_type_1'])) && (!count($data['meta']['location_fixed_dropoff_service_type_1']));

        /***/

        return($data);
    }
    
    /**************************************************************************/
    
    function getBookingFormVehicle($meta)
    {
        $category=array();
        
        if(count($meta['vehicle_category_id']))
            $category=array_diff($meta['vehicle_category_id'],array(-1));
   
        $Date=new CHBSDate();
        $Vehicle=new CHBSVehicle();
        
        $dictionary=$Vehicle->getDictionary(array('category_id'=>$category),$meta['vehicle_sorting_type']);
                        
        $data=CHBSHelper::getPostOption();
                
        if(isset($data['service_type_id']))
        {
            $serviceTypeId=$data['service_type_id'];
            
            /***/
            
            if((int)$serviceTypeId===3)
            {
                $Route=new CHBSRoute();
                
                $route=$Route->getDictionary(array('route_id'=>(int)$data['route_service_type_3']));
                $route=$Route->getEnableVehicleFromRoute($route);
                
                foreach($dictionary as $index=>$value)
                {
                    if(!in_array($index,$route))
                        unset($dictionary[$index]);
                }
            }
            
            /***/
            
            $pickupDate=$Date->formatDateToStandard($data['pickup_date_service_type_'.$serviceTypeId]);
            $pickupTime=$Date->formatTimeToStandard($data['pickup_time_service_type_'.$serviceTypeId]);    
            
            /***/
            
            $returnDate=null;
            $returnTime=null;
            
            if(in_array($serviceTypeId,array(1,3)))
            {
                if((int)$data['transfer_type_service_type_'.$serviceTypeId]===3)
                {
                    $returnDate=$Date->formatDateToStandard($data['return_date_service_type_'.$serviceTypeId]);
                    $returnTime=$Date->formatTimeToStandard($data['return_time_service_type_'.$serviceTypeId]);                       
                }
            }
            
            /***/
                    
            $duration=$data['duration_sum'];
            
            if($meta['step_second_enable']==1)
                $dictionary=$Vehicle->checkAvailability($dictionary,$pickupDate,$pickupTime,$returnDate,$returnTime,$duration,$data,$meta);
        }
        
        $Vehicle->getVehicleAttribute($dictionary);
        
        return($dictionary);
    }
    
    /**************************************************************************/
    
    function getBookingFormVehicleCategory($meta)
    {
        $Vehicle=new CHBSVehicle();
        $dictionary=$Vehicle->getCategory();
     
        $vehicleCategory=array();
        if(count($meta['vehicle_category_id']))
            $vehicleCategory=array_diff($meta['vehicle_category_id'],array(-1));
                
        if(!count($vehicleCategory)) return($dictionary);
        
        foreach($dictionary as $index=>$value)
        {
            if(!in_array($index,$vehicleCategory))
                unset($dictionary[$index]);
        }

        return($dictionary);
    }
    
    /**************************************************************************/
    
    function getBookingFormRoute($meta)
    {
        $Route=new CHBSRoute();
        
        $route=array();
        if(count($meta['route_id']))
            $route=array_diff($meta['route_id'],array(-1));      
        
        $dictionary=$Route->getDictionary(array('route_id'=>$route));
        
        return($dictionary);
    }
   
    /**************************************************************************/
    
    function getBookingFormPayment($meta)
    {
        $Payment=new CHBSPayment();
        
        $payment=$Payment->getPayment();
        foreach($payment as $index=>$value)
        {
            if(!in_array($index,$meta['payment_id']))
               unset($payment[$index]);
        }
        
        return($payment);
    }
    
    /**************************************************************************/
    
    function getBookingFormExtra($meta)
    {
        $category=array();
        
        $data=CHBSHelper::getPostOption();
        
        $ServiceType=new CHBSServiceType();
        $TransferType=new CHBSTransferType();
        
        if(count($meta['booking_extra_category_id']))
            $category=array_diff($meta['booking_extra_category_id'],array(-1));
  
        $BookingExtra=new CHBSBookingExtra();
        $dictionary=$BookingExtra->getDictionary(array('category_id'=>$category));
                
        $serviceTypeId=array_key_exists('service_type_id',$data) ? $data['service_type_id'] : 0;
        
        if($ServiceType->isServiceType($serviceTypeId))
        {
            $transferTypeId=array_key_exists('transfer_type_service_type_'.$serviceTypeId,$data) ? $data['transfer_type_service_type_'.$serviceTypeId] : 0;
            if(!$TransferType->isTransferType($transferTypeId)) $transferTypeId=1;
            
            foreach($dictionary as $index=>$value)
            {
                if(array_key_exists('service_type_id_enable',$value['meta']))
                {
                    if(is_array($value['meta']['service_type_id_enable']))
                    {
                        if(!in_array($serviceTypeId,$value['meta']['service_type_id_enable']))
                            unset($dictionary[$index]);
                    }
                }
                if(array_key_exists('transfer_type_id_enable',$value['meta']))
                {
                    if(is_array($value['meta']['transfer_type_id_enable']))
                    {
                        if(!in_array($transferTypeId,$value['meta']['transfer_type_id_enable']))
                            unset($dictionary[$index]);
                    }
                }            
            }
        }
        
        $Coupon=new CHBSCoupon();
        $coupon=$Coupon->checkCode();
        
        if($coupon!==false)
        {
            $discountPercentage=$coupon['meta']['discount_percentage'];
            foreach($dictionary as $index=>$value)
                $dictionary[$index]['meta']['price']=round($dictionary[$index]['meta']['price']*(1-$discountPercentage/100),2);
        }
        
        return($dictionary);        
    }
    
    /**************************************************************************/
    
    function getAvailableStepNumber($stepCurrent,$stepRequest,$bookingForm)
    {
        if(in_array($stepRequest,$bookingForm['step']['disable']))
            return($this->getAvailableStepNumber($stepCurrent,($stepRequest>$stepCurrent ? $stepRequest+1 : $stepRequest-1),$bookingForm));
        
        return($stepRequest);
    }
    
    /**************************************************************************/

    function goToStep()
    {
        $response=array();
        
        $User=new CHBSUser();
        $Date=new CHBSDate();
        $Length=new CHBSLength();
        $Payment=new CHBSPayment();
        $Country=new CHBSCountry();
        $Validation=new CHBSValidation();
        $WooCommerce=new CHBSWooCommerce();
        $TransferType=new CHBSTransferType();
        $BookingFormElement=new CHBSBookingFormElement();
       
        $data=CHBSHelper::getPostOption();
              
        if(!is_array($bookingForm=$this->checkBookingForm($data['booking_form_id'])))
        {
            if($bookingForm===-3)
            {
                $response['step']=1;
                $this->setErrorGlobal($response,__('Cannot find at least one vehicle available in selected time period.','chauffeur-booking-system'));
                $this->createFormResponse($response);
            }
        }
       
        $response['booking_summary_hide_fee']=$bookingForm['meta']['booking_summary_hide_fee'];
        
        if((!in_array($data['step_request'],array(2,3,4,5))) || (!in_array($data['step'],array(1,2,3,4))))
        {
            $response['step']=1;
            $this->createFormResponse($response);            
        }

        $data['step_request']=$this->getAvailableStepNumber($data['step'],$data['step_request'],$bookingForm);
        
        /***/
        /***/
        
        if($data['step_request']>1)
        {
            if(!in_array($data['service_type_id'],$bookingForm['meta']['service_type_id']))
                $data['service_type_id']=1;
            
            $data['pickup_date_service_type_'.$data['service_type_id']]=$Date->formatDateToStandard($data['pickup_date_service_type_'.$data['service_type_id']]);
            $data['pickup_time_service_type_'.$data['service_type_id']]=$Date->formatTimeToStandard($data['pickup_time_service_type_'.$data['service_type_id']]);          
            
            $dateTimeError=false;
            $validateReturnDateTime=false;
                        
            if(count($bookingForm['meta']['transfer_type_enable_'.$data['service_type_id']]))
            {
                if(!$TransferType->isTransferType($data['transfer_type_service_type_'.$data['service_type_id']]))
                    $this->setErrorLocal($response,CHBSHelper::getFormName('transfer_type_service_type_3',false),__('Select a valid transfer type.','chauffeur-booking-system'));
                else 
                {
                    if((int)$data['transfer_type_service_type_'.$data['service_type_id']]===3)
                    {
                        $validateReturnDateTime=true;
                     
                        $data['return_date_service_type_'.$data['service_type_id']]=$Date->formatDateToStandard($data['return_date_service_type_'.$data['service_type_id']]);
                        $data['return_time_service_type_'.$data['service_type_id']]=$Date->formatTimeToStandard($data['return_time_service_type_'.$data['service_type_id']]);                        
                    }
                }
            }

            if(!$validateReturnDateTime)
            {
                CHBSHelper::removeUIndex($data,'return_date_service_type_'.$data['service_type_id'],'return_time_service_type_'.$data['service_type_id']);
                
                $data['return_date_service_type_'.$data['service_type_id']]=null;
                $data['return_time_service_type_'.$data['service_type_id']]=null;
            }
            
            /***/
            
            // check if format of pickup date is valid
            if(!$Validation->isDate($data['pickup_date_service_type_'.$data['service_type_id']]))
            {
                $dateTimeError=true;
                $this->setErrorLocal($response,CHBSHelper::getFormName('pickup_date_service_type_'.$data['service_type_id'],false),__('Enter a valid date.','chauffeur-booking-system'));
            }
            // check if format of pickup time is valid
            if(!$Validation->isTime($data['pickup_time_service_type_'.$data['service_type_id']]))
            {   
                $dateTimeError=true;
                $this->setErrorLocal($response,CHBSHelper::getFormName('pickup_time_service_type_'.$data['service_type_id'],false),__('Enter a valid time.','chauffeur-booking-system'));
            }
            if($validateReturnDateTime)
            {
                // check if format of return date is valid
                if(!$Validation->isDate($data['return_date_service_type_'.$data['service_type_id']]))
                {
                    $dateTimeError=true;
                    $this->setErrorLocal($response,CHBSHelper::getFormName('return_date_service_type_'.$data['service_type_id'],false),__('Enter a valid date.','chauffeur-booking-system'));
                }
                // check if format of return time is valid
                if(!$Validation->isTime($data['return_time_service_type_'.$data['service_type_id']]))
                {   
                    $dateTimeError=true;
                    $this->setErrorLocal($response,CHBSHelper::getFormName('return_time_service_type_'.$data['service_type_id'],false),__('Enter a valid time.','chauffeur-booking-system'));
                }                
            }
            
            /***/
            
            if(!$dateTimeError)
            {
                // check if pickup date/time is later than current date/time
                if(in_array($Date->compareDate($data['pickup_date_service_type_'.$data['service_type_id']].' '.$data['pickup_time_service_type_'.$data['service_type_id']],date_i18n('Y-m-d H:i')),array(2)))
                {
                    $dateTimeError=true;
                    $this->setErrorLocal($response,CHBSHelper::getFormName('pickup_date_service_type_'.$data['service_type_id'],false),__('Pickup date and time has to be later than current one.','chauffeur-booking-system'));                    
                }                    
            }            
            
            /***/
            
            if(!$dateTimeError)
            {
                if($validateReturnDateTime)
                {
                    // check if return date/time is later than pickup date/time
                    if(in_array($Date->compareDate($data['pickup_date_service_type_'.$data['service_type_id']].' '.$data['pickup_time_service_type_'.$data['service_type_id']],$data['return_date_service_type_'.$data['service_type_id']].' '.$data['return_time_service_type_'.$data['service_type_id']]),array(0,1)))
                    {
                        $dateTimeError=true;
                        $this->setErrorLocal($response,CHBSHelper::getFormName('return_date_service_type_'.$data['service_type_id'],false),__('Return date and time has to be later than pick up date and time.','chauffeur-booking-system'));                    
                    }
                }
            }          
            
            /***/
            
            // check booking period for pickup date/time
            if(!$dateTimeError)
            {
                $bookingPeriodFrom=$bookingForm['meta']['booking_period_from'];
                if(!$Validation->isNumber($bookingPeriodFrom,0,9999))
                    $bookingPeriodFrom=0;
                
                list($date1,$date2)=$this->getDatePeriod($data,$bookingForm,'pickup',$bookingPeriodFrom);
                if($Date->compareDate($date1,$date2)===2)
                {
                    $this->setErrorLocal($response,CHBSHelper::getFormName('pickup_date_service_type_'.$data['service_type_id'],false),__('Enter a valid date.','chauffeur-booking-system'));
                    $dateTimeError=true;                    
                }       

                if(!$dateTimeError)
                {
                    $bookingPeriodTo=$bookingForm['meta']['booking_period_to'];
                    if($Validation->isNumber($bookingPeriodTo,0,9999))
                    {
                        $bookingPeriodTo+=$bookingPeriodFrom;
                        
                        list($date1,$date2)=$this->getDatePeriod($data,$bookingForm,'pickup',$bookingPeriodTo);    
                        if($Date->compareDate($date1,$date2)===1)
                        {
                            $this->setErrorLocal($response,CHBSHelper::getFormName('pickup_date_service_type_'.$data['service_type_id'],false),__('Enter a valid date.','chauffeur-booking-system'));
                            $dateTimeError=true;                    
                        }                               
                    }
                }
            }
            
            // check booking period for return date/time
            if((!$dateTimeError) && ($validateReturnDateTime))
            {
                $bookingPeriodFrom=$bookingForm['meta']['booking_period_from'];
                if(!$Validation->isNumber($bookingPeriodFrom,0,9999))
                    $bookingPeriodFrom=0;
                
                list($date1,$date2)=$this->getDatePeriod($data,$bookingForm,'return',$bookingPeriodFrom);
                if($Date->compareDate($date1,$date2)===2)
                {
                    $this->setErrorLocal($response,CHBSHelper::getFormName('return_date_service_type_'.$data['service_type_id'],false),__('Enter a valid date.','chauffeur-booking-system'));
                    $dateTimeError=true;                    
                }       

                if(!$dateTimeError)
                {
                    $bookingPeriodTo=$bookingForm['meta']['booking_period_to'];
                    if($Validation->isNumber($bookingPeriodTo,0,9999))
                    {
                        $bookingPeriodTo+=$bookingPeriodFrom;
                        list($date1,$date2)=$this->getDatePeriod($data,$bookingForm,'return',$bookingPeriodTo);
                        
                        if($Date->compareDate($date1,$date2)===1)
                        {
                            $this->setErrorLocal($response,CHBSHelper::getFormName('return_date_service_type_'.$data['service_type_id'],false),__('Enter a valid date.','chauffeur-booking-system'));
                            $dateTimeError=true;                    
                        }                               
                    }
                }
            }
            
            /****/
            
            // check exclude dates
            if(!$dateTimeError)
            {
                if(is_array($bookingForm['meta']['date_exclude']))
                {
                    foreach($bookingForm['meta']['date_exclude'] as $index=>$value)
                    {
                        if($Date->dateInRange($data['pickup_date_service_type_'.$data['service_type_id']],$value['start'],$value['stop']))
                        {
                            $this->setErrorLocal($response,CHBSHelper::getFormName('pickup_date_service_type_'.$data['service_type_id'],false),__('Enter a valid date.','chauffeur-booking-system'));
                            $dateTimeError=true;
                            break;
                        }
                        
                        if($validateReturnDateTime)
                        {
                            if($Date->dateInRange($data['return_date_service_type_'.$data['service_type_id']],$value['start'],$value['stop']))
                            {
                                $this->setErrorLocal($response,CHBSHelper::getFormName('return_date_service_type_'.$data['service_type_id'],false),__('Enter a valid date.','chauffeur-booking-system'));
                                $dateTimeError=true;
                                break;
                            }                            
                        }
                    }
                }
            }
            
            /***/
            
            // check business hours
            if(!$dateTimeError)
            {
                $number=$Date->getDayNumberOfWeek($data['pickup_date_service_type_'.$data['service_type_id']]);
                
                if(isset($bookingForm['meta']['business_hour'][$number]))
                {
                    if(($Validation->isNotEmpty($bookingForm['meta']['business_hour'][$number]['start'])) && ($Validation->isNotEmpty($bookingForm['meta']['business_hour'][$number]['stop'])))
                    {
                        if(!$Date->timeInRange($data['pickup_time_service_type_'.$data['service_type_id']],$bookingForm['meta']['business_hour'][$number]['start'],$bookingForm['meta']['business_hour'][$number]['stop']))
                        {
                            $this->setErrorLocal($response,CHBSHelper::getFormName('pickup_time_service_type_'.$data['service_type_id'],false),__('Enter a valid time in format.','chauffeur-booking-system'));
                            $dateTimeError=true;
                        }
                    }
                    else
                    {
                        $this->setErrorLocal($response,CHBSHelper::getFormName('pickup_date_service_type_'.$data['service_type_id'],false),__('Enter a valid date.','chauffeur-booking-system'));
                        $dateTimeError=true;                        
                    }
                }
            }
            if((!$dateTimeError) && ($validateReturnDateTime))
            {
                $number=$Date->getDayNumberOfWeek($data['return_date_service_type_'.$data['service_type_id']]);
                
                if(isset($bookingForm['meta']['business_hour'][$number]))
                {
                    if(($Validation->isNotEmpty($bookingForm['meta']['business_hour'][$number]['start'])) && ($Validation->isNotEmpty($bookingForm['meta']['business_hour'][$number]['stop'])))
                    {
                        if(!$Date->timeInRange($data['return_time_service_type_'.$data['service_type_id']],$bookingForm['meta']['business_hour'][$number]['start'],$bookingForm['meta']['business_hour'][$number]['stop']))
                        {
                            $this->setErrorLocal($response,CHBSHelper::getFormName('return_time_service_type_'.$data['service_type_id'],false),__('Enter a valid time in format.','chauffeur-booking-system'));
                            $dateTimeError=true;
                        }
                    }
                    else
                    {
                        $this->setErrorLocal($response,CHBSHelper::getFormName('return_date_service_type_'.$data['service_type_id'],false),__('Enter a valid date.','chauffeur-booking-system'));
                        $dateTimeError=true;                        
                    }
                }                
            }
            
            /***/
            
            if(in_array($data['service_type_id'],array(1,2)))
            {              
                if(count($bookingForm['meta']['location_fixed_pickup_service_type_'.$data['service_type_id']]))
                {
                    if(!array_key_exists($data['fixed_location_pickup_service_type_'.$data['service_type_id']],$bookingForm['dictionary']['location']))
                        $this->setErrorLocal($response,CHBSHelper::getFormName('fixed_location_pickup_service_type_'.$data['service_type_id'],false),__('Select a valid location.','chauffeur-booking-system'));
                }
                else
                {
                    if(!$Validation->isCoordinateGroup($data['pickup_location_coordinate_service_type_'.$data['service_type_id']]))
                        $this->setErrorLocal($response,CHBSHelper::getFormName('pickup_location_coordinate_service_type_'.$data['service_type_id'],false),__('Enter a valid location.','chauffeur-booking-system'));

                    if(($data['service_type_id']==1) && ($bookingForm['meta']['waypoint_enable']==1))
                    {
                        if(is_array($data['waypoint_location_coordinate_service_type_1']))
                        {
                            unset($data['waypoint_location_coordinate_service_type_1'][0]);
                            foreach($data['waypoint_location_coordinate_service_type_1'] as $index=>$value)
                            {
                                if(!$Validation->isCoordinateGroup($value))
                                    $this->setErrorLocal($response,CHBSHelper::getFormName('waypoint_location_service_type_1-'.$index,false),__('Enter a valid location.','chauffeur-booking-system'));
                            }
                        }
                    }
                }
                
                if(count($bookingForm['meta']['location_fixed_dropoff_service_type_'.$data['service_type_id']]))
                {  
                    if(!array_key_exists($data['fixed_location_dropoff_service_type_'.$data['service_type_id']],$bookingForm['dictionary']['location']))
                        $this->setErrorLocal($response,CHBSHelper::getFormName('fixed_location_dropoff_service_type_'.$data['service_type_id'],false),__('Select a valid location.','chauffeur-booking-system'));
                    else 
                    {
                        $Location=new CHBSLocation();
                        
                        $bookingFormId=$bookingForm['post']->ID;
                        
                        $fixedLocationPickupId=$data['fixed_location_pickup_service_type_'.$data['service_type_id']];
                        
                        $fixedLocationPickupData=$Location->getDictionary(array('location_id'=>$fixedLocationPickupId));
                        
                        $t=$fixedLocationPickupData[$fixedLocationPickupId]['meta'];
                        
                        if((is_array($t)) && (array_key_exists('location_dropoff_disable_service_type_'.$data['service_type_id'],$t)))
                        {
                            $t=$t['location_dropoff_disable_service_type_'.$data['service_type_id']];
                            
                            if(array_key_exists($bookingFormId,$t))
                            {
                                if(!in_array(-1,$t[$bookingFormId]))
                                {
                                    if(in_array($data['fixed_location_dropoff_service_type_'.$data['service_type_id']],$t[$bookingFormId]))
                                        $this->setErrorLocal($response,CHBSHelper::getFormName('fixed_location_dropoff_service_type_'.$data['service_type_id'],false),__('This drop off location is not available for selected pikcup location.','chauffeur-booking-system'));
                                }
                            }
                        }
                    }
                }
                else
                {
                    if($data['service_type_id']==1)
                    {
                        if(!$Validation->isCoordinateGroup($data['dropoff_location_coordinate_service_type_'.$data['service_type_id']]))
                            $this->setErrorLocal($response,CHBSHelper::getFormName('dropoff_location_coordinate_service_type_'.$data['service_type_id'],false),__('Enter a valid location.','chauffeur-booking-system'));
                    }
                    else if($data['service_type_id']==2)
                    {
                        if($Validation->isNotEmpty($data['dropoff_location_coordinate_service_type_'.$data['service_type_id']]))
                        {
                            if(!$Validation->isCoordinateGroup($data['dropoff_location_coordinate_service_type_'.$data['service_type_id']]))
                                $this->setErrorLocal($response,CHBSHelper::getFormName('dropoff_location_coordinate_service_type_'.$data['service_type_id'],false),__('Enter a valid location.','chauffeur-booking-system'));
                        }
                    }
                }
            }
            
            if(in_array($data['service_type_id'],array(3)))
            {
                if(!array_key_exists($data['route_service_type_3'],$bookingForm['dictionary']['route']))
                    $this->setErrorLocal($response,CHBSHelper::getFormName('route_service_type_3',false),__('Enter a valid route.','chauffeur-booking-system'));
            }            
            
            if(in_array($data['service_type_id'],array(2)))
            {
                $find=false;
                $value=$data['duration_service_type_2'];
                
                for($i=$bookingForm['meta']['duration_min'];$i<=$bookingForm['meta']['duration_max'];$i+=$bookingForm['meta']['duration_step'])
                {
                    if($i==$value)
                    {
                        $find=true;
                        break;
                    }
                }
                
                if(!$find) $this->setErrorLocal($response,CHBSHelper::getFormName('duration_service_type_2',false),__('Enter a valid duration.','chauffeur-booking-system'));
            }
            
            if(count($bookingForm['meta']['transfer_type_enable_'.$data['service_type_id']]))
            {
                if(!$TransferType->isTransferType($data['transfer_type_service_type_'.$data['service_type_id']]))
                    $this->setErrorLocal($response,CHBSHelper::getFormName('transfer_type_service_type_3',false),__('Select a valid transfer type.','chauffeur-booking-system'));
                else 
                {
                    if($data['transfer_type_service_type_'.$data['service_type_id']]===3)
                    {


                    }
                }
            }
            
            if((CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id'],'adult')) || CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id'],'children'))
            {
                $sum=0;
                
                if(CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id'],'adult'))
                {
                    if(!$Validation->isNumber($data['passenger_adult_service_type_'.$data['service_type_id']],0,99))
                    {
                        $this->setErrorLocal($response,CHBSHelper::getFormName('passenger_adult_service_type_'.$data['service_type_id'],false),__('Enter a valid number of adult passengers.','chauffeur-booking-system'));
                    }
                    else $sum+=$data['passenger_adult_service_type_'.$data['service_type_id']];
                }
                            
                if(CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id'],'children'))
                {
                    if(!$Validation->isNumber($data['passenger_children_service_type_'.$data['service_type_id']],0,99))
                    {
                        $this->setErrorLocal($response,CHBSHelper::getFormName('passenger_children_service_type_'.$data['service_type_id'],false),__('Enter a valid number of children passengers.','chauffeur-booking-system'));
                    }
                    else $sum+=$data['passenger_children_service_type_'.$data['service_type_id']];
                }                
                
                if($sum===0)
                {
                    if(CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id'],'adult'))
                        $this->setErrorLocal($response,CHBSHelper::getFormName('passenger_adult_service_type_'.$data['service_type_id'],false),__('Enter a valid number of adult passengers.','chauffeur-booking-system'));
                    if(CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id'],'adult'))
                        $this->setErrorLocal($response,CHBSHelper::getFormName('passenger_children_service_type_'.$data['service_type_id'],false),__('Enter a valid number of children passengers.','chauffeur-booking-system'));
                }
            }
            
            if(in_array($data['service_type_id'],array(1,3)))
            {
                if($bookingForm['meta']['extra_time_enable']==1)
                {
                    $find=false;
                    $value=$data['extra_time_service_type_'.$data['service_type_id']];
                    
                    for($i=$bookingForm['meta']['extra_time_range_min'];$i<=$bookingForm['meta']['extra_time_range_max'];$i+=$bookingForm['meta']['extra_time_step'])
                    {
                        if($i==$value)
                        {
                            $find=true;
                            break;
                        }                        
                    }
                    
                    if(!$find) $this->setErrorLocal($response,CHBSHelper::getFormName('extra_time_service_type_'.$data['service_type_id'],false),__('Select a valid extra time value.','chauffeur-booking-system'));
                }
            }
            
            if(!isset($response['error']))
            {
                if(in_array($data['service_type_id'],array(1)))
                {
                    $distanceSum=$data['distance_sum'];
                    $distanceMinimum=$bookingForm['meta']['distance_minimum'];
                    
                    if(CHBSOption::getOption('length_unit')==2)
                    {
                        $distanceSum=round($Length->convertUnit($distanceSum,1,2),1);
                        $distanceMinimum=round($Length->convertUnit($bookingForm['meta']['distance_minimum'],1,2),1);
                    }
                    
                    if($distanceMinimum>=$distanceSum)
                    {
                        if(CHBSOption::getOption('length_unit')==2)
                            $this->setErrorGlobal($response,sprintf(__('Distance cannot to be lower than %s miles.','chauffeur-booking-system'),$distanceMinimum));
                        else $this->setErrorGlobal($response,sprintf(__('Distance cannot to be lower than %s kilometers.','chauffeur-booking-system'),$distanceMinimum));
                    }
                }
                
                if(in_array($data['service_type_id'],array(1,2)))
                {
                    $durationSum=$data['duration_sum'];
                    $durationMinimum=$bookingForm['meta']['duration_minimum'];                    
                    
                    if($durationMinimum>=$durationSum)
                        $this->setErrorGlobal($response,sprintf(__('Duration cannot to be lower than %s minutes.','chauffeur-booking-system'),$durationMinimum));
                }
            }
            
            if(isset($response['error']))
            {
                $response['step']=1;
                $this->createFormResponse($response);
            }
        }        
        
        /***/
                        
        if($data['step_request']>2)
        {
            $error=false;
            
            if(!array_key_exists($data['vehicle_id'],$bookingForm['dictionary']['vehicle']))
            {
                $error=true;
                $this->setErrorGlobal($response,__('Select a vehicle.','chauffeur-booking-system'));
            }
            
            if(!$error)
            {
                foreach($bookingForm['dictionary']['booking_extra'] as $index=>$value)
                {
                    if((int)$value['meta']['mandatory']===1)
                    {
                        if((in_array(-1,$value['meta']['vehicle_id'])) || (in_array($data['vehicle_id'],$value['meta']['vehicle_id'])))
                        {
                            if((int)$data['booking_extra_value'][$index]===-1)
                            {
                                $error=true;
                                $this->setErrorGlobal($response,__('Select all booking extra marked as required (*).','chauffeur-booking-system'));
                                break;
                            }
                        }
                    }
                }
            }
            
            if(!$error)
            {
                $passengerSum=0;
                if(CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id'],'adult'))
                    $passengerSum+=$data['passenger_adult'];
                if(CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id'],'children'))
                    $passengerSum+=$data['passenger_children'];           

                // *rule
                $argument=array
                (
                    'booking_form_id'                                           =>  (int)$data['booking_form_id'],
                    'service_type_id'                                           =>  (int)$data['service_type_id'],
                    'transfer_type_id'                                          =>  $data['transfer_type_service_type_'.$data['service_type_id']],
                    'pickup_location_coordinate'                                =>  $data['pickup_location_coordinate_service_type_'.$data['service_type_id']],
                    'dropoff_location_coordinate'                               =>  $data['dropoff_location_coordinate_service_type_'.$data['service_type_id']],
                    'fixed_location_pickup'                                     =>  (int)$data['fixed_location_pickup_service_type_'.$data['service_type_id']],
                    'fixed_location_dropoff'                                    =>  (int)$data['fixed_location_dropoff_service_type_'.$data['service_type_id']],
                    'route_id'                                                  =>  (int)$data['route_service_type_3'],
                    'vehicle_id'                                                =>  (int)$data['vehicle_id'],
                    'pickup_date'                                               =>  $data['pickup_date_service_type_'.$data['service_type_id']],
                    'pickup_time'                                               =>  $data['pickup_time_service_type_'.$data['service_type_id']],
                    'base_location_distance'                                    =>  CHBSBookingHelper::getBaseLocationDistance($data['vehicle_id']),
                    'base_location_return_distance'                             =>  CHBSBookingHelper::getBaseLocationDistance($data['vehicle_id'],true),                    
                    'distance'                                                  =>  $data['distance_map'],
                    'distance_sum'                                              =>  $data['distance_sum'],
                    'duration'                                                  =>  in_array($data['service_type_id'],array(1,3)) ? 0 : $data['duration_service_type_2']*60,
                    'duration_map'                                              =>  $data['duration_map'],
                    'duration_sum'                                              =>  in_array($data['service_type_id'],array(1,3)) ? $data['duration_sum'] : $data['duration_service_type_2']*60,
                    'passenger_sum'                                             =>  $passengerSum
                );

                $PriceRule=new CHBSPriceRule();
                
                $priceRule=$PriceRule->getPriceFromRule($argument,$bookingForm);
   
                if((int)$priceRule['calculation_on_request_enable']===1)
                {
                    $error=true;
                    $this->setErrorGlobal($response,__('Select a vehicle.','chauffeur-booking-system'));                        
                }
            }
            
            if(!$error)
            {
                if(($bookingForm['meta']['order_value_minimum']>0) && ((int)$bookingForm['meta']['price_hide']===0))
                {
                    $Booking=new CHBSBooking();

                    $data['booking_form']=$bookingForm;

                    if(($price=$Booking->calculatePrice($data))!==false)      
                    {
                        $orderValueMinimum=number_format($bookingForm['meta']['order_value_minimum']*CHBSCurrency::getExchangeRate(),2,'.','');
                        if($orderValueMinimum>$price['total']['sum']['gross']['value'])
                        {
                            $this->setErrorGlobal($response,sprintf(__('Minimum value of order is %s.','chauffeur-booking-system'),CHBSPrice::format($orderValueMinimum,CHBSCurrency::getFormCurrency())));
                        }
                    }
                }
            }
            
            if(isset($response['error'])) $response['step']=2;
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
                            $this->setErrorGlobal($response,__('Login to your account or provide all needed details.','chauffeur-booking-system'));   
                        }
                    }
                }
                
                if(!$error)
                {
                    if($Validation->isEmpty($data['client_contact_detail_first_name']))
                        $this->setErrorLocal($response,CHBSHelper::getFormName('client_contact_detail_first_name',false),__('Enter your first name','chauffeur-booking-system'));
                    if($Validation->isEmpty($data['client_contact_detail_last_name']))
                        $this->setErrorLocal($response,CHBSHelper::getFormName('client_contact_detail_last_name',false),__('Enter your last name','chauffeur-booking-system'));
                    if(!$Validation->isEmailAddress($data['client_contact_detail_email_address']))
                        $this->setErrorLocal($response,CHBSHelper::getFormName('client_contact_detail_email_address',false),__('Enter valid e-mail address','chauffeur-booking-system'));

                    if(((int)$data['client_billing_detail_enable']===1) || ((int)$bookingForm['meta']['billing_detail_state']===3))
                    {
                         if($Validation->isEmpty($data['client_billing_detail_street_name']))
                            $this->setErrorLocal($response,CHBSHelper::getFormName('client_billing_detail_street_name',false),__('Enter street name','chauffeur-booking-system'));               
                        if($Validation->isEmpty($data['client_billing_detail_city']))
                            $this->setErrorLocal($response,CHBSHelper::getFormName('client_billing_detail_city',false),__('Enter city name','chauffeur-booking-system'));                 
                        if($Validation->isEmpty($data['client_billing_detail_postal_code']))
                            $this->setErrorLocal($response,CHBSHelper::getFormName('client_billing_detail_postal_code',false),__('Enter postal code','chauffeur-booking-system'));                  
                        if(!$Country->isCountry($data['client_billing_detail_country_code']))
                            $this->setErrorLocal($response,CHBSHelper::getFormName('client_billing_detail_country_code',false),__('Enter country name','chauffeur-booking-system')); 
                    }

                    if($WooCommerce->isEnable($bookingForm['meta']))
                    {
                        if(!$User->isSignIn())
                        {
                            if(((int)$data['client_sign_up_enable']===1) || ((int)$bookingForm['meta']['woocommerce_account_enable_type']===2))
                            {
                                $validationResult=$User->validateCreateUser($data['client_contact_detail_email_address'],$data['client_sign_up_login'],$data['client_sign_up_password'],$data['client_sign_up_password_retype']);

                                if(in_array('EMAIL_INVALID',$validationResult))
                                    $this->setErrorLocal($response,CHBSHelper::getFormName('client_contact_detail_email_address',false),__('E-mail address is invalid.','chauffeur-booking-system')); 
                                if(in_array('EMAIL_EXISTS',$validationResult))
                                    $this->setErrorLocal($response,CHBSHelper::getFormName('client_contact_detail_email_address',false),__('E-mail address already exists','chauffeur-booking-system'));                             

                                if(in_array('LOGIN_INVALID',$validationResult))
                                    $this->setErrorLocal($response,CHBSHelper::getFormName('client_sign_up_login',false),__('Login cannot be empty.','chauffeur-booking-system'));                             
                                if(in_array('LOGIN_EXISTS',$validationResult))
                                    $this->setErrorLocal($response,CHBSHelper::getFormName('client_sign_up_login',false),__('Login already exists.','chauffeur-booking-system'));                               

                                if(in_array('PASSWORD1_INVALID',$validationResult))
                                    $this->setErrorLocal($response,CHBSHelper::getFormName('client_sign_up_password',false),__('Password cannot be empty.','chauffeur-booking-system'));                               
                                if(in_array('PASSWORD2_INVALID',$validationResult))
                                    $this->setErrorLocal($response,CHBSHelper::getFormName('client_sign_up_password_retype',false),__('Password cannot be empty.','chauffeur-booking-system'));                             
                                if(in_array('PASSWORD_MISMATCH',$validationResult))
                                    $this->setErrorLocal($response,CHBSHelper::getFormName('client_sign_up_password_retype',false),__('Passwords are not the same.','chauffeur-booking-system'));                              
                            }
                        }
                    }

                    $error=$BookingFormElement->validateField($bookingForm['meta'],$data);
                    foreach($error as $errorValue)
                        $this->setErrorLocal($response,$errorValue['name'],$errorValue['message_error']); 

                    if(!CHBSBookingHelper::isPayment($data['payment_id'],$bookingForm['meta'],3))
                        $this->setErrorGlobal($response,__('Select a payment method.','chauffeur-booking-system'));        
                }
                
                if(isset($response['error']))
                {
                    $response['step']=3;
                } 
            }
        }
        
        /***/
        
        if(!isset($response['error']))
        {
            if($data['step_request']>4)
            {
                $error=$BookingFormElement->validateAgreement($bookingForm['meta'],$data);
                if($error)
                    $this->setErrorGlobal($response,__('Approve all agreements.','chauffeur-booking-system'));
                
                if(isset($response['error']))
                {
                    $response['step']=4;
                }
                else
                {
                    $Booking=new CHBSBooking();

                    $bookingId=$Booking->sendBooking($data,$bookingForm);

                    if((int)$bookingForm['meta']['payment_processing_enable']===1)
                    {
                        if(!$WooCommerce->isEnable($bookingForm['meta']))
                        {
                            if(!$Payment->isPayment($data['payment_id']))
                                $data['payment_id']=0;

                            $response['step']=5;
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
                                $PaymentStripe=new CHBSPaymentStripe();
                                $response['form']=$PaymentStripe->createPaymentForm($data['post_id'],$bookingId,$booking['post']->post_title,$bookingBilling['summary']['pay'],$bookingForm['meta']['payment_stripe_api_key_publishable'],$bookingForm['meta']['client_contact_detail_email_address'],$booking['meta']['currency_id']);
                            }
                        }
                        else
                        {
                            $response['payment_url']=$WooCommerce->getPaymentURLAddress($bookingId+1);
                            $response['step']=5;
                            $response['payment_id']=-1;
                            
                            if($Validation->isNotEmpty($response['payment_url']))
                                $response['thank_you_page_enable']=$bookingForm['meta']['thank_you_page_enable'];
                            else $response['thank_you_page_enable']=1;
                        }
                    }
                    else
                    {
                        $response['step']=5;
                        $response['payment_id']=1;                        
                    }
                }
            }
        }
                        
        /***/
        /***/
        
        if($data['step_request']==2)
        {
            $vehicleHtml=$this->vehicleFilter(false);
            
            if($Validation->isNotEmpty($vehicleHtml))
            {
                $response['vehicle']=$vehicleHtml;
                $response['vehicle_passenger_filter_field']=$this->createVehiclePassengerFilterField($bookingForm['vehicle_passenger_count_range']['min'],$bookingForm['vehicle_passenger_count_range']['max']);
            }
            else 
            {
                $response['step']=1;
                $this->setErrorGlobal($response,__('There are no vehicles which match your filter criteria.','chauffeur-booking-system'));
                $this->createFormResponse($response);
            }
            
            $response['booking_extra']=$this->createBookingFormExtra($bookingForm,$data);
        }
        
        /***/
        
        if($data['step_request']>=3)
        {
            $userData=array();
            
            $User=new CHBSUser();
            $WooCommerce=new CHBSWooCommerce();
            
            if(($WooCommerce->isEnable($bookingForm['meta'])) && ($User->isSignIn()))
            {
                if(!array_key_exists('client_contact_detail_first_name',$data))
                    $userData=$WooCommerce->getUserData();
            }
            
            if(!array_key_exists('client_contact_detail_first_name',$data))
            {
                $userData['client_billing_detail_country_code']=$bookingForm['client_country_code'];
            }
            
            $response['client_form_sign_id']=$this->createClientFormSignIn($bookingForm);
            $response['client_form_sign_up']=$this->createClientFormSignUp($bookingForm,$userData);
        }
        
        /***/
        
        if(!isset($response['error']))
        {
            $response['step']=$data['step_request'];
            $data['step']=$response['step'];
        }
        else $data['step_request']=$data['step'];
        
        $response['summary']=$this->createSummary($data,$bookingForm);
        
        $this->createFormResponse($response);
        
        /***/
    }
    
    /**************************************************************************/
    
    function getDatePeriod($data,$bookingForm,$type,$delta)
    {
        $date=array();
        
        if((int)$bookingForm['meta']['booking_period_type']===1)
        {
            $date[0]=$data[$type.'_date_service_type_'.$data['service_type_id']];
            $date[1]=date_i18n('d-m-Y',CHBSDate::strtotime('+'.$delta.' days'));
        }
        elseif((int)$bookingForm['meta']['booking_period_type']===2)
        {
            $date[0]=$data[$type.'_date_service_type_'.$data['service_type_id']].' '.$data[$type.'_time_service_type_'.$data['service_type_id']];;
            $date[1]=date_i18n('d-m-Y H:i',CHBSDate::strtotime('+'.$delta.' hours'));                            
        }
        elseif((int)$bookingForm['meta']['booking_period_type']===3)
        {
            $date[0]=$data[$type.'_date_service_type_'.$data['service_type_id']].' '.$data[$type.'_time_service_type_'.$data['service_type_id']];;
            $date[1]=date_i18n('d-m-Y H:i',CHBSDate::strtotime('+'.$delta.' minutes'));                            
        } 
        
        return($date);
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
    
    function createFormResponse($response)
    {
        echo json_encode($response);
        exit();        
    }
    
    /**************************************************************************/
    
    function createSummaryPriceElementAjax()
    {
        $Date=new CHBSDate();
        
        $response=array();
        
        $data=CHBSHelper::getPostOption();
        
        $serviceTypeId=$data['service_type_id'];
        
        $data['pickup_date_service_type_'.$serviceTypeId]=$Date->formatDateToStandard($data['pickup_date_service_type_'.$serviceTypeId]);
        $data['pickup_time_service_type_'.$serviceTypeId]=$Date->formatTimeToStandard($data['pickup_time_service_type_'.$serviceTypeId]);  
        
        if(!is_array($bookingForm=$this->checkBookingForm($data['booking_form_id'])))
        {
            $response['html']=null;
            $this->createFormResponse($response);
        }
        
        $price=array();
        
        $response['html']=$this->createSummaryPriceElement($data,$bookingForm,$price);
        
        $this->createFormResponse($response);
    }
    
    /**************************************************************************/
    
    function createSummaryPriceElement($data,$bookingForm,&$price)
    {
        if((int)$bookingForm['meta']['price_hide']===1)
        {
            return(null);
        }
        
        $Booking=new CHBSBooking();
        
        $data['booking_form']=$bookingForm;
              
        if(($price=$Booking->calculatePrice($data,null,$data['booking_form']['meta']['booking_summary_hide_fee']))===false) return(null);
        
        CHBSBookingHelper::getPriceType($data['booking_form'],$priceType,$sumType,$showTax,$data['step']);
        
        if((int)$data['booking_form']['meta']['booking_summary_hide_fee']===0)
        {
            if($price['initial']['sum'][$priceType]['value']!=0)
            {
                $html.=
                '
                    <div class="chbs-summary-price-element-deliver-fee">
                        <span>'.__('Initial fee','chauffeur-booking-system').'</span>
                        <span>'.$price['initial']['sum'][$priceType]['format'].'</span>
                    </div>
                ';
            }
            if($price['delivery']['sum'][$priceType]['value']!=0)
            {
                $html.=
                '
                    <div class="chbs-summary-price-element-deliver-fee">
                        <span>'.__('Delivery fee','chauffeur-booking-system').'</span>
                        <span>'.$price['delivery']['sum'][$priceType]['format'].'</span>
                    </div>
                ';
            }
            if($price['delivery_return']['sum'][$priceType]['value']!=0)
            {
                $html.=
                '
                    <div class="chbs-summary-price-element-deliver-fee">
                        <span>'.__('Return to base fee','chauffeur-booking-system').'</span>
                        <span>'.$price['delivery_return']['sum'][$priceType]['format'].'</span>
                    </div>
                ';
            }
            if($price['extra_time']['sum'][$priceType]['value']!=0)
            {
                $html.=
                '
                    <div class="chbs-summary-price-element-time-extra">
                        <span>'.__('Extra time','chauffeur-booking-system').'</span>
                        <span>'.$price['extra_time']['sum'][$priceType]['format'].'</span>
                    </div>
                ';
            }
        }
        
        if($price['vehicle']['sum'][$priceType]['value']!=0)
        {
            $html.=
            '
                <div class="chbs-summary-price-element-vehicle-fee">
                    <span>'.__('Selected vehicle','chauffeur-booking-system').'</span>
                    <span>'.$price['vehicle']['sum'][$priceType]['format'].'</span>
                </div>
            ';
        }
        
        if($price['booking_extra']['sum'][$priceType]['value']!=0)
        {        
            $html.=
            '
                <div class="chbs-summary-price-element-booking-extra">
                    <span>'.__('Extra options','chauffeur-booking-system').'</span>
                    <span>'.$price['booking_extra']['sum'][$priceType]['format'].'</span>
                </div>
            ';   
        }
        
        if(($priceType==='net') && ($showTax))
        {
            if($price['tax']['sum']['value']!=0)
            {
                $html.=
                '
                    <div class="chbs-summary-price-element-booking-extra">
                        <span>'.__('Tax','chauffeur-booking-system').'</span>
                        <span>'.$price['tax']['sum']['format'].'</span>
                    </div>
                ';
            }
        }
        
        $html.=
        '
            <div class="chbs-summary-price-element-total">
                <span>'.__('Total','chauffeur-booking-system').'</span>
                <span>'.$price['total']['sum'][$sumType]['format'].'</span>
            </div>
        ';            
        
        if(CHBSBookingHelper::isPaymentDepositEnable($data['booking_form']['meta']))
        {
            $html.=
            '
                <div class="chbs-summary-price-element-pay">
                    <span>'.sprintf(__('To pay <span>(%s%% deposit)</span>','chauffeur-booking-system'),$bookingForm['meta']['payment_deposit_value']).'</span>
                    <span>'.$price['pay']['sum']['gross']['format'].'</span>
                </div>
            ';
        }

        $html=
        '
            <div class="chbs-summary-price-element">
                '.$html.'
            </div>
        ';

        return($html);
    }
    
    /**************************************************************************/
    
    function createSummary($data,$bookingForm)
    {
        $response=array();
        
        $User=new CHBSUser();
        $Date=new CHBSDate();
        $Length=new CHBSLength();
        $Country=new CHBSCountry();
        $TaxRate=new CHBSTaxRate();
        $Duration=new CHBSDuration();
        $Validation=new CHBSValidation();
        $WooCommerce=new CHBSWooCommerce();
        $ServiceType=new CHBSServiceType();
        $TransferType=new CHBSTransferType();
        $BookingExtra=new CHBSBookingExtra();
        $BookingFormSummary=new CHBSBookingFormSummary();
        
        $serviceType=$ServiceType->getServiceType($data['service_type_id']);
        
        /***/
        
        $taxRateDictionary=$TaxRate->getDictionary();
        
        /***/
  
        $price=array();
        $priceHtml=$this->createSummaryPriceElement($data,$bookingForm,$price);
   
        /***/
                
        $bookingExtraHtml=array();
        
        if((int)$bookingForm['meta']['price_hide']===0)
        {
            $bookingExtra=$BookingExtra->validate($data,$bookingForm,$taxRateDictionary);
            foreach($bookingExtra as $value)
            {
                $dictionary=$bookingForm['dictionary']['booking_extra'][$value['id']];
                $dictionary['quantity']=$value['quantity'];

                $bookingExtraPrice=$BookingExtra->calculatePrice($dictionary,$taxRateDictionary);

                array_push($bookingExtraHtml,$value['quantity'].' x '.$value['name'].' - '.$bookingExtraPrice['sum']['gross']['format']);
            }
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
        
        $routeHtml=array(null,null);
        
        if(in_array($data['service_type_id'],array(1,2)))
        {
            $waypointLocationHtml=null;
            
            if($data['service_type_id']==1)
                $routeHtml['label']=__('From - To','chauffeur-booking-system');
            else
                $routeHtml['label']=__('Pickup location','chauffeur-booking-system');
            
            if(count($bookingForm['meta']['location_fixed_pickup_service_type_'.$data['service_type_id']]))
            {
                $pickupLocationId=$data['fixed_location_pickup_service_type_'.$data['service_type_id']];
                $pickupLocationHtml=$bookingForm['meta']['location_fixed_pickup_service_type_'.$data['service_type_id']][$pickupLocationId]['formatted_address'];                
            }
            else
            {
                $pickupLocation=json_decode(stripslashes($data['pickup_location_coordinate_service_type_'.$data['service_type_id']]));
                $pickupLocationHtml=$pickupLocation->{'formatted_address'};
                                
                if(($data['service_type_id']==1) && ($bookingForm['meta']['waypoint_enable']==1))
                {
                    if(is_array($data['waypoint_location_coordinate_service_type_1']))
                    {
                        foreach($data['waypoint_location_coordinate_service_type_1'] as $value)
                        {
                            $waypointLocation=json_decode($value);
                            $waypointLocationHtml.=' - '.$waypointLocation->{'formatted_address'};                       
                        }
                    }
                }
            }
            
            if(count($bookingForm['meta']['location_fixed_dropoff_service_type_'.$data['service_type_id']]))
            {
                $dropoffLocationId=$data['fixed_location_dropoff_service_type_'.$data['service_type_id']];
                $dropoffLocationHtml=$bookingForm['meta']['location_fixed_dropoff_service_type_'.$data['service_type_id']][$dropoffLocationId]['formatted_address'];
            }
            else
            {
                $dropoffLocation=json_decode(stripslashes($data['dropoff_location_coordinate_service_type_'.$data['service_type_id']]));
                $dropoffLocationHtml=$dropoffLocation->{'formatted_address'};                   
            }
            
            if($data['service_type_id']==1)
                $routeHtml['value']=$pickupLocationHtml.$waypointLocationHtml.' - '.$dropoffLocationHtml;
            else $routeHtml['value']=$pickupLocationHtml;
        }
        else
        {
            $routeHtml['label']=__('Route','chauffeur-booking-system');
            $routeHtml['value']=$bookingForm['dictionary']['route'][$data['route_service_type_3']]['post']->post_title;            
        }
        
        /***/
        
        $pickupDate=$Date->formatDateToDisplay($data['pickup_date_service_type_'.$data['service_type_id']]);
        $pickupTime=$Date->formatTimeToDisplay($data['pickup_time_service_type_'.$data['service_type_id']]);

        /***/
        
        $returnDate=null;
        $returnTime=null;
        $transferType=null;

        if(in_array($data['service_type_id'],array(1,3)))
        {
            if((count($bookingForm['meta']['transfer_type_enable_1'])) || (count($bookingForm['meta']['transfer_type_enable_3'])))
            {
                $transferType=$TransferType->getTransferTypeName($data['transfer_type_service_type_'.$data['service_type_id']]);
                
                if((int)$data['transfer_type_service_type_'.$data['service_type_id']]===3)
                {
                    if($Validation->isNotEmpty($data['return_date_service_type_'.$data['service_type_id']]))
                        $returnDate=$Date->formatDateToDisplay($data['return_date_service_type_'.$data['service_type_id']]);
                    if($Validation->isNotEmpty($data['return_time_service_type_'.$data['service_type_id']]))
                        $returnTime=$Date->formatTimeToDisplay($data['return_time_service_type_'.$data['service_type_id']]); 
                }
            }            
        }

        /***/
        
        $passengerHtml=null;
        if((CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id'],'adult')) || (CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id'],'children')))
            $passengerHtml=CHBSBookingHelper::getPassengerLabel($data['passenger_adult_service_type_'.$data['service_type_id']],$data['passenger_children_service_type_'.$data['service_type_id']]);
        
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
                            __('Logged as','chauffeur-booking-system'),
                            $userHtml
                        )
                    );                    
                }
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Service type','chauffeur-booking-system'),
                        $serviceType[0]
                    )
                );
                
                if($Validation->isNotEmpty($transferType))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Transfer type','chauffeur-booking-system'),
                            $transferType
                        )
                    );                    
                }
                
                $BookingFormSummary->add
                (
                    array
                    (
                        $routeHtml['label'],
                        $routeHtml['value']
                    )
                );
                if($data['service_type_id']==2)
                {
                    if($Validation->isNotEmpty($dropoffLocationHtml))
                    {
                        $BookingFormSummary->add
                        (
                            array
                            (
                                __('Dropoff location','chauffeur-booking-system'),
                                $dropoffLocationHtml
                            )
                        );
                    }
                }
               
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Pickup date, time','chauffeur-booking-system'),
                        $pickupDate.', '.$pickupTime
                    )
                );
                
                if(($Validation->isNotEmpty($returnDate)) && ($Validation->isNotEmpty($returnTime)))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Return date, time','chauffeur-booking-system'),
                            $returnDate.', '.$returnTime
                        )
                    );
                }
                
                if(($bookingForm['meta']['extra_time_enable']==1) && (in_array($data['service_type_id'],array(1,3))))
                {
                    $value=$data['extra_time_service_type_'.$data['service_type_id']];
                    
                    if($value>0)
                    {
                        $BookingFormSummary->add
                        (
                            array
                            (
                                __('Extra time','chauffeur-booking-system'),
                                sprintf(((int)$bookingForm['meta']['extra_time_unit']===1 ? __('%s minutes','chauffeur-booking-system') : __('%s hours','chauffeur-booking-system')),$value)
                            )
                        );
                    }
                }
                
                $BookingFormSummary->add
                (
                    array
                    (
                        array
                        (
                            __('Total distance','chauffeur-booking-system'),
                            $Length->format($data['distance_sum'])
                        ),
                        array
                        (
                            __('Total time','chauffeur-booking-system'),
                            $Duration->format($data['duration_sum'])
                        )
                    ),
                    2
                );
                
                if($Validation->isNotEmpty($passengerHtml))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Passengers','chauffeur-booking-system'),
                            $passengerHtml
                        )
                    );
                }                
                
                $response[0]=$BookingFormSummary->create(__('Summary','chauffeur-booking-system')).$priceHtml;
                
            break;
        
            case 3:
                
                if(!is_null($userHtml))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Logged as','chauffeur-booking-system'),
                            $userHtml
                        )
                    );                    
                }
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Service type','chauffeur-booking-system'),
                        $serviceType[0]
                    )
                );
                
                if($Validation->isNotEmpty($transferType))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Transfer type','chauffeur-booking-system'),
                            $transferType
                        )
                    );                    
                }
                
                $BookingFormSummary->add
                (
                    array
                    (
                        $routeHtml['label'],
                        $routeHtml['value']
                    )
                );
                
                if($data['service_type_id']==2)
                {
                    if($Validation->isNotEmpty($dropoffLocationHtml))
                    {
                        $BookingFormSummary->add
                        (
                            array
                            (
                                __('Dropoff location','chauffeur-booking-system'),
                                $dropoffLocationHtml
                            )
                        );
                    }
                }
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Pickup date, time','chauffeur-booking-system'),
                        $pickupDate.', '.$pickupTime
                    )
                );
                
                if(($Validation->isNotEmpty($returnDate)) && ($Validation->isNotEmpty($returnTime)))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Return date, time','chauffeur-booking-system'),
                            $returnDate.', '.$returnTime
                        )
                    );
                }                
                
                if(($bookingForm['meta']['extra_time_enable']==1) && (in_array($data['service_type_id'],array(1,3))))
                {
                    $value=$data['extra_time_service_type_'.$data['service_type_id']];
                    
                    if($value>0)
                    {
                        $BookingFormSummary->add
                        (
                            array
                            (
                                __('Extra time','chauffeur-booking-system'),
                                sprintf(((int)$bookingForm['meta']['extra_time_unit']===1 ? __('%s minutes','chauffeur-booking-system') : __('%s hours','chauffeur-booking-system')),$value)
                            )
                        );
                    }
                }
                
                $BookingFormSummary->add
                (
                    array
                    (
                        array
                        (
                            __('Total distance','chauffeur-booking-system'),
                            $Length->format($data['distance_sum'])
                        ),
                        array
                        (
                            __('Total time','chauffeur-booking-system'),
                            $Duration->format($data['duration_sum'])
                        ),
                    ),
                    2
                );
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Vehicle','chauffeur-booking-system'),
                        $bookingForm['dictionary']['vehicle'][$data['vehicle_id']]['post']->post_title
                    )
                );
                
                if(count($bookingExtraHtml))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Extra options','chauffeur-booking-system'),
                            $bookingExtraHtml
                        ),
                        3
                    );
                }
                
                if($Validation->isNotEmpty($passengerHtml))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Passengers','chauffeur-booking-system'),
                            $passengerHtml
                        )
                    );
                }       
                
                $response[0]=$BookingFormSummary->create(__('Summary','chauffeur-booking-system')).$priceHtml;
                
            break;
        
            case 4:
                
                /***/
                
                $BookingFormElement=new CHBSBookingFormElement();
                $panel=$BookingFormElement->getPanel($bookingForm['meta']);
                
                if(!is_null($userHtml))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Logged as','chauffeur-booking-system'),
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
                            __('First name','chauffeur-booking-system'),
                            $data['client_contact_detail_first_name']
                        ),
                        array
                        (
                            __('Last name','chauffeur-booking-system'),
                            $data['client_contact_detail_last_name']
                        )
                    ),
                    2
                );
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('E-mail adddress','chauffeur-booking-system'),
                        $data['client_contact_detail_email_address']
                    )
                );       
                
                if($Validation->isNotEmpty($data['client_contact_detail_phone_number']))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Phone number','chauffeur-booking-system'),
                            $data['client_contact_detail_phone_number']
                        )
                    );
                }
                
                foreach($panel as $panelValue)
                {
                    if(in_array($panelValue['id'],array(1)))
                    {
                        foreach($bookingForm['meta']['form_element_field'] as $fieldValue)
                        {
                            if($fieldValue['panel_id']==$panelValue['id'])
                            {
                                if(array_key_exists('service_type_id_enable',$fieldValue))
                                {
                                    if(is_array($fieldValue['service_type_id_enable']))
                                    {
                                        if(!in_array($data['service_type_id'],$fieldValue['service_type_id_enable']))
                                            continue;
                                    }
                                }
                                
                                $fieldName='form_element_field_'.$fieldValue['id'];

                                $BookingFormSummary->add
                                (
                                    array
                                    (
                                        $fieldValue['label'],
                                        $data[$fieldName]
                                    )
                                ); 
                            }
                        }
                    }
                }
                
                /***/
                   
                if($data['client_billing_detail_enable']==1)
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            array
                            (
                                __('Company name','chauffeur-booking-system'),
                                $data['client_billing_detail_company_name']
                            ),
                            array
                            (
                                __('Tax number','chauffeur-booking-system'),
                                $data['client_billing_detail_tax_number']
                            )
                        ),
                        2
                    );
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Billing address','chauffeur-booking-system'),
                            array
                            (
                                $data['client_billing_detail_street_name'].' '.$data['client_billing_detail_street_number'],
                                $data['client_billing_detail_postal_code'].' '.$data['client_billing_detail_city'],
                                $data['client_billing_detail_state'],
                                $Country->getCountryName($data['client_billing_detail_country_code'])
                            )
                        ),
                        3
                    );
                }
                
                foreach($panel as $panelValue)
                {
                    if(in_array($panelValue['id'],array(2)))
                    {
                        foreach($bookingForm['meta']['form_element_field'] as $fieldValue)
                        {
                            if($fieldValue['panel_id']==$panelValue['id'])
                            {
                                if(array_key_exists('service_type_id_enable',$fieldValue))
                                {
                                    if(is_array($fieldValue['service_type_id_enable']))
                                    {
                                        if(!in_array($data['service_type_id'],$fieldValue['service_type_id_enable']))
                                            continue;
                                    }
                                }
                                                                
                                $fieldName='form_element_field_'.$fieldValue['id'];

                                $BookingFormSummary->add
                                (
                                    array
                                    (
                                        $fieldValue['label'],
                                        $data[$fieldName]
                                    )
                                ); 
                            }
                        }
                    }
                }
                
                if($Validation->isNotEmpty($data['comment']))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Comments','chauffeur-booking-system'),
                            $data['comment']
                        )
                    );
                }
                
                if($Validation->isNotEmpty($passengerHtml))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Passengers','chauffeur-booking-system'),
                            $passengerHtml
                        )
                    );
                }  

                /***/
                
                $response[0].=$BookingFormSummary->create(__('Contact & Billing Info','chauffeur-booking-system'),3);
                
                /***/
                
                $paymentName=CHBSBookingHelper::getPaymentName($data['payment_id'],-1,$bookingForm['meta']);
                
                if(!is_null($paymentName))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Your choice','chauffeur-booking-system'),
                            $paymentName
                        )
                    );  

                    $response[0].=$BookingFormSummary->create(__('Payment Method','chauffeur-booking-system'),3);
                }
                
                /***/
                                
                foreach($panel as $panelValue)
                {
                    if(in_array($panelValue['id'],array(1,2))) continue;
                    
                    foreach($bookingForm['meta']['form_element_field'] as $fieldValue)
                    {
                        if($fieldValue['panel_id']==$panelValue['id'])
                        {
                            if(array_key_exists('service_type_id_enable',$fieldValue))
                            {
                                if(is_array($fieldValue['service_type_id_enable']))
                                {
                                    if(!in_array($data['service_type_id'],$fieldValue['service_type_id_enable']))
                                        continue;
                                }
                            }                          
                            
                            $fieldName='form_element_field_'.$fieldValue['id'];
                    
                            $BookingFormSummary->add
                            (
                                array
                                (
                                    $fieldValue['label'],
                                    $data[$fieldName]
                                )
                            ); 
                        }
                    }
                    
                    $response[0].=$BookingFormSummary->create($panelValue['label'],3);
                }

                /***/
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Service type','chauffeur-booking-system'),
                        $serviceType[0]
                    )
                );
                
                if($Validation->isNotEmpty($transferType))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Transfer type','chauffeur-booking-system'),
                            $transferType
                        )
                    );                    
                }
                
                $BookingFormSummary->add
                (
                    array
                    (
                        $routeHtml['label'],
                        $routeHtml['value']
                    )
                );
                
                if($data['service_type_id']==2)
                {
                    if($Validation->isNotEmpty($dropoffLocationHtml))
                    {
                        $BookingFormSummary->add
                        (
                            array
                            (
                                __('Dropoff location','chauffeur-booking-system'),
                                $dropoffLocationHtml
                            )
                        );
                    }
                }
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Pickup date, time','chauffeur-booking-system'),
                        $pickupDate.', '.$pickupTime
                    )
                );
                
                if(($Validation->isNotEmpty($returnDate)) && ($Validation->isNotEmpty($returnTime)))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Return date, time','chauffeur-booking-system'),
                            $returnDate.', '.$returnTime
                        )
                    );
                }
                
                if(($bookingForm['meta']['extra_time_enable']==1) && (in_array($data['service_type_id'],array(1,3))))
                {
                    $value=$data['extra_time_service_type_'.$data['service_type_id']];
                    
                    if($value>0)
                    {
                        $BookingFormSummary->add
                        (
                            array
                            (
                                __('Extra time','chauffeur-booking-system'),
                                sprintf(((int)$bookingForm['meta']['extra_time_unit']===1 ? __('%s minutes','chauffeur-booking-system') : __('%s hours','chauffeur-booking-system')),$value)
                            )
                        );
                    }
                }
                
                $BookingFormSummary->add
                (
                    array
                    (
                        array
                        (
                            __('Total distance','chauffeur-booking-system'),
                            $Length->format($data['distance_sum'])
                        ),
                        array
                        (
                            __('Total time','chauffeur-booking-system'),
                            $Duration->format($data['duration_sum'])
                        ),
                    ),
                    2
                );
                
                $class=array('chbs-google-map-summary');
                
                if((int)$bookingForm['meta']['step_1_right_panel_visibility']!==1)
                    array_push($class,'chbs-hidden');
                                
                $googleMapHtml='<div'.CHBSHelper::createCSSClassAttribute($class).'></div>';
                
                $response[1]=$googleMapHtml.$BookingFormSummary->create(__('Ride details','chauffeur-booking-system'),1);
                
                /***/
                
                $BookingFormSummary->add
                (
                    array
                    (
                        __('Vehicle','chauffeur-booking-system'),
                        $bookingForm['dictionary']['vehicle'][$data['vehicle_id']]['post']->post_title
                    )
                );     
                
                if(count($bookingExtraHtml))
                {
                    $BookingFormSummary->add
                    (
                        array
                        (
                            __('Extra options','chauffeur-booking-system'),
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
                        <div class="chbs-clear-fix chbs-coupon-code-section">
                            <div class="chbs-form-field">
                                <label>'.__('Do you have a discount code?','chauffeur-booking-system').'</label>
                                <input maxlength="12" name="'.CHBSHelper::getFormName('coupon_code',false).'" value="'.esc_html(array_key_exists('coupon_code',$data) ? $data['coupon_code'] : '').'" type="text">
                            </div>
                            <a href="#" class="chbs-button chbs-button-style-2">
                                '.__('Apply code','chauffeur-booking-system').'
                                <span class="chbs-meta-icon-arrow-horizontal"></span>
                            </a>
                        </div>
                    ';
                }
                
                $thumbnailHtml=null;
                $thumbnail=get_the_post_thumbnail_url($data['vehicle_id'],PLUGIN_CHBS_CONTEXT.'_vehicle');
        
                if($thumbnail!==false)
                    $thumbnailHtml='<div><img src="'.esc_url($thumbnail).'" alt=""/></div>';
                                                              
                $response[2]=$thumbnailHtml.$BookingFormSummary->create(__('Vehicle info','chauffeur-booking-system'),2).$couponHtml.$priceHtml;
                
            break;
        }
        
        return($response);
    }
    
    /**************************************************************************/ 
    
    function createVehicle($data,&$priceToSort)
    {
        $html=array(null);
        
        $Vehicle=new CHBSVehicle();
        $Validation=new CHBSValidation();
        
        /***/
        
        $thumbnail=get_the_post_thumbnail_url($data['vehicle_id'],PLUGIN_CHBS_CONTEXT.'_vehicle');
        if($thumbnail!==false)
        {
            $htmlGallery=null;
            
            $galleryImageUrl=array();
            
            foreach($data['vehicle']['meta']['gallery_image_id'] as $value)
            {
                $url=wp_get_attachment_image_src($value,'full');
                if($url!==false) array_push($galleryImageUrl,$url[0]);
            }
            
            if(count($galleryImageUrl))
            {
                foreach($galleryImageUrl as $galleryImageUrlValue)
                    $htmlGallery.='<li><img src="'.esc_url($galleryImageUrlValue).'"></li>';
                
                $htmlGallery='<div class="chbs-vehicle-gallery"><ul>'.$htmlGallery.'</ul></div>';
            }
            
            /***/
            
            $alt=null;
            $class=array('chbs-vehicle-image');
            
            if($Validation->isNotEmpty($htmlGallery))
            {
                $alt=__('Click to open vehicle gallery.','chauffeur-booking-system');
                array_push($class,'chbs-vehicle-image-has-gallery');
            }
            
            $html[0]='<div'.CHBSHelper::createCSSClassAttribute($class).'><img src="'.esc_url($thumbnail).'" alt="'.esc_attr($alt).'"/></div>'.$htmlGallery;
        }
            
        /***/
        
        // *rule
        $argument=array
        (
            'booking_form_id'                                                   =>  $data['booking_form_id'],
            'service_type_id'                                                   =>  $data['service_type_id'],
            'transfer_type_id'                                                  =>  $data['transfer_type_id'],
            'pickup_location_coordinate'                                        =>  $data['pickup_location_coordinate'],
            'dropoff_location_coordinate'                                       =>  $data['dropoff_location_coordinate'],
            'fixed_location_pickup'                                             =>  $data['fixed_location_pickup'],
            'fixed_location_dropoff'                                            =>  $data['fixed_location_dropoff'],
            'transfer_type_id'                                                  =>  $data['transfer_type_id'],
            'route_id'                                                          =>  $data['route_id'],
            'vehicle_id'                                                        =>  $data['vehicle_id'],
            'pickup_date'                                                       =>  $data['pickup_date'],
            'pickup_time'                                                       =>  $data['pickup_time'],
            'passenger_adult'                                                   =>  $data['passenger_adult'],
            'passenger_children'                                                =>  $data['passenger_children'],
            'base_location_distance'                                            =>  $data['base_location_distance'],
            'base_location_return_distance'                                     =>  $data['base_location_return_distance'],
            'distance'                                                          =>  $data['distance'],
            'distance_sum'                                                      =>  $data['distance_sum'],
            'duration'                                                          =>  $data['duration'],
            'duration_map'                                                      =>  $data['duration_map'],
            'duration_sum'                                                      =>  $data['duration_sum'],
            'booking_form'                                                      =>  $data['booking_form']
        );
        
        $price=$Vehicle->calculatePrice($argument);
        
        /***/
        
        $htmlDescription=null;
        
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
                    <li class="chbs-clear-fix">
                        <div>'.esc_html($value['name']).'</div>
                        <div>'.esc_html($value['value']).'</div>
                    </li>
                ';
            }
            
            if($Validation->isNotEmpty($htmlAttribute[0]))
                $htmlAttribute[0]='<ul class="chbs-list-reset">'.$htmlAttribute[0].'</ul>';
            if($Validation->isNotEmpty($htmlAttribute[1]))
                $htmlAttribute[1]='<ul class="chbs-list-reset">'.$htmlAttribute[1].'</ul>';                
            
            $htmlDescription.=
            '
                <div class="chbs-vehicle-content-description-attribute chbs-clear-fix">
                    '.$htmlAttribute[0].'
                    '.$htmlAttribute[1].'    
                </div>
            ';
        }
        
        if($Validation->isNotEmpty($htmlDescription))
        {
            $classDescription=array('chbs-vehicle-content-description');
            if((int)$data['booking_form']['meta']['vehicle_more_info_default_show']===1)
                array_push($classDescription,'chbs-state-open');
            
            $htmlDescription='<div'.CHBSHelper::createCSSClassAttribute($classDescription).'><div>'.$htmlDescription.'</div></div>';
        }
        
        /****/
        
        $htmlMoreInfo=null;
        if($Validation->isNotEmpty($htmlDescription))
        {
            $htmlMoreInfo=
            '
                <div class="chbs-vehicle-content-meta-button">
                    <a href="#" class="'.((int)$data['booking_form']['meta']['vehicle_more_info_default_show']===1 ? 'chbs-state-selected' : '').'">
                        <span class="chbs-circle chbs-meta-icon-arrow-vertical-small"></span>
                        <span>'.esc_html__('More info','chauffeur-booking-system').'</span>
                        <span>'.esc_html__('Less info','chauffeur-booking-system').'</span>
                    </a> 
                </div>
            ';
        }

        /***/
        
        $htmlPrice=null;
        if(((int)$data['booking_form']['meta']['price_hide']===0) && ((int)$price['price']['base']['calculation_on_request_enable']===0))
        {
            CHBSBookingHelper::getPriceType($data['booking_form'],$priceType,$sumType,$taxShow,$data['step']);
            
            $priceToDisplay=$price['price']['sum'][$priceType]['formatHtml'];
                        
            if((int)$data['booking_form']['meta']['show_price_per_single_passenger']===1)
            {
                if(CHBSBookingHelper::isPassengerEnable($data['booking_form']['meta'],$data['service_type_id'],-1))
                {
                    if($price['price']['other'][$priceType]['price_passenger_children']['value']!=$price['price']['other'][$priceType]['price_passenger_adult']['value'])
                    {
                        $priceToDisplay=$price['price']['other'][$priceType]['price_passenger_children']['formatHtml'].' - '.$price['price']['other'][$priceType]['price_passenger_adult']['formatHtml'];
                    }
                    else $priceToDisplay=$price['price']['other'][$priceType]['price_passenger_adult']['formatHtml'];
                }
                else if(CHBSBookingHelper::isPassengerEnable($data['booking_form']['meta'],$data['service_type_id'],'adult'))
                {
                    $priceToDisplay=$price['price']['other'][$priceType]['price_passenger_adult']['formatHtml'];
                }
                else if(CHBSBookingHelper::isPassengerEnable($data['booking_form']['meta'],$data['service_type_id'],'children'))
                {
                    $priceToDisplay=$price['price']['other'][$priceType]['price_passenger_children']['formatHtml'];
                }
            }
            
            $htmlPrice=
            '
                <div class="chbs-vehicle-content-price">
                    <span>
                        <span>'.$priceToDisplay.'</span>
                    </span>
                </div>  
            ';
        }        
                
        if((int)$price['price']['base']['calculation_on_request_enable']===1)
        {
            $htmlSelect=
            '
                <a href="'.esc_attr($price['price']['base']['calculation_on_request_redirect_url']).'" target="_blank" class="chbs-button chbs-button-style-2 chbs-button-on-request">
                    '.esc_html__('On Request','chauffeur-booking-system').'
                </a>               
            ';
        }
        else 
        {
            $htmlSelect=
            '
                <a href="#" class="chbs-button chbs-button-style-2 '.($data['vehicle_selected_id']==$data['vehicle_id'] ? 'chbs-state-selected' : null).'">
                    '.esc_html__('Select','chauffeur-booking-system').'
                    <span class="chbs-meta-icon-tick"></span>
                </a>            
            ';
        }
        
        $distance=CHBSBookingHelper::getBaseLocationDistance($data['vehicle_id'],false,false);
        $returnDistance=CHBSBookingHelper::getBaseLocationDistance($data['vehicle_id'],true,false);
        
        $html=
        '
            <div class="chbs-vehicle chbs-clear-fix" data-id="'.esc_attr($data['vehicle_id']).'" data-base_location_cooridnate_lat="'.esc_attr($data['vehicle']['meta']['base_location_coordinate_lat']).'"  data-base_location_cooridnate_lng="'.esc_attr($data['vehicle']['meta']['base_location_coordinate_lng']).'">

                '.$html[0].'

                <div class="chbs-vehicle-content">
                
                    <div class="chbs-vehicle-content-header"> 
                        <span>'.get_the_title($data['vehicle_id']).'</span>
                        '.$htmlSelect.'
                    </div>
                    
                    '.$htmlPrice.'
                    
                    '.$htmlDescription.'
                                 
                    <div class="chbs-vehicle-content-meta">
                    
                        <div>
                    
                            '.$htmlMoreInfo.'
                        
                            <div class="chbs-vehicle-content-meta-info">
                                <div>
                                    <span class="chbs-meta-icon-people"></span>
                                    <span class="chbs-circle">'.$data['vehicle']['meta']['passenger_count'].'</span>
                                    <span class="chbs-meta-icon-bag"></span>
                                    <span class="chbs-circle">'.$data['vehicle']['meta']['bag_count'].'</span>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
                
                <input type="hidden" name="'.CHBSHelper::getFormName('base_location_vehicle_distance['.(int)$data['vehicle_id'].']',false).'" value="'.$distance.'"/>
                <input type="hidden" name="'.CHBSHelper::getFormName('base_location_vehicle_return_distance['.(int)$data['vehicle_id'].']',false).'" value="'.$returnDistance.'"/>

            </div>
        ';
        
        $priceToSort=$price['price']['sum']['gross']['value'];
        
        return($html);
    }

    /**************************************************************************/ 
    
    function getVehiclePassengerCountRange($bookingForm)
    {
        $count=array();
        foreach($bookingForm['dictionary']['vehicle'] as $value)
            array_push($count,$value['meta']['passenger_count']);
            
        $count=array('min'=>1,'max'=>max($count));
        
        $data=CHBSHelper::getPostOption();
        
        if(array_key_exists('service_type_id',$data))
        {
            if(CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id']))
            {
                $sum=CHBSBookingHelper::getPassenegerSum($bookingForm['meta'],$data);

                if($sum>1) $count['min']=$sum;
                if($count['min']>$count['max']) $count['max']=$count['min'];
            }
        }
        
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
    
    function vehicleFilter($ajax=true)
    {           
        if(!is_bool($ajax)) $ajax=true;
        
        $html=null;
        $response=array();
        
        $Date=new CHBSDate();
        $Validation=new CHBSValidation();
        
        $data=CHBSHelper::getPostOption();
        
        $serviceTypeId=$data['service_type_id'];
        
        $data['pickup_date_service_type_'.$serviceTypeId]=$Date->formatDateToStandard($data['pickup_date_service_type_'.$serviceTypeId]);
        $data['pickup_time_service_type_'.$serviceTypeId]=$Date->formatTimeToStandard($data['pickup_time_service_type_'.$serviceTypeId]);  
        
        if(!is_array($bookingForm=$this->checkBookingForm($data['booking_form_id'])))
        {
            if(!$ajax) return($html);
            
            $this->setErrorGlobal($response,__('There are no vehicles which match your filter criteria.','chauffeur-booking-system'));
            $this->createFormResponse($response);
        }
        
        $response['booking_summary_hide_fee']=$bookingForm['meta']['booking_summary_hide_fee'];
        
        if(!$Validation->isNumber($data['vehicle_standard'],1,4)) $data['vehicle_standard']=1;
        if(!$Validation->isNumber($data['vehicle_bag_count'],1,99)) $data['vehicle_bag_count']=1;
        if(!$Validation->isNumber($data['vehicle_passenger_count'],1,99)) $data['vehicle_passenger_count']=1;        
        
        $sum=CHBSBookingHelper::getPassenegerSum($bookingForm['meta'],$data);
        
        if($sum>0) 
        {
            if($data['vehicle_passenger_count']<$sum)
                $data['vehicle_passenger_count']=$sum;
        }
        
        $attribute=array();
        
        /***/
        
        $meta=$bookingForm['meta'];
        
        $vehicleCategory=$this->getBookingFormVehicleCategory($bookingForm['meta']);
        
        if($data['vehicle_category']!=0)
            $attribute=array('category_id'=>$data['vehicle_category']);
        
        if(isset($attribute['category_id']))
        {
            if(!array_key_exists($attribute['category_id'],$vehicleCategory))
                $attribute['category_id']=array_keys($vehicleCategory);
            
            $meta['vehicle_category_id']=(array)$attribute['category_id'];
        }
        else
        {
            if(!in_array(-1,$bookingForm['meta']['vehicle_category_id']))
            {
                $attribute['category_id']=array_keys($vehicleCategory);
                $meta['vehicle_category_id']=(array)$attribute['category_id'];
            }
        }
        
        /***/
        
        $dictionary=$this->getBookingFormVehicle($meta);
        
        $vehicleHtml=array();
        $vehiclePrice=array();
        
        foreach($dictionary as $index=>$value)
        {
            if(!(($value['meta']['passenger_count']>=$data['vehicle_passenger_count']) && ($value['meta']['bag_count']>=$data['vehicle_bag_count']) && ($value['meta']['standard']>=$data['vehicle_standard']))) continue;
            
            // *rule
            $argument=array
            (
                'booking_form_id'                                               =>  $bookingForm['post']->ID,
                'service_type_id'                                               =>  $data['service_type_id'],
                'transfer_type_id'                                              =>  $data['transfer_type_service_type_'.$data['service_type_id']],
                'pickup_location_coordinate'                                    =>  $data['pickup_location_coordinate_service_type_'.$data['service_type_id']],
                'dropoff_location_coordinate'                                   =>  $data['dropoff_location_coordinate_service_type_'.$data['service_type_id']],                
                'fixed_location_pickup'                                         =>  $data['fixed_location_pickup_service_type_'.$data['service_type_id']],
                'fixed_location_dropoff'                                        =>  $data['fixed_location_dropoff_service_type_'.$data['service_type_id']],                
                'transfer_type_id'                                              =>  $data['transfer_type_service_type_'.$data['service_type_id']],
                'route_id'                                                      =>  $data['route_service_type_3'],
                'vehicle'                                                       =>  $value,
                'vehicle_id'                                                    =>  $value['post']->ID,
                'vehicle_selected_id'                                           =>  $data['vehicle_id'],
                'pickup_date'                                                   =>  $data['pickup_date_service_type_'.$data['service_type_id']],
                'pickup_time'                                                   =>  $data['pickup_time_service_type_'.$data['service_type_id']],
                'passenger_adult'                                               =>  $data['passenger_adult_service_type_'.$data['service_type_id']],
                'passenger_children'                                            =>  $data['passenger_children_service_type_'.$data['service_type_id']],
                'base_location_distance'                                        =>  CHBSBookingHelper::getBaseLocationDistance($value['post']->ID),
                'base_location_return_distance'                                 =>  CHBSBookingHelper::getBaseLocationDistance($value['post']->ID,true),
                'distance'                                                      =>  $data['distance_map'],
                'distance_sum'                                                  =>  $data['distance_sum'],
                'duration'                                                      =>  in_array($data['service_type_id'],array(1,3)) ? 0 : $data['duration_service_type_2']*60,
                'duration_map'                                                  =>  $data['duration_map'],
                'duration_sum'                                                  =>  in_array($data['service_type_id'],array(1,3)) ? $data['duration_sum'] : $data['duration_service_type_2']*60,
                'booking_form'                                                  =>  $bookingForm
            );
            
            $price=0;
            
            $vehicleHtml[$index]=$this->createVehicle($argument,$price);
            $vehiclePrice[$index]=$price;
        }

        if(in_array((int)$bookingForm['meta']['vehicle_sorting_type'],array(1,2)))
        {
            asort($vehiclePrice);         
            if((int)$bookingForm['meta']['vehicle_sorting_type']===2)
                $vehiclePrice=array_reverse($vehiclePrice,true);
        }
        
        /***/
        
        $i=0;
        if($bookingForm['meta']['vehicle_limit']>0)
        {
            foreach($vehiclePrice as $index=>$value)
            {
                if((++$i)>$bookingForm['meta']['vehicle_limit'])
                    unset($vehicleHtml[$index],$vehiclePrice[$index]);
            }
        }
        
        /**/
        
        $i=0;
        $vehiclePerPage=(int)$bookingForm['meta']['vehicle_pagination_vehicle_per_page'];
        
        foreach($vehiclePrice as $index=>$value)
        {
            $class=array();
            
            if($vehiclePerPage>0)
            {
                if(($i++)>=$vehiclePerPage)
                    array_push($class,'chbs-hidden');
            }
            
            $html.='<li'.CHBSHelper::createCSSClassAttribute($class).'>'.$vehicleHtml[$index].'</li>';
        }
        
        $html='<ul class="chbs-list-reset">'.$html.'</ul>';
        
        $html.=$this->createPagination($dictionary,$bookingForm['meta']['vehicle_pagination_vehicle_per_page']);
        
        $response['html']=$html;
        
        if($Validation->isEmpty($html))
        {
            if($ajax)
            {
                $this->setErrorGlobal($response,__('There are no vehicles which match your filter criteria.','chauffeur-booking-system'));
                $this->createFormResponse($response);
            }
        }
        
        if(!$ajax) return($html);
        
        $this->createFormResponse($response);
    }
    
    /**************************************************************************/
    
    function createClientFormSignUp($bookingForm,$userData=array())
    {
        $User=new CHBSUser();
        $WooCommerce=new CHBSWooCommerce();
        $BookingFormElement=new CHBSBookingFormElement();
        
        /***/
        
        $data=CHBSHelper::getPostOption();
        if(count($userData)) $data=array_merge($data,$userData);

        /***/
        
        $html=null;
        $htmlElement=array(null,null,null,null,null,null);
        
        foreach($bookingForm['dictionary']['country'] as $index=>$value)
            $htmlElement[0].='<option value="'.esc_attr($index).'" '.($data['client_billing_detail_country_code']==$index ? 'selected' : null).'>'.esc_html($value[0]).'</option>';
        
        $htmlElement[1]=$BookingFormElement->createField(1,$data['service_type_id'],$bookingForm['meta']);
        
        $htmlElement[2]=$BookingFormElement->createField(2,$data['service_type_id'],$bookingForm['meta']);
        
        $panel=$BookingFormElement->getPanel($bookingForm['meta']);
        foreach($panel as $index=>$value)
        {
            if(in_array($value['id'],array(1,2))) continue;
            $htmlElement[3].=$BookingFormElement->createField($value['id'],$data['service_type_id'],$bookingForm['meta']);
        }
        
        if($WooCommerce->isEnable($bookingForm['meta']))
        {
            if(!$User->isSignIn())
            {
                if(in_array((int)$bookingForm['meta']['woocommerce_account_enable_type'],array(1,2)))
                {
                    $class=array(array('chbs-form-checkbox'),array());
                    
                    if(in_array((int)$bookingForm['meta']['woocommerce_account_enable_type'],array(2)))
                    {
                        
                    }
                    else
                    {
                        if((int)$data['client_sign_up_enable']===0)
                        {
                            array_push($class[1],'chbs-hidden');
                        }
                        else
                        {
                            array_push($class[0],'chbs-state-selected');
                        }
                    }
                    
                    $htmlElement[4].=
                    '
                        <div class="chbs-clear-fix">
                            <label class="chbs-form-label-group">
                    ';
                    
                    if(in_array((int)$bookingForm['meta']['woocommerce_account_enable_type'],array(2)))
                    {
                        $htmlElement[4].=esc_html__('New account','chauffeur-booking-system');
                    }
                    else
                    {
                        $htmlElement[4].=
                        '
                                <span'.CHBSHelper::createCSSClassAttribute($class[0]).'>
                                    <span class="chbs-meta-icon-tick"></span>
                                </span>
                                <input type="hidden" name="'.CHBSHelper::getFormName('client_sign_up_enable',false).'" value="'.esc_attr($data['client_sign_up_enable']).'"/> 
                                '.esc_html__('Create an account?','chauffeur-booking-system').'
                        ';                        
                    }
                    
                    $htmlElement[4].=
                    '                    
                            </label>                    
                        </div>

                        <div'.CHBSHelper::createCSSClassAttribute($class[1]).'>

                            <div class="chbs-clear-fix">
                                <div class="chbs-form-field chbs-form-field-width-33">
                                    <label>'.esc_html__('Login *','chauffeur-booking-system').'</label>
                                    <input type="text" name="'.CHBSHelper::getFormName('client_sign_up_login',false).'"/>
                                </div>
                                <div class="chbs-form-field chbs-form-field-width-33">
                                    <label>
                                        '.esc_html__('Password *','chauffeur-booking-system').'
                                        &nbsp;
                                        <a href="#" class="chbs-sign-up-password-generate">'.esc_html__('Generate','chauffeur-booking-system').'</a>
                                        <a href="#" class="chbs-sign-up-password-show">'.esc_html__('Show','chauffeur-booking-system').'</a>
                                    </label>
                                    <input type="password" name="'.CHBSHelper::getFormName('client_sign_up_password',false).'"/>
                                </div>
                                <div class="chbs-form-field chbs-form-field-width-33">
                                    <label>'.esc_html__('Re-type password *','chauffeur-booking-system').'</label>
                                    <input type="password" name="'.CHBSHelper::getFormName('client_sign_up_password_retype',false).'"/>
                                </div>
                            </div>

                        </div>
                    ';
                }
            }
        }
        
        /***/
        
        $class=array('chbs-client-form-sign-up','chbs-hidden');
        
        if($WooCommerce->isEnable($bookingForm['meta']))
        {
            if(($User->isSignIn()) || ((int)$data['client_account']===1) || ((int)$bookingForm['meta']['woocommerce_account_enable_type']===0)) unset($class[1]);
        }  
        else unset($class[1]);
 
        $html=
        '
            <div'.CHBSHelper::createCSSClassAttribute($class).'>

                <div class="chbs-box-shadow">
                
                    <div class="chbs-clear-fix">
                        <label class="chbs-form-label-group">'.esc_html__('Contact details','chauffeur-booking-system').'</label>
                        <div class="chbs-form-field chbs-form-field-width-50">
                            <label>'.esc_html__('First name *','chauffeur-booking-system').'</label>
                            <input type="text" name="'.CHBSHelper::getFormName('client_contact_detail_first_name',false).'" value="'.esc_attr($data['client_contact_detail_first_name']).'"/>
                        </div>
                        <div class="chbs-form-field chbs-form-field-width-50">
                            <label>'.esc_html__('Last name *','chauffeur-booking-system').'</label>
                            <input type="text" name="'.CHBSHelper::getFormName('client_contact_detail_last_name',false).'" value="'.esc_attr($data['client_contact_detail_last_name']).'"/>
                        </div>
                    </div>

                    <div class="chbs-clear-fix">
                        <div class="chbs-form-field chbs-form-field-width-50">
                            <label>'.esc_html__('E-mail address *','chauffeur-booking-system').'</label>
                            <input type="text" name="'.CHBSHelper::getFormName('client_contact_detail_email_address',false).'"  value="'.esc_attr($data['client_contact_detail_email_address']).'"/>
                        </div>
                        <div class="chbs-form-field chbs-form-field-width-50">
                            <label>'.esc_html__('Phone number','chauffeur-booking-system').'</label>
                            <input type="text" name="'.CHBSHelper::getFormName('client_contact_detail_phone_number',false).'"  value="'.esc_attr($data['client_contact_detail_phone_number']).'"/>
                        </div>
                    </div>

                    <div class="chbs-clear-fix">
                        <div class="chbs-form-field">
                            <label>'.esc_html__('Comments','chauffeur-booking-system').'</label>
                            <textarea name="'.CHBSHelper::getFormName('comment',false).'">'.esc_html(array_key_exists('comment',$data) ? $data['comment'] : CHBSRequestData::post('chbs_comment_hidden')).'</textarea>
                        </div>
                    </div>
                    
                    '.$htmlElement[1].'
                        
                    '.$htmlElement[4].'
        ';
        
        $class=array(array('chbs-form-checkbox'),array());

        /***/
        
        $checkboxHtml=null;
        if((int)$bookingForm['meta']['billing_detail_state']===3)
        {
            $class[0]=$class[1]=array();
            $checkboxHtml='<input type="hidden" name="'.CHBSHelper::getFormName('client_billing_detail_enable',false).'" value="1"/> ';
        }
        else
        {
            if(!array_key_exists('client_billing_detail_enable',$data))
                $data['client_billing_detail_enable']=(int)$bookingForm['meta']['billing_detail_state']-1;;
            
            if((int)$data['client_billing_detail_enable']===0)
            {
                array_push($class[1],'chbs-hidden');
            }
            else
            {
                array_push($class[0],'chbs-state-selected');
            }
            
            $checkboxHtml=
            '
                <span'.CHBSHelper::createCSSClassAttribute($class[0]).'>
                    <span class="chbs-meta-icon-tick"></span>
                </span>
                <input type="hidden" name="'.CHBSHelper::getFormName('client_billing_detail_enable',false).'" value="'.esc_attr($data['client_billing_detail_enable']).'"/> 
            ';
        }
        
        /***/
        
        $state=CHBSHelper::splitBy($bookingForm['meta']['billing_detail_list_state']);
        if(count($state))
        {
            foreach($state as $value)
                $htmlElement[5].='<option value="'.esc_attr($value).'" '.($data['client_billing_detail_state']==$value ? 'selected' : null).'>'.esc_html($value).'</option>';
            
            $htmlElement[5]=
            '
                <select name="'.CHBSHelper::getFormName('client_billing_detail_state',false).'">
                    '.$htmlElement[5].'
                </select>  
            ';
        }
        else
        {
            $htmlElement[5]=
            '
                <input type="text" name="'.CHBSHelper::getFormName('client_billing_detail_state',false).'" value="'.esc_attr($data['client_billing_detail_state']).'"/>
            ';
        }
        
        /***/
        
        $html.=
        '
                    <div class="chbs-clear-fix">
                        <label class="chbs-form-label-group">
                            '.$checkboxHtml.'
                            '.esc_html__('Billing address','chauffeur-booking-system').'
                        </label>                    
                    </div>

                    <div'.CHBSHelper::createCSSClassAttribute($class[1]).'>

                        <div class="chbs-clear-fix">
                            <div class="chbs-form-field chbs-form-field-width-50">
                                <label>'.esc_html__('Company registered name','chauffeur-booking-system').'</label>
                                <input type="text" name="'.CHBSHelper::getFormName('client_billing_detail_company_name',false).'" value="'.esc_attr($data['client_billing_detail_company_name']).'"/>
                            </div>
                            <div class="chbs-form-field chbs-form-field-width-50">
                                <label>'.esc_html__('Tax number','chauffeur-booking-system').'</label>
                                <input type="text" name="'.CHBSHelper::getFormName('client_billing_detail_tax_number',false).'" value="'.esc_attr($data['client_billing_detail_tax_number']).'"/>
                            </div>
                        </div>

                        <div class="chbs-clear-fix">
                            <div class="chbs-form-field chbs-form-field-width-33">
                                <label>'.esc_html__('Street *','chauffeur-booking-system').'</label>
                                <input type="text" name="'.CHBSHelper::getFormName('client_billing_detail_street_name',false).'" value="'.esc_attr($data['client_billing_detail_street_name']).'"/>
                            </div>
                            <div class="chbs-form-field chbs-form-field-width-33">
                                <label>'.esc_html__('Street number','chauffeur-booking-system').'</label>
                                <input type="text" name="'.CHBSHelper::getFormName('client_billing_detail_street_number',false).'" value="'.esc_attr($data['client_billing_detail_street_number']).'"/>
                            </div>
                            <div class="chbs-form-field chbs-form-field-width-33">
                                <label>'.esc_html__('City *','chauffeur-booking-system').'</label>
                                <input type="text" name="'.CHBSHelper::getFormName('client_billing_detail_city',false).'" value="'.esc_attr($data['client_billing_detail_city']).'"/>
                            </div>                    
                        </div>
                        <div class="chbs-clear-fix">
                            <div class="chbs-form-field chbs-form-field-width-33">
                                <label>'.esc_html__('State','chauffeur-booking-system').'</label>
                                '.$htmlElement[5].'
                            </div>
                            <div class="chbs-form-field chbs-form-field-width-33">
                                <label>'.esc_html__('Postal code *','chauffeur-booking-system').'</label>
                                <input type="text" name="'.CHBSHelper::getFormName('client_billing_detail_postal_code',false).'" value="'.esc_attr($data['client_billing_detail_postal_code']).'"/>
                            </div>
                            <div class="chbs-form-field chbs-form-field-width-33">
                                <label>'.esc_html__('Country *','chauffeur-booking-system').'</label>
                                <select name="'.CHBSHelper::getFormName('client_billing_detail_country_code',false).'">
                                    '.$htmlElement[0].'
                                </select>
                            </div>                    
                        </div> 
                        
                        '.$htmlElement[2].'
                            
                    </div>
                    
                    '.$htmlElement[3].'
                        
                </div>
                
            </div>
        ';
        
        return($html);
    }
    
    /**************************************************************************/
   
    function createClientFormSignIn($bookingForm)
    {
        $User=new CHBSUser();
        $WooCommerce=new CHBSWooCommerce();
        
        if(!$WooCommerce->isEnable($bookingForm['meta'])) return;
        if($User->isSignIn()) return;
        
        if((int)$bookingForm['meta']['woocommerce_account_enable_type']===0) return;
        
        $data=CHBSHelper::getPostOption();
        
        $html=
        '
            <div class="chbs-client-form-sign-in">

                <div class="chbs-box-shadow">
                
                    <div class="chbs-clear-fix">
                        <label class="chbs-form-label-group">'.esc_html__('Sign In','chauffeur-booking-system').'</label>
                        <div class="chbs-form-field chbs-form-field-width-50">
                            <label>'.esc_html__('Login *','chauffeur-booking-system').'</label>
                            <input type="text" name="'.CHBSHelper::getFormName('client_sign_in_login',false).'" value=""/>
                        </div>
                        <div class="chbs-form-field chbs-form-field-width-50">
                            <label>'.esc_html__('Password *','chauffeur-booking-system').'</label>
                            <input type="password" name="'.CHBSHelper::getFormName('client_sign_in_password',false).'" value=""/>
                        </div>
                    </div>
                 
                </div>
                
                <div class="chbs-clear-fix">
                
                   <a href="#" class="chbs-button chbs-button-style-2 chbs-button-sign-up">
                        '.esc_html__('Don\'t Have an Account?','chauffeur-booking-system').'
                   </a> 
                   
                   <a href="#" class="chbs-button chbs-button-style-1 chbs-button-sign-in">
                       '.esc_html__('Sign In','chauffeur-booking-system').'
                   </a> 
                   
                   <input type="hidden" name="'.CHBSHelper::getFormName('client_account',false).'" value="'.(int)$data['client_account'].'"/> 
                    
                </div>

            </div>
        ';
        
        return($html);
    }
    
    /**************************************************************************/
    
    function manageEditColumns($column)
    {
        $column=array
        (
            'cb'                                                                =>  $column['cb'],
            'title'                                                             =>  __('Title','chauffeur-booking-system'),
            'service_type'                                                      =>  __('Service type','chauffeur-booking-system'),
            'date'                                                              =>  $column['date']
        );
   
		return($column);          
    }
    
    /**************************************************************************/
    
    function managePostsCustomColumn($column)
    {
		global $post;
		
		$meta=CHBSPostMeta::getPostMeta($post);
        
        $Validation=new CHBSValidation();
        $ServiceType=new CHBSServiceType();
        
		switch($column) 
		{
			case 'service_type':
                
                $html=null;
                
                foreach($meta['service_type_id'] as $value)
                {
                    $serviceType=$ServiceType->getServiceType($value);
                    
                    if($Validation->isNotEmpty($html)) $html.=', ';
                    $html.=$serviceType[0];
                }
                
                echo esc_html($html);
                
			break;
        }
    }
    
    /**************************************************************************/
    
    function manageEditSortableColumns($column)
    {
		return($column);       
    }
    
    /**************************************************************************/   
    
    function userSignIn()
    {
        $data=CHBSHelper::getPostOption();
        
        $response=array('user_sign_in'=>0);
        
        if(!is_array($bookingForm=$this->checkBookingForm($data['booking_form_id'])))
        {
            $this->setErrorGlobal($response,__('Login error.','chauffeur-booking-system'));
            $this->createFormResponse($response);
        }
        
        $User=new CHBSUser();
        $WooCommerce=new CHBSWooCommerce();
        
        if(!$User->signIn($data['client_sign_in_login'],$data['client_sign_in_password']))
            $this->setErrorGlobal($response,__('Login error.','chauffeur-booking-system'));
        else 
        {
            $userData=$WooCommerce->getUserData();
            
            $response['user_sign_in']=1;  
            
            $response['summary']=$this->createSummary($data,$bookingForm);
            $response['client_form_sign_up']=$this->createClientFormSignUp($bookingForm,$userData);
        }
        
        $this->createFormResponse($response);
    }
    
    /**************************************************************************/
    
    function createVehiclePassengerFilterField($min,$max)
    {
        $html=null;
        
        for($i=$min;$i<=$max;$i++)
            $html.='<option value="'.esc_attr($i).'"'.($i==1 ? ' selected="selected"' : '').'>'.esc_html($i).'</option>';
            
        $html='<select name="'.CHBSHelper::getFormName('vehicle_passenger_count',false).'">'.$html.'</select>';

        return($html);
    }
    
    /**************************************************************************/
    
    function checkCouponCode()
    {        
        $response=array();
        
        $data=CHBSHelper::getPostOption();
        
        if(!is_array($bookingForm=$this->checkBookingForm($data['booking_form_id'])))
        {
            $response['html']=null;
            CHBSHelper::createJSONResponse($response);
        }
        
        $price=array();
        $response['html']=$this->createSummaryPriceElement($data,$bookingForm,$price);
        
        $Coupon=new CHBSCoupon();
        $coupon=$Coupon->checkCode();
        
        $response['error']=$coupon===false ? 1 : 0;
        
        if($response['error']===1)
           $response['message']=__('Provided coupon is invalid.','chauffeur-booking-system'); 
        else 
            $response['message']=__('Provided coupon is valid. Discount has been granted.','chauffeur-booking-system');
        
        CHBSHelper::createJSONResponse($response);
    }
    
    /**************************************************************************/
    
    function createBookingFormExtra($bookingForm,$data)
    {
        $html=null;
                   
        if(count($bookingForm['dictionary']['booking_extra']))
        {
            $BookingExtra=new CHBSBookingExtra();

            $html.=
            '
                <h4 class="chbs-booking-extra-header">
                    <span class="chbs-circle chbs-meta-icon-cart"></span>
                    <span>'.esc_html__('Extra options','chauffeur-booking-system').'</span>
                </h4> 
            ';
            
            if((int)$bookingForm['meta']['booking_extra_category_display_enable']===1)
            {
                if((is_array($bookingForm['dictionary']['booking_extra_category'])) && (count($bookingForm['dictionary']['booking_extra_category'])))
                {
                    $htmlCategory=null;
                    
                    foreach($bookingForm['dictionary']['booking_extra_category'] as $index=>$value)
                        $htmlCategory.='<div><a href="#" data-category_id="'.(int)$value['term_id'].'">'.esc_html($value['name']).'</a></div>';
                    
                    $html.=
                    '
                        <div class="chbs-booking-extra-category-list">
                            '.$htmlCategory.'
                        </div>
                    ';
                }
            }
            
            $html.=
            '
                <div class="chbs-booking-extra-list">
                    <ul class="chbs-list-reset">
            ';
            
            foreach($bookingForm['dictionary']['booking_extra'] as $index=>$value)
            {
                $price=$BookingExtra->calculatePrice($value,$bookingForm['dictionary']['tax_rate']);
                
                $class=array();
                if($value['meta']['quantity_enable']==1)
                    array_push($class,'chbs-booking-extra-list-item-quantity-enable');

                $category=null;
                if(count($value['category'][0]))
                    $category=join(',',array_keys($value['category'][0]));

                $html.=
                '
                        <li'.CHBSHelper::createCSSClassAttribute($class).' data-category_id="'.esc_attr($category).'" data-vehicle_id="'.esc_attr(join(',',$value['meta']['vehicle_id'])).'">
                            <div class="chbs-column-1">
                                <span class="booking-form-extra-name">
                                    '.$value['post']->post_title.'
                                </span>
                ';
                
                if((int)$bookingForm['meta']['price_hide']===0)
                {
                    CHBSBookingHelper::getPriceType($bookingForm,$priceType,$sumType,$taxShow,$data['step']);
                    
                    $html.=
                    '
                                <span class="booking-form-extra-price">
                                    '.$price['price'][$priceType]['format'].'
                                </span>
                    ';
                }
                
                $html.=
                '
                                <span class="booking-form-extra-description">
                                    '.esc_html($value['meta']['description']).'
                                </span>
                            </div>
                ';
                
                if((int)$value['meta']['quantity_enable']===1)
                {
                    $fieldName='booking_extra_'.$index.'_quantity';
                    
                    $html.=
                    '
                            <div class="chbs-column-2">
                                <div class="chbs-form-field">
                                    <label>'.esc_html__('Number','chauffeur-booking-system').'</label>
                                    <div class="chbs-quantity-section">
                                        <span class="chbs-quantity-section-button chbs-meta-icon-minus" data-step="-1"></span>
                                        <input type="text" name="'.CHBSHelper::getFormName($fieldName,false).'" value="'.(array_key_exists($fieldName,$data) ? $data[$fieldName] : 1).'" data-quantity-max="'.(int)$value['meta']['quantity_max'].'"/>
                                        <span class="chbs-quantity-section-button chbs-meta-icon-plus" data-step="1"></span>
                                    </div>
                                </div>  
                            </div>
                    ';
                }
                else
                {
                    $html.=
                    '
                            <div class="chbs-column-2"></div>
                    ';
                }
                
                $bookingExtraIdSelected=preg_split('/,/',$data['booking_extra_id']);

                if((int)$value['meta']['mandatory']===1)
                {
                    $fieldName='booking_extra_value';
                    $fieldValue=-1;
                    
                    if((array_key_exists($fieldName,$data)) && (array_key_exists($index,$data[$fieldName])))
                        $fieldValue=$data[$fieldName][$index];
                    
                    $class=array(array('chbs-button','chbs-button-style-2'),array('chbs-button','chbs-button-style-2'));
                    
                    if($fieldValue==$index) array_push($class[1],'chbs-state-selected');
                    else if($fieldValue==0) array_push($class[0],'chbs-state-selected');
                    
                    $html.=
                    '
                                <div class="chbs-column-3">
                                    <div class="chbs-button-radio">
                                        <a href="#"'.CHBSHelper::createCSSClassAttribute($class[1]).' data-value="'.esc_attr($index).'">
                                            '.esc_html__('Yes','chauffeur-booking-system').'
                                        </a>
                                        <a href="#"'.CHBSHelper::createCSSClassAttribute($class[0]).' data-value="0">
                                            '.esc_html__('No','chauffeur-booking-system').'
                                        </a>
                                        <input type="hidden" name="'.CHBSHelper::getFormName($fieldName.'['.$index.']',false).'"  value="'.$fieldValue.'"/>
                                        <span>*</span>
                                    </div>
                                </div>
                            </li>
                    ';                    
                }
                else
                {
                    $class=array('chbs-button','chbs-button-style-2');
                    if(in_array($index,$bookingExtraIdSelected))
                            array_push($class,'chbs-state-selected');
                    
                    $html.=
                    '
                                <div class="chbs-column-3">
                                    <a href="#"'.CHBSHelper::createCSSClassAttribute($class).' data-value="'.esc_attr($index).'">
                                        '.esc_html__('Select','chauffeur-booking-system').'
                                        <span class="chbs-meta-icon-tick"></span>
                                    </a>
                                </div>
                            </li>
                    ';
                }
            }
            
            $html.=
            '
                    </ul>
                </div>
            ';
        }

        return($html);
    }
    
    /**************************************************************************/
    
    function getBookingFormDateAvailable($meta)
    {
        $date=array();
        
        $Date=new CHBSDate();
        $Validation=new CHBSValidation();
        
        $type=array(1=>'days',2=>'hours',3=>'minutes');
        
        /***/
              
        $dateStart=strtotime('+ '.(int)$meta['booking_period_from'].' '.$type[(int)$meta['booking_period_type']],strtotime(date_i18n('d-m-Y G:i')));
        
        $offset=(int)$meta['booking_period_from'];
        
        if((int)$meta['booking_period_type']===1)
           $offset*=24;
        if((int)$meta['booking_period_type']===3)
            $offset*=3600;       
        
        /***/
        
        if($Validation->isEmpty($meta['booking_period_to'])) $dateStop=-1;
        else $dateStop=strtotime('+ '.(int)$meta['booking_period_to'].' '.$type[(int)$meta['booking_period_type']],$dateStart);
     
        /***/
        
        array_push($date,$dateStart);
        
        $dateCurrent=$dateStart;
        
        if($dateStop!=-1)
        {
            while(1)
            {
                $dateCurrent=strtotime('+ 1 day',$dateCurrent);
                   
                if($dateCurrent>$dateStop)
                {
                    array_push($date,$dateStop);
                    break;
                }
                
                array_push($date,$dateCurrent);
            }
        }
        else
        {
            for($i=1;$i<7;$i++)
            {
                $dateCurrent=strtotime('+ '.$i.' day',$dateCurrent);
                array_push($date,$dateCurrent);                
            }
        }
        
        /***/
        
        $length=count($date);
        
        foreach($date as $index=>$value)
        {
            $dayOfWeek=date('N',$value);
            
            $businessHour=$meta['business_hour'][$dayOfWeek];
            
            $index2=((int)($offset/24))+$index;
            
            if(($Validation->isEmpty($businessHour['start'])) || ($Validation->isEmpty($businessHour['stop'])))
            {
                unset($date[$index]);
                continue;
            }
            
            $timeCurrent=date('G:i',$value);
            
            if($index===0)
            {
                if($Date->timeInRange($timeCurrent,$businessHour['start'],$businessHour['stop']))
                {
                    $date[$index]=array('index'=>$index2,'date'=>date('d-m-Y',$value),'time'=>array('start'=>$timeCurrent,'stop'=>$businessHour['stop']));
                }
                else
                {
                    if(in_array($Date->compareTime($timeCurrent,$businessHour['start']),array(0,2)))
                    {
                        $date[$index]=array('index'=>$index2,'date'=>date('d-m-Y',$value),'time'=>array('start'=>$businessHour['start'],'stop'=>$businessHour['stop']));
                    }
                    else if(in_array($Date->compareTime($timeCurrent,$businessHour['stop']),array(1)))
                    {
                        unset($date[$index]);
                        continue;
                    }
                }
            }
            else if($index===$length-1)
            {
                if($Date->timeInRange($timeCurrent,$businessHour['start'],$businessHour['stop']))
                {
                    $date[$index]=array('index'=>$index2,'date'=>date('d-m-Y',$value),'time'=>array('start'=>$businessHour['start'],'stop'=>$timeCurrent));
                }
                else
                {
                    if(in_array($Date->compareTime($timeCurrent,$businessHour['start']),array(0,2)))
                    {
                        unset($date[$index]);
                        continue;                       
                    }
                    else if(in_array($Date->compareTime($timeCurrent,$businessHour['stop']),array(2)))
                    {
                        $date[$index]=array('index'=>$index2,'date'=>date('d-m-Y',$value),'time'=>array('start'=>$businessHour['start'],'stop'=>$timeCurrent));
                    }
                }             
            }
            else
            {
                $date[$index]=array('index'=>$index2,'date'=>date('d-m-Y',$value),'time'=>array('start'=>$businessHour['start'],'stop'=>$businessHour['stop']));
            }
        }

        if($dateStop===-1)
            array_push($date,array('index'=>-1));
 
        return($date);
    }
    
    /**************************************************************************/
    
	function createPagination($dictionary,$vehiclePerPage)
	{
        $vehicleTotal=count($dictionary);
        
        if($vehiclePerPage<=0) return(null);
        if($vehiclePerPage>=$vehicleTotal) return(null);
        
        $html=
		'
            <div class="chbs-pagination" data-page_current="1" data-vehicle_per_page="'.(int)$vehiclePerPage.'">
                <a href="#-1" class="chbs-pagination-prev"></a>
                <a href="#1" class="chbs-pagination-next"></a>
			</div>
		';
		
		return($html);
	}

    /**************************************************************************/
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/