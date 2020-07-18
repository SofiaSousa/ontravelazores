<?php

/******************************************************************************/
/******************************************************************************/

class CHBSPriceRule
{
	/**************************************************************************/
	
    function __construct()
    {
        $this->priceType=array
        (
            1                                                                   =>  array(__('Variable pricing','chauffeur-booking-system')),
            2                                                                   =>  array(__('Fixed pricing','chauffeur-booking-system'))
        );
        
        $this->priceAlterType=array
        (
            1                                                                   =>  array(__('- Inherited -','chauffeur-booking-system')),
            2                                                                   =>  array(__('Set value','chauffeur-booking-system')),            
            3                                                                   =>  array(__('Increase by value','chauffeur-booking-system')),
            4                                                                   =>  array(__('Decrease by value','chauffeur-booking-system')), 
            5                                                                   =>  array(__('Increase by percentage','chauffeur-booking-system')),
            6                                                                   =>  array(__('Decrease by percentage','chauffeur-booking-system')) 
        );
        
        $this->priceUseType=array
        (
            'fixed'                                                             =>  array(),
            'fixed_return'                                                      =>  array(),            
            'fixed_return_new_ride'                                             =>  array(),
            'initial'                                                           =>  array(),         
            'delivery'                                                          =>  array(),
            'delivery_return'                                                   =>  array(),            
            'distance'                                                          =>  array(),
            'distance_return'                                                   =>  array(),            
            'distance_return_new_ride'                                          =>  array(),
            'hour'                                                              =>  array(),           
            'hour_return'                                                       =>  array(),
            'hour_return_new_ride'                                              =>  array(),            
            'extra_time'                                                        =>  array(),
            'passenger_adult'                                                   =>  array(),
            'passenger_children'                                                =>  array(),            
        );
    }
    
    /**************************************************************************/
    
    function getPriceIndexName($index,$type='value')
    {
        return('price_'.$index.'_'.$type);
    }
    
    /**************************************************************************/
    
    function extractPriceFromData($price,$data)
    {
        $priceComponent=array('value','alter_type_id','tax_rate_id');
        
        foreach($this->getPriceUseType() as $priceUseTypeIndex=>$priceUseTypeValue)
        {
            foreach($priceComponent as $priceComponentIndex=>$priceComponentValue)
            {
                $key=$this->getPriceIndexName($priceUseTypeIndex,$priceComponentValue);
                if(isset($data[$key])) $price[$key]=$data[$key];
                else
                {
                    if($priceComponentValue==='alter_type_id') $price[$key]=2;
                }
            }
        }
        
        $price['price_type']=$data['price_type'];

        return($price);
    }
    
    /**************************************************************************/
    
    function getPriceType()
    {
        return($this->priceType);
    }
    
    /**************************************************************************/
    
    function isPriceType($priceType)
    {
        return(array_key_exists($priceType,$this->priceType));
    }
    
    /**************************************************************************/
    
    function getPriceAlterType()
    {
        return($this->priceAlterType);
    }
    
    /**************************************************************************/
    
    function isPriceAlterType($priceAlterType)
    {
        return(array_key_exists($priceAlterType,$this->priceAlterType));
    }
    
    /**************************************************************************/
    
    function getPriceUseType()
    {
        return($this->priceUseType);
    }
    
    /**************************************************************************/
    
    function isPriceUseType($priceUseType)
    {
        return(array_key_exists($priceUseType,$this->priceUseType));
    }
    
    /**************************************************************************/
    
    public function init()
    {
        $this->registerCPT();
    }
    
	/**************************************************************************/

