<?php

/******************************************************************************/
/******************************************************************************/

class CHBSBookingStatus
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->bookingStatus=array
		(
			1																	=>	array(__('New','chauffeur-booking-system')),
			2																	=>	array(__('Accepted','chauffeur-booking-system')),
			3																	=>	array(__('Rejected','chauffeur-booking-system')),
			4																	=>	array(__('Finished','chauffeur-booking-system'))
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