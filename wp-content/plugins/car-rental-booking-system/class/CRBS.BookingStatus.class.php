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
			1																	=>	array(__('Pending (new)','car-rental-booking-system')),
			2																	=>	array(__('Processing (accepted)','car-rental-booking-system')),
			3																	=>	array(__('Cancelled (rejected)','car-rental-booking-system')),
			4																	=>	array(__('Completed (finished)','car-rental-booking-system')),
			5																	=>	array(__('On hold','car-rental-booking-system')),
			6																	=>	array(__('Refunded','car-rental-booking-system')),	
			7																	=>	array(__('Failed','car-rental-booking-system'))
		);		
		
		$this->bookingStatusMap=array
		(
			1																	=>	'pending',
			2																	=>	'processing',
			3																	=>	'cancelled',
			4																	=>	'completed',
			5																	=>	'on-hold',
			6																	=>	'refunded',	
			7																	=>	'failed'
		);	
		
		$this->bookingStatusSynchronization=array
		(
			1																	=>	array(__('No synchronization','car-rental-booking-system')),
			2																	=>	array(__('One way: from wooCommerce to plugin','car-rental-booking-system')),
			3																	=>	array(__('One way: from plugin to wooCommerce','car-rental-booking-system'))
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
	
	function getBookingStatusSynchronization($bookingStatusSynchronization=null)
	{
        if(is_null($bookingStatusSynchronization)) return($this->bookingStatusSynchronization);
        else return($this->bookingStatusSynchronization[$bookingStatusSynchronization]);
	}
    
    /**************************************************************************/
    
    function isBookingStatusSynchronization($bookingStatusSynchronization)
    {
        return(array_key_exists($bookingStatusSynchronization,$this->getBookingStatusSynchronization()));
    }
	
	/**************************************************************************/
	
	function mapBookingStatus($bookingStatusId)
	{
		if($this->isBookingStatus($bookingStatusId))
		{
			return($this->bookingStatusMap[$bookingStatusId]);
		}
		else
		{
			return(array_search($bookingStatusId,$this->bookingStatusMap));
		}
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/