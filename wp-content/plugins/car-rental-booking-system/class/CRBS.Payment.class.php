<?php

/******************************************************************************/
/******************************************************************************/

class CRBSPayment
{
	/**************************************************************************/
	
    function __construct()
    {
        $this->payment=array
        (
            '1'                                                                 =>  array(__('Cash','car-rental-booking-system'),'cash'),
            '2'                                                                 =>  array(__('Stripe','car-rental-booking-system'),'stripe'),
            '3'                                                                 =>  array(__('PayPal','car-rental-booking-system'),'paypal'),
            '4'                                                                 =>  array(__('Wire transfer','car-rental-booking-system'),'wire_transfer')
        );
    }
    
    /**************************************************************************/
    
    function getPayment($payment=null)
    {
        if($payment===null) return($this->payment);
        else return($this->payment[$payment]);
    }
    
    /**************************************************************************/
    
    function getPaymentName($payment)
    {
        if($this->isPayment($payment))
            return($this->payment[$payment][0]);
        
        return(null);
    }
    
    /**************************************************************************/
    
    function isPayment($payment)
    {
        return(array_key_exists($payment,$this->payment));
    }
        
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/