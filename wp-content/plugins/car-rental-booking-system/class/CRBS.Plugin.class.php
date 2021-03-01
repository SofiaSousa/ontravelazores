<?php

/******************************************************************************/
/******************************************************************************/

class CRBSPlugin
{
    /**************************************************************************/
    
    private $optionDefault;
    private $libraryDefault;

    /**************************************************************************/	
	
	function __construct()
	{
        /***/
        
		$this->libraryDefault=array
		(
			'script'															=>	array
			(
				'use'															=>	1,
				'inc'															=>	true,
				'path'															=>	PLUGIN_CRBS_SCRIPT_URL,
				'file'															=>	'',
				'in_footer'														=>	true,
				'dependencies'													=>	array('jquery'),
			),
			'style'																=>	array
			(
				'use'															=>	1,
				'inc'															=>	true,
				'path'															=>	PLUGIN_CRBS_STYLE_URL,
				'file'															=>	'',
				'dependencies'													=>	array()
			)
		);
        
        /***/
        
        $this->optionDefault=array
        (
            'billing_type'                                                      =>  2,
            'logo'                                                              =>  '',
            'google_map_api_key'                                                =>  '',
			'google_map_duplicate_script_remove'                                =>  '0',
            'currency'                                                          =>  'USD',
            'date_format'                                                       =>  'd-m-Y',
            'time_format'                                                       =>  'G:i',
            'sender_default_email_account_id'                                   =>  '-1',
            'coupon_generate_count'                                             =>  '1',
            'coupon_generate_usage_limit'                                       =>  '1',
            'coupon_generate_discount_percentage'                               =>  '0',
            'coupon_generate_discount_fixed'                                    =>  '0',
            'coupon_generate_active_date_start'                                 =>  '',
            'coupon_generate_active_date_stop'                                  =>  '',
            'attachment_woocommerce_email'                                      =>  '',
            'geolocation_server_id'                                             =>  '1',
            'geolocation_server_id_3_api_key'                                   =>  '',
            'email_report_status'                                               =>  '0',
            'email_report_sender_email_account_id'                              =>  '-1',
            'email_report_recipient_email_address'                              =>  '',
            'currency_exchange_rate'                                            =>  array(),
            'fixer_io_api_key'                                                  =>  '',
			'booking_status_payment_success'									=>	'-1',
			'booking_status_synchronization'									=>	'1',
			'payment_stripe_webhook_endpoint_id'								=>	''
        );
        
        /***/
	}
	
	/**************************************************************************/
	
