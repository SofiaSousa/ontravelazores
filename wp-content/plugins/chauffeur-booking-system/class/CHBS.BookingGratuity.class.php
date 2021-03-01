<?php

/******************************************************************************/
/******************************************************************************/

class CHBSBookingGratuity
{
	/**************************************************************************/
	
    function __construct()
    {
        $this->type=array
        (
            1                                                                   =>  array(__('Fixed','chauffeur-booking-system')),
            2                                                                   =>  array(__('Percentage','chauffeur-booking-system'))      
        );
    }
    
    /**************************************************************************/
    
    function getType()
    {
        return($this->type);
    }
    
    /**************************************************************************/
    
    function isType($type)
    {
        return(array_key_exists($type,$this->type));
    }
      
    /**************************************************************************/
    
    function isEnable($bookingFormMeta)
    {
        return(((int)$bookingFormMeta['price_hide']===0) && ((int)$bookingFormMeta['gratuity_enable']===1));
    }
    
    /**************************************************************************/
    
    function isEnableCustomer($bookingFormMeta)
    {
        return(($this->isEnable($bookingFormMeta)) && ((int)$bookingFormMeta['gratuity_customer_enable']===1));
    }
    
    /**************************************************************************/
    
    function calculateBookingGratuity($bookingFormMeta,$priceTotalSum)
    {
        $gratuity=0.00;
        
        $Validation=new CHBSValidation();
        
        if($this->isEnable($bookingFormMeta))
        {
            if((int)$bookingFormMeta['gratuity_admin_type']===1)
            {
                $gratuity=$bookingFormMeta['gratuity_admin_value'];
            }
            else
            {
                $gratuity=$priceTotalSum*($bookingFormMeta['gratuity_admin_value']/100);
            }
            
            if($this->isEnableCustomer($bookingFormMeta))
            {
                $type=1;
                $value=CHBSHelper::getPostValue('gratuity_customer_value');
                        
				if($Validation->isEmpty($value))
				{
					$value=$gratuity;
					$type=(int)$bookingFormMeta['gratuity_admin_type'];
				}
				else
				{
					if(preg_match('/\%$/',$value))
					{
						$type=2;
						$value=preg_replace('/\%/','',$value);
					}
				}
				
                if($Validation->isPrice($value))
                {
                    if(($type==1) && (in_array(1,$bookingFormMeta['gratuity_customer_type'])))
                    {
                        $gratuity=$value;
                    }
                    elseif(($type==2) && (in_array(2,$bookingFormMeta['gratuity_customer_type'])))
                    {
                        $gratuity=$priceTotalSum*($value/100);
                    }
                }
            }
        }
                
        return(number_format($gratuity,2,'.',''));
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/