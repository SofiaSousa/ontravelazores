<?php

/******************************************************************************/
/******************************************************************************/

class CRBSPostMeta
{
	/**************************************************************************/
	
	static function getPostMeta($post)
	{
		$data=array();
		
		$prefix=PLUGIN_CRBS_CONTEXT.'_';
		
		if(!is_object($post)) $post=get_post($post);
		
		$meta=get_post_meta($post->ID);
		
		if(!is_array($meta)) $meta=array();
		
		foreach($meta as $metaIndex=>$metaData)
		{
			if(preg_match('/^'.$prefix.'/',$metaIndex))
				$data[preg_replace('/'.$prefix.'/',null,$metaIndex)]=$metaData[0];
		}
        
		switch($post->post_type)
		{
			case PLUGIN_CRBS_CONTEXT.'_vehicle':
                
                self::unserialize($data,array('location_id','attribute','date_exclude'));
                
				$Vehicle=new CRBSVehicle();
				$Vehicle->setPostMetaDefault($data);
				
			break;
            
			case PLUGIN_CRBS_CONTEXT.'_vehicle_attr':
                
                self::unserialize($data,array('attribute_value'));

				$VehicleAttribute=new CRBSVehicleAttribute();
				$VehicleAttribute->setPostMetaDefault($data);
				
			break;
            
			case PLUGIN_CRBS_CONTEXT.'_booking_extra':
                
                self::unserialize($data,array('location_id','vehicle_price'));
                
				$BookingExtra=new CRBSBookingExtra();
				$BookingExtra->setPostMetaDefault($data);
				
			break;
        
			case PLUGIN_CRBS_CONTEXT.'_booking_form':
                
                self::unserialize($data,array('location_id','currency','field_mandatory','style_color','form_element_panel','form_element_field','form_element_agreement','geolocation_enable','customer_pickup_location_restriction_country','customer_return_location_restriction_country'));
  
				$BookingForm=new CRBSBookingForm();
				$BookingForm->setPostMetaDefault($data);
				
			break;
        
			case PLUGIN_CRBS_CONTEXT.'_booking':
                
                self::unserialize($data,array('booking_extra','coordinate','payment_data','form_element_panel','form_element_field'));
  
				$Booking=new CRBSBooking();
				$Booking->setPostMetaDefault($data);
				
			break;
        
			case PLUGIN_CRBS_CONTEXT.'_price_rule':
                
                self::unserialize($data,array('pickup_location_id','return_location_id','location_geofence_pickup','location_geofence_return','booking_form_id','vehicle_id','pickup_day_number','pickup_date','rental_day_count','rental_hour_count','driver_age'));
                
				$PriceRule=new CRBSPriceRule();
				$PriceRule->setPostMetaDefault($data);
				
			break;
        
            case PLUGIN_CRBS_CONTEXT.'_location':
                
                self::unserialize($data,array('vehicle_availability_check_type','business_hour','date_exclude','payment_id'));
                
				$Location=new CRBSLocation();
				$Location->setPostMetaDefault($data);
   
                if(!is_array($data['payment_id'])) $data['payment_id']=array();
                
			break;
            
			case PLUGIN_CRBS_CONTEXT.'_coupon':
                
				self::unserialize($data,array('discount_rental_day_count'));
				
				$Coupon=new CRBSCoupon();
				$Coupon->setPostMetaDefault($data);
				
			break;
        
			case PLUGIN_CRBS_CONTEXT.'_tax_rate':
                
				$TaxRate=new CRBSTaxRate();
				$TaxRate->setPostMetaDefault($data);
				
			break;
        
			case PLUGIN_CRBS_CONTEXT.'_email_account':
                
				$EmailAccount=new CRBSEmailAccount();
				$EmailAccount->setPostMetaDefault($data);
				
			break;
		
			case PLUGIN_CRBS_CONTEXT.'_geofence':
                
                self::unserialize($data,array('shape_coordinate'));
                
				$Geofence=new CRBSGeofence();
				$Geofence->setPostMetaDefault($data);
				
			break;
		}
		
		return($data);
	}
    
    /**************************************************************************/
    
    static function unserialize(&$data,$unserializeIndex)
    {
        foreach($unserializeIndex as $index)
        {
            if(isset($data[$index]))
                $data[$index]=maybe_unserialize($data[$index]);
        }
    }
	
	/**************************************************************************/
	
	static function updatePostMeta($post,$name,$value)
	{
		$name=PLUGIN_CRBS_CONTEXT.'_'.$name;
		$postId=(int)(is_object($post) ? $post->ID : $post);
		
		update_post_meta($postId,$name,$value);
	}
    
	/**************************************************************************/
	
	static function removePostMeta($post,$name)
	{
		$name=PLUGIN_CRBS_CONTEXT.'_'.$name;
		$postId=(int)(is_object($post) ? $post->ID : $post);
		
		delete_post_meta($postId,$name);
	}
    	
	/**************************************************************************/
	
	static function createArray(&$array,$index)
	{
		$array=array($index=>array());
		return($array);
	}

	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/