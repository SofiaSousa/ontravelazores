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
}