	private function prepareLibrary()
	{
		$this->library=array
		(
			'script'															=>	array
			(
				'jquery-ui-core'												=>	array
				(
					'path'														=>	''
				),
				'jquery-ui-tabs'												=>	array
				(
                    'use'                                                       =>  3,
					'path'														=>	''
				),
				'jquery-ui-button'												=>	array
				(
					'path'														=>	''
				),
 				'jquery-ui-slider'  											=>	array
				(
					'path'														=>	''
				),    
				'jquery-ui-selectmenu'											=>	array
				(
                    'use'                                                       =>  2,
					'path'														=>	''
				),               
				'jquery-ui-sortable'                                            =>	array
				(
					'path'														=>	''
				),
				'jquery-ui-widget'                                            =>	array
				(
                    'use'                                                       =>  2,
					'path'														=>	''
				),
				'jquery-ui-datepicker'                                          =>	array
				(
                    'use'                                                       =>  3,
					'path'														=>	''
				), 
				'jquery-colorpicker'											=>	array
				(
					'file'														=>	'jquery.colorpicker.js'
				),
				'jquery-actual'                                                 =>	array
				(
                    'use'                                                       =>  2,
					'file'														=>	'jquery.actual.min.js'
				),
				'jquery-fileupload'                                             =>	array
				(
                    'use'                                                       =>  2,
                    'file'														=>	'jquery.fileupload.js'
				),  
				'jquery-timepicker'                                             =>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.timepicker.min.js'
				),
				'jquery-dropkick'												=>	array
				(
					'file'														=>	'jquery.dropkick.min.js'
				),
				'jquery-qtip'													=>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.qtip.min.js'
				),
				'jquery-blockUI'												=>	array
				(
					'file'														=>	'jquery.blockUI.js'
				),
				'resizesensor'													=>	array
				(
					'use'														=>	2,
					'file'														=>	'ResizeSensor.min.js'
				),				
				'jquery-theia-sticky-sidebar'									=>	array
				(
					'use'														=>	2,
					'file'														=>	'jquery.theia-sticky-sidebar.min.js'
				),
				'jquery-table'                                                  =>	array
				(
					'file'														=>	'jquery.table.js'
				),	
				'jquery-infieldlabel'											=>	array
				(
					'file'														=>	'jquery.infieldlabel.min.js'
				),
 				'jquery-scrollTo'                                               =>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.scrollTo.min.js'
				),  
 				'clipboard'                                                     =>	array
				(
					'file'														=>	'clipboard.min.js'
				),       
				'jquery-themeOption'											=>	array
				(
					'file'														=>	'jquery.themeOption.js'
				),
				'jquery-themeOptionElement'										=>	array
				(
					'file'														=>	'jquery.themeOptionElement.js'
				),
				'crbs-helper'                                                    =>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'CRBS.Helper.class.js'
				),
				'crbs-admin'                                                     =>	array
				(
					'file'														=>	'admin.js'
				),
				'crbs-booking-form-admin'                                       =>	array
				(
                    'use'                                                       =>  1,
					'file'														=>	'jquery.CRBSBookingFormAdmin.js'
				),
				'crbs-geofence-admin'											=>	array
				(
                    'use'                                                       =>  1,
					'file'														=>	'jquery.CRBSGeofenceAdmin.js'
				),	
				'crbs-booking-form'                                              =>	array
				(
                    'use'                                                       =>  2,
					'file'														=>	'jquery.CRBSBookingForm.js'
				),	                
				'google-map'        											=>	array
				(
					'use'														=>	3,
					'path'														=>	'',
					'file'														=>	add_query_arg(array('key'=>urlencode(CRBSOption::getOption('google_map_api_key')),'libraries'=>'places,drawing'),'//maps.google.com/maps/api/js'),
				),	
			),
			'style'																=>	array
			(
				'google-font-open-sans'											=>	array
				(
					'path'														=>	'', 
					'file'														=>	add_query_arg(array('family'=>urlencode('Open Sans:300,300i,400,400i,600,600i,700,700i,800,800i'),'subset'=>'cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese'),'//fonts.googleapis.com/css')
				),
				'google-font-lato'                                              =>	array
				(
                    'use'                                                       =>  2,
					'path'														=>	'', 
					'file'														=>	add_query_arg(array('family'=>urlencode('Lato:300,400,700'),'subset'=>'latin-ext'),'//fonts.googleapis.com/css')
				),
				'jquery-ui'														=>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.ui.min.css',
				),
				'jquery-qtip'   												=>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.qtip.min.css',
				),
				'jquery-dropkick'   											=>	array
				(
					'file'														=>	'jquery.dropkick.css',
				),
				'jquery-dropkick-rtl'											=>	array
				(				
					'inc'														=>	false,
					'file'														=>	'jquery.dropkick.rtl.css',
				),
				'jquery-colorpicker'   											=>	array
				(
					'file'														=>	'jquery.colorpicker.css',
				),
				'jquery-timepicker'   											=>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.timepicker.min.css',
				),
				'jquery-themeOption'											=>	array
				(
					'file'														=>	'jquery.themeOption.css'
				),
				'jquery-themeOption-rtl'										=>	array
				(
					'inc'														=>	false,
					'file'														=>	'jquery.themeOption.rtl.css',
				),
				'mfds-jquery-themeOption-overwrite'								=>	array
				(
					'file'														=>	'jquery.themeOption.overwrite.css'
				),
				'crbs-public'        											=>	array
				(
                    'use'                                                       =>  2,
					'file'														=>	'public.css'
				),
				'crbs-public-rtl'                                               =>	array
				(
                    'use'                                                       =>  2,
					'inc'														=>	false,
					'file'														=>	'public.rtl.css'
				)
			)
		);		
	}	
	
	/**************************************************************************/
	
	private function addLibrary($type,$use)
	{
		if(CRBSFile::fileExist(CRBSFile::getMultisiteBlogCSS()))
		{
            $this->library['style']['crbs-public-booking-form-']=array
			(
                'use'                                                           =>	2,
				'path'                                                          =>	'',
				'file'                                                          =>	CRBSFile::getMultisiteBlogCSS('url')
			);
		}
        
		foreach($this->library[$type] as $index=>$value)
			$this->library[$type][$index]=array_merge($this->libraryDefault[$type],$value);
        
		foreach($this->library[$type] as $index=>$data)
		{
			if(!$data['inc']) continue;
			
			if($data['use']!=3)
			{
				if($data['use']!=$use) continue;
			}			
			
			if($type=='script')
			{
				wp_enqueue_script($index,$data['path'].$data['file'],$data['dependencies'],rand(1,10000),$data['in_footer']);
			}
			else 
			{
				wp_enqueue_style($index,$data['path'].$data['file'],$data['dependencies'],rand(1,10000));
			}
		}
	}
	
	/**************************************************************************/
	
	public function pluginActivation()
	{    
        CRBSOption::createOption();
        
        $optionSave=array();
        $optionCurrent=CRBSOption::getOptionObject();
             
		foreach($this->optionDefault as $index=>$value)
		{
			if(!array_key_exists($index,$optionCurrent))
				$optionSave[$index]=$value;
		}
		
		$optionSave=array_merge((array)$optionSave,$optionCurrent);
		foreach($optionSave as $index=>$value)
		{
			if(!array_key_exists($index,$this->optionDefault))
				unset($optionSave[$index]);
		}
        
        CRBSOption::resetOption();
        CRBSOption::updateOption($optionSave);
        
        $BookingFormStyle=new CRBSBookingFormStyle();
        $BookingFormStyle->createCSSFile();
        
        /***/
        
        $Validation=new CRBSValidation();
        
		$argument=array
		(
			'post_type'															=> CRBSVehicle::getCPTName(),
			'post_status'														=>	'any',
			'posts_per_page'													=>	-1
		);
        
        $query=new WP_Query($argument);
		if($query!==false)
        {
            while($query->have_posts())
            {
                $query->the_post();

                $price=get_post_meta(get_the_ID(),PLUGIN_CRBS_CONTEXT.'_price_rental_hour_value',true);
                
		        if($Validation->isPrice($price)) continue;
                
                $meta=CRBSPostMeta::getPostMeta(get_the_ID());
                
                $data=array
                (
                    'price_rental_hour_value'                                   =>  0.00,
                    'price_rental_day_value'                                    =>  $meta['price_rental_value']
                );
                
                foreach($data as $index=>$value)
                    CRBSPostMeta::updatePostMeta(get_the_ID(),$index,$value);
                
                CRBSPostMeta::removePostMeta(get_the_ID(),'price_rental_value');
            }
        } 
        
        /***/
        
		$argument=array
		(
			'post_type'															=> CRBSPriceRule::getCPTName(),
			'post_status'														=>	'any',
			'posts_per_page'													=>	-1
		);
        
        $query=new WP_Query($argument);
		if($query!==false)
        {
            while($query->have_posts())
            {
                $query->the_post();

                $price=get_post_meta(get_the_ID(),PLUGIN_CRBS_CONTEXT.'_price_rental_hour_value',true);
                
                if($Validation->isPrice($price)) continue;
                
                $meta=CRBSPostMeta::getPostMeta(get_the_ID());
                
                $data=array
                (
                    'price_rental_hour_value'                                   =>  0.00,
                    'price_rental_day_value'                                    =>  $meta['price_rental_value']
                );
                
                foreach($data as $index=>$value)
                    CRBSPostMeta::updatePostMeta(get_the_ID(),$index,$value);
                
                CRBSPostMeta::removePostMeta(get_the_ID(),'price_rental_value');
            }
        } 
        
        /***/
        
		$argument=array
		(
			'post_type'															=> CRBSBooking::getCPTName(),
			'post_status'														=>	'any',
			'posts_per_page'													=>	-1
		);
        
        $query=new WP_Query($argument);
		if($query!==false)
        {
            while($query->have_posts())
            {
                $query->the_post();

                $price=get_post_meta(get_the_ID(),PLUGIN_CRBS_CONTEXT.'_price_rental_hour_value',true);
                
                if($Validation->isPrice($price)) continue;
                
                $meta=CRBSPostMeta::getPostMeta(get_the_ID());
                
                $data=array
                (
                    'billing_type'                                              =>  2,
                    'price_rental_hour_value'                                   =>  0.00,
                    'price_rental_day_value'                                    =>  $meta['price_rental_value']
                );
                
                foreach($data as $index=>$value)
                    CRBSPostMeta::updatePostMeta(get_the_ID(),$index,$value);
                
                CRBSPostMeta::removePostMeta(get_the_ID(),'price_rental_value');
            }
        } 
		
        /***/
	}
	
	/**************************************************************************/
	
	public function pluginDeactivation()
	{

	}
    
	/**************************************************************************/
	
	public function init()
	{  
        $Booking=new CRBSBooking();
        $BookingForm=new CRBSBookingForm();
        $BookingExtra=new CRBSBookingExtra();

        $Vehicle=new CRBSVehicle();
        $VehicleAttribute=new CRBSVehicleAttribute();
        
        $PriceRule=new CRBSPriceRule();
        $Location=new CRBSLocation();
        
        $Coupon=new CRBSCoupon();
        
        $TaxRate=new CRBSTaxRate();
        $EmailAccount=new CRBSEmailAccount();
		$Geofence=new CRBSGeofence();
		
		$ExchangeRateProvider=new CRBSExchangeRateProvider();
        
		$LogManager=new CRBSLogManager();
		
        $Booking->init();
        $BookingForm->init();
        $BookingExtra->init();
        
        $Vehicle->init();
        $VehicleAttribute->init();
        
        $PriceRule->init();
        
        $Location->init();
        
        $Coupon->init();
        
        $TaxRate->init();
        $EmailAccount->init();
        
		$Geofence->init();
		
        add_filter('custom_menu_order',array($this,'adminCustomMenuOrder'));
        
        add_action('admin_init',array($this,'adminInit'));
        add_action('admin_menu',array($this,'adminMenu'));
        
        add_action('wp_ajax_'.PLUGIN_CRBS_CONTEXT.'_option_page_save',array($this,'adminOptionPanelSave'));
        
		add_action('wp_ajax_'.PLUGIN_CRBS_CONTEXT.'_go_to_step',array($BookingForm,'goToStep'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CRBS_CONTEXT.'_go_to_step',array($BookingForm,'goToStep'));
        
		add_action('wp_ajax_'.PLUGIN_CRBS_CONTEXT.'_file_upload',array($BookingForm,'fileUpload'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CRBS_CONTEXT.'_file_upload',array($BookingForm,'fileUpload'));
        
		add_action('wp_ajax_'.PLUGIN_CRBS_CONTEXT.'_vehicle_filter',array($BookingForm,'vehicleFilter'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CRBS_CONTEXT.'_vehicle_filter',array($BookingForm,'vehicleFilter'));        
        
        add_action('wp_ajax_'.PLUGIN_CRBS_CONTEXT.'_coupon_code_check',array($BookingForm,'checkCouponCode'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CRBS_CONTEXT.'_coupon_code_check',array($BookingForm,'checkCouponCode'));  
        
        add_action('wp_ajax_'.PLUGIN_CRBS_CONTEXT.'_option_page_import_demo',array($this,'importDemo'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CRBS_CONTEXT.'_option_page_import_demo',array($this,'importDemo'));
        
        add_action('wp_ajax_'.PLUGIN_CRBS_CONTEXT.'_create_summary_price_element',array($BookingForm,'createSummaryPriceElementAjax'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CRBS_CONTEXT.'_create_summary_price_element',array($BookingForm,'createSummaryPriceElementAjax'));
        
        add_action('wp_ajax_'.PLUGIN_CRBS_CONTEXT.'_option_page_create_coupon_code',array($Coupon,'create'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CRBS_CONTEXT.'_option_page_create_coupon_code',array($Coupon,'create'));
        
        add_action('wp_ajax_'.PLUGIN_CRBS_CONTEXT.'_user_sign_in',array($BookingForm,'userSignIn'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CRBS_CONTEXT.'_user_sign_in',array($BookingForm,'userSignIn'));  
        
        add_action('wp_ajax_'.PLUGIN_CRBS_CONTEXT.'_option_page_import_exchange_rate',array($ExchangeRateProvider,'importExchangeRate'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CRBS_CONTEXT.'_option_page_import_exchange_rate',array($ExchangeRateProvider,'importExchangeRate'));
		
        add_action('admin_notices',array($this,'adminNotice'));
        
		add_action('wp_mail_failed',array($LogManager,'logWPMailError'));
		
        if((int)CRBSOption::getOption('google_map_duplicate_script_remove')===1)
            add_action('wp_print_scripts',array($this,'removeMultipleGoogleMap'),100);		
		
        add_theme_support('post-thumbnails');
        
        add_image_size(PLUGIN_CRBS_CONTEXT.'_vehicle',460,306); 
        
		if(!is_admin())
        {
			$PaymentStripe=new CRBSPaymentStripe();
            
			add_action('wp_enqueue_scripts',array($this,'publicInit'));
			
            add_action('wp_loaded',array($PaymentStripe,'receivePayment'));
        }
        
        $WooCommerce=new CRBSWooCommerce();
        $WooCommerce->addAction();
	}
    
	/**************************************************************************/
	

	public function publicInit()
	{
		$this->prepareLibrary();
		
		if(is_rtl())
			$this->library['style']['crbs-public-rtl']['inc']=true;
		
		$this->addLibrary('style',2);
		$this->addLibrary('script',2);
	}
	
	/**************************************************************************/
	
	public function adminInit()
	{
		$this->prepareLibrary();
		
		if(is_rtl())
		{
			$this->library['style']['jquery-themeOption-rtl']['inc']=true;
			$this->library['style']['jquery-dropkick-rtl']['inc']=true;
		}
		
		$this->addLibrary('style',1);
		$this->addLibrary('script',1);
        
        $data=array();
        
        $data['jqueryui_buttonset_enable']=(int)PLUGIN_CRBS_JQUERYUI_BUTTONSET_ENABLE;
        
		wp_localize_script('jquery-themeOption','crbsData',array('l10n_print_after'=>'crbsData='.json_encode($data).';'));
	}
    
    /**************************************************************************/
    
    public function adminMenu()
    {
        global $submenu;

        add_options_page(__('Car Rental Booking System','car-rental-booking-system'),__('Car Rental<br/>Booking System','car-rental-booking-system'),'edit_theme_options',PLUGIN_CRBS_CONTEXT,array($this,'adminCreateOptionPage'));
        add_submenu_page('edit.php?post_type=crbs_booking',__('Vehicle Types','car-rental-booking-system'),__('Vehicle Types','car-rental-booking-system'),'edit_themes', 'edit-tags.php?taxonomy='.CRBSVehicle::getCPTCategoryName());
    }
    
    /**************************************************************************/
    
    public function adminCreateOptionPage()
    {
		$data=array();
        
        $Currency=new CRBSCurrency();
        $GeoLocation=new CRBSGeoLocation();
        $BillingType=new CRBSBillingType();
        $EmailAccount=new CRBSEmailAccount();
		$BookingStatus=new CRBSBookingStatus();
		$ExchangeRateProvider=new CRBSExchangeRateProvider();
        
        $data['option']=CRBSOption::getOptionObject();
        
        $data['dictionary']['currency']=$Currency->getCurrency();
        
        $data['dictionary']['billing_type']=$BillingType->getDictionary();
        $data['dictionary']['email_account']=$EmailAccount->getDictionary();
        
        $data['dictionary']['geolocation_server']=$GeoLocation->getServer();
        
		$data['dictionary']['exchange_rate_provider']=$ExchangeRateProvider->getProvider();
		
		$data['dictionary']['booking_status']=$BookingStatus->getBookingStatus();
        $data['dictionary']['booking_status_synchronization']=$BookingStatus->getBookingStatusSynchronization();
		
        wp_enqueue_media();
        
		$Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'admin/option.php');
		echo $Template->output();	
    }
    
    /**************************************************************************/
    
    public function adminOptionPanelSave()
    {        
        $option=CRBSHelper::getPostOption();

        $response=array('global'=>array('error'=>1));

        $Notice=new CRBSNotice();
        $Currency=new CRBSCurrency();
        $Validation=new CRBSValidation();
        $BillingType=new CRBSBillingType();
		$BookingStatus=new CRBSBookingStatus();
        
        $invalidValue=__('This field includes invalid value.','car-rental-booking-system');
        
        /* General */
        if(!$BillingType->isBillingType($option['billing_type']))
            $Notice->addError(CRBSHelper::getFormName('billing_type',false),$invalidValue);	
        if(!$Validation->isBool($option['google_map_duplicate_script_remove']))
            $Notice->addError(CRBSHelper::getFormName('google_map_duplicate_script_remove',false),$invalidValue);	
        if(!$Currency->isCurrency($option['currency']))
            $Notice->addError(CRBSHelper::getFormName('currency',false),$invalidValue);	
        if($Validation->isEmpty($option['date_format']))
            $Notice->addError(CRBSHelper::getFormName('date_format',false),$invalidValue);
        if($Validation->isEmpty($option['time_format']))
            $Notice->addError(CRBSHelper::getFormName('time_format',false),$invalidValue);        
        if(!$Validation->isBool($option['email_report_status']))
            $Notice->addError(CRBSHelper::getFormName('email_report_status',false),$invalidValue);
        
		/* Payment */
		if((int)$option['booking_status_payment_success']!==-1)
		{
			if(!$BookingStatus->isBookingStatus($option['booking_status_payment_success']))
				$Notice->addError(CRBSHelper::getFormName('booking_status_payment_success',false),$invalidValue);	
		}
		if(!$BookingStatus->isBookingStatusSynchronization($option['booking_status_synchronization']))
            $Notice->addError(CRBSHelper::getFormName('booking_status_synchronization',false),$invalidValue);	
		
		if($Notice->isError())
		{
			$response['local']=$Notice->getError();
		}
		else
		{
			$response['global']['error']=0;
			CRBSOption::updateOption($option);
		}

		$response['global']['notice']=$Notice->createHTML(PLUGIN_CRBS_TEMPLATE_PATH.'notice.php');

		echo json_encode($response);
		exit;
    }
    
    /**************************************************************************/
    
    function importDemo()
    {
		$Demo=new CRBSDemo();
		$Notice=new CRBSNotice();
		$Validation=new CRBSValidation();
		
		$response=array('global'=>array('error'=>1));
		
		$buffer=$Demo->import();
		
		if($buffer!==false)
		{
			$response['global']['error']=0;
			$subtitle=__('Seems, that demo data has been imported. To make sure if this process has been successfully completed,please check below content of buffer returned by external applications.','car-rental-booking-system');
		}
		else
		{
			$response['global']['error']=1;
			$subtitle=__('Dummy data cannot be imported.','car-rental-booking-system');
		}
			
		$response['global']['notice']=$Notice->createHTML(PLUGIN_CRBS_TEMPLATE_PATH.'admin/notice.php',true,$response['global']['error'],$subtitle);
		
		if($Validation->isNotEmpty($buffer))
		{
			$response['global']['notice'].=
			'
				<div class="to-buffer-output">
					'.$buffer.'
				</div>
			';
		}
		
		echo json_encode($response);
		exit;			        
    }
    
    /**************************************************************************/
    
    function adminCustomMenuOrder()
    {
        global $submenu;

        $key='edit.php?post_type=crbs_booking';
        
        if(array_key_exists($key,$submenu))
        {
            $menu=array();
			
            $menu[5]=$submenu[$key][5];
            $menu[11]=$submenu[$key][11];
            $menu[12]=$submenu[$key][12];
            $menu[13]=$submenu[$key][13];
            $menu[14]=$submenu[$key][21];
            $menu[15]=$submenu[$key][14];
            $menu[16]=$submenu[$key][15];
            $menu[17]=$submenu[$key][16];
            $menu[18]=$submenu[$key][17];
            $menu[19]=$submenu[$key][18];
            $menu[20]=$submenu[$key][19];
            $menu[21]=$submenu[$key][20];
			
            $menu[14][2].='&post_type=crbs_booking';
            
            $submenu[$key]=$menu;
        }
    }
    
    /**************************************************************************/
    
    function afterSetupTheme()
    {
        $VisualComposer=new CRBSVisualComposer();
        $VisualComposer->init();
    }
    
    /**************************************************************************/
    
    function adminNotice()
    {
        $Validation=new CRBSValidation();
        
        if($Validation->isEmpty(CRBSOption::getOption('google_map_api_key')))
        {
            echo 
            '
                <div class="notice notice-error">
                    <p>
                        <b>'.esc_html('Car Rental Booking System','car-rental-booking-system').'</b> '.sprintf(__('Please enter your Google Maps API key in <a href="%s">Plugin Options</a>.','car-rental-booking-system'),admin_url('options-general.php?page=crbs',false)).'
                    </p>
                </div>
            ';
        }
    }
	
	/**************************************************************************/
	
    function removeMultipleGoogleMap()
    {
        global $wp_scripts;
           
        foreach($wp_scripts->queue as $handle) 
        {
            if($handle=='crbs-google-map') continue;
            
            $src=$wp_scripts->registered[$handle]->src;
            
            if(preg_match('/maps.google.com\/maps\/api\//',$src))
            {
                wp_dequeue_script($handle);
                wp_deregister_script($handle);    
            }
        }
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/