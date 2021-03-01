<?php

/******************************************************************************/
/******************************************************************************/

class CRBSCoupon
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
        return(PLUGIN_CRBS_CONTEXT.'_coupon');
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
					'name'														=>	__('Coupons','car-rental-booking-system'),
					'singular_name'												=>	__('Coupons','car-rental-booking-system'),
					'add_new'													=>	__('Add New','car-rental-booking-system'),
					'add_new_item'												=>	__('Add New Coupon','car-rental-booking-system'),
					'edit_item'													=>	__('Edit Coupon','car-rental-booking-system'),
					'new_item'													=>	__('New Coupon','car-rental-booking-system'),
					'all_items'													=>	__('Coupons','car-rental-booking-system'),
					'view_item'													=>	__('View Coupon','car-rental-booking-system'),
					'search_items'												=>	__('Search Coupons','car-rental-booking-system'),
					'not_found'													=>	__('No Coupons Found','car-rental-booking-system'),
					'not_found_in_trash'										=>	__('No Coupons in Trash','car-rental-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('Coupons','car-rental-booking-system')
				),	
				'public'														=>	false,  
				'show_ui'														=>	true, 
				'show_in_menu'													=>	'edit.php?post_type='.CRBSBooking::getCPTName(),
				'capability_type'												=>	'post',
				'menu_position'													=>	2,
				'hierarchical'													=>	false,  
				'rewrite'														=>	false,  
				'supports'														=>	false
			)
		);
        
        add_action('save_post',array($this,'savePost'));
        add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
        add_filter('postbox_classes_'.self::getCPTName().'_crbs_meta_box_coupon',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }

    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CRBS_CONTEXT.'_meta_box_coupon',__('Main','car-rental-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
        
        $Booking=new CRBSBooking();
		$Vehicle=new CRBSVehicle();
        
		$data=array();
               
        $data['meta']=CRBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CRBSHelper::createNonceField(PLUGIN_CRBS_CONTEXT.'_meta_box_coupon');
        
        if(!isset($data['meta']['code']))
        {
            $code=$this->generateCode();
            
            wp_update_post(array('ID'=>$post->ID,'post_title'=>$code));
            
            CRBSPostMeta::updatePostMeta($post->ID,'code',$code);
            CRBSPostMeta::updatePostMeta($post->ID,'usage_count',0);
            
            $data['meta']=CRBSPostMeta::getPostMeta($post);
        }
        
        $data['meta']['usage_count']=$Booking->getCouponCodeUsageCount($data['meta']['code']);
		
		$data['dictionary']['vehicle']=$Vehicle->getDictionary();
		$data['dictionary']['vehicle_category']=$Vehicle->getCategory();
		
		$Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'admin/meta_box_coupon.php');
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
        CRBSHelper::setDefault($meta,'usage_limit','');
        
		CRBSHelper::setDefault($meta,'vehicle_id',array(-1));
		CRBSHelper::setDefault($meta,'vehicle_category_id',array(-1));
		
        CRBSHelper::setDefault($meta,'discount_percentage',0);
        CRBSHelper::setDefault($meta,'discount_fixed',0);
        
        CRBSHelper::setDefault($meta,'active_date_start','');
        CRBSHelper::setDefault($meta,'active_date_stop','');
	
		CRBSHelper::setDefault($meta,'discount_rental_day_count',array());
	}
    
    /**************************************************************************/
    
    function savePost($postId)
    {      
        if(!$_POST) return(false);
        
        if(CRBSHelper::checkSavePost($postId,PLUGIN_CRBS_CONTEXT.'_meta_box_coupon_noncename','savePost')===false) return(false);
        
        $Date=new CRBSDate();
		$Vehicle=new CRBSVehicle();
        $Validation=new CRBSValidation();
        
        $option=CRBSHelper::getPostOption();
             
		/**/
		
        if(!$Validation->isNumber($option['usage_limit'],1,9999))
            $option['usage_limit']='';
         
		/***/
		
        if(!$Validation->isDate($option['active_date_start']))
            $option['active_date_start']='';
        if(!$Validation->isDate($option['active_date_stop']))
            $option['active_date_stop']='';
        if(($Validation->isDate($option['active_date_start'])) && ($Validation->isDate($option['active_date_stop'])))
        {
            if($Date->compareDate($option['active_date_start'],$option['active_date_stop'])==1)
            {
                $option['active_date_start']='';
                $option['active_date_stop']='';
            }
        }    
		
        $option['active_date_start']=$Date->formatDateToStandard($option['active_date_start']);
        $option['active_date_stop']=$Date->formatDateToStandard($option['active_date_stop']);
		
		/***/
		
		$vehicle=$Vehicle->getDictionary();
		$option['vehicle_id']=(array)$option['vehicle_id'];
		
		foreach($option['vehicle_id'] as $index=>$value)
		{
			if(!array_key_exists($value,$vehicle))
				unset($option['vehicle_id'][$index]);
		}
		
		if(!count($option['vehicle_id'])) $option['vehicle_id']=array(-1);
		
		/***/
		
		$vehicleCategory=$Vehicle->getCategory();
		$option['vehicle_category_id']=(array)$option['vehicle_category_id'];
		
		foreach($option['vehicle_category_id'] as $index=>$value)
		{
			if(!array_key_exists($value,$vehicleCategory))
				unset($option['vehicle_category_id'][$index]);
		}

		if(!count($option['vehicle_category_id'])) $option['vehicle_category_id']=array(-1);		
		
		/***/
		
        if($Validation->isNumber($option['discount_percentage'],1,99,false))
        {
            $option['discount_fixed']=0;
        }
        else $option['discount_percentage']=0;
        
        if(($Validation->isPrice($option['discount_fixed'])) && ($option['discount_fixed']>0))
        {
            $option['discount_percentage']=0;
        }
        else $option['discount_fixed']=0;        
        
		/***/		

        $number=array();
       
        foreach($option['discount_rental_day_count']['start'] as $index=>$value)
        {
            $d=array
			(
				$value,
				$option['discount_rental_day_count']['stop'][$index],
				$option['discount_rental_day_count']['discount_percentage'][$index],
				$option['discount_rental_day_count']['discount_fixed'][$index]
			);
            
            if(!$Validation->isNumber($d[0],0,99999)) continue;
            if(!$Validation->isNumber($d[1],0,99999)) continue;
  
			if($Validation->isNumber($d[2],1,99,false)) $d[3]=0;
			
			if(($Validation->isPrice($d[3])) && ($d[3]>0)) $d[2]=0;
			else $d[3]=0;
			
            if($d[0]>$d[1]) continue;
            
            array_push($number,array('start'=>$d[0],'stop'=>$d[1],'discount_percentage'=>$d[2],'discount_fixed'=>CRBSPrice::formatToSave($d[3])));
        }
        
        $option['discount_rental_day_count']=$number;
				
		/***/
		
		$option['discount_fixed']=CRBSPrice::formatToSave($option['discount_fixed']);
		
        $key=array
        (
            'usage_limit',
            'active_date_start',
            'active_date_stop',
			'vehicle_id',
			'vehicle_category_id',
            'discount_percentage',
            'discount_fixed',
			'discount_rental_day_count'
        );
		
        foreach($key as $index)
            CRBSPostMeta::updatePostMeta($postId,$index,$option[$index]);
    }
    
    /**************************************************************************/
    
    function manageEditColumns($column)
    {
        $column=array
        (
            'cb'                                                                =>  $column['cb'],
            'title'                                                             =>  __('Code','car-rental-booking-system'),
            'usage_limit'                                                       =>  __('Usage limit','car-rental-booking-system'),
            'discount_percentage'                                               =>  __('Percentage discount','car-rental-booking-system'),
            'discount_fixed'                                                    =>  __('Fixed discount','car-rental-booking-system'),
            'active_date_start'                                                 =>  __('Active from','car-rental-booking-system'),    
            'active_date_stop'                                                  =>  __('Active to','car-rental-booking-system'),
            'date'                                                              =>  $column['date']
        );
   
		return($column);          
    }
    
    /**************************************************************************/
    
    function managePostsCustomColumn($column)
    {
		global $post;
        
        $Date=new CRBSDate();
		
		$meta=CRBSPostMeta::getPostMeta($post);
        
		switch($column) 
		{
			case 'usage_limit':
				
                echo esc_html($meta['usage_limit']);
                
			break;
        
			case 'discount_percentage':
				
                echo esc_html($meta['discount_percentage']);
                
			break;
        
			case 'discount_fixed':
				
                echo esc_html($meta['discount_fixed']);
                
			break;
        
			case 'active_date_start':

                echo $Date->formatDateToDisplay($meta['active_date_start']);
                
			break;

			case 'active_date_stop':
                
                echo $Date->formatDateToDisplay($meta['active_date_stop']);
				
			break;            
        }
    }
    
    /**************************************************************************/
    
    function manageEditSortableColumns($column)
    {
		return($column);       
    }
    
    /**************************************************************************/
    
    function create()
    {
        $option=CRBSHelper::getPostOption();

        $response=array('global'=>array('error'=>1));

        $Date=new CRBSDate();
        $Coupon=new CRBSCoupon();
        $Notice=new CRBSNotice();
        $Validation=new CRBSValidation();
        
        $invalidValue=__('This field includes invalid value.','car-rental-booking-system');
        
        if(!$Validation->isNumber($option['coupon_generate_count'],1,999))
            $Notice->addError(CRBSHelper::getFormName('coupon_generate_count',false),$invalidValue);	        
        if(!$Validation->isNumber($option['coupon_generate_usage_limit'],1,9999,true))
            $Notice->addError(CRBSHelper::getFormName('coupon_generate_usage_limit',false),$invalidValue);	        
        
        $option['coupon_generate_active_date_start']=$Date->formatDateToStandard($option['coupon_generate_active_date_start']);
        $option['coupon_generate_active_date_stop']=$Date->formatDateToStandard($option['coupon_generate_active_date_stop']);
        
        if(!$Validation->isDate($option['coupon_generate_active_date_start'],true))
            $Notice->addError(CRBSHelper::getFormName('coupon_generate_active_date_start',false),$invalidValue);      
        else if(!$Validation->isDate($option['coupon_generate_active_date_stop'],true))
            $Notice->addError(CRBSHelper::getFormName('coupon_generate_active_date_stop',false),$invalidValue);              
        else
        {
            if($Date->compareDate($option['coupon_generate_active_date_start'],$option['coupon_generate_active_date_stop'])==1)
            {
                $Notice->addError(CRBSHelper::getFormName('coupon_generate_active_date_start',false),__('Invalid dates range.','car-rental-booking-system'));
                $Notice->addError(CRBSHelper::getFormName('coupon_generate_active_date_stop',false),__('Invalid dates range.','car-rental-booking-system')); 
            }            
        }
        
		if($Notice->isError())
		{
			$response['local']=$Notice->getError();
		}
		else
		{
            $Coupon->generate($option);
			$response['global']['error']=0;
		}

		$response['global']['notice']=$Notice->createHTML(PLUGIN_CRBS_TEMPLATE_PATH.'notice.php');

		echo json_encode($response);
		exit;
    }
    
    /**************************************************************************/
    
    function generate($data)
    {
        $Validation=new CRBSValidation();
        
		for($i=0;$i<$data['coupon_generate_count'];$i++)
		{
			$couponCode=$this->generateCode();
            
			$couponId=wp_insert_post
            (
                array
                (
					'comment_status'                                            =>  'closed',
					'ping_status'                                               =>	'closed',
					'post_author'                                               =>	get_current_user_id(),
					'post_title'                                                =>	$couponCode,
					'post_status'                                               =>	'publish',
					'post_type'                                                 =>	self::getCPTName()
				)
			);
            
            if($couponId>0)
            {
                $discountPercentage=$data['coupon_generate_discount_percentage'];
                $discountFixed=$data['coupon_generate_discount_fixed'];
                
                if($Validation->isNumber($discountPercentage,1,99,true))
                {
                    $discountFixed=0;
                }
                else 
                {
                    $discountPercentage=0;
                    if($Validation->isPrice($discountFixed))
                    {
                        $discountPercentage=0;
                    }
                    else $discountFixed=0;                     
                }
                
                CRBSPostMeta::updatePostMeta($couponId,'code',$couponCode);
                
                CRBSPostMeta::updatePostMeta($couponId,'usage_count',0);
                CRBSPostMeta::updatePostMeta($couponId,'usage_limit',$data['coupon_generate_usage_limit']);
                
                CRBSPostMeta::updatePostMeta($couponId,'discount_percentage',$discountPercentage);
                CRBSPostMeta::updatePostMeta($couponId,'discount_fixed',$discountFixed);
                
                CRBSPostMeta::updatePostMeta($couponId,'active_date_start',$data['coupon_generate_active_date_start']);
                CRBSPostMeta::updatePostMeta($couponId,'active_date_stop',$data['coupon_generate_active_date_stop']);
            }
		}
    }
    
    /**************************************************************************/
    
	function generateCode($length=12)
	{
		$code=null;
        
		$char='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charLength=strlen($char);
        
		for($i=0;$i<$length;$i++)
			$code.=$char[rand(0,$charLength-1)];
		return($code);
	}
    
    /**************************************************************************/
    
    function checkCode($bookingForm,&$couponCodeSourceType)
    {
        global $post;
        
        $Date=new CRBSDate();
        $Booking=new CRBSBooking();
        $Validation=new CRBSValidation();
		
		$couponCode=null;
		
		$data=CRBSHelper::getPostOption();
		
		if($bookingForm['meta']['coupon_id']!=-1)
		{
			$couponCodeSourceType=1;
			
			$dictionary=$this->getDictionary();
			if(array_key_exists($bookingForm['meta']['coupon_id'],$dictionary))
				$couponCode=$dictionary[$bookingForm['meta']['coupon_id']]['meta']['code'];
		}
		
		if(array_key_exists('coupon_code',$data))
		{
			$couponCodeSourceType=2;
			$couponCode=$data['coupon_code'];
		}
		
        if($Validation->isEmpty($couponCode)) return(false);
        
        /***/
        
        $argument=array
		(
			'post_type'															=>	self::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1,
            'meta_key'                                                          =>  PLUGIN_CRBS_CONTEXT.'_code',
            'meta_value'                                                        =>  $couponCode,
            'meta_compare'                                                      =>  '='
		);
		
        $query=new WP_Query($argument);
		if($query===false) return(false);
        
        /***/
        
        if($query->found_posts!=1) return(false);
        
        $query->the_post();
        
        $meta=CRBSPostMeta::getPostMeta($post);
        
        /***/
        
        if($Validation->isNotEmpty($meta['usage_limit']))
        {    
           $count=$Booking->getCouponCodeUsageCount($couponCode);
      
           if($count===false) return(false);
           if($count>=$meta['usage_limit']) return(false);
        }
        
        /***/
        
        if($Validation->isNotEmpty($meta['active_date_start']))
        {
            if($Date->compareDate(date_i18n('Y-m-d'),$meta['active_date_start'])===2) return(false);
        }
        
        if($Validation->isNotEmpty($meta['active_date_stop']))
        {
            if($Date->compareDate($meta['active_date_stop'],date_i18n('Y-m-d'))===2) return(false);
        }  
		
		/***/

		if(array_key_exists($data['vehicle_id'],$bookingForm['dictionary']['vehicle']))
		{
			if((is_array($meta['vehicle_id'])) && (count($meta['vehicle_id'])) && (!in_array(-1,$meta['vehicle_id'])))
			{
				if(!in_array($data['vehicle_id'],$meta['vehicle_id'])) return(false);
			}
			if((is_array($meta['vehicle_category_id'])) && (count($meta['vehicle_category_id'])) && (!in_array(-1,$meta['vehicle_category_id'])))
			{
				$categoryFound=false;
				
				foreach($meta['vehicle_category_id'] as $value)
				{
					if(has_term($value,CRBSVehicle::getCPTCategoryName(),$data['vehicle_id']))
					{
						$categoryFound=true;
						break;
					}
				}
				
				if(!$categoryFound) return(false);
			}
		}
		
        /***/

        return(array('post'=>$post,'meta'=>$meta));
    }
    
    /**************************************************************************/
    
    function calculateDiscountPercentage($discountFixed,$countDay,$countHour,$priceDay,$priceHour)
    {
        if($discountFixed==0) return(0);
        
        $sum=$countDay*$priceDay+$countHour*$priceHour;
        
        if($sum<=$discountFixed) return(0);
        
        $discountPercentage=($discountFixed/$sum)*100;

        return($discountPercentage);
    }
	
	/**************************************************************************/
	
    function getDictionary($attr=array())
    {
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'coupon_id'			  												=>	0
		);
		
		$attribute=shortcode_atts($default,$attr);
		CRBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'															=>	self::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1
		);
		
		if($attribute['coupon_id'])
			$argument['p']=$attribute['coupon_id'];

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