<?php

/******************************************************************************/
/******************************************************************************/

class CRBSBooking
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
        return(PLUGIN_CRBS_CONTEXT.'_booking');
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
                    'name'														=>	__('Bookings','car-rental-booking-system'),
                    'singular_name'												=>	__('Booking','car-rental-booking-system'),
                    'edit_item'													=>	__('Edit Booking','car-rental-booking-system'),
                    'all_items'													=>	__('Bookings','car-rental-booking-system'),
                    'view_item'													=>	__('View Booking','car-rental-booking-system'),
                    'search_items'												=>	__('Search Bookings','car-rental-booking-system'),
                    'not_found'													=>	__('No Bookings Found','car-rental-booking-system'),
                    'not_found_in_trash'										=>	__('No Bookings Found in Trash','car-rental-booking-system'), 
                    'parent_item_colon'											=>	'',
                    'menu_name'													=>	__('Car Rental Booking System','car-rental-booking-system')
                ),	
                'public'														=>	false,  
                'menu_icon'														=>	'dashicons-calendar-alt',
                'show_ui'														=>	true,  
                'capability_type'												=>	'post',
                'capabilities'													=>	array
                (
                     'create_posts'												=>	'do_not_allow',
                ),
                'map_meta_cap'													=>	true, 
                'menu_position'													=>	100,
                'hierarchical'													=>	false,  
                'rewrite'														=>	false,  
                'supports'														=>	array('title')  
            )
        );
        
        add_action('save_post',array($this,'savePost'));
        
        add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
        add_filter('postbox_classes_'.self::getCPTName().'_crbs_meta_box_booking_form',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
        
        add_action('restrict_manage_posts',array($this,'restrictManagePosts'));
        add_filter('parse_query',array($this,'parseQuery'));
    }
    
    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CRBS_CONTEXT.'_meta_box_booking_form',__('Main','car-rental-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');
        add_meta_box(PLUGIN_CRBS_CONTEXT.'_meta_box_booking_form_woocommerce',__('WooCommerce','car-rental-booking-system'),array($this,'addMetaBoxWooCommerce'),self::getCPTName(),'side','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
                
        $data=$this->getBooking($post->ID);
            
        $data['nonce']=CRBSHelper::createNonceField(PLUGIN_CRBS_CONTEXT.'_meta_box_booking');
        
        $data['billing']=$this->createBilling($post->ID);
        
		$Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'admin/meta_box_booking.php');
		echo $Template->output();	        
    }
	
	/**************************************************************************/
	
	function addMetaBoxWooCommerce()
	{
		global $post;
		
		$booking=$this->getBooking($post->ID);
		
		if((int)$booking['meta']['woocommerce_booking_id']>0)
		{
			echo 
			'
				<div>
					<div>'.esc_html__('This booking has corresponding wooCommerce order. Click on button below to see its details in new window.','car-rental-booking-system').'</div>
					<br/>
					<a class="button button-primary" href="'.get_edit_post_link($booking['meta']['woocommerce_booking_id']).'" target="_blank">'.esc_html__('Open booking','car-rental-booking-system').'</a>
				</div>
			';
		}
		else
		{
			echo 
			'
				<div>
					<div>'.esc_html__('This booking hasn\'t corresponding wooCommerce order.','car-rental-booking-system').'</div>
				</div>
			';			
		}
	}
    
    /**************************************************************************/
    
    function getBooking($bookingId)
    {
		$post=get_post($bookingId);
		if(is_null($post)) return(false);
        
        $booking=array();
        
        $Payment=new CRBSPayment();
        $Country=new CRBSCountry();
        $BookingStatus=new CRBSBookingStatus();
        
        $booking['post']=$post;
        $booking['meta']=CRBSPostMeta::getPostMeta($post);		
  
        if($booking['meta']['client_billing_detail_enable']==1)
        {
            $country=$Country->getCountry($booking['meta']['client_billing_detail_country_code']);
            $booking['client_billing_detail_country_name']=$country[0];
        }
        
        if($Payment->isPayment($booking['meta']['payment_id']))
        {
            $payment=$Payment->getPayment($booking['meta']['payment_id']);
            $booking['payment_name']=$payment[0];
        }
        
        if($BookingStatus->isBookingStatus($booking['meta']['booking_status_id']))
        {
            $bookingStatus=$BookingStatus->getBookingStatus($booking['meta']['booking_status_id']);
            $booking['booking_status_name']=$bookingStatus[0];
        }
          
        /***/
        
        $booking['dictionary']['booking_status']=$BookingStatus->getBookingStatus();

        /***/
        
        return($booking);
    }
    
    /**************************************************************************/
    
    function adminCreateMetaBoxClass($class) 
    {
        array_push($class,'to-postbox-1');
        return($class);
    }
    
    /**************************************************************************/
    
    function sendBooking($data,$bookingForm)
    {      
        $bookingId=wp_insert_post(array
        (
            'post_type'                                                         =>  self::getCPTName(),
            'post_status'                                                       =>  'publish'
        ));
        
        if($bookingId===0) return(false);
        
        wp_update_post(array
        (
 			'ID'																=>	$bookingId,
			'post_title'														=>	$this->getBookingTitle($bookingId)           
        ));
        
        /***/
        
        $WPML=new CRBSWPML();
        $TaxRate=new CRBSTaxRate();
        $Vehicle=new CRBSVehicle();
        $Location=new CRBSLocation();
		$PriceRule=new CRBSPriceRule();
        $WooCommerce=new CRBSWooCommerce();
        $BookingForm=new CRBSBookingForm();
        $BookingFormElement=new CRBSBookingFormElement();
        
        $taxRateDictionary=$TaxRate->getDictionary();
        $locationDictionary=$Location->getDictionary();
        
        list($pickupLocationId,$pickupLocationCustomerAddress)=$BookingForm->getBookingFormPickupLocation($bookingForm);
        list($returnLocationId,$returnLocationCustomerAddress)=$BookingForm->getBookingFormReturnLocation($bookingForm);

        /***/
        
        CRBSPostMeta::updatePostMeta($bookingId,'woocommerce_enable',$WooCommerce->isEnable($bookingForm['meta']));
        
        CRBSPostMeta::updatePostMeta($bookingId,'billing_type',CRBSOption::getOption('billing_type'));
        
        CRBSPostMeta::updatePostMeta($bookingId,'booking_status_id',$bookingForm['meta']['booking_status_id_default']);
        
        CRBSPostMeta::updatePostMeta($bookingId,'booking_form_id',$data['booking_form_id']);
        
        CRBSPostMeta::updatePostMeta($bookingId,'currency_id',CRBSCurrency::getFormCurrency());
        
        CRBSPostMeta::updatePostMeta($bookingId,'pickup_time',$data['pickup_time']);
        CRBSPostMeta::updatePostMeta($bookingId,'pickup_date',$data['pickup_date']);
        
        CRBSPostMeta::updatePostMeta($bookingId,'return_time',$data['return_time']);
        CRBSPostMeta::updatePostMeta($bookingId,'return_date',$data['return_date']);
        
        CRBSPostMeta::updatePostMeta($bookingId,'booking_extra_day_enable',$bookingForm['meta']['booking_extra_day_enable']);
        CRBSPostMeta::updatePostMeta($bookingId,'booking_extra_day_time',$bookingForm['meta']['booking_extra_day_time']);
        CRBSPostMeta::updatePostMeta($bookingId,'booking_extra_day_number',$bookingForm['meta']['booking_extra_day_number']);
                        
        /***/
        
        CRBSPostMeta::updatePostMeta($bookingId,'pickup_location_id',$pickupLocationId);
        CRBSPostMeta::updatePostMeta($bookingId,'return_location_id',$returnLocationId);
        
        if($pickupLocationCustomerAddress!==false)
        {
            CRBSPostMeta::updatePostMeta($bookingId,'pickup_location_custom',1);
            
            CRBSPostMeta::updatePostMeta($bookingId,'pickup_location_name',$pickupLocationCustomerAddress->{'formatted_address'});
            CRBSPostMeta::updatePostMeta($bookingId,'pickup_location_coordinate_lat',$pickupLocationCustomerAddress->{'lat'});
            CRBSPostMeta::updatePostMeta($bookingId,'pickup_location_coordinate_lng',$pickupLocationCustomerAddress->{'lng'});
        }
        else
        {
            CRBSPostMeta::updatePostMeta($bookingId,'pickup_location_name',$locationDictionary[$pickupLocationId]['post']->post_title);
        }
        
        if($returnLocationCustomerAddress!==false)
        {
            CRBSPostMeta::updatePostMeta($bookingId,'return_location_custom',1);
            
            CRBSPostMeta::updatePostMeta($bookingId,'return_location_name',$returnLocationCustomerAddress->{'formatted_address'});
            CRBSPostMeta::updatePostMeta($bookingId,'return_location_coordinate_lat',$returnLocationCustomerAddress->{'lat'});
            CRBSPostMeta::updatePostMeta($bookingId,'return_location_coordinate_lng',$returnLocationCustomerAddress->{'lng'});
        }
        else
        {
            if((int)$data['return_location_id']===-1)
            {
                if($pickupLocationCustomerAddress!==false)
                {
                    CRBSPostMeta::updatePostMeta($bookingId,'return_location_custom',1);
                    
                    CRBSPostMeta::updatePostMeta($bookingId,'return_location_name',$pickupLocationCustomerAddress->{'formatted_address'});
                    CRBSPostMeta::updatePostMeta($bookingId,'return_location_coordinate_lat',$pickupLocationCustomerAddress->{'lat'});
                    CRBSPostMeta::updatePostMeta($bookingId,'return_location_coordinate_lng',$pickupLocationCustomerAddress->{'lng'});                      
                }
                else
                {
                    CRBSPostMeta::updatePostMeta($bookingId,'return_location_name',$locationDictionary[$pickupLocationId]['post']->post_title);
                }
            }
            else CRBSPostMeta::updatePostMeta($bookingId,'return_location_name',$locationDictionary[$returnLocationId]['post']->post_title);
        }        
        
        CRBSPostMeta::updatePostMeta($bookingId,'pickup_datetime',$data['pickup_date'].' '.$data['pickup_time']);
        
        /***/
        
        $vehicle=$bookingForm['dictionary']['vehicle'][$data['vehicle_id']];
        		
        $argument=array
        (
            'booking_form_id'                                                   =>  $data['booking_form_id'],
            'vehicle_id'                                                        =>  $data['vehicle_id'],
            'pickup_location_id'                                                =>  $pickupLocationId,
            'pickup_date'                                                       =>  $data['pickup_date'],
            'pickup_time'                                                       =>  $data['pickup_time'],
            'return_location_id'                                                =>  $returnLocationId,
            'return_date'                                                       =>  $data['return_date'],
            'return_time'                                                       =>  $data['return_time'],
            'driver_age'                                                        =>  $data['driver_age']
        );
        
        $discountPercentage=0;
               
        $vehiclePrice=$Vehicle->calculatePrice($argument,$bookingForm,$discountPercentage);    
		
        CRBSPostMeta::updatePostMeta($bookingId,'vehicle_id',$WPML->translateID($data['vehicle_id']));
        CRBSPostMeta::updatePostMeta($bookingId,'vehicle_name',$vehicle['post']->post_title);
        
		$vehiclePriceBooking=array();
		
		foreach($PriceRule->getPriceUseType() as $index=>$value)
		{
			$taxRateIndex=$PriceRule->getTaxRateIndexName($index);
			
			$vehiclePriceBooking['price_'.$index.'_value']=$vehiclePrice['price']['base']['price_'.$index.'_value'];
			$vehiclePriceBooking['price_'.$taxRateIndex.'_tax_rate_value']=$TaxRate->getTaxRateValue($vehiclePrice['price']['base']['price_'.$taxRateIndex.'_tax_rate_id'],$taxRateDictionary);
		}
             
        foreach($vehiclePriceBooking as $index=>$value)
            CRBSPostMeta::updatePostMeta($bookingId,$index,$value);
        
        /***/
        
        CRBSPostMeta::updatePostMeta($bookingId,'delivery_distance',$vehiclePrice['other']['delivery']['distance']); 
        CRBSPostMeta::updatePostMeta($bookingId,'delivery_return_distance',$vehiclePrice['other']['delivery_return']['distance']);
        
		/***/
		
		$paymentDepositType=CRBSBookingFormHelper::isPaymentDepositEnable($bookingForm);
        
        CRBSPostMeta::updatePostMeta($bookingId,'payment_deposit_type',$paymentDepositType);
		CRBSPostMeta::updatePostMeta($bookingId,'payment_deposit_type_fixed_value',$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['payment_deposit_type_fixed_value']);
        CRBSPostMeta::updatePostMeta($bookingId,'payment_deposit_type_percentage_value',$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['payment_deposit_type_percentage_value']);
		
        /***/
        
        if(in_array((int)$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['driver_license_attach_enable'],array(1,2)))
        {
			if((int)$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['driver_license_file_name_random_enable']===1)
			{
				$Validation=new CRBSValidation();
				
				$info=pathinfo($data['driver_license_file_name']);

				$data['driver_license_file_name']=CRBSHelper::createHash(microtime());
				
				if($Validation->isNotEmpty($info['extension']))
					$data['driver_license_file_name'].='.'.$info['extension'];
			}
			
            $file1=CRBSFile::getUploadPath().'/'.$data['driver_license_file_tmp_name'];
            $file2=CRBSFile::getUploadPath().'/'.$data['driver_license_file_name'];
            
            if(rename($file1,$file2))
            {
                $upload=wp_upload_bits($data['driver_license_file_name'],null,file_get_contents($file2));

                if($upload['error']===false)
                {
                    $attachment=array
                    (
                        'post_title'                                            =>  $data['driver_license_file_name'],
                        'post_mime_type'                                        =>  $data['driver_license_file_type'],
                        'post_content'                                          =>  '',
                        'post_status'                                           =>  'inherit'
                    );

                    $attachmentId=wp_insert_attachment($attachment,$upload['file'],$bookingId);

                    if($attachmentId>0)
                    {
                        $attachmentData=wp_generate_attachment_metadata($attachmentId,$upload['file'] );
                        wp_update_attachment_metadata($attachmentId,$attachmentData);

                        CRBSPostMeta::updatePostMeta($bookingId,'driver_license_attachment_id',$attachmentId);
                    }
                }
            }
            
            @unlink($file1);
            @unlink($file2);
        }
        
        /***/
        
        $BookingFormElement->sendBookingField($bookingId,$bookingForm['meta'],$data);
        
        /***/
        
        $BookingExtra=new CRBSBookingExtra();
        $bookingExtra=$BookingExtra->validate($data,$bookingForm,$taxRateDictionary);
        
        CRBSPostMeta::updatePostMeta($bookingId,'booking_extra',$bookingExtra);
        
        /***/
        
        $field=array('first_name','last_name','email_address','phone_number');
        foreach($field as $value)
            CRBSPostMeta::updatePostMeta($bookingId,'client_contact_detail_'.$value,$data['client_contact_detail_'.$value]);
        
        CRBSPostMeta::updatePostMeta($bookingId,'client_billing_detail_enable',(int)$data['client_billing_detail_enable']);   
        
        if((int)$data['client_billing_detail_enable']===1)
        {
            $field=array('company_name','tax_number','street_name','street_number','city','state','postal_code','country_code');
            foreach($field as $value)
                CRBSPostMeta::updatePostMeta($bookingId,'client_billing_detail_'.$value,$data['client_billing_detail_'.$value]);            
        }
        
        /***/
        
        CRBSPostMeta::updatePostMeta($bookingId,'comment',$data['comment']);
        
        /***/
        
        CRBSPostMeta::updatePostMeta($bookingId,'payment_id',$data['payment_id']);
        CRBSPostMeta::updatePostMeta($bookingId,'payment_name',CRBSBookingHelper::getPaymentName($data['payment_id'],-1,$bookingForm['meta']));
        
        /***/
		
		$couponCodeSourceType=0;
        
        $Coupon=new CRBSCoupon();
        $code=$Coupon->checkCode($bookingForm,$couponCodeSourceType);
        
        if($code===false)
        {
            CRBSPostMeta::updatePostMeta($bookingId,'coupon_code','');
            CRBSPostMeta::updatePostMeta($bookingId,'coupon_discount_percentage',0);
        }
        else
        {
            CRBSPostMeta::updatePostMeta($bookingId,'coupon_code',$code['meta']['code']);
            CRBSPostMeta::updatePostMeta($bookingId,'coupon_discount_percentage',$discountPercentage);            
        }
        
        /***/
        
		$this->sendEmailBooking($bookingId,'AFTER_BOOKING');
      
        if($locationDictionary[$pickupLocationId]['meta']['nexmo_sms_enable']==1)
        {
            $Nexmo=new CRBSNexmo();
            $Nexmo->sendSMS($locationDictionary[$pickupLocationId]['meta']['nexmo_sms_api_key'],$locationDictionary[$pickupLocationId]['meta']['nexmo_sms_api_key_secret'],$locationDictionary[$pickupLocationId]['meta']['nexmo_sms_sender_name'],$locationDictionary[$pickupLocationId]['meta']['nexmo_sms_recipient_phone_number'],$locationDictionary[$pickupLocationId]['meta']['nexmo_sms_message']);
        }
        
        if($locationDictionary[$pickupLocationId]['meta']['twilio_sms_enable']==1)
        {
            $Twilio=new CRBSTwilio();
            $Twilio->sendSMS($locationDictionary[$pickupLocationId]['meta']['twilio_sms_api_sid'],$locationDictionary[$pickupLocationId]['meta']['twilio_sms_api_token'],$locationDictionary[$pickupLocationId]['meta']['twilio_sms_sender_phone_number'],$locationDictionary[$pickupLocationId]['meta']['twilio_sms_recipient_phone_number'],$locationDictionary[$pickupLocationId]['meta']['twilio_sms_message']);
        }
        
 		if($locationDictionary[$pickupLocationId]['meta']['telegram_enable']==1)
        {
            $Telegram=new CRBSTelegram();
            $Telegram->sendMessage($locationDictionary[$pickupLocationId]['meta']['telegram_token'],$locationDictionary[$pickupLocationId]['meta']['telegram_group_id'],$locationDictionary[$pickupLocationId]['meta']['telegram_message']);
        }
        
        /***/
        
        $GoogleCalendar=new CRBSGoogleCalendar();
        $GoogleCalendar->sendBooking($bookingId);
		
        /***/
	
        return($bookingId);
    }
	
	/**************************************************************************/
	
	function sendEmailBooking($bookingId,$state='AFTER_BOOKING')
	{
		$Location=new CRBSLocation();
		
		if(($booking=$this->getBooking($bookingId))===false) return(false);

		$locationDictionary=$Location->getDictionary();
		
		$pickupLocationId=$booking['meta']['pickup_location_id'];
		
		if(!array_key_exists($pickupLocationId,$locationDictionary)) return(false);
		
		$subject=sprintf(__('New booking "%s" is received','car-rental-booking-system'),$this->getBookingTitle($bookingId));
        
        $recipient=array();
        $recipient[0]=array($booking['meta']['client_contact_detail_email_address']);
        $recipient[1]=preg_split('/;/',$locationDictionary[$pickupLocationId]['meta']['booking_new_recipient_email_address']);
        
		global $crbs_logEvent;

		if((int)$locationDictionary[$pickupLocationId]['meta']['booking_new_customer_email_notification']===1)
		{
			$crbs_logEvent=1;
			$this->sendEmail($bookingId,$locationDictionary[$pickupLocationId]['meta']['booking_new_sender_email_account_id'],'booking_new_client',$recipient[0],$subject);
		}
	
		if((int)$locationDictionary[$pickupLocationId]['meta']['booking_new_admin_email_notification']===1)
		{
			$crbs_logEvent=2;
			$this->sendEmail($bookingId,$locationDictionary[$pickupLocationId]['meta']['booking_new_sender_email_account_id'],'booking_new_admin',$recipient[1],$subject);
		}
	}
    
    /**************************************************************************/
    
    function getBookingTitle($bookingId)
    {
        return(sprintf(__('Booking #%s','car-rental-booking-system'),$bookingId));
    }
    
	/**************************************************************************/
    
	function setPostMetaDefault(&$meta)
	{
        CRBSHelper::setDefault($meta,'woocommerce_enable',0);
        CRBSHelper::setDefault($meta,'woocommerce_booking_id',0);
		
        CRBSHelper::setDefault($meta,'billing_type',2);
		CRBSHelper::setDefault($meta,'booking_status_id',1);
        
        CRBSHelper::setDefault($meta,'coupon_code','');
        CRBSHelper::setDefault($meta,'coupon_discount_percentage',0);
        
        CRBSHelper::setDefault($meta,'driver_license_attachment_id',0);
        
        CRBSHelper::setDefault($meta,'pickup_location_custom',0);
        CRBSHelper::setDefault($meta,'pikcup_location_coordinate_lat','');
        CRBSHelper::setDefault($meta,'pickup_location_coordinate_lng','');
        
        CRBSHelper::setDefault($meta,'return_location_custom',0);
        CRBSHelper::setDefault($meta,'return_location_coordinate_lat','');
        CRBSHelper::setDefault($meta,'return_location_coordinate_lng','');
                
        CRBSHelper::setDefault($meta,'delivery_distance',0);
        CRBSHelper::setDefault($meta,'delivery_return_distance',0);
		
		$PriceRule=new CRBSPriceRule();
		
		foreach($PriceRule->getPriceUseType() as $priceUseTypeIndex=>$priceUseTypeValue)
		{
			CRBSHelper::setDefault($meta,'price_'.$priceUseTypeIndex.'_value',0);
			CRBSHelper::setDefault($meta,'price_'.$priceUseTypeIndex.'_tax_rate_value',0);			
		}
		
        CRBSHelper::setDefault($meta,'payment_deposit_type',0);
        CRBSHelper::setDefault($meta,'payment_deposit_type_fixed_value',0.00);
        CRBSHelper::setDefault($meta,'payment_deposit_type_percentage_value',0);		
	}
    
    /**************************************************************************/
    
	function savePost($postId)
	{		
        if(!$_POST) return(false);
        
        if(CRBSHelper::checkSavePost($postId,PLUGIN_CRBS_CONTEXT.'_meta_box_booking_noncename','savePost')===false) return(false);
	
		$oldMeta=CRBSPostMeta::getPostMeta($postId);
		
		$BookingStatus=new CRBSBookingStatus();
		
        if($BookingStatus->isBookingStatus(CRBSHelper::getPostValue('booking_status_id')))
           CRBSPostMeta::updatePostMeta($postId,'booking_status_id',CRBSHelper::getPostValue('booking_status_id')); 
            
        $newPost=get_post($postId);
		$newMeta=CRBSPostMeta::getPostMeta($postId);
		
		if($oldMeta['booking_status_id']!=$newMeta['booking_status_id'])
        {
            $BookingStatus=new CRBSBookingStatus();
            $bookingStatus=$BookingStatus->getBookingStatus($newMeta['booking_status_id']);
            
            $recipient=array();
            $recipient[0]=array($newMeta['client_contact_detail_email_address']);
       
            $subject=sprintf(__('Booking "%s" has changed status to "%s"','car-rental-booking-system'),$newPost->post_title,$bookingStatus[0]);
        
			global $crbs_logEvent;
        
			$crbs_logEvent=3;
            $this->sendEmail($postId,CRBSOption::getOption('sender_default_email_account_id'),'booking_change_status',$recipient[0],$subject);           
        }
		
		$WooCommerce=new CRBSWooCommerce();
		$WooCommerce->changeStaus(-1,$postId);
	}

    /**************************************************************************/
    
    function manageEditColumns($column)
    {
        $addColumn=array
        (
            'status'                                                            =>  __('Booking status','car-rental-booking-system'),
            'location'                                                          =>  __('Pickup and return location','car-rental-booking-system'),
            'rental_period'                                                     =>  __('Rental period','car-rental-booking-system'),
            'vehicle'                                                           =>  __('Vehicle','car-rental-booking-system'),
            'client'                                                            =>  __('Client','car-rental-booking-system'),
            'price'                                                             =>  __('Price','car-rental-booking-system'),
            'date'                                                              =>  $column['date']
        );
   
        unset($column['date']);
        
        foreach($addColumn as $index=>$value)
            $column[$index]=$value;
        
		return($column);          
    }
    
    /**************************************************************************/
    
    function managePostsCustomColumn($column)
    {
		global $post;
		
        $Date=new CRBSDate();
		$BookingStatus=new CRBSBookingStatus();
        
		$meta=CRBSPostMeta::getPostMeta($post);
        
        $billing=$this->createBilling($post->ID);
		
		switch($column) 
		{
			case 'status':
				
                $bookingStatus=$BookingStatus->getBookingStatus($meta['booking_status_id']);
                echo '<div class="to-booking-status to-booking-status-'.(int)$meta['booking_status_id'].'">'.esc_html($bookingStatus[0]).'</div>';
                
			break;
        
            case 'location':
                
                echo esc_html($meta['pickup_location_name'].' - '.$meta['return_location_name']);
                
            break;
        
            case 'rental_period':
                
                echo esc_html__('From: ','car-rental-booking-system').esc_html($Date->formatDateToDisplay($meta['pickup_date']).' '.$Date->formatTimeToDisplay($meta['pickup_time']));
                echo '<br>';
                echo esc_html__('To: ','car-rental-booking-system').esc_html($Date->formatDateToDisplay($meta['return_date']).' '.$Date->formatTimeToDisplay($meta['return_time']));
                
            break;
        
            case 'vehicle':
                
                echo esc_html($meta['vehicle_name']);
                
            break;
          
            case 'client':
                
                echo esc_html($meta['client_contact_detail_first_name'].' '.$meta['client_contact_detail_last_name']);
                
            break;
   
            case 'price':
                
                echo esc_html(CRBSPrice::format($billing['summary']['value_gross'],$meta['currency_id']));
                
            break;
        }
    }
    
    /**************************************************************************/
    
    function manageEditSortableColumns($column)
    {
		return($column);       
    }
    
    /**************************************************************************/
    
    function restrictManagePosts()
    {
 		if(!is_admin()) return;
		if(CRBSHelper::getGetValue('post_type',false)!==self::getCPTName()) return;       
        
        $html=null;
        
        /***/
        
        $BookingStatus=new CRBSBookingStatus();
        $bookingStatusDirectory=$BookingStatus->getBookingStatus();
        
        $directory=array();
        foreach($bookingStatusDirectory as $index=>$value)
            $directory[$index]=$value[0];
        
		$directory[-2]=__('New & accepted','car-rental-booking-system');
		
		asort($directory,SORT_STRING);
        
		if(!array_key_exists('booking_status_id',$_GET))
			$_GET['booking_status_id']=-2;
        
 		foreach($directory as $index=>$value)
			$html.='<option value="'.(int)$index.'" '.(((int)CRBSHelper::getGetValue('booking_status_id',false)==$index) ?  'selected' : null).'>'.esc_html($value).'</option>';
		
		$html=
		'
			<select name="booking_status_id">
				<option value="0">'.__('All statuses','car-rental-booking-system').'</option>
				'.$html.'
			</select>
		';
        
        /***/
        
        echo $html;
    }
    
    /**************************************************************************/
    
    function parseQuery($query)
    {
		if(!is_admin()) return;
		if(CRBSHelper::getGetValue('post_type',false)!==self::getCPTName()) return;
		if($query->query['post_type']!==self::getCPTName()) return;       
        
        $metaQuery=array();
        $Validation=new CRBSValidation();
        
		$bookingStatusId=CRBSHelper::getGetValue('booking_status_id',false);
		if($Validation->isEmpty($bookingStatusId)) $bookingStatusId=-2;

		if($bookingStatusId!=0)
		{
			array_push($metaQuery,array
			(
				'key'															=>	PLUGIN_CRBS_CONTEXT.'_booking_status_id',
				'value'															=>	$bookingStatusId==-2 ? array(1,2) : array($bookingStatusId),
				'compare'														=>	'IN'
			));
		}  
        
		$order=CRBSHelper::getGetValue('order',false);
		$orderby=CRBSHelper::getGetValue('orderby',false);
		
		if($orderby=='title')
		{
			$query->set('orderby','title');
		}
        elseif($orderby=='date')
		{
			$query->set('orderby','date');
		}
		else
		{
			switch($orderby)
			{
				default:

					$query->set('meta_key',PLUGIN_CRBS_CONTEXT.'_pickup_datetime');
					$query->set('meta_type','DATETIME');

					if($Validation->isEmpty($order)) $order='asc';
			}

			$query->set('orderby','meta_value');
		}

		$query->set('order',$order);

		if(count($metaQuery)) $query->set('meta_query',$metaQuery);
    }
    
    /**************************************************************************/
    
    function calculatePrice($data=null,$vehiclePrice=null,$hideFee=false)
    {        
        $TaxRate=new CRBSTaxRate();
        $Vehicle=new CRBSVehicle();
        $BookingForm=new CRBSBookingForm();
        $BookingExtra=new CRBSBookingExtra();
        
        $taxRateDictionary=$TaxRate->getDictionary();
        
        /***/
        
        $component=array('vehicle','deposit','initial','one_way','after_business_hour_return','after_business_hour_pickup','customer_pickup_location','customer_return_location','booking_extra','total','pay');
        
        foreach($component as $value)
        {
            $price[$value]=array
            (
                'sum'                                                           =>  array
                (
                    'net'                                                       =>  array
                    (
                        'value'                                                 =>  0.00
                    ),
                    'gross'                                                     =>  array
                    (
                        'value'                                                 =>  0.00,
                        'format'                                                =>  0.00
                    )
                )                
            );
        }
        
        /***/
    
        list($pickupLocationId)=$BookingForm->getBookingFormPickupLocation($data['booking_form']);
        list($returnLocationId)=$BookingForm->getBookingFormReturnLocation($data['booking_form']);
        
        if(array_key_exists($data['vehicle_id'],$data['booking_form']['dictionary']['vehicle']))
        {
            $argument=array
            (
                'booking_form_id'                                               =>  (int)$data['booking_form_id'],
                'vehicle_id'                                                    =>  (int)$data['vehicle_id'],
                'pickup_location_id'                                            =>  $pickupLocationId,
                'pickup_date'                                                   =>  $data['pickup_date'],
                'pickup_time'                                                   =>  $data['pickup_time'],
                'return_location_id'                                            =>  $returnLocationId,
                'return_date'                                                   =>  $data['return_date'],
                'return_time'                                                   =>  $data['return_time'],
                'driver_age'                                                    =>  $data['driver_age']
            );
         
            if(is_null($vehiclePrice))
            {
                $discountPercentage=0;
                $vehiclePrice=$Vehicle->calculatePrice($argument,$data['booking_form'],$discountPercentage,false);
            }
            
            $price['vehicle']['sum']['gross']['value']=$vehiclePrice['price']['sum']['gross']['value'];        
            $price['vehicle']['sum']['gross']['format']=$vehiclePrice['price']['sum']['gross']['format'];  
            $price['vehicle']['sum']['net']['value']=$vehiclePrice['price']['sum']['net']['value'];        
            $price['vehicle']['sum']['net']['format']=$vehiclePrice['price']['sum']['net']['format'];           
                    
            $price['deposit']['sum']['gross']['value']=$vehiclePrice['price']['deposit']['gross']['value'];   
            $price['deposit']['sum']['gross']['format']=$vehiclePrice['price']['deposit']['gross']['format'];
            $price['deposit']['sum']['net']['value']=$vehiclePrice['price']['deposit']['net']['value'];   
            $price['deposit']['sum']['net']['format']=$vehiclePrice['price']['deposit']['net']['format'];            
 
            $price['initial']['sum']['gross']['value']=$vehiclePrice['price']['initial']['gross']['value']; 
            $price['initial']['sum']['gross']['format']=$vehiclePrice['price']['initial']['gross']['format'];          
            $price['initial']['sum']['net']['value']=$vehiclePrice['price']['initial']['net']['value']; 
            $price['initial']['sum']['net']['format']=$vehiclePrice['price']['initial']['net']['format'];   
			
            $price['one_way']['sum']['gross']['value']=$vehiclePrice['price']['one_way']['gross']['value']; 
            $price['one_way']['sum']['gross']['format']=$vehiclePrice['price']['one_way']['gross']['format'];          
            $price['one_way']['sum']['net']['value']=$vehiclePrice['price']['one_way']['net']['value']; 
            $price['one_way']['sum']['net']['format']=$vehiclePrice['price']['one_way']['net']['format'];   
            
            $price['delivery']['sum']['gross']['value']=$vehiclePrice['price']['delivery']['gross']['value']; 
            $price['delivery']['sum']['gross']['format']=$vehiclePrice['price']['delivery']['gross']['format']; 
            $price['delivery']['sum']['net']['value']=$vehiclePrice['price']['delivery']['net']['value']; 
            $price['delivery']['sum']['net']['format']=$vehiclePrice['price']['delivery']['net']['format']; 

            $price['delivery_return']['sum']['gross']['value']=$vehiclePrice['price']['delivery_return']['gross']['value'];     
            $price['delivery_return']['sum']['gross']['format']=$vehiclePrice['price']['delivery_return']['gross']['format'];  
            $price['delivery_return']['sum']['net']['value']=$vehiclePrice['price']['delivery_return']['net']['value'];     
            $price['delivery_return']['sum']['net']['format']=$vehiclePrice['price']['delivery_return']['net']['format'];  
            
            $price['after_business_hour_pickup']['sum']['gross']['value']=$vehiclePrice['price']['after_business_hour_pickup']['gross']['value'];
            $price['after_business_hour_pickup']['sum']['gross']['format']=$vehiclePrice['price']['after_business_hour_pickup']['gross']['format']; 
            $price['after_business_hour_pickup']['sum']['net']['value']=$vehiclePrice['price']['after_business_hour_pickup']['net']['value'];
            $price['after_business_hour_pickup']['sum']['net']['format']=$vehiclePrice['price']['after_business_hour_pickup']['net']['format']; 
			
            $price['after_business_hour_return']['sum']['gross']['value']=$vehiclePrice['price']['after_business_hour_return']['gross']['value'];
            $price['after_business_hour_return']['sum']['gross']['format']=$vehiclePrice['price']['after_business_hour_return']['gross']['format']; 
            $price['after_business_hour_return']['sum']['net']['value']=$vehiclePrice['price']['after_business_hour_return']['net']['value'];
            $price['after_business_hour_return']['sum']['net']['format']=$vehiclePrice['price']['after_business_hour_return']['net']['format']; 
        
            $price['customer_pickup_location']['sum']['gross']['value']=$vehiclePrice['price']['customer_pickup_location']['gross']['value'];
            $price['customer_pickup_location']['sum']['gross']['format']=$vehiclePrice['price']['customer_pickup_location']['gross']['format']; 
            $price['customer_pickup_location']['sum']['net']['value']=$vehiclePrice['price']['customer_pickup_location']['net']['value'];
            $price['customer_pickup_location']['sum']['net']['format']=$vehiclePrice['price']['customer_pickup_location']['net']['format']; 
			
            $price['customer_return_location']['sum']['gross']['value']=$vehiclePrice['price']['customer_return_location']['gross']['value'];
            $price['customer_return_location']['sum']['gross']['format']=$vehiclePrice['price']['customer_return_location']['gross']['format']; 
            $price['customer_return_location']['sum']['net']['value']=$vehiclePrice['price']['customer_return_location']['net']['value'];
            $price['customer_return_location']['sum']['net']['format']=$vehiclePrice['price']['customer_return_location']['net']['format']; 
			
            if($hideFee)
            {
                $price['vehicle']['sum']['gross']['value']+=$price['deposit']['sum']['gross']['value']+$price['initial']['sum']['gross']['value']+$price['one_way']['sum']['gross']['value']+$price['delivery']['sum']['gross']['value']+$price['delivery_return']['sum']['gross']['value']+$price['after_business_hour_pickup']['sum']['gross']['value']+$price['after_business_hour_return']['sum']['gross']['value']+$price['customer_pickup_location']['sum']['gross']['value']+$price['customer_return_location']['sum']['gross']['value'];
                $price['vehicle']['sum']['gross']['format']=CRBSPrice::format($price['vehicle']['sum']['gross']['value'],CRBSCurrency::getFormCurrency());
                
                $price['vehicle']['sum']['net']['value']+=$price['deposit']['sum']['net']['value']+$price['initial']['sum']['net']['value']+$price['one_way']['sum']['net']['value']+$price['delivery']['sum']['net']['value']+$price['delivery_return']['sum']['net']['value']+$price['after_business_hour_pickup']['sum']['net']['value']+$price['after_business_hour_return']['sum']['net']['value']+$price['customer_pickup_location']['sum']['net']['value']+$price['customer_return_location']['sum']['net']['value'];
                $price['vehicle']['sum']['net']['format']=CRBSPrice::format($price['vehicle']['sum']['net']['value'],CRBSCurrency::getFormCurrency());
            }
        }
        
        /***/

        $bookingExtra=$BookingExtra->validate($data,$data['booking_form'],$taxRateDictionary);      
        foreach($bookingExtra as $value)
        {
            $price['booking_extra']['sum']['gross']['value']+=$value['sum_gross'];
            $price['booking_extra']['sum']['net']['value']+=$value['sum_net'];
        }
        
        $price['booking_extra']['sum']['gross']['format']=CRBSPrice::format($price['booking_extra']['sum']['gross']['value'],CRBSCurrency::getFormCurrency());
        $price['booking_extra']['sum']['net']['format']=CRBSPrice::format($price['booking_extra']['sum']['net']['value'],CRBSCurrency::getFormCurrency());
        
        /***/
      
        if($hideFee)
        {
            $price['total']['sum']['gross']['value']=$price['vehicle']['sum']['gross']['value']+$price['booking_extra']['sum']['gross']['value'];
            $price['total']['sum']['net']['value']=$price['vehicle']['sum']['net']['value']+$price['booking_extra']['sum']['net']['value'];
        }
        else 
        {
            $price['total']['sum']['gross']['value']=$price['vehicle']['sum']['gross']['value']+$price['deposit']['sum']['gross']['value']+$price['initial']['sum']['gross']['value']+$price['one_way']['sum']['gross']['value']+$price['after_business_hour_pickup']['sum']['gross']['value']+$price['after_business_hour_return']['sum']['gross']['value']+$price['delivery']['sum']['gross']['value']+$price['delivery_return']['sum']['gross']['value']+$price['customer_pickup_location']['sum']['gross']['value']+$price['customer_return_location']['sum']['gross']['value']+$price['booking_extra']['sum']['gross']['value'];
            $price['total']['sum']['net']['value']=$price['vehicle']['sum']['net']['value']+$price['deposit']['sum']['net']['value']+$price['initial']['sum']['net']['value']+$price['one_way']['sum']['net']['value']+$price['after_business_hour_pickup']['sum']['net']['value']+$price['after_business_hour_return']['sum']['net']['value']+$price['delivery']['sum']['net']['value']+$price['delivery_return']['sum']['net']['value']+$price['customer_pickup_location']['sum']['net']['value']+$price['customer_return_location']['sum']['net']['value']+$price['booking_extra']['sum']['net']['value'];
        }
            
        $price['total']['tax']['value']=$price['total']['sum']['gross']['value']-$price['total']['sum']['net']['value'];
        $price['total']['tax']['format']=CRBSPrice::format($price['total']['tax']['value'],CRBSCurrency::getFormCurrency());
        
        $price['total']['sum']['gross']['format']=CRBSPrice::format($price['total']['sum']['gross']['value'],CRBSCurrency::getFormCurrency());
        $price['total']['sum']['net']['format']=CRBSPrice::format($price['total']['sum']['net']['value'],CRBSCurrency::getFormCurrency());

        $price['pay']=$price['total'];
        
        if(CRBSBookingFormHelper::isPaymentDepositEnable($data['booking_form']))
        {
			$pickupLocationId=$data['pickup_location_id'];
			
			if((int)$data['booking_form']['dictionary']['location'][$pickupLocationId]['meta']['payment_deposit_type']===1)
				$value=$data['booking_form']['dictionary']['location'][$pickupLocationId]['meta']['payment_deposit_type_fixed_value'];
			else $value=$price['pay']['sum']['gross']['value']*($data['booking_form']['dictionary']['location'][$pickupLocationId]['meta']['payment_deposit_type_percentage_value']/100);
			
            $price['pay']['sum']['gross']['value']=$value;
            $price['pay']['sum']['gross']['format']=CRBSPrice::format($price['pay']['sum']['gross']['value'],CRBSCurrency::getFormCurrency());
        }
		
		$balance=$price['total']['sum']['gross']['value']-$price['pay']['sum']['gross']['value'];
		if($balance<0) $balance=0;
		
		$price['balance']['sum']['gross']['value']=$balance;
		$price['balance']['sum']['gross']['format']=CRBSPrice::format($price['balance']['sum']['gross']['value'],CRBSCurrency::getFormCurrency());		
		
        return($price);
    }
        
    /**************************************************************************/
    
    function createResponse($response)
    {
        echo json_encode($response);
        exit;             
    }
    
    /**************************************************************************/
    
    function createBilling($bookingId)
    {
        $billing=array();
        
        if(($booking=$this->getBooking($bookingId))===false) return($billing);

        /***/
        
        $rentalDayCount=CRBSBookingHelper::calculateRentalDayCount($booking['meta']['pickup_date'],$booking['meta']['pickup_time'],$booking['meta']['return_date'],$booking['meta']['return_time'],$booking['meta'],$booking['meta']['billing_type']);
        
        $period=CRBSBookingHelper::calculateRentalPeriod($booking['meta']['pickup_date'],$booking['meta']['pickup_time'],$booking['meta']['return_date'],$booking['meta']['return_time'],$booking['meta'],$booking['meta']['billing_type']);
        
        if($booking['meta']['price_initial_value']>0)
        {
            $billing['detail'][]=array
            (
                'type'                                                          =>  'initial',
                'name'                                                          =>  __('Initial fee','car-rental-booking-system'),
                'unit'                                                          =>  __('Item','car-rental-booking-system'),  
                'quantity'                                                      =>  1,
                'price_net'                                                     =>  $booking['meta']['price_initial_value'],
                'value_net'                                                     =>  $booking['meta']['price_initial_value'],                   
                'tax_value'                                                     =>  $booking['meta']['price_initial_tax_rate_value'],
                'value_gross'                                                   =>  CRBSPrice::calculateGross($booking['meta']['price_initial_value'],0,$booking['meta']['price_initial_tax_rate_value'])
            );
        }     
		
        if(in_array($booking['meta']['billing_type'],array(2,3)))
        {      
            $valueNet=$booking['meta']['price_rental_day_value']*$period['day'];
            if($valueNet>0.00)
            {
                $billing['detail'][]=array
                (
                    'type'                                                      =>  'rental_per_day',
                    'name'                                                      =>  __('Rental fee per day','car-rental-booking-system'),
                    'unit'                                                      =>  __('No. of days','car-rental-booking-system'),  
                    'quantity'                                                  =>  $period['day'],
                    'price_net'                                                 =>  $booking['meta']['price_rental_day_value'],
                    'value_net'                                                 =>  $valueNet,                   
                    'tax_value'                                                 =>  $booking['meta']['price_rental_tax_rate_value'],
                    'value_gross'                                               =>  CRBSPrice::calculateGross($valueNet,0,$booking['meta']['price_rental_tax_rate_value'])
                );
            }            
        } 
		
        if(in_array($booking['meta']['billing_type'],array(1,3)))
        {
            $valueNet=$booking['meta']['price_rental_hour_value']*$period['hour'];
            if($valueNet>0.00)
            {
                $billing['detail'][]=array
                (
                    'type'                                                      =>  'rental_per_hour',
                    'name'                                                      =>  __('Rental fee per hour','car-rental-booking-system'),
                    'unit'                                                      =>  __('No. of hours','car-rental-booking-system'),  
                    'quantity'                                                  =>  $period['hour'],
                    'price_net'                                                 =>  $booking['meta']['price_rental_hour_value'],
                    'value_net'                                                 =>  $valueNet,                   
                    'tax_value'                                                 =>  $booking['meta']['price_rental_tax_rate_value'],
                    'value_gross'                                               =>  CRBSPrice::calculateGross($valueNet,0,$booking['meta']['price_rental_tax_rate_value'])
                );
            }
        }
		
        if(($booking['meta']['price_delivery_value']>0) && ($booking['meta']['delivery_distance']>0))
        {
            $valueNet=$booking['meta']['price_delivery_value']*$booking['meta']['delivery_distance'];
            
            $billing['detail'][]=array
            (
                'type'                                                          =>  'delivery',
                'name'                                                          =>  __('Delivery fee','car-rental-booking-system'),
                'unit'                                                          =>  __('Kilometers','car-rental-booking-system'),  
                'quantity'                                                      =>  $booking['meta']['delivery_distance'],
                'price_net'                                                     =>  $booking['meta']['price_delivery_value'],
                'value_net'                                                     =>  $valueNet,                   
                'tax_value'                                                     =>  $booking['meta']['price_delivery_tax_rate_value'],
                'value_gross'                                                   =>  CRBSPrice::calculateGross($valueNet,0,$booking['meta']['price_delivery_tax_rate_value'])
            );
        }  
        
        if(($booking['meta']['price_delivery_return_value']>0) && ($booking['meta']['delivery_return_distance']>0))
        {
            $valueNet=$booking['meta']['price_delivery_return_value']*$booking['meta']['delivery_return_distance'];
            
            $billing['detail'][]=array
            (
                'type'                                                          =>  'delivery',
                'name'                                                          =>  __('Delivery return fee','car-rental-booking-system'),
                'unit'                                                          =>  __('Kilometers','car-rental-booking-system'),  
                'quantity'                                                      =>  $booking['meta']['delivery_return_distance'],
                'price_net'                                                     =>  $booking['meta']['price_delivery_return_value'],
                'value_net'                                                     =>  $valueNet,                   
                'tax_value'                                                     =>  $booking['meta']['price_delivery_return_tax_rate_value'],
                'value_gross'                                                   =>  CRBSPrice::calculateGross($valueNet,0,$booking['meta']['price_delivery_return_tax_rate_value'])
            );
        }   
        
        if($booking['meta']['price_deposit_value']>0)
        {
            $billing['detail'][]=array
            (
                'type'                                                          =>  'deposit',
                'name'                                                          =>  __('Deposit fee','car-rental-booking-system'),
                'unit'                                                          =>  __('Item','car-rental-booking-system'),  
                'quantity'                                                      =>  1,
                'price_net'                                                     =>  $booking['meta']['price_deposit_value'],
                'value_net'                                                     =>  $booking['meta']['price_deposit_value'],                   
                'tax_value'                                                     =>  $booking['meta']['price_deposit_tax_rate_value'],
                'value_gross'                                                   =>  CRBSPrice::calculateGross($booking['meta']['price_deposit_value'],0,$booking['meta']['price_deposit_tax_rate_value'])
            );
        }
		
        if($booking['meta']['price_one_way_value']>0)
        {
            $billing['detail'][]=array
            (
                'type'                                                          =>  'one_way',
                'name'                                                          =>  __('One way fee','car-rental-booking-system'),
                'unit'                                                          =>  __('Item','car-rental-booking-system'),  
                'quantity'                                                      =>  1,
                'price_net'                                                     =>  $booking['meta']['price_one_way_value'],
                'value_net'                                                     =>  $booking['meta']['price_one_way_value'],                   
                'tax_value'                                                     =>  $booking['meta']['price_one_way_tax_rate_value'],
                'value_gross'                                                   =>  CRBSPrice::calculateGross($booking['meta']['price_one_way_value'],0,$booking['meta']['price_one_way_tax_rate_value'])
            );
        }  
		
        if($booking['meta']['price_after_business_hour_pickup_value']>0)
        {
            $billing['detail'][]=array
            (
                'type'                                                          =>  'after_business_hour_pickup',
                'name'                                                          =>  __('After hours pickup fee','car-rental-booking-system'),
                'unit'                                                          =>  __('Item','car-rental-booking-system'),  
                'quantity'                                                      =>  1,
                'price_net'                                                     =>  $booking['meta']['price_after_business_hour_pickup_value'],
                'value_net'                                                     =>  $booking['meta']['price_after_business_hour_pickup_value'],                   
                'tax_value'                                                     =>  $booking['meta']['price_after_business_hour_pickup_tax_rate_value'],
                'value_gross'                                                   =>  CRBSPrice::calculateGross($booking['meta']['price_after_business_hour_pickup_value'],0,$booking['meta']['price_after_business_hour_pickup_tax_rate_value'])
            );
        }  

        if($booking['meta']['price_after_business_hour_return_value']>0)
        {
            $billing['detail'][]=array
            (
                'type'                                                          =>  'after_business_hour_return',
                'name'                                                          =>  __('After hours return fee','car-rental-booking-system'),
                'unit'                                                          =>  __('Item','car-rental-booking-system'),  
                'quantity'                                                      =>  1,
                'price_net'                                                     =>  $booking['meta']['price_after_business_hour_return_value'],
                'value_net'                                                     =>  $booking['meta']['price_after_business_hour_return_value'],                   
                'tax_value'                                                     =>  $booking['meta']['price_after_business_hour_return_tax_rate_value'],
                'value_gross'                                                   =>  CRBSPrice::calculateGross($booking['meta']['price_after_business_hour_return_value'],0,$booking['meta']['price_after_business_hour_return_tax_rate_value'])
            );
        }     
		
        if($booking['meta']['price_customer_pickup_location_value']>0)
        {
            $billing['detail'][]=array
            (
                'type'                                                          =>  'customer_pickup_location',
                'name'                                                          =>  __('Customer pickup location fee','car-rental-booking-system'),
                'unit'                                                          =>  __('Item','car-rental-booking-system'),  
                'quantity'                                                      =>  1,
                'price_net'                                                     =>  $booking['meta']['price_customer_pickup_location_value'],
                'value_net'                                                     =>  $booking['meta']['price_customer_pickup_location_value'],                   
                'tax_value'                                                     =>  $booking['meta']['price_customer_pickup_location_tax_rate_value'],
                'value_gross'                                                   =>  CRBSPrice::calculateGross($booking['meta']['price_customer_pickup_location_value'],0,$booking['meta']['price_customer_pickup_location_tax_rate_value'])
            );
        }   
		
        if($booking['meta']['price_customer_return_location_value']>0)
        {
            $billing['detail'][]=array
            (
                'type'                                                          =>  'customer_return_location',
                'name'                                                          =>  __('Customer return location fee','car-rental-booking-system'),
                'unit'                                                          =>  __('Item','car-rental-booking-system'),  
                'quantity'                                                      =>  1,
                'price_net'                                                     =>  $booking['meta']['price_customer_return_location_value'],
                'value_net'                                                     =>  $booking['meta']['price_customer_return_location_value'],                   
                'tax_value'                                                     =>  $booking['meta']['price_customer_return_location_tax_rate_value'],
                'value_gross'                                                   =>  CRBSPrice::calculateGross($booking['meta']['price_customer_return_location_value'],0,$booking['meta']['price_customer_return_location_tax_rate_value'])
            );
        }   		
		
        /***/
        
        if(is_array($booking['meta']['booking_extra']))
        {
            foreach($booking['meta']['booking_extra'] as $value)
            {
                $priceNet=$value['price'];
                $quantity=$value['quantity'];
                
                if($value['price_type']==2)
                    $priceNet*=$rentalDayCount;
                        
                $valueNet=$priceNet*$quantity;

                $billing['detail'][]=array
                (
                    'type'                                                      =>  'booking_extra',
                    'name'                                                      =>  $value['name'],
                    'unit'                                                      =>  __('Item','car-rental-booking-system'),    
                    'quantity'                                                  =>  $quantity,
                    'price_net'                                                 =>  $priceNet,
                    'value_net'                                                 =>  $valueNet,                   
                    'tax_value'                                                 =>  $value['tax_rate_value'],
                    'value_gross'                                               =>  CRBSPrice::calculateGross($valueNet,0,$value['tax_rate_value'])
                );                   
            }
        }
        
        /***/
        
        $billing['summary']['duration']=0;
        $billing['summary']['distance']=0;
        $billing['summary']['value_net']=0;
        $billing['summary']['value_gross']=0;
        
        foreach($billing['detail'] as $value)
        {
            $billing['summary']['value_net']+=$value['value_net'];
            $billing['summary']['value_gross']+=$value['value_gross'];
        }

        foreach($billing['summary'] as $aIndex=>$aValue)
        {
            if(in_array($aIndex,array('value_net','value_gross')))
                $billing['summary'][$aIndex]=number_format(round($aValue,2),2,'.','');
        }      
        
        /***/
		
        if((int)$booking['meta']['payment_deposit_type']!==0)
		{
			if((int)$booking['meta']['payment_deposit_type']===1)
				$value=$booking['meta']['payment_deposit_type_fixed_value'];
			else $value=number_format(round($billing['summary']['value_gross']*($booking['meta']['payment_deposit_type_percentage_value']/100),2),2,'.','');
				
			$billing['summary']['pay']=$value;
		}
		else $billing['summary']['pay']=$billing['summary']['value_gross'];
        
		/***/
		
        foreach($billing['detail'] as $aIndex=>$aValue)
        {
            foreach($aValue as $bIndex=>$bValue)
            {
                if(in_array($bIndex,array('price_net','value_net','value_gross','tax_value')))
                    $billing['detail'][$aIndex][$bIndex]=number_format(round($bValue,2),2,'.','');
            }
        }

        /***/
        
        return($billing);
    }
    
    /**************************************************************************/
    
    function sendEmail($bookingId,$emailAccountId,$template,$recipient,$subject)
    {
        $Email=new CRBSEmail();
        $EmailAccount=new CRBSEmailAccount();
        
        if(($booking=$this->getBooking($bookingId))===false) return(false);
        
        if(($emailAccount=$EmailAccount->getDictionary(array('email_account_id'=>$emailAccountId)))===false) return(false);
        
        if(!isset($emailAccount[$emailAccountId])) return(false);
        
        $data=array();
        
        $emailAccount=$emailAccount[$emailAccountId];
        
        /***/
        
        global $crbs_phpmailer;
        
        $crbs_phpmailer['sender_name']=$emailAccount['meta']['sender_name'];
        $crbs_phpmailer['sender_email_address']=$emailAccount['meta']['sender_email_address'];
        
        $crbs_phpmailer['smtp_auth_enable']=$emailAccount['meta']['smtp_auth_enable'];
        $crbs_phpmailer['smtp_auth_debug_enable']=$emailAccount['meta']['smtp_auth_debug_enable'];
        
        $crbs_phpmailer['smtp_auth_username']=$emailAccount['meta']['smtp_auth_username'];
        $crbs_phpmailer['smtp_auth_password']=$emailAccount['meta']['smtp_auth_password'];
        
        $crbs_phpmailer['smtp_auth_host']=$emailAccount['meta']['smtp_auth_host'];
        $crbs_phpmailer['smtp_auth_port']=$emailAccount['meta']['smtp_auth_port'];
        
        $crbs_phpmailer['smtp_auth_secure_connection_type']=$emailAccount['meta']['smtp_auth_secure_connection_type'];
        
        /***/
        
        if(in_array($template,array('booking_new_admin','booking_new_client','booking_change_status')))
        {
            $templateFile='email_booking.php';
            
            $booking['booking_title']=$booking['post']->post_title;
            if($template==='booking_new_admin')
                $booking['booking_title']='<a href="'.admin_url('post.php?post='.(int)$booking['post']->ID.'&action=edit').'">'.$booking['booking_title'].'</a>';        
        }
		
        /***/
        
        $data['style']=$Email->getEmailStyle();
        
        $data['booking']=$booking;
        $data['booking']['billing']=$this->createBilling($bookingId);
        
        /***/
                
        $Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.$templateFile);
        $body=$Template->output();
        
        /***/
		
        $Email->send($recipient,$subject,$body);
    }
    
    /**************************************************************************/
    
    function getCouponCodeUsageCount($couponCode)
    {
        $argument=array
		(
			'post_type'															=>	self::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1,
            'meta_key'                                                          =>  PLUGIN_CRBS_CONTEXT.'_coupon_code',
            'meta_value'                                                        =>  $couponCode,
            'meta_compare'                                                      =>  '='
		);
		
        $query=new WP_Query($argument);
		if($query===false) return(false); 
        
        return($query->found_posts);
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/