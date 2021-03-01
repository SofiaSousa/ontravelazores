<?php

/******************************************************************************/
/******************************************************************************/

class CRBSBookingExtra
{
	/**************************************************************************/
	
    function __construct()
    {
        $this->priceType=array
        (
            1                                                                   =>  array(__('Price per entire rental period','car-rental-booking-system')),
            2                                                                   =>  array(__('Price per single day','car-rental-booking-system'))
        );
    }
    
    /**************************************************************************/
    
    public function getPriceType()
    {
        return($this->priceType);
    }
    
    /**************************************************************************/
    
    public function isPriceType($piceTypeId)
    {
        return(array_key_exists($piceTypeId,$this->priceType) ? true : false);
    }
        
    /**************************************************************************/
    
    public function init()
    {
        $this->registerCPT();
    }
    
	/**************************************************************************/

    public static function getCPTName()
    {
        return(PLUGIN_CRBS_CONTEXT.'_booking_extra');
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
					'name'														=>	__('Booking Extras','car-rental-booking-system'),
					'singular_name'												=>	__('Booking Extra','car-rental-booking-system'),
					'add_new'													=>	__('Add New','car-rental-booking-system'),
					'add_new_item'												=>	__('Add New Booking Add-on','car-rental-booking-system'),
					'edit_item'													=>	__('Edit Booking Extra','car-rental-booking-system'),
					'new_item'													=>	__('New Booking Extra','car-rental-booking-system'),
					'all_items'													=>	__('Booking Extras','car-rental-booking-system'),
					'view_item'													=>	__('View Booking Extra','car-rental-booking-system'),
					'search_items'												=>	__('Search Booking Extras','car-rental-booking-system'),
					'not_found'													=>	__('No Booking Extras Found','car-rental-booking-system'),
					'not_found_in_trash'										=>	__('No Booking Extras Found in Trash','car-rental-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('Booking Extras','car-rental-booking-system')
				),	
				'public'														=>	false,  
				'show_ui'														=>	true, 
				'show_in_menu'													=>	'edit.php?post_type='.CRBSBooking::getCPTName(),
				'capability_type'												=>	'post',
				'menu_position'													=>	2,
				'hierarchical'													=>	false,  
				'rewrite'														=>	false,  
				'supports'														=>	array('title','page-attributes')  
			)
		);
        
		register_taxonomy
		(
			self::getCPTCategoryName(),
			self::getCPTName(),
			array
			(
				'label'                                                         =>	__('Booking Extra Categories','car-rental-booking-system'),
                'hierarchical'                                                  =>  false
			)
		);
        
        add_action('save_post',array($this,'savePost'));
        add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
        add_filter('postbox_classes_'.self::getCPTName().'_crbs_meta_box_booking_extra',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }

    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CRBS_CONTEXT.'_meta_box_booking_extra',__('Main','car-rental-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
        
		$data=array();
        
        $TaxRate=new CRBSTaxRate();
        $Vehicle=new CRBSVehicle();
        $Location=new CRBSLocation();
        
        $data['meta']=CRBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CRBSHelper::createNonceField(PLUGIN_CRBS_CONTEXT.'_meta_box_booking_extra');
        
        $data['dictionary']['tax_rate']=$TaxRate->getDictionary();
        $data['dictionary']['location']=$Location->getDictionary();
        $data['dictionary']['price_type']=$this->getPriceType();
        
        $data['dictionary']['vehicle']=$Vehicle->getDictionary();
        
		$Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'admin/meta_box_booking_extra.php');
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
        $TaxRate=new CRBSTaxRate();
        $Vehicle=new CRBSVehicle();
        
        CRBSHelper::setDefault($meta,'location_id',array());
        
        CRBSHelper::setDefault($meta,'description','');
        
        CRBSHelper::setDefault($meta,'quantity_enable','1');
        CRBSHelper::setDefault($meta,'quantity_max','1');
        CRBSHelper::setDefault($meta,'quantity_default','1');
		
		CRBSHelper::setDefault($meta,'button_select_default_state',0);
		
        CRBSHelper::setDefault($meta,'price_type',1);
        
        CRBSHelper::setDefault($meta,'price','0.00');
        
        CRBSHelper::setDefault($meta,'tax_rate_id',$TaxRate->getDefaultTaxPostId());
        
        $dictionary=$Vehicle->getDictionary();
        foreach($dictionary as $index=>$value)
        {
            if(isset($meta['vehicle_price'][$index])) continue;
            
            $meta['vehicle_price'][$index]['status']=0;
            $meta['vehicle_price'][$index]['price']='0.00';
            $meta['vehicle_price'][$index]['tax_rate_id']=$TaxRate->getDefaultTaxPostId();
        }
	}
    
    /**************************************************************************/
    
    function savePost($postId)
    {      
        if(!$_POST) return(false);
        
        if(CRBSHelper::checkSavePost($postId,PLUGIN_CRBS_CONTEXT.'_meta_box_booking_extra_noncename','savePost')===false) return(false);
        
        $TaxRate=new CRBSTaxRate();
        $Vehicle=new CRBSVehicle();
        $Location=new CRBSLocation();
        $Validation=new CRBSValidation();
        
		$option=CRBSHelper::getPostOption();
      
        /***/
        
        $dictionary=$Location->getDictionary();
        foreach($option['location_id'] as $index=>$value)
        {
            if(!array_key_exists($value,$dictionary))
                unset($option['location_id'][$index]);
        }
        
        if(!$Validation->isBool($option['quantity_enable']))
            $option['quantity_enable']=1;

		if(!$Validation->isNumber($option['quantity_max'],1,9999))
			$option['quantity_max']=1;
        
		if(!$Validation->isNumber($option['quantity_default'],1,9999))
			$option['quantity_default']=1;		
		if($option['quantity_default']>$option['quantity_max'])
			$option['quantity_default']=1;
		
		if(!in_array($option['button_select_default_state'],array(0,1,2)))
			$option['button_select_default_state']=0;
		
        if(!$this->isPriceType($option['price_type']))
            $option['price_type']=1;
        
        if(!$Validation->isPrice($option['price'],false))
           $option['price']=0.00;  
		
		$option['price']=CRBSPrice::formatToSave($option['price']);
        
        if(!$TaxRate->isTaxRate($option['tax_rate_id']))
            $option['tax_rate_id']=0;
        
        /***/
        
        $key=array
        (
            'location_id',
            'description',
            'quantity_enable',
            'quantity_max',
			'quantity_default',
			'button_select_default_state',
            'price',
            'price_type',
            'tax_rate_id'
        );
        
		foreach($key as $value)
			CRBSPostMeta::updatePostMeta($postId,$value,$option[$value]);
        
        /***/
        
        $vehiclePrice=array();
        $vehicleDictionary=$Vehicle->getDictionary();
        
        foreach($vehicleDictionary as $index=>$value)
        {
            if(!isset($option['vehicle_price'][$index])) continue;
            
            if(!$Validation->isBool($option['vehicle_price'][$index]['status']))
                $option['vehicle_price'][$index]['status']=0;
            
            if(!$Validation->isPrice($option['vehicle_price'][$index]['price'],false))
                $option['vehicle_price'][$index]['price']=0.00;
        
			$option['vehicle_price'][$index]['price']=CRBSPrice::formatToSave($option['vehicle_price'][$index]['price'],true);
			
            if(!$TaxRate->isTaxRate($option['vehicle_price'][$index]['tax_rate_id']))
                $option['vehicle_price'][$index]['tax_rate_id']=0;
            
            $vehiclePrice[$index]=array
            (
                'status'                                                        =>  $option['vehicle_price'][$index]['status'],
                'price'                                                         =>  $option['vehicle_price'][$index]['price'],
                'tax_rate_id'                                                   =>  $option['vehicle_price'][$index]['tax_rate_id']
            );
        }
        
        CRBSPostMeta::updatePostMeta($postId,'vehicle_price',$vehiclePrice);
    }
    
    /**************************************************************************/
    
    function getDictionary($attr=array())
    {
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'booking_extra_id'                                                  =>	0,
            'category_id'                                                       =>  array()
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
		
		if($attribute['booking_extra_id'])
			$argument['p']=$attribute['booking_extra_id'];

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
    
    function calculatePrice($bookingExtra,$taxRate)
    {
        $Currency=new CRBSCurrency();
        
        /***/
        
        $taxRateValue=0;
        $taxRateId=$bookingExtra['meta']['tax_rate_id'];
        
        if(isset($taxRate[$taxRateId]))
            $taxRateValue=$taxRate[$taxRateId]['meta']['tax_rate_value'];
        
        /***/
        
        $currency=$Currency->getCurrency(CRBSCurrency::getFormCurrency());
        
        /***/
        
        if(!array_key_exists('quantity',$bookingExtra))
            $bookingExtra['quantity']=1;
        
        /***/
        
        $priceNetValue=number_format($bookingExtra['meta']['price']*CRBSCurrency::getExchangeRate(),2,'.',''); 
        $priceGrossValue=CRBSPrice::calculateGross($priceNetValue,$taxRateId);
        
        $sumNetValue=$priceNetValue*$bookingExtra['quantity'];
        $sumGrossValue=$priceGrossValue*$bookingExtra['quantity'];

        $priceGrossFormat=CRBSPrice::format($priceGrossValue,CRBSCurrency::getFormCurrency());
        $sumGrossFormat=CRBSPrice::format($sumGrossValue,CRBSCurrency::getFormCurrency());
        
        $priceNetValue=number_format($priceNetValue,2,'.','');
        $priceGrossValue=number_format($priceGrossValue,2,'.','');       
        
        /***/
        
        $suffix=null;
        
        if((int)$bookingExtra['meta']['quantity_enable']==1)
        {
            $suffix=__(' each per rental','car-rental-booking-system');
            if((int)$bookingExtra['meta']['price_type']==2)
                $suffix=__(' each per day','car-rental-booking-system');
        }
        else 
        {
            $suffix=__(' per rental','car-rental-booking-system'); 
            if((int)$bookingExtra['meta']['price_type']==2)
                $suffix=__(' per day','car-rental-booking-system');            
        }
        
        /***/
        
        $data=array
        (
            'price'                                                             =>  array
            (
                'net'                                                           =>  array
                (
                    'value'                                                     =>  $priceNetValue,
                ),
                'gross'                                                         =>  array
                (
                    'value'                                                     =>  $priceGrossValue,
                    'format'                                                    =>  $priceGrossFormat
                )
            ),
            'sum'                                                               =>  array
            (
                'net'                                                           =>  array
                (
                    'value'                                                     =>  $sumNetValue,
                ),
                'gross'                                                         =>  array
                (
                    'value'                                                     =>  $sumGrossValue,
                    'format'                                                    =>  $sumGrossFormat
                )
            ),
            'suffix'                                                            =>  $suffix,
            'currency'                                                          =>  $currency
        );
        
        return($data);
    }
    
    /**************************************************************************/
    
    function validate($data,$bookingForm,$taxRateDictionary)
    {
		$bookingExtraDictionary=$bookingForm['dictionary']['booking_extra'];
		
        $bookingExtra=array();
        $bookingExtraId=preg_split('/,/',$data['booking_extra_id']);
        
		foreach($bookingExtraDictionary as $index=>$value)
		{
			if((int)$value['meta']['button_select_default_state']===2)
				array_push($bookingExtraId,$index);
		}
		
		$bookingExtraId=array_unique($bookingExtraId,SORT_NUMERIC);
		
        foreach($bookingExtraId as $value)
        {
            if(array_key_exists($value,$bookingExtraDictionary))
            {
                if(!array_key_exists('booking_extra_'.$value.'_quantity',$data)) continue;
                
                $quantity=(int)$data['booking_extra_'.$value.'_quantity'];
                
                if($bookingExtraDictionary[$value]['meta']['quantity_enable']==1) 
                {
                    if(!(($quantity>=1) && ($quantity<=$bookingExtraDictionary[$value]['meta']['quantity_max']))) 
                        $quantity=1;
                }
                else $quantity=1;
                
                /***/
                
                $vehicleId=$data['vehicle_id'];
                
                $price=$bookingExtraDictionary[$value]['meta']['price'];
				
                $taxRateId=$bookingExtraDictionary[$value]['meta']['tax_rate_id'];
                
                if((isset($bookingExtraDictionary[$value]['meta']['vehicle_price'])) && (isset($bookingExtraDictionary[$value]['meta']['vehicle_price'][$vehicleId])))
                {
                    if((int)$bookingExtraDictionary[$value]['meta']['vehicle_price'][$vehicleId]['status']===1)
                    {
                        $price=$bookingExtraDictionary[$value]['meta']['vehicle_price'][$vehicleId]['price'];
                        $taxRateId=$bookingExtraDictionary[$value]['meta']['vehicle_price'][$vehicleId]['tax_rate_id'];
                    }
                }
                
				/***/
				
                if(CRBSCurrency::getBaseCurrency()!=CRBSCurrency::getFormCurrency())
                {				
                    $rate=0;
                    $dictionary=CRBSOption::getOption('currency_exchange_rate');
					
                    if(array_key_exists(CRBSCurrency::getFormCurrency(),$dictionary))
                        $rate=$dictionary[CRBSCurrency::getFormCurrency()];

                    $price*=$rate;
                }
				
                /***/
                
                $sumNet=$price*$quantity;
                
                if($bookingExtraDictionary[$value]['meta']['price_type']==2)
                {
                    $dayCount=CRBSBookingHelper::calculateRentalDayCount($data['pickup_date'],$data['pickup_time'],$data['return_date'],$data['return_time'],$bookingForm['meta'],CRBSOption::getOption('billing_type'));
                    $sumNet*=$dayCount;
                }
                
                $taxValue=0;
                if(isset($taxRateDictionary[$taxRateId]))
                    $taxValue=$taxRateDictionary[$taxRateId]['meta']['tax_rate_value'];

                /***/
				
                $sumGross=CRBSPrice::calculateGross($sumNet,0,$taxValue);
                
                array_push($bookingExtra,array
                (
                    'id'                                                        =>  $value,
                    'name'                                                      =>  $bookingExtraDictionary[$value]['post']->post_title,
                    'price'                                                     =>  $price,
                    'price_gross'                                               =>  CRBSPrice::calculateGross($price,$taxRateId),
                    'price_type'                                                =>  $bookingExtraDictionary[$value]['meta']['price_type'],
                    'quantity'                                                  =>  $quantity,
                    'tax_rate_value'                                            =>  $taxValue,
                    'sum_net'                                                   =>  $sumNet,
                    'sum_gross'                                                 =>  $sumGross
                ));
            }
        }

        return($bookingExtra);
    }
    
    /**************************************************************************/
    
    function manageEditColumns($column)
    {
        $column=array
        (
            'cb'                                                                =>  $column['cb'],
            'title'                                                             =>  __('Title','car-rental-booking-system'),
            'price'                                                             =>  __('Price','car-rental-booking-system'),
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
			case 'price':
                
                echo CRBSPrice::format($meta['price'],CRBSOption::getOption('currency'));
                
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