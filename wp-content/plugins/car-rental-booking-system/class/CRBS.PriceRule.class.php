<?php

/******************************************************************************/
/******************************************************************************/

class CRBSPriceRule
{
	/**************************************************************************/
	
    function __construct()
    {
		$this->priceSourceType													=	array
		(
			1																	=>	array(__('Set directly in rule','car-rental-booking-system')),
			2																	=>	array(__('Calculation based on rental dates','car-rental-booking-system')),
			3																	=>	array(__('Calculation based on rental days number (all ranges)','car-rental-booking-system')),
			4																	=>	array(__('Calculation based on rental days number (exact range)','car-rental-booking-system')),
		);
		
        $this->priceAlterType=array
        (
            1                                                                   =>  array(__('- Inherited -','car-rental-booking-system')),
            2                                                                   =>  array(__('Set value','car-rental-booking-system')),            
            3                                                                   =>  array(__('Increase by value','car-rental-booking-system')),
            4                                                                   =>  array(__('Decrease by value','car-rental-booking-system')), 
            5                                                                   =>  array(__('Increase by percentage','car-rental-booking-system')),
            6                                                                   =>  array(__('Decrease by percentage','car-rental-booking-system')) 
        );
        
        $this->priceUseType=array
        (
            'initial'															=>  array(__('Initial','car-rental-booking-system')),
            'rental_day'														=>  array(__('Rental per day','car-rental-booking-system')),            
            'rental_hour'														=>  array(__('Rental per hour','car-rental-booking-system')),
            'delivery'                                                          =>  array(__('Delivery','car-rental-booking-system')),
            'delivery_return'                                                   =>  array(__('Delivery (return)','car-rental-booking-system')),            
            'deposit'															=>  array(__('Deposit','car-rental-booking-system')),
            'one_way'															=>  array(__('One way','car-rental-booking-system')),   
			'after_business_hour_pickup'										=>  array(__('Pickup after bussiness hours','car-rental-booking-system')),
            'after_business_hour_return'										=>  array(__('Return after bussiness hours','car-rental-booking-system')),
			'customer_pickup_location'											=>	array(__('Customer pickup location','car-rental-booking-system')),
			'customer_return_location'											=>	array(__('Customer return location','car-rental-booking-system'))
        );
    }
	
    /**************************************************************************/
    
