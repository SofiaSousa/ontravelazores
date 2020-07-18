<?php

/******************************************************************************/
/******************************************************************************/

class CRBSBookingHelper
{
    /**************************************************************************/
    
    static function calculateRentalDayCount($pickupDate,$pickupTime,$returnDate,$returnTime,$bookingFormMeta=null,$billingType=-1)
    {
        $Date=new CRBSDate();
                
        $calculated=false;
        
        if((int)$billingType===2)
        {
            if(is_array($bookingFormMeta))
            {
                if((int)$bookingFormMeta['booking_extra_day_enable']===1)
                {
                    if($Date->compareDate($returnDate,$pickupDate)==1)
                    {
                        $calculated=true;
                        
                        $interval=floor((strtotime($returnDate.' 00:00')-strtotime($pickupDate.' 00:00'))/60/60/24);
                        
                        if($Date->compareTime($returnTime,$bookingFormMeta['booking_extra_day_time'])==1)
                        {
                            $interval+=$bookingFormMeta['booking_extra_day_number'];
                        }
                    }
                }
            }
        }       
        
        if(!$calculated)
        {
            $interval=(strtotime($returnDate.' '.$returnTime)-strtotime($pickupDate.' '.$pickupTime))/60/60/24;
            
            if(floor($interval)==$interval) $interval--;
            $interval=floor($interval)+1;
        }
        
        return($interval);        
    }
    
    /**************************************************************************/
       
    static function calculateRentalPeriod($pickupDate,$pickupTime,$returnDate,$returnTime,$bookingFormMeta=null,$billingType=-1)
    {
        $period=array('day'=>0,'hour'=>0);
        
        if($billingType===-1)
            $billingType=CRBSOption::getOption('billing_type');
        
        $hour=strtotime($returnDate.' '.$returnTime)-strtotime($pickupDate.' '.$pickupTime);
        $hour=ceil($hour/60/60);
        
        switch($billingType)
        {
            case 1:
                
                $period['hour']=$hour;
                
            break;
        
            case 2:
                
                $period['day']=self::calculateRentalDayCount($pickupDate,$pickupTime,$returnDate,$returnTime,$bookingFormMeta,$billingType);
                
            break;
        
            case 3:
                
                $period['day']=floor($hour/24);
                $period['hour']=$hour-$period['day']*24;
        
            break;
        }
        
        return($period);
    }
    
    /**************************************************************************/
    
	static function getPaymentName($paymentId,$wooCommerceEnable=-1,$meta=array())
	{
        $Payment=new CRBSPayment();
        $WooCommerce=new CRBSWooCommerce();
        
        if($wooCommerceEnable===-1)
            $wooCommerceEnable=$WooCommerce->isEnable($meta);
        
        if($wooCommerceEnable)
        {
           $paymentName=$WooCommerce->getPaymentName($paymentId);
        }
        else
        {
            $paymentName=$Payment->getPaymentName($paymentId);
        }
        
        return($paymentName);
	}
    
    /**************************************************************************/
    
    static function isPayment(&$paymentId,$bookingFormMeta,$locationMeta)
    {
        $Payment=new CRBSPayment();
        $WooCommerce=new CRBSWooCommerce();
        
        if(($WooCommerce->isEnable($bookingFormMeta)) && ((int)$bookingFormMeta['payment_woocommerce_step_3_enable']===0))
        {
            return(true);
        }
		
        if((int)$locationMeta['payment_mandatory_enable']===0)
        {
            if($WooCommerce->isEnable($meta))
            {
                if(empty($paymentId))
                {
                    $paymentId=0;
                    return(true);
                }
            }
            else
            {
                if($paymentId==0)
                {
                    return(true);
                }
            }
        }
        
        if($WooCommerce->isEnable($bookingFormMeta))
        {
            return($WooCommerce->isPayment($paymentId));
        }
        else
        {
            if(!$Payment->isPayment($paymentId)) return(false);
        }
        
        return(true);
    }
    
    /**************************************************************************/
    
    static function formatDateTimeToStandard($data)
    {
        $Date=new CRBSDate();
        
        $data['pickup_date']=$Date->formatDateToStandard($data['pickup_date']);
        $data['pickup_time']=$Date->formatTimeToStandard($data['pickup_time']);

        $data['return_date']=$Date->formatDateToStandard($data['return_date']);
        $data['return_time']=$Date->formatTimeToStandard($data['return_time']);
        
        return($data);
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/