    public static function getCPTName()
    {
        return(PLUGIN_CHBS_CONTEXT.'_price_rule');
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
					'name'														=>	__('Pricing Rules','chauffeur-booking-system'),
					'singular_name'												=>	__('Pricing Rule','chauffeur-booking-system'),
					'add_new'													=>	__('Add New','chauffeur-booking-system'),
					'add_new_item'												=>	__('Add New Pricing Rule','chauffeur-booking-system'),
					'edit_item'													=>	__('Edit Pricing Rule','chauffeur-booking-system'),
					'new_item'													=>	__('New Pricing Rule','chauffeur-booking-system'),
					'all_items'													=>	__('Pricing Rules','chauffeur-booking-system'),
					'view_item'													=>	__('View Pricing Rule','chauffeur-booking-system'),
					'search_items'												=>	__('Search Pricing Rules','chauffeur-booking-system'),
					'not_found'													=>	__('No Pricing Rules Found','chauffeur-booking-system'),
					'not_found_in_trash'										=>	__('No Pricing Rules in Trash','chauffeur-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('Pricing Rules','chauffeur-booking-system')
				),	
				'public'														=>	false,  
				'show_ui'														=>	true, 
				'show_in_menu'													=>	'edit.php?post_type='.CHBSBooking::getCPTName(),
				'capability_type'												=>	'post',
				'menu_position'													=>	2,
				'hierarchical'													=>	false,  
				'rewrite'														=>	false,  
				'supports'														=>	array('title','page-attributes')  
			)
		);
        
        add_action('save_post',array($this,'savePost'));
        add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
        add_filter('postbox_classes_'.self::getCPTName().'_chbs_meta_box_price_rule',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }

    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CHBS_CONTEXT.'_meta_box_price_rule',__('Main','chauffeur-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
        
		$data=array();
        
        $Route=new CHBSRoute();
        $Vehicle=new CHBSVehicle();
        $TaxRate=new CHBSTaxRate();
        $Country=new CHBSCountry();
        $Geofence=new CHBSGeofence();
        $Location=new CHBSLocation();
        $PriceType=new CHBSPriceType();
        $ServiceType=new CHBSServiceType();
        $BookingForm=new CHBSBookingForm();
        $TransferType=new CHBSTransferType();
        $VehicleCompany=new CHBSVehicleCompany();
        
        $data['meta']=CHBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CHBSHelper::createNonceField(PLUGIN_CHBS_CONTEXT.'_meta_box_price_rule');

        $data['dictionary']['route']=$Route->getDictionary();
        $data['dictionary']['country']=$Country->getCountry();
        $data['dictionary']['vehicle']=$Vehicle->getDictionary();
        $data['dictionary']['tax_rate']=$TaxRate->getDictionary();
        $data['dictionary']['geofence']=$Geofence->getDictionary();
        $data['dictionary']['location']=$Location->getDictionary();
        $data['dictionary']['price_type']=$PriceType->getPriceType();
        $data['dictionary']['alter_type']=$this->getPriceAlterType();
        $data['dictionary']['booking_form']=$BookingForm->getDictionary();
        $data['dictionary']['service_type']=$ServiceType->getServiceType();
        $data['dictionary']['transfer_type']=$TransferType->getTransferType();
        $data['dictionary']['vehicle_company']=$VehicleCompany->getDictionary();
        
		$Template=new CHBSTemplate($data,PLUGIN_CHBS_TEMPLATE_PATH.'admin/meta_box_price_rule.php');
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
        $TaxRate=new CHBSTaxRate();
        
        CHBSHelper::setDefault($meta,'booking_form_id',array(-1));
        CHBSHelper::setDefault($meta,'service_type_id',array(-1));
        CHBSHelper::setDefault($meta,'transfer_type_id',array(-1));
        
        CHBSHelper::setDefault($meta,'route_id',array(-1));
        CHBSHelper::setDefault($meta,'vehicle_id',array(-1));
        CHBSHelper::setDefault($meta,'vehicle_company_id',array(-1));
        
        CHBSHelper::setDefault($meta,'pickup_day_number',array(-1));
        
        CHBSHelper::setDefault($meta,'location_fixed_pickup',array(-1));
        CHBSHelper::setDefault($meta,'location_fixed_dropoff',array(-1));
        
        CHBSHelper::setDefault($meta,'location_country_pickup',array(-1));
        CHBSHelper::setDefault($meta,'location_country_dropoff',array(-1));       
        
        CHBSHelper::setDefault($meta,'location_geofence_pickup',array(-1));
        CHBSHelper::setDefault($meta,'location_geofence_dropoff',array(-1));
        
        CHBSHelper::setDefault($meta,'process_next_rule_enable',0);
        
        CHBSHelper::setDefault($meta,'minimum_order_value',0.00);
        
        CHBSHelper::setDefault($meta,'calculation_on_request_enable',0);
        CHBSHelper::setDefault($meta,'calculation_on_request_redirect_url','');
        
        CHBSHelper::setDefault($meta,'price_type',1);
        
        foreach($this->getPriceUseType() as $index=>$value)
        {
            CHBSHelper::setDefault($meta,'price_'.$index.'_value','0.00');
            CHBSHelper::setDefault($meta,'price_'.$index.'_alter_type_id',2);
            CHBSHelper::setDefault($meta,'price_'.$index.'_tax_rate_id',$TaxRate->getDefaultTaxPostId());            
        }
	}
    
    /**************************************************************************/
    
    function savePost($postId)
    {      
        if(!$_POST) return(false);
        
        if(CHBSHelper::checkSavePost($postId,PLUGIN_CHBS_CONTEXT.'_meta_box_price_rule_noncename','savePost')===false) return(false);
        
        $Date=new CHBSDate();
        $Route=new CHBSRoute();
        $Vehicle=new CHBSVehicle();
        $TaxRate=new CHBSTaxRate();
        $Country=new CHBSCountry();
        $Geofence=new CHBSGeofence();
        $Location=new CHBSLocation();
        $ServiceType=new CHBSServiceType();
        $BookingForm=new CHBSBookingForm();
        $TransferType=new CHBSTransferType();
        $VehicleCompany=new CHBSVehicleCompany();
        
        $Validation=new CHBSValidation();
        
        $option=CHBSHelper::getPostOption();
        
        /***/
        
        $dictionary=array
        (
            'booking_form_id'                                                   =>  array
            (
                'dictionary'                                                    =>  $BookingForm->getDictionary()
            ),
            'service_type_id'                                                   =>  array
            (
                'dictionary'                                                    =>  $ServiceType->getServiceType()
            ),          
            'transfer_type_id'                                                  =>  array
            (
                'dictionary'                                                    =>  $TransferType->getTransferType()
            ),                 
            'route_id'                                                          =>  array
            (
                'dictionary'                                                    =>  $Route->getDictionary()
            ),            
            'vehicle_id'                                                        =>  array
            (
                'dictionary'                                                    =>  $Vehicle->getDictionary()
            ),
            'vehicle_company_id'                                                =>  array
            (
                'dictionary'                                                    =>  $VehicleCompany->getDictionary()
            ),
            'pickup_day_number'                                                 =>  array
            (
                'dictionary'                                                    =>  array(1,2,3,4,5,6,7)
            ),
            'location_country_pickup'                                           =>  array
            (
                'dictionary'                                                    =>  $Geofence->getDictionary()
            ),            
            'location_country_dropoff'                                          =>  array
            (
                'dictionary'                                                    =>  $Geofence->getDictionary()
            )              
        );
        
        foreach($dictionary as $dIndex=>$dValue)
        {
            $option[$dIndex]=(array)CHBSHelper::getPostValue($dIndex);
            if(in_array(-1,$option[$dIndex]))
            {
                $option[$dIndex]=array(-1);
            }
            else
            {
                foreach($option[$dIndex] as $oIndex=>$oValue)
                {
                    if(!isset($dValue['dictionary']))
                        unset($option[$dIndex][$oIndex]);                
                }
            }             
        }
        
        /***/
        
        $locationDictionary=$Location->getDictionary();
        
        $field=array('location_fixed_pickup','location_fixed_dropoff');
        
        foreach($field as $fieldName)
        {
            $option[$fieldName]=(array)$option[$fieldName];
            foreach($option[$fieldName] as $index=>$value)
            {
                if($value==-1)
                {
                    $option[$fieldName]=array();
                    break;
                }

                if(!array_key_exists($value,$locationDictionary))                        
                    unset($option[$fieldName][$index]);
            }
            
            if(!count($option[$fieldName]))
                $option[$fieldName]=array(-1);
        }
        
        /***/
        
        $countryDictionary=$Country->getCountry();
        
        $field=array('location_country_pickup','location_country_dropoff');
        
        foreach($field as $fieldName)
        {
            $option[$fieldName]=(array)$option[$fieldName];
            
            foreach($option[$fieldName] as $index=>$value)
            {
                if($value==-1)
                {
                    $option[$fieldName]=array();
                    break;
                }

                if(!array_key_exists($value,$countryDictionary))                        
                    unset($option[$fieldName][$index]);
            }
            
            if(!count($option[$fieldName]))
                $option[$fieldName]=array(-1);
        }
        
        /***/
        
        $date=array();
       
        foreach($option['pickup_date']['start'] as $index=>$value)
        {
            $d=array($value,$option['pickup_date']['stop'][$index]);
            
            $d[0]=$Date->formatDateToStandard($d[0]);
            $d[1]=$Date->formatDateToStandard($d[1]);
            
            if(!$Validation->isDate($d[0])) continue;
            if(!$Validation->isDate($d[1])) continue;
            
            if($Date->compareDate($d[0],$d[1])==1) continue;
            
            array_push($date,array('start'=>$d[0],'stop'=>$d[1]));
        }

        $option['pickup_date']=$date;

        /***/
        
        $time=array();
       
        foreach($option['pickup_time']['start'] as $index=>$value)
        {
            $t=array($value,$option['pickup_time']['stop'][$index]);
            
            $t[0]=$Date->formatTimeToStandard($t[0]);
            $t[1]=$Date->formatTimeToStandard($t[1]);
            
            if(!$Validation->isTime($t[0])) continue;
            if(!$Validation->isTime($t[1])) continue;
            
            if($Date->compareTime($t[0],$t[1])!=2) continue;
            
            array_push($time,array('start'=>$t[0],'stop'=>$t[1]));
        }
        
        $option['pickup_time']=$time;
        
        /***/
        
        $distance=array();
       
        foreach($option['distance']['start'] as $index=>$value)
        {
            $d=array($value,$option['distance']['stop'][$index]);
            
            if(!$Validation->isFloat($d[0],0,999999999.99)) continue;
            if(!$Validation->isFloat($d[1],0,999999999.99)) continue;
  
            if($d[0]>$d[1]) continue;
            
            if(CHBSOption::getOption('length_unit')==2)
            {
                $Length=new CHBSLength();
                
                $d[0]=$Length->convertUnit($d[0],2,1);
                $d[1]=$Length->convertUnit($d[1],2,1);
            }

            array_push($distance,array('start'=>$d[0],'stop'=>$d[1]));
        }
        
        $option['distance']=$distance;
        
        /***/
               
        $distance=array();
  
        foreach($option['distance_base_location']['start'] as $index=>$value)
        {
            $d=array($value,$option['distance_base_location']['stop'][$index]);
            
            if(!$Validation->isFloat($d[0],0,999999999.99)) continue;
            if(!$Validation->isFloat($d[1],0,999999999.99)) continue;
  
            if($d[0]>$d[1]) continue;
            
            if(CHBSOption::getOption('length_unit')==2)
            {
                $Length=new CHBSLength();
                
                $d[0]=$Length->convertUnit($d[0],2,1);
                $d[1]=$Length->convertUnit($d[1],2,1);
            }

            array_push($distance,array('start'=>$d[0],'stop'=>$d[1]));
        }
        
        $option['distance_base_location']=$distance;
 
        /***/
        
        $passenger=array();
       
        foreach($option['passenger']['start'] as $index=>$value)
        {
            $d=array($value,$option['passenger']['stop'][$index]);
            
            if(!$Validation->isNumber($d[0],1,99)) continue;
            if(!$Validation->isNumber($d[1],1,99)) continue;
  
            if($d[0]>$d[1]) continue;

            array_push($passenger,array('start'=>$d[0],'stop'=>$d[1]));
        }        
        
        $option['passenger']=$passenger;
       
        /***/
        
        $duration=array();
       
        foreach($option['duration']['start'] as $index=>$value)
        {
            $d=array($value,$option['duration']['stop'][$index]);
            
            if(!$Validation->isNumber($d[0],0,9999)) continue;
            if(!$Validation->isNumber($d[1],0,9999)) continue;
  
            if($d[0]>$d[1]) continue;

            array_push($duration,array('start'=>$d[0],'stop'=>$d[1]));
        }        
        
        $option['duration']=$duration;        
        
        /***/
        
        if(!$Validation->isBool($option['process_next_rule_enable']))
            $option['process_next_rule_enable']=0;          
        
        if(!$Validation->isPrice($option['minimum_order_value'],false))
           $option['minimum_order_value']=0.00;
       
        if(!$Validation->isBool($option['calculation_on_request_enable']))
            $option['calculation_on_request_enable']=0;         
        
        if(!$this->isPriceType($option['price_type']))
            $option['price_type']=1;

        /***/
        
        foreach($this->getPriceUseType() as $index=>$value)
        {
            if(!$Validation->isPrice($option['price_'.$index.'_value'],false))
                $option['price_'.$index.'_value']=0.00;
            if(!$this->isPriceAlterType($option['price_'.$index.'_alter_type_id']))
                $option['price_'.$index.'_alter_type_id']=1;
            
            if(in_array($option['price_'.$index.'_alter_type_id'],array(5,6)))
            {
                if(!$Validation->isNumber($option['price_'.$index.'_alter_type_id'],0,100))
                    $option['price_'.$index.'_alter_type_id']=0;
            }
            
            if((int)$option['price_'.$index.'_tax_rate_id']===-1)
                $option['price_'.$index.'_tax_rate_id']=-1;
            else
            {
                if(!$TaxRate->isTaxRate($option['price_'.$index.'_tax_rate_id']))
                    $option['price_'.$value.'_tax_rate_id']=0; 
            }
        }

        /***/

        $key=array
        (
            'booking_form_id',
            'service_type_id',
            'transfer_type_id',
            'route_id',
            'vehicle_id',
            'vehicle_company_id',
            'location_fixed_pickup',
            'location_fixed_dropoff',
            'location_country_pickup',
            'location_country_dropoff',
            'location_geofence_pickup',
            'location_geofence_dropoff',
            'pickup_day_number',
            'pickup_date',
            'pickup_time',
            'distance',
            'distance_base_location',
            'passenger',
            'duration',
            'process_next_rule_enable',
            'minimum_order_value',
            'calculation_on_request_enable',
            'calculation_on_request_redirect_url',
            'price_type',
        );
        
        foreach($this->getPriceUseType() as $index=>$value)
            array_push($key,'price_'.$index.'_value','price_'.$index.'_alter_type_id','price_'.$index.'_tax_rate_id');
            
        foreach($key as $value)
            CHBSPostMeta::updatePostMeta($postId,$value,$option[$value]);
    }
    
    /**************************************************************************/

    function manageEditColumns($column)
    {
        $column=array
        (
            'cb'                                                                =>  $column['cb'],
            'title'                                                             =>  $column['title'],
            'rule'                                                              =>  __('Rules','chauffeur-booking-system'),
            'price'                                                             =>  __('Prices','chauffeur-booking-system')
        );
   
		return($column);          
    }
    
    /**************************************************************************/
    
    function getPricingRuleAdminListDictionary()
    {
        $dictionary=array();
    
        $Date=new CHBSDate();
        $Route=new CHBSRoute();
        $Country=new CHBSCountry();
        $Vehicle=new CHBSVehicle();
        $Geofence=new CHBSGeofence();
        $Location=new CHBSLocation();
        $ServiceType=new CHBSServiceType();
        $BookingForm=new CHBSBookingForm();
        $TransferType=new CHBSTransferType();
        $VehicleCompany=new CHBSVehicleCompany();
        
        $dictionary['route']=$Route->getDictionary();
        $dictionary['vehicle']=$Vehicle->getDictionary();
        $dictionary['geofence']=$Geofence->getDictionary();
        $dictionary['location']=$Location->getDictionary();
        $dictionary['booking_form']=$BookingForm->getDictionary();
        $dictionary['vehicle_company']=$VehicleCompany->getDictionary();
        
        $dictionary['country']=$Country->getCountry();
        $dictionary['service_type']=$ServiceType->getServiceType();
        $dictionary['transfer_type']=$TransferType->getTransferType();
        
        $dictionary['day']=$Date->day;
        
        return($dictionary);
    }
    
    /**************************************************************************/
    
    function displayPricingRuleAdminListValue($data,$dictionary,$link=false,$sort=false)
    {
        if(in_array(-1,$data)) return(__(' - ','chauffeur-booking-system'));
        
        $html=null;
        
        $Validation=new CHBSValidation();
        
        $dataSort=array();

        foreach($data as $value)
        {
            if(!array_key_exists($value,$dictionary)) continue;

            if(array_key_exists('post',$dictionary[$value]))
                $label=$dictionary[$value]['post']->post_title;
            else $label=$dictionary[$value][0];            

            $dataSort[$value]=$label;
        }

        if($sort) asort($dataSort);

        $data=$dataSort;
        
        foreach($data as $index=>$value)
        {
            $label=$value;
            
            if($link) $label='<a href="'.get_edit_post_link($index).'">'.$value.'</a>';
            if($Validation->isNotEmpty($html)) $html.=', ';
            $html.=$label;
        }
        
        return($html);
    }
    
    /**************************************************************************/
    
    function managePostsCustomColumn($column)
    {
		global $post;
        
        $Date=new CHBSDate();
        $Length=new CHBSLength();
        $PriceType=new CHBSPriceType();
        $Validation=new CHBSValidation();
        
        $meta=CHBSPostMeta::getPostMeta($post);
        
        $dictionary=CHBSGlobalData::setGlobalData('pricing_rule_admin_list_dictionary',array($this,'getPricingRuleAdminListDictionary'));
        
		switch($column) 
		{
			case 'rule':
                
                $html=array
                (
                    'pickup_date'                                               =>  '',
                    'pickup_time'                                               =>  '',
                    'distance'                                                  =>  '',
                    'distance_base_location'                                    =>  '',
                    'duration'                                                  =>  '',
                    'passenger'                                                 =>  ''
                );
                
                if((isset($meta['pickup_date'])) && (count($meta['pickup_date'])))
                {
                    foreach($meta['pickup_date'] as $value)
                    {
                        if(!$Validation->isEmpty($html['pickup_date'])) $html['pickup_date'].=', ';
                        $html['pickup_date'].=$Date->formatDateToDisplay($value['start']).' - '.$Date->formatDateToDisplay($value['stop']);
                    }
                }
            
                if((isset($meta['pickup_time'])) && (count($meta['pickup_time'])))
                {
                    foreach($meta['pickup_time'] as $value)
                    {
                        if(!$Validation->isEmpty($html['pickup_time'])) $html['pickup_time'].=', ';
                        $html['pickup_time'].=$Date->formatTimeToDisplay($value['start']).' - '.$Date->formatTimeToDisplay($value['stop']);
                    }
                }
                
                if((isset($meta['distance'])) && (count($meta['distance'])))
                {
                    foreach($meta['distance'] as $value)
                    {
                        if(CHBSOption::getOption('length_unit')==2)
                        {
                            $value['start']=round($Length->convertUnit($value['start'],1,2),1);
                            $value['stop']=round($Length->convertUnit($value['stop'],1,2),1); 
                        }
                        
                        if(!$Validation->isEmpty($html['distance'])) $html['distance'].=', ';
                        $html['distance'].=$value['start'].' - '.$value['stop'].' '.$Length->getUnitShortName(CHBSOption::getOption('length_unit'));
                    }
                }  
                
                if((isset($meta['distance_base_location'])) && (count($meta['distance_base_location'])))
                {
                    foreach($meta['distance_base_location'] as $value)
                    {
                        if(CHBSOption::getOption('length_unit')==2)
                        {
                            $value['start']=round($Length->convertUnit($value['start'],1,2),1);
                            $value['stop']=round($Length->convertUnit($value['stop'],1,2),1); 
                        }
                        
                        if(!$Validation->isEmpty($html['distance_base_location'])) $html['distance_base_location'].=', ';
                        $html['distance_base_location'].=$value['start'].' - '.$value['stop'].' '.$Length->getUnitShortName(CHBSOption::getOption('length_unit'));
                    }
                }   
                
                if((isset($meta['passenger'])) && (count($meta['passenger'])))
                {
                    foreach($meta['passenger'] as $value)
                    {
                        if(!$Validation->isEmpty($html['passenger'])) $html['passenger'].=', ';
                        $html['passenger'].=$value['start'].' - '.$value['stop'];                        
                    }
                }                
                
                if((isset($meta['duration'])) && (count($meta['duration'])))
                {
                    foreach($meta['duration'] as $value)
                    {
                        if(!$Validation->isEmpty($html['duration'])) $html['duration'].=', ';
                        $html['duration'].=$value['start'].' - '.$value['stop'];                          
                    }
                }                

                /***/
                
                echo 
                '
                    <table class="to-table-post-list">
                        <tr>
                            <td>'.esc_html__('Booking form','chauffeur-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['booking_form_id'],$dictionary['booking_form'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Service type','chauffeur-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['service_type_id'],$dictionary['service_type'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Transfer type','chauffeur-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['transfer_type_id'],$dictionary['transfer_type'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Routes','chauffeur-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['route_id'],$dictionary['route'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Vehicles','chauffeur-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['vehicle_id'],$dictionary['vehicle'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Vehicle companies','chauffeur-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['vehicle_company_id'],$dictionary['vehicle_company'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Pickup location','chauffeur-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['location_fixed_pickup'],$dictionary['location'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Dropoff location','chauffeur-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['location_fixed_dropoff'],$dictionary['location'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Pickup country location','chauffeur-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['location_country_pickup'],$dictionary['country'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Dropoff country location','chauffeur-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['location_country_dropoff'],$dictionary['country'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Pickup geofence','chauffeur-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['location_geofence_pickup'],$dictionary['geofence'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Drop off geofence','chauffeur-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['location_geofence_dropoff'],$dictionary['geofence'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Pickup day numbers','chauffeur-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['pickup_day_number'],$dictionary['day'],true,true).'</td>
                        </tr>                        
                        <tr>
                            <td>'.esc_html__('Pickup date','chauffeur-booking-system').'</td>
                            <td>'.$html['pickup_date'].'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Pickup time','chauffeur-booking-system').'</td>
                            <td>'.$html['pickup_time'].'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Distance','chauffeur-booking-system').'</td>
                            <td>'.$html['distance'].'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Distance from base to pickup location','chauffeur-booking-system').'</td>
                            <td>'.$html['distance_base_location'].'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Ride duration','chauffeur-booking-system').'</td>
                            <td>'.$html['duration'].'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Passengers','chauffeur-booking-system').'</td>
                            <td>'.$html['passenger'].'</td>
                        </tr>
                    </table>
                ';

			break;
        
			case 'price':
                
                $Length=new CHBSLength();

                echo 
                '
                    <table class="to-table-post-list">
                        <tr>
                            <td>'.esc_html__('Price type','chauffeur-booking-system').'</td>
                            <td>'.$PriceType->getPriceTypeName($meta['price_type']).'</td>
                        </tr>  
                ';
                
                if((int)$meta['price_type']===2)
                {
                    echo
                    '
                        <tr>
                            <td>'.esc_html__('Fixed price','chauffeur-booking-system').'</td>
                            <td>'.self::displayPriceAlter($meta,'fixed').'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Fixed price (return)','chauffeur-booking-system').'</td>
                            <td>'.self::displayPriceAlter($meta,'fixed_return').'</td>
                        </tr>  
                        <tr>
                            <td>'.esc_html__('Fixed price (return, new ride)','chauffeur-booking-system').'</td>
                            <td>'.self::displayPriceAlter($meta,'fixed_return_new_ride').'</td>
                        </tr>  
                    ';
                }
                else
                {
                    echo
                    '
                        <tr>
                            <td>'.__('Initial fee','chauffeur-booking-system').'</td>
                            <td>'.self::displayPriceAlter($meta,'initial').'</td>
                        </tr>
                        <tr>
                            <td>'.__('Delivery fee','chauffeur-booking-system').'</td>
                            <td>'.self::displayPriceAlter($meta,'delivery').'</td>
                        </tr>
                        <tr>
                            <td>'.__('Delivery (return) fee','chauffeur-booking-system').'</td>
                            <td>'.self::displayPriceAlter($meta,'delivery_return').'</td>
                        </tr>
                        <tr>
                            <td>'.$Length->label(CHBSOption::getOption('length_unit'),1).'</td>
                            <td>'.self::displayPriceAlter($meta,'distance').'</td>
                        </tr>
                        <tr>
                            <td>'.$Length->label(CHBSOption::getOption('length_unit'),4).'</td>
                            <td>'.self::displayPriceAlter($meta,'distance_return').'</td>
                        </tr>
                        <tr>
                            <td>'.$Length->label(CHBSOption::getOption('length_unit'),5).'</td>
                            <td>'.self::displayPriceAlter($meta,'distance_return_new_ride').'</td>
                        </tr>
                        <tr>
                            <td>'.__('Price per hour','chauffeur-booking-system').'</td>
                            <td>'.self::displayPriceAlter($meta,'hour').'</td>
                        </tr>
                        <tr>
                            <td>'.__('Price per hour (return)','chauffeur-booking-system').'</td>
                            <td>'.self::displayPriceAlter($meta,'hour_return').'</td>
                        </tr>
                        <tr>
                            <td>'.__('Price per hour (return, new ride)','chauffeur-booking-system').'</td>
                            <td>'.self::displayPriceAlter($meta,'hour_return_new_ride').'</td>
                        </tr>
                    ';
                }
                
                echo
                '
                    <tr>
                        <td>'.__('Price per extra time','chauffeur-booking-system').'</td>
                        <td>'.self::displayPriceAlter($meta,'extra_time').'</td>
                    </tr>
                ';
                
                if((int)$meta['price_type']===1)
                {
                    echo
                    '
                        <tr>
                            <td>'.__('Price per adult','chauffeur-booking-system').'</td>
                            <td>'.self::displayPriceAlter($meta,'passenger_adult').'</td>
                        </tr>
                        <tr>
                            <td>'.__('Price per child','chauffeur-booking-system').'</td>
                            <td>'.self::displayPriceAlter($meta,'passenger_children').'</td>
                        </tr>
                    ';
                }
                
                echo
                '
                        <tr>
                            <td>'.__('Priority','chauffeur-booking-system').'</td>
                            <td>'.(int)$post->menu_order.'</td>
                        </tr>
                        <tr>
                            <td>'.__('Next rule processing','chauffeur-booking-system').'</td>
                            <td>'.((int)$meta['process_next_rule_enable']===1 ? esc_html__('Enable','chauffeur-booking-system') : esc_html__('Disable','chauffeur-booking-system')).'</td>
                        </tr>
                    </table>
                ';
                
			break;
        }
    }
    
    /**************************************************************************/
    
    static function displayPriceAlter($meta,$priceUseType)
    {
        $charBefore=null;
        
        if(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(3,5)))
            $charBefore='+ ';
        if(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(4,6)))
            $charBefore='- ';        
        
        if(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(1)))
        {
            return(__('Inherited','chauffeur-booking-system'));
        }
        elseif(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(2)))
        {
            return(CHBSPrice::format($meta['price_'.$priceUseType.'_value'],CHBSOption::getOption('currency')));
        }
        elseif(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(3,4)))
        {
            return($charBefore.CHBSPrice::format($meta['price_'.$priceUseType.'_value'],CHBSOption::getOption('currency')));
        }
        elseif(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(5,6)))
        {
            return($charBefore.$meta['price_'.$priceUseType.'_value'].'%');
        }
    }
    
    /**************************************************************************/
    
    function manageEditSortableColumns($column)
    {
		return($column);       
    }
    
    /**************************************************************************/
    
    function getPriceFromRule($bookingData,$bookingForm,$priceRule=array())
    {
        /* init */
        
        $Date=new CHBSDate();
        $GeoCoding=new CHBSGeoCoding();
        $Validation=new CHBSValidation();

        if(!array_key_exists('price_type',$priceRule))
            $priceRule['price_type']=1;
        if(!array_key_exists('calculation_on_request_enable',$priceRule))
            $priceRule['calculation_on_request_enable']=0;
        if(!array_key_exists('calculation_on_request_redirect_url',$priceRule))
            $priceRule['calculation_on_request_redirect_url']='';
        if(!array_key_exists('minimum_order_value',$priceRule))
            $priceRule['minimum_order_value']=0;
        
        /* get rule */
        
        $rule=$bookingForm['dictionary']['price_rule'];
        if($rule===false) return($priceRule);

        /* process rule */
        
        foreach($rule as $ruleData)
        {
            if(!in_array(-1,$ruleData['meta']['booking_form_id']))
            {
                if(!in_array($bookingData['booking_form_id'],$ruleData['meta']['booking_form_id'])) continue;
            }
            if(!in_array(-1,$ruleData['meta']['service_type_id']))
            {
                if(!in_array($bookingData['service_type_id'],$ruleData['meta']['service_type_id'])) continue;
            }  
            if(in_array($bookingData['service_type_id'],array(1,3)))
            {
                if(!in_array(-1,$ruleData['meta']['transfer_type_id']))
                {
                    if(!in_array($bookingData['transfer_type_id'],$ruleData['meta']['transfer_type_id'])) continue;
                }
            }     
            if($bookingData['service_type_id']==3)
            {
                if(!in_array(-1,$ruleData['meta']['route_id']))
                {
                    if(!in_array($bookingData['route_id'],$ruleData['meta']['route_id'])) continue;
                }
            }
            if(!in_array(-1,$ruleData['meta']['vehicle_id']))
            {
                if(!in_array($bookingData['vehicle_id'],$ruleData['meta']['vehicle_id'])) continue;
            } 
            if(!in_array(-1,$ruleData['meta']['vehicle_company_id']))
            {
                $Vehicle=new CHBSVehicle();
                $vehicleDictionary=$Vehicle->getDictionary(array('vehicle_id'=>$bookingData['vehicle_id']));

                if(count($vehicleDictionary)===1)
                {
                    if(in_array($vehicleDictionary[$bookingData['vehicle_id']]['meta']['vehicle_company_id'],$ruleData['meta']['vehicle_company_id']))
                    {
                        
                    }
                    else continue;
                }
                else continue;
            }             
            if(!in_array(-1,$ruleData['meta']['location_fixed_pickup']))
            {
                if(!in_array($bookingData['fixed_location_pickup'],$ruleData['meta']['location_fixed_pickup'])) continue;
            }             
            if(!in_array(-1,$ruleData['meta']['location_fixed_dropoff']))
            {
                if(!in_array($bookingData['fixed_location_dropoff'],$ruleData['meta']['location_fixed_dropoff'])) continue;
            }     
            if($bookingData['service_type_id']==1)
            {
                if(!in_array(-1,$ruleData['meta']['location_country_pickup']))
                {
                    if($Validation->isNotEmpty($bookingData['pickup_location_coordinate']))
                    {
                        if(!is_null($document=json_decode($bookingData['pickup_location_coordinate'])))
                        {
                            $country=$GeoCoding->getCountryCode($document->{'lat'},$document->{'lng'});
                            if($country!==false)
                            {
                                if(!in_array($country,$ruleData['meta']['location_country_pickup'])) continue;
                            }
                        }
                    }
                }
            
                if(!in_array(-1,$ruleData['meta']['location_country_dropoff']))
                {
                    if($Validation->isNotEmpty($bookingData['dropoff_location_coordinate']))
                    {
                        if(!is_null($document=json_decode($bookingData['dropoff_location_coordinate'])))
                        {
                            $country=$GeoCoding->getCountryCode($document->{'lat'},$document->{'lng'});
                            if($country!==false)
                            {
                                if(!in_array($country,$ruleData['meta']['location_country_dropoff'])) continue;
                            }
                        }
                    }
                }            
            }
            
            if(!in_array(-1,$ruleData['meta']['location_geofence_pickup']))
            {
                $GeofenceChecker=new CHBSGeofenceChecker();
                
                $coordinate=$GeofenceChecker->transformShape($ruleData['meta']['location_geofence_pickup'],$bookingForm['dictionary']['geofence']);
                
                if(is_array($coordinate))
                {
                    $inside=false;
                    
                    $pickupLocation=json_decode($bookingData['pickup_location_coordinate']);
                    
                    foreach($coordinate as $coordinateValue)
                    {
                        $result=$GeofenceChecker->pointInPolygon(new CHBSPoint($pickupLocation->lat,$pickupLocation->lng),$coordinateValue);
                        
                        if($result)
                        {
                            $inside=true;
                            break;
                        }
                    }
                    
                    if(!$inside) continue;
                }
            }   
            
            if(!in_array(-1,$ruleData['meta']['location_geofence_dropoff']))
            {
                $GeofenceChecker=new CHBSGeofenceChecker();
                
                $coordinate=$GeofenceChecker->transformShape($ruleData['meta']['location_geofence_dropoff'],$bookingForm['dictionary']['geofence']);
                
                if(is_array($coordinate))
                {
                    $inside=false;
                    
                    $dropoffLocation=json_decode($bookingData['dropoff_location_coordinate']);
                    
                    foreach($coordinate as $coordinateValue)
                    {
                        $result=$GeofenceChecker->pointInPolygon(new CHBSPoint($dropoffLocation->lat,$dropoffLocation->lng),$coordinateValue);
                        
                        if($result)
                        {
                            $inside=true;
                            break;
                        }
                    }
                    
                    if(!$inside) continue;
                }
            }  
  
            if(!in_array(-1,$ruleData['meta']['pickup_day_number']))
            {
                if(!in_array(date('N',CHBSDate::strtotime($bookingData['pickup_date'])),$ruleData['meta']['pickup_day_number'])) continue;
            }
            
            if(is_array($ruleData['meta']['pickup_date']))
            {
                $match=!count($ruleData['meta']['pickup_date']);
                
                foreach($ruleData['meta']['pickup_date'] as $value)
                {
                    if($Date->dateInRange($bookingData['pickup_date'],$value['start'],$value['stop']))
                    {
                        $match=true;
                        break;
                    }
                }
                
                if(!$match) continue;
            }
            
            if(is_array($ruleData['meta']['pickup_time']))
            {
                $match=!count($ruleData['meta']['pickup_time']);
                
                foreach($ruleData['meta']['pickup_time'] as $value)
                {
                    if($Date->timeInRange($bookingData['pickup_time'],$value['start'],$value['stop']))
                    {
                        $match=true;
                        break;
                    }
                }
                
                if(!$match) continue;
            }
            
            if(in_array($bookingData['service_type_id'],array(1,3)))
            {
                if(is_array($ruleData['meta']['distance']))
                {
                    $match=!count($ruleData['meta']['distance']);

                    foreach($ruleData['meta']['distance'] as $value)
                    {
                        $key=(int)CHBSOption::getOption('pricing_rule_return_use_type')==2 ? 'distance' : 'distance_sum';

                        if(($value['start']<=$bookingData[$key]) && ($bookingData[$key]<=$value['stop']))
                        {
                            $match=true;
                            break;                        
                        }
                    }

                    if(!$match) continue;
                }
            }
            
            if(is_array($ruleData['meta']['distance_base_location']))
            {
                $match=!count($ruleData['meta']['distance_base_location']);
                
                foreach($ruleData['meta']['distance_base_location'] as $value)
                {
                    if(($value['start']<=$bookingData['base_location_distance']) && ($bookingData['base_location_distance']<=$value['stop']))
                    {
                        $match=true;
                        break;                        
                    }
                }
                
                if(!$match) continue;
            }         
            
            if((CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$bookingData['service_type_id'],'adult')) || (CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$bookingData['service_type_id'],'children')))
            {
                if(is_array($ruleData['meta']['passenger']))
                {
                    $match=!count($ruleData['meta']['passenger']);
                    foreach($ruleData['meta']['passenger'] as $value)
                    {
                        if(($value['start']<=$bookingData['passenger_sum']) && ($bookingData['passenger_sum']<=$value['stop']))
                        {
                            $match=true;
                            break;                        
                        }
                    }

                    if(!$match) continue;
                }
            }
            
            if(is_array($ruleData['meta']['duration']))
            {
				if(in_array($bookingData['service_type_id'],array(2))) $key='duration_sum';
				else $key=(int)CHBSOption::getOption('pricing_rule_return_use_type')==2 ? 'duration_map' : 'duration_sum';
                
                $bookingDuration=$bookingData[$key]/60;
                $match=!count($ruleData['meta']['duration']);
                
                foreach($ruleData['meta']['duration'] as $value)
                {
                    if(($value['start']<=$bookingDuration) && ($bookingDuration<=$value['stop']))
                    {
                        $match=true;
                        break;                        
                    }
                }

                if(!$match) continue;
            }                     
                        
            foreach($this->getPriceUseType() as $index=>$value)
            {
                if((int)$ruleData['meta']['price_'.$index.'_alter_type_id']===2)
                {
                    $priceRule['price_'.$index.'_value']=$ruleData['meta']['price_'.$index.'_value'];
                }
                elseif(in_array((int)$ruleData['meta']['price_'.$index.'_alter_type_id'],array(3,4))) 
                {
                    if((int)$ruleData['meta']['price_'.$index.'_alter_type_id']===3)
                        $priceRule['price_'.$index.'_value']+=$ruleData['meta']['price_'.$index.'_value'];
                    if((int)$ruleData['meta']['price_'.$index.'_alter_type_id']===4)
                        $priceRule['price_'.$index.'_value']-=$ruleData['meta']['price_'.$index.'_value'];
                }
                elseif(in_array((int)$ruleData['meta']['price_'.$index.'_alter_type_id'],array(5,6)))
                {
                    if((int)$ruleData['meta']['price_'.$index.'_alter_type_id']===5)
                    {
                        $priceRule['price_'.$index.'_value']=$priceRule['price_'.$index.'_value']*(1+$ruleData['meta']['price_'.$index.'_value']/100); 
                    }
                    elseif((int)$ruleData['meta']['price_'.$index.'_alter_type_id']===6)
                        $priceRule['price_'.$index.'_value']=$priceRule['price_'.$index.'_value']*(1-$ruleData['meta']['price_'.$index.'_value']/100); 
                }
                
                if($priceRule['price_'.$index.'_value']<0)
                    $priceRule['price_'.$index.'_value']=0;
                
                if((int)$ruleData['meta']['price_'.$index.'_tax_rate_id']!==-1)
                    $priceRule['price_'.$index.'_tax_rate_id']=$ruleData['meta']['price_'.$index.'_tax_rate_id'];
            }
            
            $priceRule['price_type']=$ruleData['meta']['price_type'];
            $priceRule['calculation_on_request_enable']=$ruleData['meta']['calculation_on_request_enable'];
            $priceRule['calculation_on_request_redirect_url']=$ruleData['meta']['calculation_on_request_redirect_url'];
            $priceRule['minimum_order_value']=$ruleData['meta']['minimum_order_value'];
            
            if((int)$ruleData['meta']['process_next_rule_enable']!==1) return($priceRule);
        }

        return($priceRule);
    }
    
    /**************************************************************************/
    
    function getDictionary($attr=array())
    {
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'price_rule_id'                                                     =>	0
		);
		
		$attribute=shortcode_atts($default,$attr);
		CHBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'															=>	self::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1,
			'orderby'															=>	array('menu_order'=>'asc')
		);
		
		if($attribute['price_rule_id'])
			$argument['p']=$attribute['price_rule_id'];
               
		$query=new WP_Query($argument);
		if($query===false) return($dictionary);
        
		while($query->have_posts())
		{
			$query->the_post();
			$dictionary[$post->ID]['post']=$post;
			$dictionary[$post->ID]['meta']=CHBSPostMeta::getPostMeta($post);
		}
		
		CHBSHelper::preservePost($post,$bPost,0);	
		
		return($dictionary);        
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/