    function getPriceIndexName($index,$type='value')
    {
        return('price_'.$index.'_'.$type);
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
	
	function getPriceSourceType()
	{
		return($this->priceSourceType);
	}
	
	/**************************************************************************/
	
	function isPriceSourceType($type)
	{
		return(array_key_exists($type,$this->getPriceSourceType()));
	}
	
	/**************************************************************************/
	
	function getPriceSourceTypeName($type)
	{
		if(!$this->isPriceSourceType($type)) return('');
		return($this->priceSourceType[$type][0]);
	}
	
	/**************************************************************************/
	
	function getTaxRateIndexName($priceUseType)
	{
		if(in_array($priceUseType,array('rental_day','rental_hour'))) return('rental');
		return($priceUseType);
	}
	
    /**************************************************************************/
    
    function extractPriceFromData($price,$data)
    {
        $priceComponent=array('value','alter_type_id','tax_rate_id');
        
        foreach($this->getPriceUseType() as $priceUseTypeIndex=>$priceUseTypeValue)
        {
            foreach($priceComponent as $priceComponentIndex=>$priceComponentValue)
            {
				if($priceComponentValue=='tax_rate_id')
					$priceUseTypeIndex=$this->getTaxRateIndexName($priceUseTypeIndex);
				
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
    
    public function init()
    {
        $this->registerCPT();
    }
	
	/**************************************************************************/

    public static function getCPTName()
    {
        return(PLUGIN_CRBS_CONTEXT.'_price_rule');
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
					'name'														=>	__('Pricing Rules','car-rental-booking-system'),
					'singular_name'												=>	__('Pricing Rule','car-rental-booking-system'),
					'add_new'													=>	__('Add New','car-rental-booking-system'),
					'add_new_item'												=>	__('Add New Pricing Rule','car-rental-booking-system'),
					'edit_item'													=>	__('Edit Pricing Rule','car-rental-booking-system'),
					'new_item'													=>	__('New Pricing Rule','car-rental-booking-system'),
					'all_items'													=>	__('Pricing Rules','car-rental-booking-system'),
					'view_item'													=>	__('View Pricing Rule','car-rental-booking-system'),
					'search_items'												=>	__('Search Pricing Rules','car-rental-booking-system'),
					'not_found'													=>	__('No Pricing Rules Found','car-rental-booking-system'),
					'not_found_in_trash'										=>	__('No Pricing Rules in Trash','car-rental-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('Pricing Rules','car-rental-booking-system')
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
        
        add_action('save_post',array($this,'savePost'));
        add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
        add_filter('postbox_classes_'.self::getCPTName().'_crbs_meta_box_price_rule',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }

    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CRBS_CONTEXT.'_meta_box_price_rule',__('Main','car-rental-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
        
		$data=array();
        
        $Vehicle=new CRBSVehicle();
        $TaxRate=new CRBSTaxRate();
		$Location=new CRBSLocation();
		$Geofence=new CRBSGeofence();
        $BookingForm=new CRBSBookingForm();
        
        $data['meta']=CRBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CRBSHelper::createNonceField(PLUGIN_CRBS_CONTEXT.'_meta_box_price_rule');

        $data['dictionary']['vehicle']=$Vehicle->getDictionary();
        $data['dictionary']['tax_rate']=$TaxRate->getDictionary();
		$data['dictionary']['geofence']=$Geofence->getDictionary();
        $data['dictionary']['booking_form']=$BookingForm->getDictionary();

		$data['dictionary']['price_alter_type']=$this->getPriceAlterType();
		$data['dictionary']['price_source_type']=$this->getPriceSourceType();
		
		$data['dictionary']['location']=$Location->getDictionary();
		
		$Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'admin/meta_box_price_rule.php');
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
        
        CRBSHelper::setDefault($meta,'booking_form_id',array(-1));
        
		CRBSHelper::setDefault($meta,'pickup_location_id',array(-1));
        CRBSHelper::setDefault($meta,'return_location_id',array(-1));
		
        CRBSHelper::setDefault($meta,'location_geofence_pickup',array(-1));
        CRBSHelper::setDefault($meta,'location_geofence_return',array(-1));

        CRBSHelper::setDefault($meta,'vehicle_id',array(-1));
        
        CRBSHelper::setDefault($meta,'pickup_day_number',array(-1));
        
        CRBSHelper::setDefault($meta,'pickup_date',array());
        CRBSHelper::setDefault($meta,'rental_day_count',array());
        CRBSHelper::setDefault($meta,'rental_hour_count',array());
        CRBSHelper::setDefault($meta,'driver_age',array());
        
		CRBSHelper::setDefault($meta,'price_source_type',1);
		
		CRBSHelper::setDefault($meta,'process_next_rule_enable',0);
		
        foreach($this->getPriceUseType() as $index=>$value)
        {
            CRBSHelper::setDefault($meta,'price_'.$index.'_value','0.00');
            CRBSHelper::setDefault($meta,'price_'.$index.'_alter_type_id',2);
			
			$taxRateIndex=$this->getTaxRateIndexName($index);
			
			CRBSHelper::setDefault($meta,'price_'.$taxRateIndex.'_tax_rate_id',$TaxRate->getDefaultTaxPostId());   
        }
		
		CRBSHelper::setDefault($meta,'price_rental_tax_rate_id',$TaxRate->getDefaultTaxPostId());    
	}
    
    /**************************************************************************/
    
    function savePost($postId)
    {      
        if(!$_POST) return(false);
        
        if(CRBSHelper::checkSavePost($postId,PLUGIN_CRBS_CONTEXT.'_meta_box_price_rule_noncename','savePost')===false) return(false);
        
        $Date=new CRBSDate();
        $Vehicle=new CRBSVehicle();
        $TaxRate=new CRBSTaxRate();
		$Geofence=new CRBSGeofence();
		$Location=new CRBSLocation();
        $BookingForm=new CRBSBookingForm();
        
        $Validation=new CRBSValidation();
        
        $option=CRBSHelper::getPostOption();
        
        /***/
        
        $dictionary=array
        (
            'booking_form_id'                                                   =>  array
            (
                'dictionary'                                                    =>  $BookingForm->getDictionary()
            ),
            'pickup_location_id'                                                =>  array
            (
                'dictionary'                                                    =>  $Location->getDictionary()
            ),			
            'return_location_id'                                                =>  array
            (
                'dictionary'                                                    =>  $Location->getDictionary()
            ),	
            'location_geofence_pickup'                                          =>  array
            (
                'dictionary'                                                    =>  $Geofence->getDictionary()
            ),				
            'location_geofence_return'                                          =>  array
            (
                'dictionary'                                                    =>  $Geofence->getDictionary()
            ),					
            'vehicle_id'                                                        =>  array
            (
                'dictionary'                                                    =>  $Vehicle->getDictionary()
            ),
            'pickup_day_number'													=>  array
            (
                'dictionary'                                                    =>  array(1,2,3,4,5,6,7)
            ),
        );
        
        foreach($dictionary as $dIndex=>$dValue)
        {
            $option[$dIndex]=(array)CRBSHelper::getPostValue($dIndex);
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
        
        $date=array();
       
        foreach($option['pickup_date']['start'] as $index=>$value)
        {
            $d=array($value,$option['pickup_date']['stop'][$index],$option['pickup_date']['price'][$index]);
            
            $d[0]=$Date->formatDateToStandard($d[0]);
            $d[1]=$Date->formatDateToStandard($d[1]);
            
            if(!$Validation->isDate($d[0])) continue;
            if(!$Validation->isDate($d[1])) continue;
            
            if($Date->compareDate($d[0],$d[1])==1) continue;
            
			if(!$Validation->isPrice($d[2],true)) $d[2]=0.00;
			
            array_push($date,array('start'=>$d[0],'stop'=>$d[1],'price'=>$d[2]));
        }

        $option['pickup_date']=$date;

        /***/
        
        $number=array();
       
        foreach($option['rental_day_count']['start'] as $index=>$value)
        {
            $d=array($value,$option['rental_day_count']['stop'][$index],$option['rental_day_count']['price'][$index]);
            
            if(!$Validation->isNumber($d[0],0,99999)) continue;
            if(!$Validation->isNumber($d[1],0,99999)) continue;
  
            if(!$Validation->isPrice($d[2],true)) $d[2]=0.00;
            
            if($d[0]>$d[1]) continue;
            
            array_push($number,array('start'=>$d[0],'stop'=>$d[1],'price'=>$d[2]));
        }
        
        $option['rental_day_count']=$number;
        
        /***/
        
        $number=array();
       
        foreach($option['rental_hour_count']['start'] as $index=>$value)
        {
            $d=array($value,$option['rental_hour_count']['stop'][$index]);
            
            if(!$Validation->isNumber($d[0],0,99999)) continue;
            if(!$Validation->isNumber($d[1],0,99999)) continue;
  
            if($d[0]>$d[1]) continue;
            
            array_push($number,array('start'=>$d[0],'stop'=>$d[1]));
        }
        
        $option['rental_hour_count']=$number;
        
        /***/
        
        $number=array();
       
        foreach($option['driver_age']['start'] as $index=>$value)
        {
            $d=array($value,$option['driver_age']['stop'][$index]);
            
            if(!$Validation->isNumber($d[0],0,999)) continue;
            if(!$Validation->isNumber($d[1],0,999)) continue;
  
            if($d[0]>$d[1]) continue;
            
            array_push($number,array('start'=>$d[0],'stop'=>$d[1]));
        }
        
        $option['driver_age']=$number;

        /***/
        
		if(!$this->isPriceSourceType($option['price_source_type']))
			$option['price_source_type']=1;
		if(!$Validation->isBool($option['process_next_rule_enable']))
			$option['process_next_rule_enable']=0;
		
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
         
			$taxRateIndex=$this->getTaxRateIndexName($index);
			
            if((int)$option['price_'.$taxRateIndex.'_tax_rate_id']===-1)
                $option['price_'.$taxRateIndex.'_tax_rate_id']=-1;
            else
            {
                if(!$TaxRate->isTaxRate($option['price_'.$taxRateIndex.'_tax_rate_id']))
                    $option['price_'.$taxRateIndex.'_tax_rate_id']=0; 
            }
        }
		
        /***/

        $key=array
        (
            'booking_form_id',
			'pickup_location_id',
			'return_location_id',
			'location_geofence_pickup',
			'location_geofence_return',
            'vehicle_id',
            'pickup_day_number',
            'pickup_date',
            'rental_day_count',
            'rental_hour_count',
            'driver_age',
            'price_source_type',
			'process_next_rule_enable'
        );
        
        foreach($this->getPriceUseType() as $index=>$value)
		{
            array_push($key,'price_'.$index.'_value','price_'.$index.'_alter_type_id');
			
			$taxRateIndex=$this->getTaxRateIndexName($index);
			
			array_push($key,'price_'.$taxRateIndex.'_tax_rate_id');
		}
		
		array_unique($key);
		
        foreach($key as $value)
            CRBSPostMeta::updatePostMeta($postId,$value,$option[$value]);
    }
    
    /**************************************************************************/

    function manageEditColumns($column)
    {
        $column=array
        (
            'cb'                                                                =>  $column['cb'],
            'title'                                                             =>  $column['title'],
            'rule'                                                              =>  __('Rules','car-rental-booking-system'),
            'price'                                                             =>  __('Prices','car-rental-booking-system')
        );
   
		return($column);           
    }
    
    /**************************************************************************/
    
    function getPricingRuleAdminListDictionary()
    {
        $dictionary=array();
    
        $Date=new CRBSDate();
        $Vehicle=new CRBSVehicle();
		$Location=new CRBSLocation();
		$Geofence=new CRBSGeofence();
        $BookingForm=new CRBSBookingForm();
        
        $dictionary['vehicle']=$Vehicle->getDictionary();
        $dictionary['location']=$Location->getDictionary();
        $dictionary['geofence']=$Geofence->getDictionary();
        $dictionary['booking_form']=$BookingForm->getDictionary();

        $dictionary['day']=$Date->day;
        
        return($dictionary);
    }
    
    /**************************************************************************/
    
    function displayPricingRuleAdminListValue($data,$dictionary,$link=false,$sort=false)
    {
		if(in_array(-1,$data)) return(__(' - ','car-rental-booking-system'));
		
        $html=null;
        
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
            $html.='<div>'.$label.'</div>';
        }
        
        return($html);
    }
    
    /**************************************************************************/
    
    function managePostsCustomColumn($column)
    {
		global $post;
        
        $Date=new CRBSDate();
        $Validation=new CRBSValidation();
		
        $meta=CRBSPostMeta::getPostMeta($post);
        
        $dictionary=CRBSGlobalData::setGlobalData('pricing_rule_admin_list_dictionary',array($this,'getPricingRuleAdminListDictionary'));
        
		switch($column) 
		{
			case 'rule':
                
                $html=array
                (
                    'pickup_date'                                               =>  '',
                    'rental_day_count'											=>  '',
                    'rental_hour_count'											=>  '',
                    'driver_age'                                                =>  ''
                );
				
                if((isset($meta['pickup_date'])) && (count($meta['pickup_date'])))
                {
                    foreach($meta['pickup_date'] as $value)
                    {
                        if(!$Validation->isEmpty($html['pickup_date'])) $html['pickup_date'].='<br>';
                        $html['pickup_date'].=$Date->formatDateToDisplay($value['start']).' - '.$Date->formatDateToDisplay($value['stop']);      
						
						if((int)$meta['price_source_type']===2)
							$html['pickup_date'].=' ('.CRBSPrice::format($value['price'],CRBSOption::getOption('currency')).')';
                    }
                }   
				
                if((isset($meta['rental_day_count'])) && (count($meta['rental_day_count'])))
                {
                    foreach($meta['rental_day_count'] as $value)
                    {
                        if(!$Validation->isEmpty($html['rental_day_count'])) $html['rental_day_count'].='<br>';
                        $html['rental_day_count'].=esc_html($value['start']).' - '.esc_html($value['stop']);      
						
						if(in_array((int)$meta['price_source_type'],array(3,4)))
							$html['rental_day_count'].=' ('.CRBSPrice::format($value['price'],CRBSOption::getOption('currency')).')';
                    }
                }                
                
                if((isset($meta['rental_hour_count'])) && (count($meta['rental_hour_count'])))
                {
                    foreach($meta['rental_hour_count'] as $value)
                    {
                        if(!$Validation->isEmpty($html['rental_hour_count'])) $html['rental_hour_count'].='<br>';
                        $html['rental_hour_count'].=esc_html($value['start']).' - '.esc_html($value['stop']);      
                    }
                }       	
				
                if((isset($meta['driver_age'])) && (count($meta['driver_age'])))
                {
                    foreach($meta['driver_age'] as $value)
                    {
                        if(!$Validation->isEmpty($html['driver_age'])) $html['driver_age'].='<br>';
                        $html['driver_age'].=esc_html($value['start']).' - '.esc_html($value['stop']);                          
                    }
                }    
				
				foreach($html as $index=>$value)
				{
					if($Validation->isEmpty($value)) $html[$index]=esc_html__(' - ','car-rental-booking-system');				
				}
				
                /***/
                
                echo 
                '
                    <table class="to-table-post-list">
                        <tr>
                            <td>'.esc_html__('Booking form','car-rental-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['booking_form_id'],$dictionary['booking_form'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Pickup location','car-rental-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['pickup_location_id'],$dictionary['location'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Return location','car-rental-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['return_location_id'],$dictionary['location'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Pickup geofence','car-rental-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['location_geofence_pickup'],$dictionary['geofence'],true,true).'</td>
                        </tr>						
                        <tr>
                            <td>'.esc_html__('Return geofence','car-rental-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['location_geofence_return'],$dictionary['geofence'],true,true).'</td>
                        </tr>	
                        <tr>
                            <td>'.esc_html__('Vehicles','car-rental-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['vehicle_id'],$dictionary['vehicle'],true,true).'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Pickup day numbers','car-rental-booking-system').'</td>
                            <td>'.$this->displayPricingRuleAdminListValue($meta['pickup_day_number'],$dictionary['day'],true,true).'</td>
                        </tr>                        
                        <tr>
                            <td>'.esc_html__('Rental/pickup dates','car-rental-booking-system').'</td>
                            <td>'.$html['pickup_date'].'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Rental days count','car-rental-booking-system').'</td>
                            <td>'.$html['rental_day_count'].'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Rental hours count','car-rental-booking-system').'</td>
                            <td>'.$html['rental_hour_count'].'</td>
                        </tr>
                        <tr>
                            <td>'.esc_html__('Driver\'s age','car-rental-booking-system').'</td>
                            <td>'.$html['driver_age'].'</td>
                        </tr>
                    </table>
                ';

			break;
        
			case 'price':
                
                echo 
                '
                    <table class="to-table-post-list">
                        <tr>
                            <td>'.esc_html__('Price source type','car-rental-booking-system').'</td>
                            <td>'.$this->getPriceSourceTypeName($meta['price_source_type']).'</td>
                        </tr>  
                        <tr>
                            <td>'.__('Priority','car-rental-booking-system').'</td>
                            <td>'.(int)$post->menu_order.'</td>
                        </tr>
                        <tr>
                            <td>'.__('Next rule processing','car-rental-booking-system').'</td>
                            <td>'.((int)$meta['process_next_rule_enable']===1 ? esc_html__('Enable','car-rental-booking-system') : esc_html__('Disable','car-rental-booking-system')).'</td>
                        </tr>
				';
				
				foreach($this->getPriceUseType() as $index=>$value)
				{
					echo 
					'
						<tr>
                            <td>'.esc_html($value[0]).'</td>
                            <td>'.self::displayPriceAlter($meta,$index).'</td>
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
	
    static function displayPriceAlter($meta,$priceUseType)
    {
        $charBefore=null;
        
        if(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(3,5)))
            $charBefore='+ ';
        if(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(4,6)))
            $charBefore='- ';        
        
        if(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(1)))
        {
            return(__('Inherited','car-rental-booking-system'));
        }
        elseif(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(2)))
        {
            return(CRBSPrice::format($meta['price_'.$priceUseType.'_value'],CRBSOption::getOption('currency')));
        }
        elseif(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(3,4)))
        {
            return($charBefore.CRBSPrice::format($meta['price_'.$priceUseType.'_value'],CRBSOption::getOption('currency')));
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
    
    function getPriceFromRule($bookingData,$bookingForm,$priceRule)
    {
		$Date=new CRBSDate();
		
        $pricePerDay=-1;
		
        $rule=$bookingForm['dictionary']['price_rule'];
        if($rule===false) return($priceRule);

        foreach($rule as $ruleData)
        {
            if(!in_array(-1,$ruleData['meta']['booking_form_id']))
            {
                if(!in_array($bookingData['booking_form_id'],$ruleData['meta']['booking_form_id'])) continue;
            }
			
            if(!in_array(-1,$ruleData['meta']['pickup_location_id']))
            {
                if(!in_array($bookingData['pickup_location_id'],$ruleData['meta']['pickup_location_id'])) continue;
            }

            if(!in_array(-1,$ruleData['meta']['return_location_id']))
            {
                if(!in_array($bookingData['return_location_id'],$ruleData['meta']['return_location_id'])) continue;
            }
			
			if(!in_array(-1,$ruleData['meta']['location_geofence_pickup']))
			{
				if($bookingData['pickup_location_address_data']!==false)
				{
					$GeofenceChecker=new CRBSGeofenceChecker();
					
					$coordinate=$GeofenceChecker->transformShape($ruleData['meta']['location_geofence_pickup'],$bookingForm['dictionary']['geofence']);
					
					if(is_array($coordinate))
					{
						$inside=false;

						$pickupLocation=$bookingData['pickup_location_address_data'];
						
						foreach($coordinate as $coordinateValue)
						{
							$result=$GeofenceChecker->pointInPolygon(new CRBSPoint($pickupLocation->lat,$pickupLocation->lng),$coordinateValue);

							if($result)
							{
								$inside=true;
								break;
							}
						}

						if(!$inside) continue;
					}
				}
				else continue;
			}
            
			if(!in_array(-1,$ruleData['meta']['location_geofence_return']))
			{
				if($bookingData['return_location_address_data']!==false)
				{
					$GeofenceChecker=new CRBSGeofenceChecker();

					$coordinate=$GeofenceChecker->transformShape($ruleData['meta']['location_geofence_return'],$bookingForm['dictionary']['geofence']);

					if(is_array($coordinate))
					{
						$inside=false;

						$returnLocation=$bookingData['return_location_address_data'];

						foreach($coordinate as $coordinateValue)
						{
							$result=$GeofenceChecker->pointInPolygon(new CRBSPoint($returnLocation->lat,$returnLocation->lng),$coordinateValue);

							if($result)
							{
								$inside=true;
								break;
							}
						}

						if(!$inside) continue;
					}
				}
				else continue;
			}      
            
            if(!in_array(-1,$ruleData['meta']['vehicle_id']))
            {
                if(!in_array($bookingData['vehicle_id'],$ruleData['meta']['vehicle_id'])) continue;
            } 
            
            if((int)CRBSOption::getOption('billing_type')===2)
            {
                if(!in_array(-1,$ruleData['meta']['pickup_day_number']))
                {
                    $date=$Date->formatDateToStandard($bookingData['pickup_date']);

                    if(!in_array(date('N',strtotime($date)),$ruleData['meta']['pickup_day_number'])) continue;
                }
            }
            
            if(is_array($ruleData['meta']['pickup_date']))
            {
                if(count($ruleData['meta']['pickup_date']))
                {
                    $match=!count($ruleData['meta']['pickup_date']);
					
					if(((int)$ruleData['meta']['price_source_type']===2) && ((int)CRBSOption::getOption('billing_type')===2))
					{
						$sum=0;
						$match=true;
						
						$dateStart=$Date->formatDateToStandard($bookingData['pickup_date']);
						
                        $period=CRBSBookingHelper::calculateRentalPeriod($bookingData['pickup_date'],$bookingData['pickup_time'],$bookingData['return_date'],$bookingData['return_time'],$bookingForm['meta'],CRBSOption::getOption('billing_type'));

						for($i=0;$i<$period['day'];$i++)
						{
							$date=date('d-m-Y',strtotime('+'.$i.' day', strtotime($dateStart)));
								
							$dateIndex=-1;
							
							foreach($ruleData['meta']['pickup_date'] as $index=>$value)
							{
								if($Date->dateInRange($date,$value['start'],$value['stop']))
								{
									$dateIndex=$index;
									break;
								}
							}
							
							if($dateIndex!=-1)
							{
								$sum+=$ruleData['meta']['pickup_date'][$dateIndex]['price'];
							}
							else
							{
								$match=false;
								break;
							}
						}
						
						if($match)
						{
							$pricePerDay=$sum/$period['day'];
						}
					}
					else
					{
						$date=$Date->formatDateToStandard($bookingData['pickup_date']);
						
						foreach($ruleData['meta']['pickup_date'] as $value)
						{
							if($Date->dateInRange($date,$value['start'],$value['stop']))
							{
								$match=true;
								break;
							}
						}
					}

                    if(!$match) continue;
                }
            }
            
            if((int)CRBSOption::getOption('billing_type')===2)
            {
                if(is_array($ruleData['meta']['rental_day_count']))
                {
                    if(count($ruleData['meta']['rental_day_count']))
                    {
                        $match=!count($ruleData['meta']['rental_day_count']);

                        $period=CRBSBookingHelper::calculateRentalPeriod($bookingData['pickup_date'],$bookingData['pickup_time'],$bookingData['return_date'],$bookingData['return_time'],$bookingForm['meta'],CRBSOption::getOption('billing_type'));

                        if((int)$ruleData['meta']['price_source_type']===3)
                        {
							$match=true;
							
							for($i=1;$i<=$period['day'];$i++)
							{
								foreach($ruleData['meta']['rental_day_count'] as $value)
								{
									if(($value['start']<=$i) && ($value['stop']>=$i))
									{
										$sum+=$value['price'];
										break;
									}
								}		
							}
							
							$pricePerDay=$sum/$period['day'];
                        }
						if((int)$ruleData['meta']['price_source_type']===4)
						{
							$match=true;
							
							foreach($ruleData['meta']['rental_day_count'] as $value)
							{
								if(($value['start']<=$period['day']) && ($value['stop']>=$period['day']))
								{
									$pricePerDay=$value['price'];
									break;
								}
							}		
						}
                        else
                        {
                        
                            foreach($ruleData['meta']['rental_day_count'] as $value)
                            {
                                if(($value['start']<=$period['day']) && ($period['day']<=$value['stop']))
                                {
                                    $match=true;
                                    break;                        
                                }
                            }
                        }
                        
                        if(!$match) continue;
                    }
                }  
            }
			
            if((int)CRBSOption::getOption('billing_type')===1)
            {
                if(is_array($ruleData['meta']['rental_hour_count']))
                {
                    if(count($ruleData['meta']['rental_hour_count']))
                    {
                        $match=!count($ruleData['meta']['rental_hour_count']);

                        $period=CRBSBookingHelper::calculateRentalPeriod($bookingData['pickup_date'],$bookingData['pickup_time'],$bookingData['return_date'],$bookingData['return_time'],$bookingForm['meta'],CRBSOption::getOption('billing_type'));

                        foreach($ruleData['meta']['rental_hour_count'] as $value)
                        {
                            if(($value['start']<=$period['hour']) && ($period['hour']<=$value['stop']))
                            {
                                $match=true;
                                break;                        
                            }
                        }

                        if(!$match) continue;
                    }
                }                  
            }
            
            if(is_array($ruleData['meta']['driver_age']))
            {
                if(count($ruleData['meta']['driver_age']))
                {
                    $match=false;

                    foreach($ruleData['meta']['driver_age'] as $value)
                    {
                        if(($value['start']<=$bookingData['driver_age']) && ($bookingData['driver_age']<=$value['stop']))
                        {
                            $match=true;
                            break;                        
                        }
                    }

                    if(!$match) continue;
                }
            }
			
			if($pricePerDay!=-1)
			{
				$priceRule['price_rental_day_value']=$pricePerDay;
				$pricePerDay=-1;
			}
			else
			{
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
				}
			}
			
			foreach($this->getPriceUseType() as $index=>$value)
			{
				$taxRateIndexName=$this->getTaxRateIndexName($index);

				if((int)$ruleData['meta']['price_'.$taxRateIndexName.'_tax_rate_id']!==0)
					$priceRule['price_'.$taxRateIndexName.'_tax_rate_id']=$ruleData['meta']['price_'.$taxRateIndexName.'_tax_rate_id'];			
			}

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
		CRBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'															=>	self::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1,
			'orderby'															=>	array('menu_order'=>'desc')
		);
		
		if($attribute['price_rule_id'])
			$argument['p']=$attribute['price_rule_id'];
               
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
}

/******************************************************************************/
/******************************************************************************/