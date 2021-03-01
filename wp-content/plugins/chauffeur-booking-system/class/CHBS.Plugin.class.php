<?php

/******************************************************************************/
/******************************************************************************/

class CHBSPlugin
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
				'path'															=>	PLUGIN_CHBS_SCRIPT_URL,
				'file'															=>	'',
				'in_footer'														=>	true,
				'dependencies'													=>	array('jquery'),
			),
			'style'																=>	array
			(
				'use'															=>	1,
				'inc'															=>	true,
				'path'															=>	PLUGIN_CHBS_STYLE_URL,
				'file'															=>	'',
				'dependencies'													=>	array()
			)
		);
        
        /***/
        
        $this->optionDefault=array
        (
            'logo'                                                              =>  '',
            'google_map_api_key'                                                =>  '',
            'currency'                                                          =>  'USD',
            'length_unit'                                                       =>  '1',
            'date_format'                                                       =>  'd-m-Y',
            'time_format'                                                       =>  'G:i',
            'sender_default_email_account_id'                                   =>  '-1',
            'coupon_generate_count'                                             =>  '1',
            'coupon_generate_usage_limit'                                       =>  '1',
            'coupon_generate_discount_percentage'                               =>  '0',
            'coupon_generate_active_date_start'                                 =>  '',
            'coupon_generate_active_date_stop'                                  =>  '',
            'currency_exchange_rate'                                            =>  array(),
            'fixer_io_api_key'                                                  =>  '',
            'pricing_rule_return_use_type'                                      =>  '1',
            'google_map_duplicate_script_remove'                                =>  '0',
            'geolocation_server_id'                                             =>  '1',
            'geolocation_server_id_3_api_key'                                   =>  '',
            'salt'                                                              =>  CHBSHelper::createSalt(),
            'booking_driver_accept_enable'                                      =>  0,
            'booking_driver_confirmation_page'                                  =>  '',
            'booking_driver_status_after_accept'                                =>  2,
            'booking_driver_status_after_reject'                                =>  1,
            'booking_driver_email_recipient'                                    =>  '',
			'payment_stripe_webhook_endpoint_id'								=>	'',
			'booking_status_payment_success'									=>	'-1',
			'booking_status_synchronization'									=>	'1'
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
				'jquery-ui-autocomplete'										=>	array
				(
                    'use'                                                       =>  2,
					'path'														=>	''
				), 
				'jquery-ui-sortable'                                            =>	array
				(
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
				'jquery-fancybox'                                               =>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.fancybox.js'
				),
				'jquery-fancybox-media'                                         =>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.fancybox-media.js'
				),                
				'jquery-fancybox-buttons'                                       =>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.fancybox-buttons.js'
				),                    
				'jquery-intlTelInput'                                           =>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'intlTelInput.min.js'
				),  
				'jquery-intlTelInputUtil'                                       =>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'intlTelInputUtil.min.js'
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
				'chbs-helper'                                                    =>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'CHBS.Helper.class.js'
				),
				'chbs-admin'                                                    =>	array
				(
					'file'														=>	'admin.js'
				),
				'chbs-chauffeur-route-admin'                                    =>	array
				(
					'file'														=>	'jquery.chauffeurRouteAdmin.js'
				),
				'chbs-chauffeur-geofence-admin'                                 =>	array
				(
					'file'														=>	'jquery.chauffeurGeofenceAdmin.js'
				),
				'chbs-booking-form-admin'                                       =>	array
				(
					'file'														=>	'jquery.chauffeurBookingFormAdmin.js'
				),	   
				'chbs-booking-form-gutenberg-block'                             =>	array
				(                  
                    'inc'                                                       =>  0,
					'file'														=>	'gutenberg/block.build.js',
                    'dependencies'                                              =>  array('wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor')
				),
				'chbs-booking-form'                                             =>	array
				(
                    'use'                                                       =>  2,
					'file'														=>	'jquery.chauffeurBookingForm.js'
				),	                
				'chbs-google-map'                                               =>	array
				(
					'use'														=>	3,
					'path'														=>	'',
					'file'														=>	add_query_arg(array('key'=>urlencode(CHBSOption::getOption('google_map_api_key')),'libraries'=>'places,drawing','language'=>(defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : '')),'//maps.google.com/maps/api/js'),
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
					'file'														=>	'jquery.ui.min.css'
				),
				'jquery-qtip'   												=>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.qtip.min.css'
				),
				'jquery-dropkick'   											=>	array
				(
					'file'														=>	'jquery.dropkick.css'
				),
				'jquery-dropkick-rtl'											=>	array
				(				
					'inc'														=>	false,
					'file'														=>	'jquery.dropkick.rtl.css'
				),
				'jquery-colorpicker'   											=>	array
				(
					'file'														=>	'jquery.colorpicker.css'
				),
				'jquery-timepicker'   											=>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.timepicker.min.css'
				),
				'jquery-fancybox'   											=>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'fancybox/jquery.fancybox.css'
				),
				'jquery-intlTelInput'                                           =>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'intlTelInput.min.css'
				),                
				'jquery-themeOption'											=>	array
				(
					'file'														=>	'jquery.themeOption.css'
				),
				'jquery-themeOption-rtl'										=>	array
				(
					'inc'														=>	false,
					'file'														=>	'jquery.themeOption.rtl.css'
				),
				'chbs-themeOption-overwrite'									=>	array
				(
					'file'														=>	'jquery.themeOption.overwrite.css'
				),
				'chbs-public'        											=>	array
				(
                    'use'                                                       =>  2,
					'file'														=>	'public.css'
				),
				'chbs-public-rtl'   											=>	array
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
		if(CHBSFile::fileExist(CHBSFile::getMultisiteBlogCSS()))
		{
            $this->library['style']['chbs-public-booking-form-']=array
			(
                'use'                                                           =>	2,
				'path'                                                          =>	'',
				'file'                                                          =>	CHBSFile::getMultisiteBlogCSS('url')
			);
		}
        
		if(function_exists('register_block_type'))
		{
            $this->library['script']['chbs-booking-form-gutenberg-block']['inc']=1;
			wp_set_script_translations('chbs-booking-form-gutenberg-block','chauffeur-booking-system',plugin_dir_path( __FILE__).'languages');
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
				wp_enqueue_script($index,$data['path'].$data['file'],$data['dependencies'],false,$data['in_footer']);
			}
			else 
			{
				wp_enqueue_style($index,$data['path'].$data['file'],$data['dependencies'],false);
			}
		}
	}
	
	/**************************************************************************/
	
	public function pluginActivation()
	{    
        CHBSOption::createOption();
        
        $optionSave=array();
        $optionCurrent=CHBSOption::getOptionObject();
             
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
        
        CHBSOption::resetOption();
        CHBSOption::updateOption($optionSave);
        
        $BookingFormStyle=new CHBSBookingFormStyle();
        $BookingFormStyle->createCSSFile();
                   
        /***/
        
		$argument=array
		(
			'post_type'															=>	CHBSVehicle::getCPTName(),
			'post_status'														=>	'any',
			'posts_per_page'													=>	-1
		);
        
        $query=new WP_Query($argument);
		if($query!==false)
        {
            while($query->have_posts())
            {
                $query->the_post();
                
                $meta=CHBSPostMeta::getPostMeta(get_the_ID());
                
                if((array_key_exists('price_fixed_return_value',$meta)) && (!array_key_exists('price_fixed_return_new_ride_value',$meta)))
                {
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'price_fixed_return_new_ride_value',$meta['price_fixed_return_value']);
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'price_fixed_return_new_ride_tax_rate_id',$meta['price_fixed_return_tax_rate_id']);
                }
                
                if((array_key_exists('price_distance_return_value',$meta)) && (!array_key_exists('price_distance_return_new_ride_value',$meta)))
                {
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'price_distance_return_new_ride_value',$meta['price_distance_return_value']);
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'price_distance_return_new_ride_tax_rate_id',$meta['price_distance_return_tax_rate_id']);
                }                

                $type=(int)get_post_meta(get_the_ID(),PLUGIN_CHBS_CONTEXT.'_price_type');
                
                if(in_array($type,array(1,2))) continue;
                
                $data=array
                (
                    'price_type'                                                =>  1,
                    'price_fixed_value'                                         =>  0.00,
                    'price_fixed_tax_rate_id'                                   =>  0,
                    'price_fixed_return_value'                                  =>  0.00,
                    'price_fixed_return_tax_rate_id'                            =>  0,
                    'price_fixed_return_new_ride_value'                         =>  0.00,
                    'price_fixed_return_new_ride_tax_rate_id'                   =>  0,                    
                    'price_initial_value'                                       =>  0.00,
                    'price_initial_tax_rate_id'                                 =>  0,
                    'price_delivery_value'                                      =>  $meta['price_distance'],
                    'price_delivery_tax_rate_id'                                =>  $meta['tax_rate_id'],
                    'price_distance_value'                                      =>  $meta['price_distance'],
                    'price_distance_tax_rate_id'                                =>  $meta['tax_rate_id'],
                    'price_distance_return_value'                               =>  $meta['price_distance'],
                    'price_distance_return_tax_rate_id'                         =>  $meta['tax_rate_id'],
                    'price_hour_value'                                          =>  $meta['price_hour'],
                    'price_hour_tax_rate_id'                                    =>  $meta['tax_rate_id'],
                    'price_extra_time_value'                                    =>  $meta['price_hour'],
                    'price_extra_time_tax_rate_id'                              =>  $meta['tax_rate_id'],
                    'price_passenger_adult_value'                               =>  0.00,
                    'price_passenger_adult_tax_rate_id'                         =>  0,                    
                    'price_passenger_children_value'                            =>  0.00,
                    'price_passenger_children_tax_rate_id'                      =>  0 
                );
                
                foreach($data as $index=>$value)
                    CHBSPostMeta::updatePostMeta(get_the_ID(),$index,$value);
                
                CHBSPostMeta::removePostMeta(get_the_ID(),'price_hour');
                CHBSPostMeta::removePostMeta(get_the_ID(),'price_distance');
                CHBSPostMeta::removePostMeta(get_the_ID(),'tax_rate_id');
            }
        } 
        
        /***/
        
		$argument=array
		(
			'post_type'															=>	CHBSBooking::getCPTName(),
			'post_status'														=>	'any',
			'posts_per_page'													=>	-1
		);
        
        $query=new WP_Query($argument);
		if($query!==false)
        {
            while($query->have_posts())
            {
                $query->the_post();

                $type=(int)get_post_meta(get_the_ID(),PLUGIN_CHBS_CONTEXT.'_price_type');
                
                $meta=CHBSPostMeta::getPostMeta(get_the_ID());
                
                if(!array_key_exists('return_date',$meta)) $meta['return_date']='00-00-0000';
                if(!array_key_exists('return_time',$meta)) $meta['return_time']='00:00';
                
                CHBSPostMeta::updatePostMeta(get_the_ID(),'pickup_datetime',CHBSDate::formatDateTimeToMySQL($meta['pickup_date'],$meta['pickup_time']));
                CHBSPostMeta::updatePostMeta(get_the_ID(),'return_datetime',CHBSDate::formatDateTimeToMySQL($meta['return_date'],$meta['return_time']));

                if((array_key_exists('price_fixed_return_value',$meta)) && (!array_key_exists('price_fixed_return_new_ride_value',$meta)))
                {
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'price_fixed_return_new_ride_value',$meta['price_fixed_return_value']);
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'price_fixed_return_new_ride_tax_rate_value',$meta['price_fixed_return_tax_rate_value']);
                }
                
                if((array_key_exists('price_distance_return_value',$meta)) && (!array_key_exists('price_distance_return_new_ride_value',$meta)))
                {
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'price_distance_return_new_ride_value',$meta['price_distance_return_value']);
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'price_distance_return_new_ride_tax_rate_value',$meta['price_distance_return_tax_rate_value']);
                }  
                
                if(in_array($type,array(1,2))) continue;
                
                $data=array
                (
                    'price_type'                                                =>  1,
                    'price_fixed_value'                                         =>  0.00,
                    'price_fixed_tax_rate_value'                                =>  0,
                    'price_fixed_return_value'                                  =>  0.00,
                    'price_fixed_return_tax_rate_value'                         =>  0,
                    'price_fixed_return_new_ride_value'                         =>  0.00,
                    'price_fixed_return_new_ride_tax_rate_value'                =>  0,                    
                    'price_initial_value'                                       =>  0.00,
                    'price_initial_tax_rate_value'                              =>  0,
                    'price_delivery_value'                                      =>  $meta['vehicle_price_distance'],
                    'price_delivery_tax_rate_value'                             =>  $meta['vehicle_tax_rate_value'],
                    'price_distance_value'                                      =>  $meta['vehicle_price_distance'],
                    'price_distance_tax_rate_value'                             =>  $meta['vehicle_tax_rate_value'],
                    'price_distance_return_value'                               =>  $meta['vehicle_price_distance'],
                    'price_distance_return_tax_rate_value'                      =>  $meta['vehicle_tax_rate_value'],
                    'price_distance_return_new_ride_value'                      =>  $meta['vehicle_price_distance'],
                    'price_distance_return_new_ride_tax_rate_value'             =>  $meta['vehicle_tax_rate_value'],
                    'price_hour_value'                                          =>  $meta['vehicle_price_hour'],
                    'price_hour_tax_rate_value'                                 =>  $meta['vehicle_tax_rate_value'],
                    'price_extra_time_value'                                    =>  $meta['vehicle_price_hour'],
                    'price_extra_time_tax_rate_value'                           =>  $meta['vehicle_tax_rate_value'],
                    'price_passenger_adult_value'                               =>  0.00,
                    'price_passenger_adult_tax_rate_value'                      =>  0,                    
                    'price_passenger_children_value'                            =>  0.00,
                    'price_passenger_children_tax_rate_value'                   =>  0 
                );
  
                foreach($data as $index=>$value)
                    CHBSPostMeta::updatePostMeta(get_the_ID(),$index,$value);
                
                CHBSPostMeta::removePostMeta(get_the_ID(),'vehicle_price_hour');
                CHBSPostMeta::removePostMeta(get_the_ID(),'vehicle_price_distance');
                CHBSPostMeta::removePostMeta(get_the_ID(),'vehicle_tax_rate_value');
            }
        }
        
        /***/
    
		$argument=array
		(
			'post_type'															=>	CHBSRoute::getCPTName(),
			'post_status'														=>	'any',
			'posts_per_page'													=>	-1
		);
        
        $query=new WP_Query($argument);
		if($query!==false)
        {
            while($query->have_posts())
            {
                $query->the_post();
			
                $meta=CHBSPostMeta::getPostMeta(get_the_ID());
                
                $data=array();
                $vehicle=$meta['vehicle'];
                
                if((array_key_exists('price_fixed_return_value',$vehicle)) && (!array_key_exists('price_fixed_return_new_ride_value',$vehicle)))
                {
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'price_fixed_return_new_ride_value',$vehicle['price_fixed_return_value']);
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'price_fixed_return_new_ride_tax_rate_id',$vehicle['price_fixed_return_tax_rate_id']);
                }
                
                if((array_key_exists('price_distance_return_value',$vehicle)) && (!array_key_exists('price_distance_return_new_ride_value',$vehicle)))
                {
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'price_distance_return_new_ride_value',$vehicle['price_distance_return_value']);
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'price_distance_return_new_ride_tax_rate_id',$vehicle['price_distance_return_tax_rate_id']);
                }                   
                
                foreach($vehicle as $vehicleIndex=>$vehicleValue)
                {
                    if(array_key_exists('price_type',$vehicleValue)) continue;
     
                    if(!array_key_exists('price_hour',$vehicleValue))
                        $vehicleValue['price_hour']=0.00;
                    if(!array_key_exists('price_distance',$vehicleValue))
                        $vehicleValue['price_distance']=0.00;                    
                    if(!array_key_exists('tax_rate_id',$vehicleValue))
                        $vehicleValue['tax_rate_id']=0;                      
                    
                    if(($vehicleValue['price_distance']==0.00) && ($vehicleValue['price_hour']==0.00))
                    {
                        $data[$vehicleIndex]=array
                        (
                            'price_type'                                        =>  1,
                            'price_source'                                      =>  1,
                            'price_fixed_value'                                 =>  0.00,
                            'price_fixed_tax_rate_id'                           =>  0,
                            'price_fixed_return_value'                          =>  0.00,
                            'price_fixed_return_tax_rate_id'                    =>  0,
                            'price_fixed_return_new_ride_value'                 =>  0.00,
                            'price_fixed_return_new_ride_tax_rate_id'           =>  0,
                            'price_initial_value'                               =>  0.00,
                            'price_initial_tax_rate_id'                         =>  0,
                            'price_delivery_value'                              =>  0.00,
                            'price_delivery_tax_rate_id'                        =>  0,
                            'price_distance_value'                              =>  0.00,
                            'price_distance_tax_rate_id'                        =>  0,
                            'price_distance_return_value'                       =>  0.00,
                            'price_distance_return_tax_rate_id'                 =>  0,
                            'price_distance_return_new_ride_value'              =>  0.00,
                            'price_distance_return_new_ride_tax_rate_id'        =>  0,
                            'price_hour_value'                                  =>  0.00,
                            'price_hour_tax_rate_id'                            =>  0,
                            'price_extra_time_value'                            =>  0.00,
                            'price_extra_time_tax_rate_id'                      =>  0,
                            'price_passenger_adult_value'                       =>  0.00,
                            'price_passenger_adult_tax_rate_id'                 =>  0,                    
                            'price_passenger_children_value'                    =>  0.00,
                            'price_passenger_children_tax_rate_vid'             =>  0                             
                        );
                    }
                    else
                    {
                        $data[$vehicleIndex]=array
                        (
                            'price_type'                                        =>  1,
                            'price_source'                                      =>  2,
                            'price_fixed_value'                                 =>  0.00,
                            'price_fixed_tax_rate_id'                           =>  0,
                            'price_fixed_return_value'                          =>  0.00,
                            'price_fixed_return_tax_rate_id'                    =>  0,
                            'price_fixed_return_new_ride_value'                 =>  0.00,
                            'price_fixed_return_new_ride_tax_rate_id'           =>  0,
                            'price_initial_value'                               =>  0.00,
                            'price_initial_tax_rate_id'                         =>  0,
                            'price_delivery_value'                              =>  $vehicleValue['price_distance'],
                            'price_delivery_tax_rate_id'                        =>  $vehicleValue['tax_rate_id'],
                            'price_distance_value'                              =>  $vehicleValue['price_distance'],
                            'price_distance_tax_rate_id'                        =>  $vehicleValue['tax_rate_id'],
                            'price_distance_return_value'                       =>  $vehicleValue['price_distance'],
                            'price_distance_return_tax_rate_id'                 =>  $vehicleValue['tax_rate_id'],
                            'price_distance_return_new_ride_value'              =>  $vehicleValue['price_distance'],
                            'price_distance_return_new_ride_tax_rate_id'        =>  $vehicleValue['tax_rate_id'],
                            'price_hour_value'                                  =>  $vehicleValue['price_hour'],
                            'price_hour_tax_rate_id'                            =>  $vehicleValue['tax_rate_id'],
                            'price_extra_time_value'                            =>  $vehicleValue['price_hour'],
                            'price_extra_time_tax_rate_id'                      =>  $vehicleValue['tax_rate_id'],
                            'price_passenger_adult_value'                       =>  0.00,
                            'price_passenger_adult_tax_rate_id'                 =>  0,                    
                            'price_passenger_children_value'                    =>  0.00,
                            'price_passenger_children_tax_rate_vid'             =>  0
                        );                        
                    }
                }
                
                if(count($data))
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'vehicle',$data);
            }
        } 
        
        /***/
        
		$argument=array
		(
			'post_type'															=>	CHBSBookingForm::getCPTName(),
			'post_status'														=>	'any',
			'posts_per_page'													=>	-1
		);
        
        $query=new WP_Query($argument);
		if($query!==false)
        {
            while($query->have_posts())
            {
                $query->the_post();

                $type=get_post_meta(get_the_ID(),PLUGIN_CHBS_CONTEXT.'_transfer_type_enable');
                
                if(!count($type)) continue;
                
                $type=$type[0];
                
                if(in_array(1,$type))
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'transfer_type_enable_1',array(1,2,3));
                
                if(in_array(3,$type))
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'transfer_type_enable_3',array(1,2,3));
                
                CHBSPostMeta::removePostMeta(get_the_ID(),'transfer_type_enable');
            }
        }
	}
	
	/**************************************************************************/
	
	public function pluginDeactivation()
	{

	}
    
	/**************************************************************************/
	
	public function init()
	{	
        $Booking=new CHBSBooking();
        $BookingForm=new CHBSBookingForm();
        $BookingExtra=new CHBSBookingExtra();

        $Route=new CHBSRoute();
        $Vehicle=new CHBSVehicle();
        $VehicleCompany=new CHBSVehicleCompany();
        $VehicleAttribute=new CHBSVehicleAttribute();
        
        $Location=new CHBSLocation();
        $PriceRule=new CHBSPriceRule();
        
        $Driver=new CHBSDriver();
        $Coupon=new CHBSCoupon();
        
        $TaxRate=new CHBSTaxRate();
        $EmailAccount=new CHBSEmailAccount();
         
        $Geofence=new CHBSGeofence();
        $LogManager=new CHBSLogManager();
        
        $Booking->init();
        $BookingForm->init();
        $BookingExtra->init();
        
        $Route->init();
        $Vehicle->init();
        $VehicleCompany->init();
        $VehicleAttribute->init();
        
        $Location->init();
        $PriceRule->init();
        
        $Driver->init();
        $Coupon->init();
        
        $TaxRate->init();
        $EmailAccount->init();
        
        $Geofence->init();
    
		$User=new CHBSUser();
		$User->init();
		
        $ExchangeRateProvider=new CHBSExchangeRateProvider();
	
        add_filter('custom_menu_order',array($this,'adminCustomMenuOrder'));
        
        add_action('admin_init',array($this,'adminInit'));
        add_action('admin_menu',array($this,'adminMenu'));
        
        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_option_page_save',array($this,'adminOptionPanelSave'));
        
		add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_go_to_step',array($BookingForm,'goToStep'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_go_to_step',array($BookingForm,'goToStep'));
        
		add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_vehicle_filter',array($BookingForm,'vehicleFilter'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_vehicle_filter',array($BookingForm,'vehicleFilter'));        
        
        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_option_page_import_demo',array($this,'importDemo'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_option_page_import_demo',array($this,'importDemo'));
        
        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_create_summary_price_element',array($BookingForm,'createSummaryPriceElementAjax'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_create_summary_price_element',array($BookingForm,'createSummaryPriceElementAjax'));
        
        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_user_sign_in',array($BookingForm,'userSignIn'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_user_sign_in',array($BookingForm,'userSignIn'));        
        
        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_option_page_create_coupon_code',array($Coupon,'create'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_option_page_create_coupon_code',array($Coupon,'create'));

        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_option_page_import_exchange_rate',array($ExchangeRateProvider,'importExchangeRate'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_option_page_import_exchange_rate',array($ExchangeRateProvider,'importExchangeRate'));
        
        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_coupon_code_check',array($BookingForm,'checkCouponCode'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_coupon_code_check',array($BookingForm,'checkCouponCode'));  
        
        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_vehicle_bid_price_check',array($BookingForm,'checkVehicleBidPrice'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_vehicle_bid_price_check',array($BookingForm,'checkVehicleBidPrice')); 
		
        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_gratuity_customer_set',array($BookingForm,'setGratuityCustomer'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_gratuity_customer_set',array($BookingForm,'setGratuityCustomer'));          
        
        add_action('admin_notices',array($this,'adminNotice'));
       
        add_action('wp_mail_failed',array($LogManager,'logWPMailError'));
        
        if((int)CHBSOption::getOption('google_map_duplicate_script_remove')===1)
            add_action('wp_print_scripts',array($this,'removeMultipleGoogleMap'),100);
       
        add_theme_support('post-thumbnails');
        
        add_image_size(PLUGIN_CHBS_CONTEXT.'_vehicle',460,306); 
        add_image_size(PLUGIN_CHBS_CONTEXT.'_vehicle_2',800,533); 
        
		if(!is_admin())
        {
            $PaymentStripe=new CHBSPaymentStripe();
            
			add_action('wp_enqueue_scripts',array($this,'publicInit'));
			
            add_action('wp_loaded',array($PaymentStripe,'receivePayment'));
        }
               
		if(function_exists('register_block_type'))
		{
			register_block_type('chbs-booking-form/block',array('editor_script'=>'chbs-booking-form-gutenberg-block'));
            add_filter('block_categories',array($this,'gutenbergBlockCategory'),10,2);
		}
        
        $WooCommerce=new CHBSWooCommerce();
        $WooCommerce->addAction();
	}
    
	/**************************************************************************/
	
	public function publicInit()
	{
		$this->prepareLibrary();
		
		if(is_rtl())
		{
            $this->library['style']['chbs-public-rtl']['inc']=true;
		}
        
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
	
        /***/
        
        $data=array();
        
        $BookingForm=new CHBSBookingForm();
        $dictionary=$BookingForm->getDictionary();
        
        $data['booking_form_dictionary']=array();
        
        $data['booking_form_dictionary'][]=array('value'=>0,'label'=>__('Select booking form','chauffeur-booking-form'));
        
        foreach($dictionary as $value)
            $data['booking_form_dictionary'][]=array('value'=>$value['post']->ID,'label'=>$value['post']->post_title);
        
        $data['jqueryui_buttonset_enable']=(int)PLUGIN_CHBS_JQUERYUI_BUTTONSET_ENABLE;
        
		wp_localize_script('jquery-themeOption','chbsData',array('l10n_print_after'=>'chbsData='.json_encode($data).';'));
    }
    
    /**************************************************************************/
    
    public function adminMenu()
    {
        global $submenu;

        add_options_page(__('Chauffeur Booking System','chauffeur-booking-system'),__('Chauffeur<br/>Booking System','chauffeur-booking-system'),'edit_theme_options',PLUGIN_CHBS_CONTEXT,array($this,'adminCreateOptionPage'));
        add_submenu_page('edit.php?post_type=chbs_booking',__('Vehicle Types','chauffeur-booking-system'),__('Vehicle Types','chauffeur-booking-system'),'edit_themes','edit-tags.php?taxonomy='.CHBSVehicle::getCPTCategoryName());
    }
    
    /**************************************************************************/
    
    public function adminCreateOptionPage()
    {
		$data=array();
        
        $Length=new CHBSLength();
        $Currency=new CHBSCurrency();
        $GeoLocation=new CHBSGeoLocation();
        $EmailAccount=new CHBSEmailAccount();
        $BookingStatus=new CHBSBookingStatus();
        $ExchangeRateProvider=new CHBSExchangeRateProvider();
        
        $data['option']=CHBSOption::getOptionObject();
        
        $data['dictionary']['currency']=$Currency->getCurrency();
        $data['dictionary']['length_unit']=$Length->getUnit();
        
        $data['dictionary']['email_account']=$EmailAccount->getDictionary();
     
        $data['dictionary']['exchange_rate_provider']=$ExchangeRateProvider->getProvider();
        
        $data['dictionary']['geolocation_server']=$GeoLocation->getServer();
        
        $data['dictionary']['booking_status']=$BookingStatus->getBookingStatus();
        $data['dictionary']['booking_status_synchronization']=$BookingStatus->getBookingStatusSynchronization();
		
        wp_enqueue_media();
        
		$Template=new CHBSTemplate($data,PLUGIN_CHBS_TEMPLATE_PATH.'admin/option.php');
		echo $Template->output();	
    }
    
    /**************************************************************************/
    
    public function adminOptionPanelSave()
    {        
        $option=CHBSHelper::getPostOption();

        $response=array('global'=>array('error'=>1));

        $Notice=new CHBSNotice();
        $Length=new CHBSLength();
        $Currency=new CHBSCurrency();
        $Validation=new CHBSValidation();
        $BookingStatus=new CHBSBookingStatus();
        
        $invalidValue=__('This field includes invalid value.','chauffeur-booking-system');
        
        /* General */
        if(!$Validation->isBool($option['google_map_duplicate_script_remove']))
            $Notice->addError(CHBSHelper::getFormName('google_map_duplicate_script_remove',false),$invalidValue);	
        if(!$Currency->isCurrency($option['currency']))
            $Notice->addError(CHBSHelper::getFormName('currency',false),$invalidValue);	
        if(!$Length->isUnit($option['length_unit']))
            $Notice->addError(CHBSHelper::getFormName('length_unit',false),$invalidValue);	
        if($Validation->isEmpty($option['date_format']))
            $Notice->addError(CHBSHelper::getFormName('date_format',false),$invalidValue);
        if($Validation->isEmpty($option['time_format']))
            $Notice->addError(CHBSHelper::getFormName('time_format',false),$invalidValue);        
        if(!in_array($option['pricing_rule_return_use_type'],array(1,2)))
            $Notice->addError(CHBSHelper::getFormName('pricing_rule_return_use_type',false),$invalidValue);  
        
		/* Payment */
		if((int)$option['booking_status_payment_success']!==-1)
		{
			if(!$BookingStatus->isBookingStatus($option['booking_status_payment_success']))
				$Notice->addError(CHBSHelper::getFormName('booking_status_payment_success',false),$invalidValue);	
		}
		if(!$BookingStatus->isBookingStatusSynchronization($option['booking_status_synchronization']))
            $Notice->addError(CHBSHelper::getFormName('booking_status_synchronization',false),$invalidValue);	
		
        /* Currency */
        foreach($option['currency_exchange_rate'] as $index=>$value)
        {
            if(!$Currency->isCurrency($index))
            {
                unset($option['currency_exchange_rate'][$index]);
                continue;
            }
            
            if(!$Validation->isPrice($option['currency_exchange_rate'][$index]))
            {
                unset($option['currency_exchange_rate'][$index]);
                continue;                
            }
            
            $option['currency_exchange_rate'][$index]=preg_replace('/,/','.',$value);
        }
        
        /* Booking acceptance */
        if(!$Validation->isBool($option['booking_driver_accept_enable']))
            $Notice->addError(CHBSHelper::getFormName('booking_driver_accept_enable',false),$invalidValue);
        if(!$Validation->isNumber($option['booking_driver_confirmation_page'],1,999999,true))
            $Notice->addError(CHBSHelper::getFormName('booking_driver_confirmation_page',false),$invalidValue); 
        if(!$Validation->isEmailAddress($option['booking_driver_email_recipient'],true))
            $Notice->addError(CHBSHelper::getFormName('booking_driver_email_recipient',false),$invalidValue); 		
		if((int)$option['booking_driver_status_after_accept']!==0)
		{
			if(!$BookingStatus->isBookingStatus($option['booking_driver_status_after_accept']))
				$Notice->addError(CHBSHelper::getFormName('booking_driver_status_after_accept',false),$invalidValue);        
		}
		if((int)$option['booking_driver_status_after_reject']!==0)
		{
			if(!$BookingStatus->isBookingStatus($option['booking_driver_status_after_reject']))
				$Notice->addError(CHBSHelper::getFormName('booking_driver_status_after_reject',false),$invalidValue);            
		}
		
		if($Notice->isError())
		{
			$response['local']=$Notice->getError();
		}
		else
		{
			$response['global']['error']=0;
			CHBSOption::updateOption($option);
		}

		$response['global']['notice']=$Notice->createHTML(PLUGIN_CHBS_TEMPLATE_PATH.'admin/notice.php');

		echo json_encode($response);
		exit;
    }
    
    /**************************************************************************/
    
    function importDemo()
    {
		$Demo=new CHBSDemo();
		$Notice=new CHBSNotice();
		$Validation=new CHBSValidation();
		
		$response=array('global'=>array('error'=>1));
		
		$buffer=$Demo->import();
		
		if($buffer!==false)
		{
			$response['global']['error']=0;
			$subtitle=__('Seems, that demo data has been imported. To make sure if this process has been successfully completed,please check below content of buffer returned by external applications.','chauffeur-booking-system');
		}
		else
		{
			$response['global']['error']=1;
			$subtitle=__('Dummy data cannot be imported.','chauffeur-booking-system');
		}
			
		$response['global']['notice']=$Notice->createHTML(PLUGIN_CHBS_TEMPLATE_PATH.'admin/notice.php',true,$response['global']['error'],$subtitle);
		
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

        $key='edit.php?post_type=chbs_booking';
        
        if(array_key_exists($key,$submenu))
        {
            $menu=array();
            
            $menu[5]=$submenu[$key][5];
            $menu[11]=$submenu[$key][11];
            $menu[12]=$submenu[$key][12];
            $menu[13]=$submenu[$key][13];
            $menu[14]=$submenu[$key][14];
            if(isset($submenu[$key][24])) $menu[15]=$submenu[$key][24];
            $menu[16]=$submenu[$key][16];
            
            $menu[17]=$submenu[$key][15];
            $menu[18]=$submenu[$key][17];
            $menu[19]=$submenu[$key][18];
            $menu[20]=$submenu[$key][19];
            $menu[21]=$submenu[$key][20];
            $menu[22]=$submenu[$key][21];
            $menu[23]=$submenu[$key][22];
            $menu[24]=$submenu[$key][23];
            
            if(isset($menu[15][2])) $menu[15][2].='&post_type=chbs_booking';
            else $menu[15][2]='&post_type=chbs_booking';
            
            $submenu[$key]=$menu;
        }
    }
    
    /**************************************************************************/
    
    function afterSetupTheme()
    {        
        $VisualComposer=new CHBSVisualComposer();
        $VisualComposer->init();
    }
    
    /**************************************************************************/
    
    function adminNotice()
    {
        $Validation=new CHBSValidation();
        
        if($Validation->isEmpty(CHBSOption::getOption('google_map_api_key')))
        {
            echo 
            '
                <div class="notice notice-error">
                    <p>
                        <b>'.esc_html('Chauffeur Booking System','chauffeur-booking-system').'</b> '.sprintf(__('Please enter your Google Maps API key in <a href="%s">Plugin Options</a>.','chauffeur-booking-system'),admin_url('options-general.php?page=chbs',false)).'
                    </p>
                </div>
            ';
        }
    }
    
    /**************************************************************************/
    
    function gutenbergBlockCategory($categories,$post)
    {
		return array_merge
        (
			$categories,
			array
            (
				array
                (
                    'slug'                                                      =>  'chbs-booking-form',
					'title'                                                     =>  __('Chauffeur Booking System','chauffeur-booking-form'),
				)
			)
		);        
    }
    
    /**************************************************************************/
    
    function removeMultipleGoogleMap()
    {
        global $wp_scripts;
           
        foreach($wp_scripts->queue as $handle) 
        {
            if($handle=='chbs-google-map') continue;
            
            $src=$wp_scripts->registered[$handle]->src;
            
            if(preg_match('/maps.google.com\/maps\/api\//',$src))
            {
                wp_dequeue_script($handle);
                wp_deregister_script($handle);    
            }
        }
    }
	
	/**************************************************************************/
	
	static function isAutoRideTheme()
	{
		$theme=wp_get_theme();
		$themeName=strtolower($theme->get('Name'));

		$parentTheme=is_object($theme->parent()) ? $theme->parent() : null;
		$parentThemeName=strtolower(is_null($parentTheme) ? null : $parentTheme->get('Name'));

		if(($themeName=='autoride') || ($parentThemeName=='autoride')) return(true);	
		
		return(false);
	}
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/