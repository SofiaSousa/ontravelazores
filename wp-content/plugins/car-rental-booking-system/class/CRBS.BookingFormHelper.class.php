<?php

/******************************************************************************/
/******************************************************************************/

class CRBSBookingFormHelper
{
	/**************************************************************************/
	
    function __construct()
    {

    }
	
	/**************************************************************************/
	
	static function enableSelectLocation($dictionary,$bookingFormMeta,$type='pickup')
	{
		if((int)$bookingFormMeta['location_single_display_enable']===1) return(true);
		
		if(count($dictionary)>1) return(true);
		
		if((int)$bookingFormMeta['customer_'.$type.'_location_enable']==1) return(true);
		
		return(false);
	}
	
    /**************************************************************************/
    
    static function isPaymentDepositEnable($bookingForm,$bookingId=-1)
    {
        if($bookingId==-1)
        {
            $WooCommerce=new CRBSWooCommerce();
            if($WooCommerce->isEnable($bookingForm['meta'])) return(0);
        }
        
		$pickupLocationId=$bookingForm['pickup_location_id'];
		
		$pickupLocationMeta=$bookingForm['dictionary']['location'][$pickupLocationId]['meta'];
		
		$depositType=(int)$pickupLocationMeta['payment_deposit_type'];
		
		if(($depositType===1) &&  ($pickupLocationMeta['payment_deposit_type_fixed_value']>0)) return($depositType);
		if(($depositType===2) &&  ($pickupLocationMeta['payment_deposit_type_percentage_value']>0)) return($depositType);
		
		return(0);
    }

	/**************************************************************************/
}