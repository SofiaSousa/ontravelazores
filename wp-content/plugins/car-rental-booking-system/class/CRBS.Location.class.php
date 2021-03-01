<?php

/******************************************************************************/
/******************************************************************************/

class CRBSLocation
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
        return(PLUGIN_CRBS_CONTEXT.'_location');
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
					'name'														=>	__('Locations','car-rental-booking-system'),
					'singular_name'												=>	__('Locations','car-rental-booking-system'),
					'add_new'													=>	__('Add New','car-rental-booking-system'),
					'add_new_item'												=>	__('Add New Location','car-rental-booking-system'),
					'edit_item'													=>	__('Edit Location','car-rental-booking-system'),
					'new_item'													=>	__('New Location','car-rental-booking-system'),
					'all_items'													=>	__('Locations','car-rental-booking-system'),
					'view_item'													=>	__('View Location','car-rental-booking-system'),
					'search_items'												=>	__('Search Locations','car-rental-booking-system'),
					'not_found'													=>	__('No Locations Found','car-rental-booking-system'),
					'not_found_in_trash'										=>	__('No Locations in Trash','car-rental-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('Locations','car-rental-booking-system')
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
        add_filter('postbox_classes_'.self::getCPTName().'_crbs_meta_box_location',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }

    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CRBS_CONTEXT.'_meta_box_location',__('Main','car-rental-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
		wp_enqueue_media();
		
        global $post;
        
        $Country=new CRBSCountry();
        $Payment=new CRBSPayment();
        $Vehicle=new CRBSVehicle();
        $EmailAccount=new CRBSEmailAccount();
        $PaymentStripe=new CRBSPaymentStripe();
		
		$data=array();
        
        $data['meta']=CRBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CRBSHelper::createNonceField(PLUGIN_CRBS_CONTEXT.'_meta_box_location');
        
        $data['dictionary']['country']=$Country->getCountry();
        $data['dictionary']['payment']=$Payment->getPayment();
        $data['dictionary']['vehicle']=$Vehicle->getDictionary();
        
        $data['dictionary']['email_account']=$EmailAccount->getDictionary();
        
		$data['dictionary']['payment_stripe_method']=$PaymentStripe->getPaymentMethod();
		
		$Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'admin/meta_box_location.php');
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
        $GeoLocation=new CRBSGeoLocation();         
        
        CRBSHelper::setDefault($meta,'pickup_period_from','0');
        CRBSHelper::setDefault($meta,'pickup_period_to','');
		CRBSHelper::setDefault($meta,'pickup_period_type',1);
        
		CRBSHelper::setDefault($meta,'booking_interval',0);
		
        CRBSHelper::setDefault($meta,'vehicle_rent_day_count_min','');
        CRBSHelper::setDefault($meta,'vehicle_rent_day_count_max','');
        
		if(!array_key_exists('vehicle_rent_date',$meta))
			$meta['vehicle_rent_date']=array();
		
		CRBSHelper::setDefault($meta,'country_default',-1);
		CRBSHelper::setDefault($meta,'country_available',array(-1));
        CRBSHelper::setDefault($meta,'vehicle_id_default',-1);
        
        CRBSHelper::setDefault($meta,'vehicle_availability_check_type',array(2,3));
        
		CRBSHelper::setDefault($meta,'after_business_hour_pickup_enable',0);
        CRBSHelper::setDefault($meta,'after_business_hour_return_enable',0);
        
        CRBSHelper::setDefault($meta,'driver_license_attach_enable',0);
        CRBSHelper::setDefault($meta,'driver_license_file_name_random_enable',0);     
		
        CRBSHelper::setDefault($meta,'address_street','');
        CRBSHelper::setDefault($meta,'address_street_number','');
        CRBSHelper::setDefault($meta,'address_postcode','');
        CRBSHelper::setDefault($meta,'address_city','');
        CRBSHelper::setDefault($meta,'address_state','');
        CRBSHelper::setDefault($meta,'address_country',$GeoLocation->getCountryCode());
      
        CRBSHelper::setDefault($meta,'contact_detail_phone_number','');
        CRBSHelper::setDefault($meta,'contact_detail_fax_number','');
        CRBSHelper::setDefault($meta,'contact_detail_email_address','');
        
        CRBSHelper::setDefault($meta,'coordinate_latitude','');
        CRBSHelper::setDefault($meta,'coordinate_longitude','');
                
		for($i=1;$i<8;$i++)
		{
			if(!isset($meta['business_hour'][$i]))
                $meta['business_hour'][$i]=array('start'=>null,'stop'=>null,'default'=>null,'break'=>null);
			
			if(!isset($meta['business_hour'][$i]['start']))
				$meta['business_hour'][$i]['start']=null;
			if(!isset($meta['business_hour'][$i]['stop']))
				$meta['business_hour'][$i]['stop']=null;
			if(!isset($meta['business_hour'][$i]['default']))
				$meta['business_hour'][$i]['default']=null;		
			if(!isset($meta['business_hour'][$i]['break']))
				$meta['business_hour'][$i]['break']=null;
		}	
        
		if(!array_key_exists('date_exclude',$meta))
			$meta['date_exclude']=array();
		
		CRBSHelper::setDefault($meta,'payment_deposit_type',0);
		CRBSHelper::setDefault($meta,'payment_deposit_type_efixed_value',0.00);	
		CRBSHelper::setDefault($meta,'payment_deposit_type_percentage_value',0.00);			
		
        CRBSHelper::setDefault($meta,'payment_mandatory_enable',0);
        
        CRBSHelper::setDefault($meta,'payment_id',array(1));
		CRBSHelper::setDefault($meta,'payment_default_id',-1);
        CRBSHelper::setDefault($meta,'payment_processing_enable',1);
		CRBSHelper::setDefault($meta,'payment_woocommerce_step_3_enable',1);
		
		CRBSHelper::setDefault($meta,'payment_cash_logo_src','');
		CRBSHelper::setDefault($meta,'payment_cash_info','');

        CRBSHelper::setDefault($meta,'payment_stripe_api_key_secret','');
        CRBSHelper::setDefault($meta,'payment_stripe_api_key_publishable','');
		CRBSHelper::setDefault($meta,'payment_stripe_method',array('card'));
		
		CRBSHelper::setDefault($meta,'payment_stripe_product_id','');
		CRBSHelper::setDefault($meta,'payment_stripe_redirect_duration','5');
		CRBSHelper::setDefault($meta,'payment_stripe_success_url_address','');
		CRBSHelper::setDefault($meta,'payment_stripe_cancel_url_address','');
		CRBSHelper::setDefault($meta,'payment_stripe_logo_src','');
		CRBSHelper::setDefault($meta,'payment_stripe_info','');
		
        CRBSHelper::setDefault($meta,'payment_paypal_email_address','');
		CRBSHelper::setDefault($meta,'payment_paypal_redirect_duration','5');
		CRBSHelper::setDefault($meta,'payment_paypal_success_url_address','');
		CRBSHelper::setDefault($meta,'payment_paypal_cancel_url_address','');
        CRBSHelper::setDefault($meta,'payment_paypal_sandbox_mode_enable',0);
		CRBSHelper::setDefault($meta,'payment_paypal_logo_src','');        
		CRBSHelper::setDefault($meta,'payment_paypal_info','');

		CRBSHelper::setDefault($meta,'payment_wire_transfer_logo_src','');
		CRBSHelper::setDefault($meta,'payment_wire_transfer_info','');
        
		CRBSHelper::setDefault($meta,'payment_credit_card_pickup_logo_src','');
		CRBSHelper::setDefault($meta,'payment_credit_card_pickup_info','');
		
        CRBSHelper::setDefault($meta,'booking_new_sender_email_account_id',-1);
        CRBSHelper::setDefault($meta,'booking_new_recipient_email_address','');
        
		CRBSHelper::setDefault($meta,'booking_new_admin_email_notification',1);
		CRBSHelper::setDefault($meta,'booking_new_customer_email_notification',1);
        CRBSHelper::setDefault($meta,'booking_new_woocommerce_email_notification',0);
        
        CRBSHelper::setDefault($meta,'nexmo_sms_enable',0);
        CRBSHelper::setDefault($meta,'nexmo_sms_api_key','');
        CRBSHelper::setDefault($meta,'nexmo_sms_api_key_secret','');
        CRBSHelper::setDefault($meta,'nexmo_sms_sender_name','');
        CRBSHelper::setDefault($meta,'nexmo_sms_recipient_phone_number','');
        CRBSHelper::setDefault($meta,'nexmo_sms_message',__('New booking is received.','car-rental-booking-system'));
        
        CRBSHelper::setDefault($meta,'twilio_sms_enable',0);
        CRBSHelper::setDefault($meta,'twilio_sms_api_sid','');
        CRBSHelper::setDefault($meta,'twilio_sms_api_token','');
        CRBSHelper::setDefault($meta,'twilio_sms_sender_phone_number','');
        CRBSHelper::setDefault($meta,'twilio_sms_recipient_phone_number','');
        CRBSHelper::setDefault($meta,'twilio_sms_message',__('New booking is received.','car-rental-booking-system'));
        
        CRBSHelper::setDefault($meta,'telegram_enable',0);
        CRBSHelper::setDefault($meta,'telegram_token','');
        CRBSHelper::setDefault($meta,'telegram_group_id','');
        CRBSHelper::setDefault($meta,'telegram_message','');
        
        CRBSHelper::setDefault($meta,'google_calendar_enable',0);
        CRBSHelper::setDefault($meta,'google_calendar_id','');
        CRBSHelper::setDefault($meta,'google_calendar_settings','');
        CRBSHelper::setDefault($meta,'google_calendar_server_reply_1','');
	}
    
    /**************************************************************************/
    
    function savePost($postId)
    {      
        if(!$_POST) return(false);
        
        if(CRBSHelper::checkSavePost($postId,PLUGIN_CRBS_CONTEXT.'_meta_box_location_noncename','savePost')===false) return(false);
                
        $Date=new CRBSDate();
        $Payment=new CRBSPayment();
        $Country=new CRBSCountry();
        $Vehicle=new CRBSVehicle();
        $Validation=new CRBSValidation();
        $EmailAccount=new CRBSEmailAccount();
        $PaymentStripe=new CRBSPaymentStripe();
		
        $data=CRBSHelper::getPostOption();
        
        $dataIndex=array
        (
			'booking_interval',
            'pickup_period_from',
			'pickup_period_type',
            'pickup_period_to',
            'vehicle_rent_day_count_min',
            'vehicle_rent_day_count_max',
			'vehicle_rent_date',
			'country_default',
			'country_available',
            'vehicle_id_default',
            'vehicle_availability_check_type',
			'after_business_hour_pickup_enable',
            'after_business_hour_return_enable',
            'driver_license_attach_enable',
			'driver_license_file_name_random_enable',
            'address_street',
            'address_street_number',
            'address_postcode',
            'address_city',
            'address_state',
            'address_country',
            'contact_detail_phone_number',
            'contact_detail_fax_number',
            'contact_detail_email_address',
            'coordinate_latitude',
            'coordinate_longitude',
            'business_hour',
            'date_exclude',
			'payment_deposit_type',
			'payment_deposit_type_fixed_value',
			'payment_deposit_type_percentage_value',
            'payment_mandatory_enable',
			'payment_processing_enable',
			'payment_woocommerce_step_3_enable',
            'payment_default_id',
			'payment_id',
			'payment_cash_logo_src',
			'payment_cash_info',
            'payment_stripe_api_key_secret',
            'payment_stripe_api_key_publishable',
			'payment_stripe_method',
			'payment_stripe_product_id',
			'payment_stripe_redirect_duration',
            'payment_stripe_success_url_address',
			'payment_stripe_cancel_url_address',
			'payment_stripe_logo_src',
			'payment_stripe_info',
            'payment_paypal_email_address',
			'payment_paypal_redirect_duration',
            'payment_paypal_success_url_address',
			'payment_paypal_cancel_url_address',
            'payment_paypal_sandbox_mode_enable',
			'payment_paypal_logo_src',
			'payment_paypal_info',	
			'payment_wire_transfer_logo_src',
			'payment_wire_transfer_info',
			'payment_credit_card_pickup_logo_src',
			'payment_credit_card_pickup_info',			
            'nexmo_sms_enable',
            'nexmo_sms_api_key',
            'nexmo_sms_api_key_secret',
            'nexmo_sms_sender_name',
            'nexmo_sms_recipient_phone_number',
            'nexmo_sms_message',
            'twilio_sms_enable',
            'twilio_sms_api_sid',
            'twilio_sms_api_token',
            'twilio_sms_sender_phone_number',
            'twilio_sms_recipient_phone_number',
            'twilio_sms_message',    
            'telegram_enable',
            'telegram_token',
            'telegram_group_id',
            'telegram_message',
            'booking_new_sender_email_account_id',
            'booking_new_recipient_email_address',
            'booking_new_woocommerce_email_notification',
			'booking_new_customer_email_notification',
			'booking_new_admin_email_notification',
            'google_calendar_enable',
            'google_calendar_id',
            'google_calendar_settings'
        );
		               
        if(!$Validation->isNumber($data['pickup_period_from'],0,9999))
            $data['pickup_period_from']='';          
        if(!$Validation->isNumber($data['pickup_period_to'],0,9999))
            $data['pickup_period_to']='';
		if(!in_array($data['pickup_period_type'],array(1,2,3)))
			$data['pickup_period_type']=1;	
       
        if(!$Validation->isNumber($data['booking_interval'],0,9999))
            $data['booking_interval']=0;   		
		
        if(!$Validation->isNumber($data['vehicle_rent_day_count_min'],1,9999))
            $data['vehicle_rent_day_count_min']='';          
        if(!$Validation->isNumber($data['vehicle_rent_day_count_max'],1,9999))
            $data['vehicle_rent_day_count_max']='';          
        
        if(($Validation->isNotEmpty($data['vehicle_rent_day_count_min'])) && ($Validation->isNotEmpty($data['vehicle_rent_day_count_max'])))
        {
            if($data['vehicle_rent_day_count_min']>$data['vehicle_rent_day_count_max'])
            {
                $data['vehicle_rent_day_count_min']='';
                $data['vehicle_rent_day_count_max']='';
            }
        }
		
		/***/
		
		$vehicleRentDate=array();
        $vehicleRentDatePost=CRBSHelper::getPostValue('vehicle_rent_date');
      
		$count=count($vehicleRentDatePost['start']);
		
		for($i=0;$i<$count;$i++)
		{
			if(!$Validation->isDate($vehicleRentDatePost['start'][$i])) continue;
			if(!$Validation->isDate($vehicleRentDatePost['stop'][$i])) continue;

			if($Date->compareDate($vehicleRentDatePost['start'][$i],$vehicleRentDatePost['stop'][$i])==1) continue;
			if($Date->compareDate(date_i18n('d-m-Y'),$vehicleRentDatePost['stop'][$i])==1) continue;
			
			if(!$Validation->isNumber($vehicleRentDatePost['day_count_min'][$i],1,9999,true)) continue;
			if(!$Validation->isNumber($vehicleRentDatePost['day_count_max'][$i],1,9999,true)) continue;
			
			if(($Validation->isEmpty($vehicleRentDatePost['day_count_min'][$i])) && ($Validation->isEmpty($vehicleRentDatePost['day_count_max'][$i]))) continue;
			
			if(($Validation->isNotEmpty($vehicleRentDatePost['day_count_min'][$i])) && ($Validation->isNotEmpty($vehicleRentDatePost['day_count_max'][$i])))
			{
				if($vehicleRentDatePost['day_count_min'][$i]>$vehicleRentDatePost['day_count_max'][$i]) continue;
			}
			
			$vehicleRentDate[]=array
			(
				'start'															=>	$vehicleRentDatePost['start'][$i],
				'stop'															=>	$vehicleRentDatePost['stop'][$i],
				'day_count_min'													=>	$vehicleRentDatePost['day_count_min'][$i],
				'day_count_max'													=>	$vehicleRentDatePost['day_count_max'][$i]
			);
		}
		
		$data['vehicle_rent_date']=$vehicleRentDate;
		
		/***/
		
		if(!$Country->isCountry($data['country_default']))
			$data['country_default']=-1;
		
		/***/
		
		if(is_array($data['country_available']))
		{
			if(in_array(-1,$data['country_available']))
				$data['country_available']=array(-1);
			else
			{
				foreach($data['country_available'] as $index=>$value)
				{
					if(!$Country->isCountry($value))
						unset($data['country_available'][$index]);
				}
			}
		}
		
		if((!is_array($data['country_available'])) || (!count($data['country_available'])))
			$data['country_available'][$index]=-1;		
		
		/***/
		
        $vehicle=$Vehicle->getDictionary();
        if(!array_key_exists($data['vehicle_id_default'],$vehicle))
            $data['vehicle_id_default']=-1;
        
        if(in_array(1,$data['vehicle_availability_check_type']))
            $data['vehicle_availability_check_type']=array(1);
       
        if(!$Validation->isBool($data['after_business_hour_pickup_enable']))
            $data['after_business_hour_pickup_enable']=0;   
        if(!$Validation->isBool($data['after_business_hour_return_enable']))
            $data['after_business_hour_return_enable']=0;         
        
        if(!in_array($data['driver_license_attach_enable'],array(0,1,2)))
            $data['driver_license_attach_enable']=0; 
        if(!$Validation->isBool($data['driver_license_file_name_random_enable']))
            $data['driver_license_file_name_random_enable']=0;   		
		
        /***/
        
        if(!$Country->isCountry($data['address_country']))
            $data['address_country']='US';       
        if(!$Validation->isEmailAddress($data['contact_detail_email_address']))
            $data['contact_detail_email_address']='';
        
        /***/
        
		$businessHour=array();
        $businessHourPost=CRBSHelper::getPostValue('business_hour');
  
		foreach(array_keys($Date->day) as $index)
		{
			$businessHour[$index]=array('start'=>null,'stop'=>null,'default'=>null,'break'=>null);
			
            if((isset($businessHourPost[$index][0])) && (isset($businessHourPost[$index][1])))
            {
                if(($Validation->isTime($businessHourPost[$index][0],false)) && ($Validation->isTime($businessHourPost[$index][1],false)))
                {
                    $result=$Date->compareTime($businessHourPost[$index][0],$businessHourPost[$index][1]);

                    if($result==2)
					{
                        $businessHour[$index]=array('start'=>$businessHourPost[$index][0],'stop'=>$businessHourPost[$index][1]);
					
						if(($Validation->isTime($businessHourPost[$index][2],false)))
						{
							$result=$Date->timeInRange($businessHourPost[$index][2],$businessHourPost[$index][0],$businessHourPost[$index][1]);
							if($result) $businessHour[$index]['default']=$businessHourPost[$index][2];
						}
						
						if($Validation->isNotEmpty($businessHourPost[$index][3]))
						{
							$breakHour=preg_split('/;/',$businessHourPost[$index][3]);
							
							foreach($breakHour as $breakHourValue)
							{
								list($start,$stop)=preg_split('/-/',$breakHourValue);
								
								if(($Validation->isTime($start)) && ($Validation->isTime($stop)))
								{
									$result=$Date->compareTime($start,$stop);
									if($result===2)
									{
										$businessHour[$index]['break'][]=array('start'=>$start,'stop'=>$stop);
									}
								}
							}
						}
					}
                }
            }
		}
				
		$data['business_hour']=$businessHour;
        
        /***/
        
		$dateExclude=array();
        $dateExcludePost=array();
        
        $dateExcludePostStart=CRBSHelper::getPostValue('date_exclude_start');
        $dateExcludePostStop=CRBSHelper::getPostValue('date_exclude_stop');
        
        foreach($dateExcludePostStart as $index=>$value)
        {
            if(isset($dateExcludePostStop[$index]))
                $dateExcludePost[]=array($dateExcludePostStart[$index],$dateExcludePostStop[$index]);
        }
      
		foreach($dateExcludePost as $index=>$value)
		{
			if(!$Validation->isDate($value[0],true)) continue;
			if(!$Validation->isDate($value[1],true)) continue;

			if($Date->compareDate($value[0],$value[1])==1) continue;
			if($Date->compareDate(date_i18n('d-m-Y'),$value[1])==1) continue;
			
			$dateExclude[]=array('start'=>$value[0],'stop'=>$value[1]);
		}
        
		$data['date_exclude']=$dateExclude;
        
        /***/
		
        if(!in_array($data['payment_deposit_type'],array(0,1,2)))
            $data['payment_deposit_type']=0; 		
        if(!$Validation->isPrice($data['payment_deposit_type_fixed_value']))
            $data['payment_deposit_type_fixed_value']=0.00;    		
        if(!$Validation->isFloat($data['payment_deposit_type_fixed_value'],0,100))
            $data['payment_deposit_type_percentage_value']=0; 		
        
        if(!$Validation->isBool($data['payment_mandatory_enable']))
            $data['payment_mandatory_enable']=0;     
        if(!$Validation->isBool($data['payment_processing_enable']))
            $data['payment_processing_enable']=1; 
        if(!$Validation->isBool($data['payment_woocommerce_step_3_enable']))
            $data['payment_woocommerce_step_3_enable']=1; 		
		if(!$Payment->isPayment($data['payment_default_id']))
			$data['payment_default_id']=-1;
		
		if(!$Validation->isNumber($data['payment_paypal_redirect_duration'],-1,99))
			$data['payment_paypal_redirect_duration']=5;
		
		if(!$Validation->isNumber($data['payment_stripe_redirect_duration'],-1,99))
			$data['payment_stripe_redirect_duration']=5;	

		if(is_array($data['payment_stripe_method']))
		{
			foreach($data['payment_stripe_method'] as $index=>$value)
			{
				if(!$PaymentStripe->isPaymentMethod($value))
					unset($data['payment_stripe_method'][$index]);
			}
		}
			
		if((!is_array($data['payment_stripe_method'])) || (!count($data['payment_stripe_method'])))
			$data['payment_stripe_method']=array('card');
			
        foreach($data['payment_id'] as $index=>$value)
        {
            if($Payment->isPayment($value)) continue;
            unset($data['payment_id'][$value]);
        }
             
        if(!$Validation->isBool($data['nexmo_sms_enable']))
            $data['nexmo_sms_enable']=0;
               
        /***/
        
        if(!$Validation->isBool($data['twilio_sms_enable']))
            $data['twilio_sms_enable']=0;
                
        /***/
        
        if(!$Validation->isBool($data['telegram_enable']))
            $data['telegram_enable']=0;        
        
        /***/
        
        $dictionary=$EmailAccount->getDictionary();
        
        if(!array_key_exists($data['booking_new_sender_email_account_id'],$dictionary))
            $data['booking_new_sender_email_account_id']=-1;
        
        $recipient=preg_split('/;/',$data['booking_new_recipient_email_address']);
        
        $data['booking_new_recipient_email_address']='';
        
        foreach($recipient as $index=>$value)
        {
            if($Validation->isEmailAddress($value))
            {
                if($Validation->isNotEmpty($data['booking_new_recipient_email_address'])) $data['booking_new_recipient_email_address'].=';';
                $data['booking_new_recipient_email_address'].=$value;
            }
        } 
        
        if(!$Validation->isBool($data['booking_new_woocommerce_email_notification']))
            $data['booking_new_woocommerce_email_notification']=0;   
        if(!$Validation->isBool($data['booking_new_customer_email_notification']))
            $data['booking_new_customer_email_notification']=0;  
        if(!$Validation->isBool($data['booking_new_admin_email_notification']))
            $data['booking_new_admin_email_notification']=0;  		
		
        /***/
        
        $data['google_calendar_settings']=CRBSHelper::getPostValue('google_calendar_settings');
        
        if(!$Validation->isBool($data['google_calendar_enable']))
            $data['google_calendar_enable']=0;  
        
        /***/
        
        foreach($dataIndex as $index)
            CRBSPostMeta::updatePostMeta($postId,$index,$data[$index]); 
    }
    
    /**************************************************************************/
    
    function getDictionary($attr=array())
    {
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'location_id'         												=>	0
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
		
		if($attribute['location_id'])
			$argument['p']=$attribute['location_id'];

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
            'date'                                                              =>  $column['date']
        );
   
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
    
    function displayAddress($locationId)
    {
        $html=null;
        
        $dictionary=$this->getDictionary(array('location_id'=>$locationId));
        if(!count($dictionary)) return($html);
        
        $location=$dictionary[$locationId];
        
        $data=array
        (
            'name'                                                              =>  $location['post']->post_title,
            'street'                                                            =>  $location['meta']['address_street'], 
            'street_number'                                                     =>  $location['meta']['address_street_number'], 
            'postcode'                                                          =>  $location['meta']['address_postcode'],  
            'city'                                                              =>  $location['meta']['address_city'],                 
            'state'                                                             =>  $location['meta']['address_state'],  
            'country'                                                           =>  $location['meta']['address_country']
        );

        $html=CRBSHelper::displayAddress($data);
        
        return($html);
    }
    
    /**************************************************************************/
    
    function getLocationInfo()
    {        
        $location=$this->getDictionary();
        if($location===false) return(false);
        
        $TaxRate=new CRBSTaxRate();
        $taxRate=$TaxRate->getDictionary();
        
        $Vehicle=new CRBSVehicle();
        $vehicle=$Vehicle->getDictionary();
        
        $data=array();
        
        foreach($location as $locationIndex=>$locationData)
        {
            $data[$locationIndex]=array
            (
                'vehicle_count'                                                 =>  0,
                'driver_age'                                                    =>  array(),
                'price_rental_day'                                              =>  array(),
                'price_rental_hour'                                             =>  array(),
            );
            
            foreach($vehicle as $vehicleData)
            {
                if(is_array($vehicleData['meta']['location_id']))
                {
                    if(in_array($locationIndex,$vehicleData['meta']['location_id']))
                    {
                        $data[$locationIndex]['vehicle_count']++;

                        array_push($data[$locationIndex]['driver_age'],$vehicleData['meta']['driver_age_min'],$vehicleData['meta']['driver_age_max']);

                        $price=CRBSPrice::calculateGross($vehicleData['meta']['price_rental_day_value'],0,$TaxRate->getTaxRateValue($vehicleData['meta']['price_rental_tax_rate_id'],$taxRate));
                        array_push($data[$locationIndex]['price_rental_day'],$price);                        

                        $price=CRBSPrice::calculateGross($vehicleData['meta']['price_rental_hour_value'],0,$TaxRate->getTaxRateValue($vehicleData['meta']['price_rental_tax_rate_id'],$taxRate));
                        array_push($data[$locationIndex]['price_rental_hour'],$price);                       
                    }
                }
            }
            
            if((int)$data[$locationIndex]['vehicle_count']===0) continue;

            $min=min($data[$locationIndex]['driver_age']);
            $max=max($data[$locationIndex]['driver_age']);
            
            $data[$locationIndex]['driver_age']=$min==$max ? $min : $min.' - '.$max;
            
            $min=min($data[$locationIndex]['price_rental_day']);
            $max=max($data[$locationIndex]['price_rental_day']);
            $data[$locationIndex]['price_rental_day']=$min==$max ? CRBSPrice::format($min,CRBSOption::getOption('currency')) : CRBSPrice::format($min,CRBSOption::getOption('currency')).' - '.CRBSPrice::format($max,CRBSOption::getOption('currency'));            

            $min=min($data[$locationIndex]['price_rental_hour']);
            $max=max($data[$locationIndex]['price_rental_hour']);
            $data[$locationIndex]['price_rental_hour']=$min==$max ? CRBSPrice::format($min,CRBSOption::getOption('currency')) : CRBSPrice::format($min,CRBSOption::getOption('currency')).' - '.CRBSPrice::format($max,CRBSOption::getOption('currency'));            
        }
        
        return($data);
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/