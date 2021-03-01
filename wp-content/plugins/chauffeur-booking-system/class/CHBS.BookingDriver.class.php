<?php

/******************************************************************************/
/******************************************************************************/

class CHBSBookingDriver
{
	/**************************************************************************/
	
    function __construct()
    {
        $this->bookingDriverEvent=array
        (
            1                                                                   =>  array
            (
                'label'                                                         =>  __('Add driver to the booking.','chauffer-booking-system')
            ),
            2                                                                   =>  array
            (
                'label'                                                         =>  __('Remove driver from the booking.','chauffer-booking-system')
            ),
            3                                                                   =>  array
            (
                'label'                                                         =>  __('Sent e-mail message to the driver.','chauffer-booking-system')
            ),
            4                                                                   =>  array
            (
                'label'                                                         =>  __('Accept booking by driver.','chauffer-booking-system')
            ),
            5                                                                   =>  array
            (
                'label'                                                         =>  __('Reject booking by driver.','chauffer-booking-system')
            )            
        );
    }
    
    /**************************************************************************/
    
	function setPostMetaDefault(&$meta)
	{
        CHBSHelper::setDefault($meta,'booking_driver_log',array());
        CHBSHelper::setDefault($meta,'booking_driver_status',-1);
    }
    
    /**************************************************************************/
    
    function getEventLabel($eventId)
    {
        if(array_key_exists($eventId,$this->bookingDriverEvent)) return($this->bookingDriverEvent[$eventId]['label']);
        return(null);
    }
        
    /**************************************************************************/
    
    function setDriver($newBooking,$oldBooking=false,$sendEmail=false,$setDriver=true)
    {
        $Driver=new CHBSDriver();
        
        /***/
        
		if($setDriver)
		{
			$driverId=-1;
			$driverDictionary=$Driver->getDictionary();

			if(array_key_exists(CHBSHelper::getPostValue('driver_id'),$driverDictionary))
				$driverId=CHBSHelper::getPostValue('driver_id');    

			CHBSPostMeta::updatePostMeta($newBooking['post']->ID,'driver_id',$driverId); 
		}
		
        /***/
        
        if($oldBooking!==false)
        {
            if($newBooking['meta']['driver_id']!=$oldBooking['meta']['driver_id'])
            {
                if($newBooking['meta']['driver_id']==-1)
                {
                    $sendEmail=false;
                    $this->addEvent($newBooking['post']->ID,2);    
                }
                else
                {
                    $sendEmail=true;
                    $this->addEvent($newBooking['post']->ID,1); 
                }
            }
        }
        else $sendEmail=true;
        
        if($sendEmail)
        {
            $this->sendEmail($newBooking,$oldBooking);
        }
    }
    
    /**************************************************************************/
    
    function addEvent($bookingId,$bookingDriverEventId)
    {
        $Driver=new CHBSDriver();
        $Booking=new CHBSBooking();
        
        if(($booking=$Booking->getBooking($bookingId))===false) return(false);
        
        if(($driverDictionary=$Driver->getDictionary())===false) return(false);
        
        $driverId=$booking['meta']['driver_id'];
        
        $data=array
        (
            'date'                                                              =>  date('Y-m-d G:i:s'),
            'booking_driver_event_id'                                           =>  $bookingDriverEventId,
            'driver_id'                                                         =>  $driverId,
            'driver_first_name'                                                 =>  '',
            'driver_second_name'                                                =>  ''
        );
        
        if(array_key_exists($driverId,$driverDictionary))
        {
            $data['driver_first_name']=$driverDictionary[$driverId]['meta']['first_name'];
            $data['driver_second_name']=$driverDictionary[$driverId]['meta']['second_name'];
        }
        
        $meta=CHBSPostMeta::getPostMeta($bookingId);
        if(!is_array($meta['booking_driver_log'])) $meta['booking_driver_log']=array();
        
        array_push($meta['booking_driver_log'],$data);
        
        CHBSPostMeta::updatePostMeta($bookingId,'booking_driver_log',$meta['booking_driver_log']); 
    }
 
    /**************************************************************************/
    
    function sendEmail($newBooking,$oldBooking=false)
    {
        $Driver=new CHBSDriver();
        $Booking=new CHBSBooking();
        
        if(($driverDictionary=$Driver->getDictionary())===false) return(false);
        
        $recipient=array();

        if(array_key_exists($newBooking['meta']['driver_id'],$driverDictionary))
            $recipient[0]=$Driver->getNotificationRecipient($newBooking['post']->ID,1);
        
        if($oldBooking!==false)
        {
            if(array_key_exists($oldBooking['meta']['driver_id'],$driverDictionary))
                $recipient[1]=$Driver->getNotificationRecipient($oldBooking['post']->ID,1,'driver');
        }
        
        if(count($recipient[0]))
        {
            $chbs_logEvent=5;
            $Booking->sendEmail($newBooking['post']->ID,CHBSOption::getOption('sender_default_email_account_id'),'booking_assign_driver',$recipient[0],sprintf(__('You have been assigned to a booking "%s"','chauffeur-booking-system'),$newBooking['post']->post_title));                 
        }

		if(isset($recipient[1]))
		{
			if(count($recipient[1]))
			{
				$chbs_logEvent=6;
				$Booking->sendEmail($newBooking['post']->ID,CHBSOption::getOption('sender_default_email_account_id'),'booking_unassign_driver',$recipient[1],sprintf(__('You has been unassigned from booking "%s"','chauffeur-booking-system'),$oldBooking['post']->post_title));                 
			}
		}
    }
    
