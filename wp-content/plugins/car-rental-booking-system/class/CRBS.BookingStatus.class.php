<?php

/******************************************************************************/
/******************************************************************************/

class CRBSBookingStatus
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->bookingStatus=array
		(
			1																	=>	array(__('New','car-rental-booking-system')),
			2																	=>	array(__('Accepted','car-rental-booking-system')),
			3																	=>	array(__('Rejected','car-rental-booking-system')),
			4																	=>	array(__('Finished','car-rental-booking-system'))
		);
	}
	
	/**************************************************************************/
	
	function getBookingStatus($bookingStatus=null)
	{
        if(is_null($bookingStatus)) return($this->bookingStatus);
        else return($this->bookingStatus[$bookingStatus]);
	}
    
    /**************************************************************************/
    
    function isBookingStatus($bookingStatus)
    {
        return(array_key_exists($bookingStatus,$this->getBookingStatus()));
    }
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/