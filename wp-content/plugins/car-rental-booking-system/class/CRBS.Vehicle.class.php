<?php

/******************************************************************************/
/******************************************************************************/

class CRBSVehicle
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
        return(PLUGIN_CRBS_CONTEXT.'_vehicle');
    }
    
    /**************************************************************************/
    
    public static function getCPTCategoryName()
    {
        return(self::getCPTName().'_c');
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
					'name'														=>	__('Vehicles','car-rental-booking-system'),
					'singular_name'												=>	__('Vehicle','car-rental-booking-system'),
					'add_new'													=>	__('Add New','car-rental-booking-system'),
					'add_new_item'												=>	__('Add New Vehicle','car-rental-booking-system'),
					'edit_item'													=>	__('Edit Vehicle','car-rental-booking-system'),
					'new_item'													=>	__('New Vehicle','car-rental-booking-system'),
					'all_items'													=>	__('Vehicles','car-rental-booking-system'),
					'view_item'													=>	__('View Vehicle','car-rental-booking-system'),
					'search_items'												=>	__('Search Vehicles','car-rental-booking-system'),
					'not_found'													=>	__('No Vehicles Found','car-rental-booking-system'),
					'not_found_in_trash'										=>	__('No Vehicles Found in Trash','car-rental-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('Vehicles','car-rental-booking-system')
				),	
				'public'														=>	false,  
				'show_ui'														=>	true, 
				'show_in_menu'													=>	'edit.php?post_type='.CRBSBooking::getCPTName(),
				'capability_type'												=>	'post',
				'menu_position'													=>	2,
				'hierarchical'													=>	false,  
				'rewrite'														=>	false,  
				'supports'														=>	array('title','editor','page-attributes','thumbnail')
			)
		);
        
		register_taxonomy
		(
			self::getCPTCategoryName(),
			self::getCPTName(),
			array
			(
				'label'                                                         =>	__('Vehicle Types','car-rental-booking-system'),
                'hierarchical'                                                  =>  false
			)
		);
        
        add_action('save_post',array($this,'savePost'));
        add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
        add_filter('postbox_classes_'.self::getCPTName().'_crbs_meta_box_vehicle',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }

    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CRBS_CONTEXT.'_meta_box_vehicle',__('Main','car-rental-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
        
		$data=array();
        
        $TaxRate=new CRBSTaxRate();
        $Location=new CRBSLocation();
        $VehicleAttribute=new CRBSVehicleAttribute();
        
        $data['meta']=CRBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CRBSHelper::createNonceField(PLUGIN_CRBS_CONTEXT.'_meta_box_vehicle');
       
        $data['dictionary']['tax_rate']=$TaxRate->getDictionary();
        $data['dictionary']['vehicleAttribute']=$VehicleAttribute->getDictionary();
        
        $data['dictionary']['location']=$Location->getDictionary();
       
		$Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'admin/meta_box_vehicle.php');
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
        
        if(CRBSHelper::checkSavePost($postId,PLUGIN_CRBS_CONTEXT.'_meta_box_vehicle_noncename','savePost')===false) return(false);
        
        $Date=new CRBSDate();
        $TaxRate=new CRBSTaxRate();
        $Location=new CRBSLocation();
		$PriceRule=new CRBSPriceRule();
        $Validation=new CRBSValidation();
        
        $option=CRBSHelper::getPostOption();
        
        $dictionary=$Location->getDictionary();
        foreach($option['location_id'] as $index=>$value)
        {
            if(!array_key_exists($value,$dictionary))
                unset($option['location_id'][$index]);
        }
		
		if((!is_array($option['location_id'])))
			$option['location_id']=array();
        
        if(!$Validation->isNumber($option['passenger_count'],1,99)) 
            $option['passenger_count']=4;
        if(!$Validation->isNumber($option['bag_count'],1,99)) 
            $option['bag_count']=4;    
        
        if(!$Validation->isNumber($option['driver_age_min'],0,99)) 
            $option['driver_age_min']=15;
        if(!$Validation->isNumber($option['driver_age_max'],0,99)) 
            $option['driver_age_max']=99;        
  
        if(!$Validation->isBool($option['booking_vehicle_similar_enable'])) 
            $option['booking_vehicle_similar_enable']=0;           
        
        if(!in_array($option['vehicle_type'],array(1,2)))
            $option['vehicle_type']=1;
        
		foreach($PriceRule->getPriceUseType() as $index=>$value)
		{
            if(!$Validation->isPrice($option['price_'.$index.'_value'],false))
                $option['price_'.$index.'_value']=0.00;
         
			$taxRateIndex=$PriceRule->getTaxRateIndexName($index);
	
			if(!$TaxRate->isTaxRate($option['price_'.$taxRateIndex.'_tax_rate_id']))
				$option['price_'.$taxRateIndex.'_tax_rate_id']=0; 
        }
		
        /***/
        
        $attribute=array();
        
        $attributePost=$option['attribute'];
        
        $VehicleAttribute=new CRBSVehicleAttribute();
        $attributeDictionary=$VehicleAttribute->getDictionary();

        foreach($attributeDictionary as $attributeDictionaryIndex=>$attributeDictionaryValue)
        {
            if(!isset($attributePost[$attributeDictionaryIndex])) continue;
            
            switch($attributeDictionaryValue['meta']['attribute_type'])
            {
                case 1:
                    
                    $attribute[$attributeDictionaryIndex]=$attributePost[$attributeDictionaryIndex];
                    
                break;
                
                case 2:
                case 3:
                    
                    if(!is_array($attributePost[$attributeDictionaryIndex])) break;
                    
                    foreach($attributeDictionaryValue['meta']['attribute_value'] as $value)
                    {
                        if(in_array($value['id'],$attributePost[$attributeDictionaryIndex]))
                        {
                            if($attributeDictionaryValue['meta']['attribute_type']===2)
                            {
                                $attribute[$attributeDictionaryIndex]=(int)$value['id'];
                                break;
                            }
                            else $attribute[$attributeDictionaryIndex][]=(int)$value['id'];
                        }
                    }
    
                break;
            }
        }
        
        /***/
        
		$dateExclude=array();
        $dateExcludePost=CRBSHelper::getPostValue('date_exclude');
        
        $count=count($dateExcludePost);
        
        for($i=0;$i<$count;$i+=4)
		{
            $dateExcludePost[$i]=$Date->formatDateToStandard($dateExcludePost[$i]);
            $dateExcludePost[$i+1]=$Date->formatTimeToStandard($dateExcludePost[$i+1]);
            $dateExcludePost[$i+2]=$Date->formatDateToStandard($dateExcludePost[$i+2]);
            $dateExcludePost[$i+3]=$Date->formatTimeToStandard($dateExcludePost[$i+3]);
            
            if($Validation->isEmpty($dateExcludePost[$i+1])) $dateExcludePost[$i+1]='00:00';
            if($Validation->isEmpty($dateExcludePost[$i+3])) $dateExcludePost[$i+3]='00:00';
            
			if(!$Validation->isDate($dateExcludePost[$i],true)) continue;
			if(!$Validation->isDate($dateExcludePost[$i+2],true)) continue;

			if(!$Validation->isTime($dateExcludePost[$i+1],true)) continue;
			if(!$Validation->isTime($dateExcludePost[$i+3],true)) continue;
            
			if($Date->compareDate($dateExcludePost[$i],$dateExcludePost[$i+2])==1) continue;
            if(($Date->compareDate(date_i18n('d-m-Y'),$dateExcludePost[$i])==1) && ($Date->compareDate(date_i18n('d-m-Y'),$dateExcludePost[$i+2])==1)) continue;
            
            if($Date->compareDate($dateExcludePost[$i],$dateExcludePost[$i+2])===0)
            {
                if($Date->compareTime($dateExcludePost[$i+1],$dateExcludePost[$i+3])===1) continue;
            }
            
			$dateExclude[]=array('start_date'=>$dateExcludePost[$i],'start_time'=>$dateExcludePost[$i+1],'stop_date'=>$dateExcludePost[$i+2],'stop_time'=>$dateExcludePost[$i+3]);
		}
        
        /***/
        
        $key=array
        (
            'location_id',
            'vehicle_make',
            'vehicle_model',
            'passenger_count',
            'bag_count',
            'fuel_state',
            'gearbox_type',
            'driver_age_min',
            'driver_age_max',
            'booking_vehicle_similar_enable',
            'group_code',
        );
		
        foreach($PriceRule->getPriceUseType() as $index=>$value)
            array_push($key,'price_'.$index.'_value','price_'.$PriceRule->getTaxRateIndexName($index).'_tax_rate_id');
        
		array_unique($key);
		
		foreach($key as $value)
			CRBSPostMeta::updatePostMeta($postId,$value,$option[$value]);
        
        CRBSPostMeta::updatePostMeta($postId,'attribute',$attribute);
        CRBSPostMeta::updatePostMeta($postId,'date_exclude',$dateExclude);
    }
    
	/**************************************************************************/
	
	function setPostMetaDefault(&$meta)
	{
        $TaxRate=new CRBSTaxRate();
		$PriceRule=new CRBSPriceRule();
        $VehicleAttribute=new CRBSVehicleAttribute();
        
        CRBSHelper::setDefault($meta,'location_id',array());
        
		CRBSHelper::setDefault($meta,'vehicle_make','');
        CRBSHelper::setDefault($meta,'vehicle_model','');
        
        CRBSHelper::setDefault($meta,'passenger_count','4');
        CRBSHelper::setDefault($meta,'bag_count','4');
        
		CRBSHelper::setDefault($meta,'fuel_state','');
        CRBSHelper::setDefault($meta,'gearbox_type','');        
        
        CRBSHelper::setDefault($meta,'driver_age_min',15); 
        CRBSHelper::setDefault($meta,'driver_age_max',99); 
        
        CRBSHelper::setDefault($meta,'booking_vehicle_similar_enable',0); 
        
        CRBSHelper::setDefault($meta,'group_code',null); 
        
        foreach($PriceRule->getPriceUseType() as $index=>$value)
        {
            CRBSHelper::setDefault($meta,'price_'.$index.'_value','0.00');
			
			$taxRateIndex=$PriceRule->getTaxRateIndexName($index);
			
			CRBSHelper::setDefault($meta,'price_'.$taxRateIndex.'_tax_rate_id',$TaxRate->getDefaultTaxPostId());   
        }
        
        $attribute=$VehicleAttribute->getDictionary();
        foreach($attribute as $attributeIndex=>$attributeData)
        {
            if(isset($meta['attribute'][$attributeIndex])) continue;
            
            if($attributeData['meta']['attribute_type']==1)
                $meta['attribute'][$attributeIndex]='';
            else $meta['attribute'][$attributeIndex]=array(-1);
        }
        
		if(!array_key_exists('date_exclude',$meta))
			$meta['date_exclude']=array();
	}
    
    /**************************************************************************/
    
    function getDictionary($attr=array(),$sortingType=1)
    {
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'vehicle_id'           												=>	0,
            'category_id'                                                       =>  0
		);
		
		$attribute=shortcode_atts($default,$attr);
        
        $Validation=new CRBSValidation();
		
		CRBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'															=>	self::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1,
			'orderby'															=>	array('menu_order'=>((int)$sortingType===4 ? 'desc' : 'asc'))
		);
		
		if($attribute['vehicle_id'])
			$argument['p']=$attribute['vehicle_id'];
 
        if(!is_array($attribute['category_id']))
            $attribute['category_id']=array($attribute['category_id']);

        if(array_sum($attribute['category_id']))
        {
            $argument['tax_query']=array
            (
                array
                (
                    'taxonomy'                                                  =>  self::getCPTCategoryName(),
                    'field'                                                     =>  'term_id',
                    'terms'                                                     =>  $attribute['category_id'],
                    'operator'                                                  =>  'IN'
                )
            );
        }

        $query=new WP_Query($argument);
		if($query===false) return($dictionary);
 
		while($query->have_posts())
		{
			$query->the_post();
			$dictionary[$post->ID]['post']=$post;
			$dictionary[$post->ID]['meta']=CRBSPostMeta::getPostMeta($post);
            
            if($Validation->isEmpty($post->post_title))
                $post->post_title=trim($dictionary[$post->ID]['meta']['vehicle_make'].' '.$dictionary[$post->ID]['meta']['vehicle_model']);
		}
        
		CRBSHelper::preservePost($post,$bPost,0);
		
		return($dictionary);        
    }
    
    /**************************************************************************/
    
    function getCategory()
    {
        $category=array();
        
        $result=get_terms(self::getCPTCategoryName());
        if(is_wp_error($result)) return($category);
        
        foreach($result as $value)
            $category[$value->{'term_id'}]=array('name'=>$value->{'name'});
        
        return($category);
    }
    
    /**************************************************************************/
    
    function manageEditColumns($column)
    {
        $column=array
        (
            'cb'                                                                =>  $column['cb'],
            'thumbnail'                                                         =>  __('Thumbnail','car-rental-booking-system'),
            'title'                                                             =>  __('Title','car-rental-booking-system'),
            'vehicle_make_model'                                                =>  __('Vehicle make and model','car-rental-booking-system'),
            'passenger_bag_count'                                               =>  __('Number of seats and bags','car-rental-booking-system'),
            'price'                                                             =>  __('Net prices','car-rental-booking-system'),
            'date'                                                              =>  $column['date']
        );
   
		return($column);          
    }
    
    /**************************************************************************/
    
    function managePostsCustomColumn($column)
    {
		global $post;
		
		$PriceRule=new CRBSPriceRule();
		
		$meta=CRBSPostMeta::getPostMeta($post);
        
		switch($column) 
		{
			case 'thumbnail':
				
                echo get_the_post_thumbnail($post,PLUGIN_CRBS_CONTEXT.'_vehicle');
                
			break;
        
			case 'vehicle_make_model':
				
                echo esc_html(trim($meta['vehicle_make'].' '.$meta['vehicle_model']));
                
			break;
        
			case 'passenger_bag_count':
				
                echo 
                '
                    <table class="to-table-post-list">
                        <tr>
                            <td>'.__('Seats','car-rental-booking-system').'</td>
                            <td>'.esc_html($meta['passenger_count']).'</td>
                        </tr>
                        <tr>
                            <td>'.__('Bags','car-rental-booking-system').'</td>
                            <td>'.esc_html($meta['bag_count']).'</td>
                        </tr>
					</table>
				';
	
			break;
        
			case 'price':
				
                echo 
                '
                    <table class="to-table-post-list">
				';
				
				foreach($PriceRule->getPriceUseType() as $index=>$value)
				{
					echo 
					'
						<tr>
                            <td>'.esc_html($value[0]).'</td>
                            <td>'.CRBSPrice::format($meta['price_'.$index.'_value'],CRBSOption::getOption('currency')).'</td>
                        </tr>	
					';
				}
				
				echo
				'
					</table>
				';

			break;
        }
    }
    
    /**************************************************************************/
    
    function manageEditSortableColumns($column)
    {
		return($column);       
    }
    
    /**************************************************************************/
    
    function checkAvailability($dictionary,$pickupDate,$pickupTime,$returnDate,$returnTime,$pickupLocationId)
    {    
        $Location=new CRBSLocation();
        $Validation=new CRBSValidation();
        
        /***/
        
        $location=$Location->getDictionary(array('location_id'=>$pickupLocationId));
        if(!isset($location[$pickupLocationId])) return($dictionary);
        
        $checkType=$location[$pickupLocationId]['meta']['vehicle_availability_check_type'];
        
        /***/
        
        if(in_array(1,$checkType)) return($dictionary); 
        
        if(($Validation->isDate($pickupDate)) && ($Validation->isTime($pickupTime)) && ($Validation->isDate($returnDate)) && ($Validation->isTime($returnTime)))
        {
            if(in_array(2,$checkType))
            {
                foreach($dictionary as $dictionaryIndex=>$dictionaryValue)
                {
                    $meta=$dictionaryValue['meta'];

                    if(!array_key_exists('date_exclude',$meta)) continue;

                    foreach($meta['date_exclude'] as $value)
                    {
                        $dateStart=strtotime($value['start_date'].' '.$value['start_time']);
                        $dateStop=strtotime($value['stop_date'].' '.$value['stop_time']);

                        $dateRentalStart=strtotime($pickupDate.' '.$pickupTime);
                        $dateRentalStop=strtotime($returnDate.' '.$returnTime);

                        $b=array_fill(0,4,false);

                        $b[0]=CRBSHelper::valueInRange($dateRentalStart,$dateStart,$dateStop);
                        $b[1]=CRBSHelper::valueInRange($dateRentalStop,$dateStart,$dateStop);
                        $b[2]=CRBSHelper::valueInRange($dateStart,$dateRentalStart,$dateRentalStop);
                        $b[3]=CRBSHelper::valueInRange($dateStop,$dateRentalStart,$dateRentalStop);

                        if(in_array(true,$b,true))
                        {
                            unset($dictionary[$dictionaryIndex]);
                            break;                    
                        }
                    }
                }
            }
        
            /***/
        
            if(in_array(3,$checkType))
            {
                if(count($dictionary))
                {
                    $WPML=new CRBSWPML();
                    $Booking=new CRBSBooking();

                    $argument=array
                    (
                        'post_type'                                             =>	$Booking::getCPTName(),
                        'post_status'											=>	'publish',
                        'posts_per_page'                                        =>	-1,
                        'meta_query'                                            =>  array
                        (
                            array
                            (
                                'key'                                           =>  PLUGIN_CRBS_CONTEXT.'_vehicle_id',
                                'value'                                         =>  array_keys($WPML->translateDictionary($dictionary)),
                                'compare'                                       =>  'IN'
                            ),
                            array
                            (
                                'key'                                           =>  PLUGIN_CRBS_CONTEXT.'_booking_status_id',
                                'value'                                         =>  array(1,2),
                                'compare'                                       =>  'IN'
                            )
                        )
                    );

                    global $post;

                    CRBSHelper::preservePost($post,$bPost);

                    $query=new WP_Query($argument);
                    if($query===false) 
                    {
                        CRBSHelper::preservePost($post,$bPost,0);
                        return($dictionary); 
                    }

                    while($query->have_posts())
                    {
                        $query->the_post();

                        $meta=CRBSPostMeta::getPostMeta($post);

                        $bookingStart=strtotime($meta['pickup_date'].' '.$meta['pickup_time']);
                        $bookingStop=strtotime($meta['return_date'].' '.$meta['return_time'].' '.(int)$location[$pickupLocationId]['meta']['booking_interval'].' minute');

                        $bookingCurrentStart=strtotime($pickupDate.' '.$pickupTime);
                        $bookingCurrentStop=strtotime($returnDate.' '.$returnTime);

                        $b=array_fill(0,4,false);

                        $b[0]=CRBSHelper::valueInRange($bookingCurrentStart,$bookingStart,$bookingStop);
                        $b[1]=CRBSHelper::valueInRange($bookingCurrentStop,$bookingStart,$bookingStop);
                        $b[2]=CRBSHelper::valueInRange($bookingStart,$bookingCurrentStart,$bookingCurrentStop);
                        $b[3]=CRBSHelper::valueInRange($bookingStop,$bookingCurrentStart,$bookingCurrentStop);
                        
                        if(in_array(true,$b,true))
                        {
                            foreach($dictionary as $index=>$value)
                            {
                                $tIndex=$WPML->translateID($index);
                                if($tIndex==$meta['vehicle_id'])
                                    unset($dictionary[$index]);
                            }
            
                            continue;                    
                        }
                    }            

                    CRBSHelper::preservePost($post,$bPost,0);
                }
            }
        }
            
        /***/
        
        return($dictionary);
    }
    
    /**************************************************************************/
    
    function getVehicleAttribute(&$vehicle)
    {
        $Validation=new CRBSValidation();
        $VehicleAttribute=new CRBSVehicleAttribute();
        
        $dictionary=$VehicleAttribute->getDictionary();
 
        foreach($vehicle as $vehicleIndex=>$vehicleValue)
        {
            foreach($vehicleValue['meta']['attribute'] as $vehicleAttributeIndex=>$vehicleAttributeValue)
            {
                if(!isset($dictionary[$vehicleAttributeIndex])) continue;
                
                switch($dictionary[$vehicleAttributeIndex]['meta']['attribute_type'])
                {
                    case 1:
                        
                        if($Validation->isNotEmpty($vehicleAttributeValue))
                            $vehicle[$vehicleIndex]['attribute'][$vehicleAttributeIndex]=array('name'=>get_the_title($vehicleAttributeIndex),'value'=>$vehicleAttributeValue);
                        
                    break;
                
                    case 2:
                    case 3:
                        
                        $value=null;
                        
                        foreach($vehicleAttributeValue as $vehicleAttributeValueValue)
                        {
                            foreach($dictionary[$vehicleAttributeIndex]['meta']['attribute_value'] as $dictionaryAttributeValue)
                            {
                                if($dictionaryAttributeValue['id']===$vehicleAttributeValueValue)
                                {
                                    if(!$Validation->isEmpty($value)) $value.=', ';
                                    $value.=$dictionaryAttributeValue['value'];
                                }
                            }
                        }
                        
                        if($Validation->isNotEmpty($value))
                            $vehicle[$vehicleIndex]['attribute'][$vehicleAttributeIndex]=array('name'=>get_the_title($vehicleAttributeIndex),'value'=>$value);
          
                    break;
                }
            }
            
            if(array_key_exists('attribute',$vehicle[$vehicleIndex]))
            {
                $attribute=$vehicle[$vehicleIndex]['attribute'];

                $vehicle[$vehicleIndex]['attribute']=array();

                foreach($dictionary as $index=>$value)
                {
                    if(array_key_exists($index,$attribute))
                        $vehicle[$vehicleIndex]['attribute'][$index]=$attribute[$index];
                }
            }
        }
    }
    
    /**************************************************************************/
    
    function calculatePrice($data,$bookingForm,&$discountPercentage=0,$calculateHiddenFee=true)
    {	
        $Currency=new CRBSCurrency();
        $PriceRule=new CRBSPriceRule();
        $BookingForm=new CRBSBookingForm();
        
        /***/
		
        list($pickupLocationId,$pickupLocationCustomerAddress,$pickupLocationIdSelect)=$BookingForm->getBookingFormPickupLocation($bookingForm);
        list($returnLocationId,$returnLocationCustomerAddress,$returnLocationIdSelect)=$BookingForm->getBookingFormReturnLocation($bookingForm);
        
		$priceBase=array();
       
        $priceBase=$PriceRule->extractPriceFromData($priceBase,$bookingForm['dictionary']['vehicle'][$data['vehicle_id']]['meta']);
				
        $argument=array
        (
            'booking_form_id'                                                   =>  (int)$data['booking_form_id'],
            'vehicle_id'                                                        =>  (int)$data['vehicle_id'],
            'pickup_location_id'                                                =>  $pickupLocationId,
			'pickup_location_address_data'										=>	$pickupLocationCustomerAddress,
            'pickup_date'                                                       =>  $data['pickup_date'],
            'pickup_time'                                                       =>  $data['pickup_time'],
            'return_location_id'                                                =>  $returnLocationId,
			'return_location_address_data'										=>	$returnLocationCustomerAddress,
            'return_date'                                                       =>  $data['return_date'],
            'return_time'                                                       =>  $data['return_time'],
            'driver_age'                                                        =>  $data['driver_age']
        );
            
		$priceBase=$PriceRule->getPriceFromRule($argument,$bookingForm,$priceBase);

        /***/
        
		$currency=$Currency->getCurrency(CRBSCurrency::getFormCurrency());
		
        $rate=CRBSCurrency::getExchangeRate(); 
        foreach($priceBase as $index=>$value)
        {
            if(preg_match('/\_value$/',$index,$result))
                $priceBase[$index]=$priceBase[$index]*$rate;
        } 
				
		/***/
		
		$vehicle=$bookingForm['dictionary']['vehicle'][$data['vehicle_id']];
		
		/***/
		
        $rentalDayCount=CRBSBookingHelper::calculateRentalDayCount($data['pickup_date'],$data['pickup_time'],$data['return_date'],$data['return_time'],$bookingForm['meta'],CRBSOption::getOption('billing_type'));
        
        /***/
  
        $price=array();
        
        $period=CRBSBookingHelper::calculateRentalPeriod($data['pickup_date'],$data['pickup_time'],$data['return_date'],$data['return_time'],$bookingForm['meta'],CRBSOption::getOption('billing_type'));
        
        /***/
        
        $Coupon=new CRBSCoupon();
        $coupon=$Coupon->checkCode();
        
        if($coupon!==false)
        {
            $discountPercentage=$coupon['meta']['discount_percentage'];
            $discountFixed=$coupon['meta']['discount_fixed'];
            
			if((int)CRBSOption::getOption('billing_type')===2)
			{
				if(array_key_exists('discount_rental_day_count',$coupon['meta']))
				{
					if(is_array($coupon['meta']['discount_rental_day_count']))
					{
						foreach($coupon['meta']['discount_rental_day_count'] as $index=>$value)
						{
							if((($value['start']<=$period['day']) && ($value['stop']>=$period['day'])))
							{
								if($value['discount_percentage']>0)
								{
									$discountPercentage=$value['discount_percentage'];
								}
								elseif($value['discount_fixed']>0)
								{
									$discountPercentage=0;
									$discountFixed=$value['discount_fixed'];
								}
								
								break;
							}
						}
					}
				}
			}
			
            if($discountPercentage==0)
            {
                if($discountFixed>0)
                {
                    $discountPercentage=$Coupon->calculateDiscountPercentage($discountFixed,$period['day'],$period['hour'],$priceBase['price_rental_day_value'],$priceBase['price_rental_hour_value']);
				}
            }
            
            $priceBase['price_rental_day_value']=round($priceBase['price_rental_day_value']*(1-$discountPercentage/100),2);
            $priceBase['price_rental_hour_value']=round($priceBase['price_rental_hour_value']*(1-$discountPercentage/100),2);
        }
        
        $price['price']['sum']['net']['value']=$priceBase['price_rental_day_value']*$period['day']+$priceBase['price_rental_hour_value']*$period['hour'];
        $price['price']['sum']['net']['format']=CRBSPrice::format($priceBase['price_rental_day_value']*$period['day']+$priceBase['price_rental_hour_value']*$period['hour'],CRBSCurrency::getFormCurrency());
        $price['price']['sum']['gross']['value']=CRBSPrice::calculateGross($price['price']['sum']['net']['value'],$priceBase['price_rental_tax_rate_id']);  
        $price['price']['sum']['gross']['format']=CRBSPrice::format($price['price']['sum']['gross']['value'],CRBSCurrency::getFormCurrency());
        
        /***/
        
        if((int)CRBSOption::getOption('billing_type')===2)
        {
            $price['price']['price_per_day']['gross']['value']=$price['price']['sum']['gross']['value']/$rentalDayCount;
        }
        else
        {
           $price['price']['price_per_day']['gross']['value']=0.00;
        }
        
        $price['price']['price_per_day']['gross']['format']=CRBSPrice::format($price['price']['price_per_day']['gross']['value'],CRBSCurrency::getFormCurrency());
            
        /***/
				
        $price['price']['deposit']['net']['value']=$priceBase['price_deposit_value'];
        $price['price']['deposit']['net']['format']=CRBSPrice::format($price['price']['deposit']['net']['value'],CRBSCurrency::getFormCurrency());
        $price['price']['deposit']['gross']['value']=CRBSPrice::calculateGross($price['price']['deposit']['net']['value'],$priceBase['price_deposit_tax_rate_id']);  
        $price['price']['deposit']['gross']['format']=CRBSPrice::format($price['price']['deposit']['gross']['value'],CRBSCurrency::getFormCurrency());
        
		/***/
		
        $price['price']['initial']['net']['value']=$priceBase['price_initial_value'];
        $price['price']['initial']['net']['format']=CRBSPrice::format($price['price']['initial']['net']['value'],CRBSCurrency::getFormCurrency());
        $price['price']['initial']['gross']['value']=CRBSPrice::calculateGross($price['price']['initial']['net']['value'],$priceBase['price_initialt_tax_rate_id']);  
        $price['price']['initial']['gross']['format']=CRBSPrice::format($price['price']['initial']['gross']['value'],CRBSCurrency::getFormCurrency());

		
        /***/
        
        if(($pickupLocationId==$returnLocationId) && ($pickupLocationCustomerAddress===false) && ($returnLocationCustomerAddress===false))
            $priceBase['price_one_way_value']=0;
        
        $price['price']['one_way']['net']['value']=$priceBase['price_one_way_value'];
        $price['price']['one_way']['net']['format']=CRBSPrice::format($price['price']['one_way']['net']['value'],CRBSCurrency::getFormCurrency());
        $price['price']['one_way']['gross']['value']=CRBSPrice::calculateGross($price['price']['one_way']['net']['value'],$priceBase['price_one_way_tax_rate_id']);
        $price['price']['one_way']['gross']['format']=CRBSPrice::format($price['price']['one_way']['gross']['value'],CRBSCurrency::getFormCurrency());
            
        /***/
		
		$dayNumber=date('N',strtotime($data['return_date']));
		
        $date1=strtotime($data['pickup_date'].' '.$data['pickup_time']);
        $date2=strtotime($data['pickup_date'].' '.$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['business_hour'][$dayNumber]['start']);
        $date3=strtotime($data['pickup_date'].' '.$bookingForm['dictionary']['location'][$pickupLocationId]['meta']['business_hour'][$dayNumber]['stop']);

        if(($date1>=$date2) && ($date1<=$date3))
            $priceBase['price_after_business_hour_pickup_value']=0;
        
        $price['price']['after_business_hour_pickup']['net']['value']=$priceBase['price_after_business_hour_pickup_value'];
        $price['price']['after_business_hour_pickup']['net']['format']=CRBSPrice::format($price['price']['after_business_hour_pickup']['net']['value'],CRBSCurrency::getFormCurrency());
        $price['price']['after_business_hour_pickup']['gross']['value']=CRBSPrice::calculateGross($price['price']['after_business_hour_pickup']['net']['value'],$priceBase['price_after_business_hour_pickup_tax_rate_id']);
        $price['price']['after_business_hour_pickup']['gross']['format']=CRBSPrice::format($price['price']['after_business_hour_pickup']['gross']['value'],CRBSCurrency::getFormCurrency());
		
		/***/
        
        $date1=strtotime($data['return_date'].' '.$data['return_time']);
        $date2=strtotime($data['return_date'].' '.$bookingForm['dictionary']['location'][$returnLocationId]['meta']['business_hour'][$dayNumber]['start']);
        $date3=strtotime($data['return_date'].' '.$bookingForm['dictionary']['location'][$returnLocationId]['meta']['business_hour'][$dayNumber]['stop']);

        if(($date1>=$date2) && ($date1<=$date3))
            $priceBase['price_after_business_hour_return_value']=0;
        
        $price['price']['after_business_hour_return']['net']['value']=$priceBase['price_after_business_hour_return_value'];
        $price['price']['after_business_hour_return']['net']['format']=CRBSPrice::format($price['price']['after_business_hour_return']['net']['value'],CRBSCurrency::getFormCurrency());
        $price['price']['after_business_hour_return']['gross']['value']=CRBSPrice::calculateGross($price['price']['after_business_hour_return']['net']['value'],$priceBase['price_after_business_hour_return_tax_rate_id']);
        $price['price']['after_business_hour_return']['gross']['format']=CRBSPrice::format($price['price']['after_business_hour_return']['gross']['value'],CRBSCurrency::getFormCurrency());
        
        /***/
        
        $price['other']['delivery']['distance']=$this->calculateDeliverDistance($data,$bookingForm,'pickup');
        $price['other']['delivery_return']['distance']=$this->calculateDeliverDistance($data,$bookingForm,'return');
        
        $price['price']['delivery']['net']['value']=$priceBase['price_delivery_value']*$price['other']['delivery']['distance'];
        $price['price']['delivery']['net']['format']=CRBSPrice::format($priceBase['price_delivery_value']*$price['other']['delivery']['distance'],CRBSCurrency::getFormCurrency());
        $price['price']['delivery']['gross']['value']=CRBSPrice::calculateGross($price['price']['delivery']['net']['value'],$priceBase['price_delivery_tax_rate_id']);
        $price['price']['delivery']['gross']['format']=CRBSPrice::format($price['price']['delivery']['gross']['value'],CRBSCurrency::getFormCurrency());        
        
        $price['price']['delivery_return']['net']['value']=$priceBase['price_delivery_return_value']*$price['other']['delivery_return']['distance'];
        $price['price']['delivery_return']['net']['format']=CRBSPrice::format($priceBase['price_delivery_return_value']*$price['other']['delivery_return']['distance'],CRBSCurrency::getFormCurrency());  
        $price['price']['delivery_return']['gross']['value']=CRBSPrice::calculateGross($price['price']['delivery_return']['net']['value'],$priceBase['price_delivery_return_tax_rate_id']);
        $price['price']['delivery_return']['gross']['format']=CRBSPrice::format($price['price']['delivery_return']['gross']['value'],CRBSCurrency::getFormCurrency());        
        
        /***/
		
		if($pickupLocationCustomerAddress!==false)
			$price['price']['customer_pickup_location']['net']['value']=$priceBase['price_customer_pickup_location_value'];
		else $priceBase['price_customer_pickup_location_value']=0; 
		
        $price['price']['customer_pickup_location']['net']['format']=CRBSPrice::format($price['price']['customer_pickup_location']['net']['value'],CRBSCurrency::getFormCurrency());
        $price['price']['customer_pickup_location']['gross']['value']=CRBSPrice::calculateGross($price['price']['customer_pickup_location']['net']['value'],$priceBase['price_customer_pickup_location_tax_rate_id']);
        $price['price']['customer_pickup_location']['gross']['format']=CRBSPrice::format($price['price']['customer_pickup_location']['gross']['value'],CRBSCurrency::getFormCurrency());
		
		/***/
		
		if(($returnLocationCustomerAddress!==false)|| (($pickupLocationCustomerAddress!==false) && ((int)$returnLocationIdSelect===-1)))
			$price['price']['customer_return_location']['net']['value']=$priceBase['price_customer_return_location_value'];
		else $priceBase['price_customer_return_location_value']=0; 
		
        $price['price']['customer_return_location']['net']['format']=CRBSPrice::format($price['price']['customer_return_location']['net']['value'],CRBSCurrency::getFormCurrency());
        $price['price']['customer_return_location']['gross']['value']=CRBSPrice::calculateGross($price['price']['customer_return_location']['net']['value'],$priceBase['price_customer_return_location_tax_rate_id']);
        $price['price']['customer_return_location']['gross']['format']=CRBSPrice::format($price['price']['customer_return_location']['gross']['value'],CRBSCurrency::getFormCurrency());
		
		/***/
        
        $price['currency']=$currency;

        /***/
       
        if(((int)$bookingForm['meta']['booking_summary_hide_fee']===1) && ($calculateHiddenFee))
        {
            $data['booking_form']=$bookingForm;
           
            $Booking=new CRBSBooking();
            $priceBooking=$Booking->calculatePrice($data,$price,true);
            
            $price['price']['sum']['gross']['value']=number_format($priceBooking['vehicle']['sum']['gross']['value'],2,'.','');
            $price['price']['sum']['net']['value']=number_format($priceBooking['vehicle']['sum']['net']['value'],2,'.','');
            
            $price['price']['sum']['gross']['format']=CRBSPrice::format($price['price']['sum']['gross']['value'],CRBSCurrency::getFormCurrency());
            $price['price']['sum']['net']['format']=CRBSPrice::format($price['price']['sum']['net']['value'],CRBSCurrency::getFormCurrency());
        }
        
        /***/
        
        $price['price']['base']=$priceBase;
		
        return($price);
    }
    
    /**************************************************************************/
    
    function calculateDeliverDistance($data,$bookingForm,$type='pickup')
    {
        $GoogleMap=new CRBSGoogleMap();
        
        $option=CRBSHelper::getPostOption();
        
        if(($option[$type.'_location_id']<0) && ((abs($option[$type.'_location_id'])==$data[$type.'_location_id'])))
        {
            if($type==='pickup')
            {
                $start=$bookingForm['dictionary']['location'][$data['pickup_location_id']]['meta'];
                $stop=json_decode($option['pickup_location_address_data']);

                return($GoogleMap->getDistance(array(array($start['coordinate_latitude'],$start['coordinate_longitude']),array($stop->{'lat'},$stop->{'lng'}))));
            }
            else
            {
                $start=json_decode($option['return_location_address_data']);
                $stop=$bookingForm['dictionary']['location'][$data['return_location_id']]['meta'];
                    
                return($GoogleMap->getDistance(array(array($start->{'lat'},$start->{'lng'}),array($stop['coordinate_latitude'],$stop['coordinate_longitude']))));
            }
        }
        
        return(0);
    }
        
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/