    /**************************************************************************/
    
    function generateLink($bookingId)
    {
        $Booking=new CHBSBooking();
        
        if(($booking=$Booking->getBooking($bookingId))===false) return(false);        
        
        $address=get_permalink(CHBSOption::getOption('booking_driver_confirmation_page'));
        
        if($address===false) return(false);
                
        $link=array();
		
		$link['accept']=add_query_arg(array('token'=>self::createToken($bookingId),'booking_id'=>$bookingId,'status'=>'accept'),$address);
		$link['reject']=add_query_arg(array('token'=>self::createToken($bookingId),'booking_id'=>$bookingId,'status'=>'reject'),$address);

        return($link);
    }
    
    /**************************************************************************/
    
    static function createToken($bookingId)
    {
        $salt=CHBSOption::getSalt();
        return(strtoupper(md5($salt.$bookingId)));
    }
    
    /**************************************************************************/
    
    function createConfirmationForm()
    {
        $html=null;
        
        $Driver=new CHBSDriver();
        $Booking=new CHBSBooking();
        $Validation=new CHBSValidation();
		$BookingStatus=new CHBSBookingStatus();
        
        $token=CHBSHelper::getGetValue('token',false);
        $status=CHBSHelper::getGetValue('status',false);
        $bookingId=CHBSHelper::getGetValue('booking_id',false);
        
        if($Validation->isEmpty($token)) return;
        if(($booking=$Booking->getBooking($bookingId))===false) return;
       
        if((int)$booking['meta']['booking_driver_status']===1) return;
                
        $confirmationToken=self::createToken($bookingId);
        if(strcmp($token,$confirmationToken)!=0) return;
        
        $driverDictionary=$Driver->getDictionary();
        
        $driverId=$booking['meta']['driver_id'];
        
        if(!array_key_exists($driverId,$driverDictionary)) return;
        
        if($status=='accept')
        {
            $this->addEvent($bookingId,4);
            
            CHBSPostMeta::updatePostMeta($bookingId,'booking_driver_status',1); 
			
			if($BookingStatus->isBookingStatus(CHBSOption::getOption('booking_driver_status_after_accept')))
				CHBSPostMeta::updatePostMeta($bookingId,'booking_status_id',CHBSOption::getOption('booking_driver_status_after_accept'));
            
            $emailTemplate='booking_driver_accept';
            $emailSubject=sprintf(__('Driver %s accepts booking %s'),$booking['driver_full_name'],$booking['post']->post_title);
            
            $html='<div class="chbs-main"><div class="chbs-notice">'.__('<b>Thank you!</b> You have accepted this booking.','chauffeur-booking-system').'</div></div>';
        }
        else
        {
            $this->addEvent($bookingId,5);
            
            CHBSPostMeta::updatePostMeta($bookingId,'booking_driver_status',0);
			
			if($BookingStatus->isBookingStatus(CHBSOption::getOption('booking_driver_status_after_reject')))
				CHBSPostMeta::updatePostMeta($bookingId,'booking_status_id',CHBSOption::getOption('booking_driver_status_after_reject'));
            
            $emailTemplate='booking_driver_reject';
            $emailSubject=sprintf(__('Driver %s rejects booking %s'),$booking['driver_full_name'],$booking['post']->post_title);
            
            $html='<div class="chbs-main"><div class="chbs-notice">'.__('You have rejected this booking.','chauffeur-booking-system').'</div></div>';
        }
       
        $recipient=preg_split('/;/',CHBSOption::getOption('booking_driver_email_recipient'));
        
        foreach($recipient as $index=>$value)
        {
            if(!$Validation->isEmailAddress($value))
                unset($recipient[$index]);
        }
        
        if(count($recipient))
        {
            $chbs_logEvent=7;
            $Booking->sendEmail($booking['post']->ID,CHBSOption::getOption('sender_default_email_account_id'),$emailTemplate,$recipient,$emailSubject);   
        }
        
        $oldBooking=$booking;
        $newBooking=$Booking->getBooking($bookingId);
        
		if($oldBooking['meta']['booking_status_id']!=$newBooking['meta']['booking_status_id'])
        {
            $BookingStatus=new CHBSBookingStatus();
            $bookingStatus=$BookingStatus->getBookingStatus($newBooking['meta']['booking_status_id']);
            
            $recipient=array();
            $recipient[0]=array($newBooking['meta']['client_contact_detail_email_address']);
       
            $subject=sprintf(__('Booking "%s" has changed status to "%s"','chauffeur-booking-system'),$newBooking['post']->post_title,$bookingStatus[0]);
        
            $chbs_logEvent=4;
            $Booking->sendEmail($bookingId,CHBSOption::getOption('sender_default_email_account_id'),'booking_change_status',$recipient[0],$subject);           
        }
        
        echo $html;
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/