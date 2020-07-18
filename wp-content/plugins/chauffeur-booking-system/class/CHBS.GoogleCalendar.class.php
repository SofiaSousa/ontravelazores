<?php

/******************************************************************************/
/******************************************************************************/

class CHBSGoogleCalendar
{
	/**************************************************************************/
	
	function __construct()
	{

	}
	
	/**************************************************************************/
	
	function sendBooking($bookingId,$bookingReturn=false)
	{
        $Booking=new CHBSBooking();
        $Validation=new CHBSValidation();
        $BookingForm=new CHBSBookingForm();
        
        /***/
        
        if(($booking=$Booking->getBooking($bookingId))===false) return(false);
        
        $bookingFormId=$booking['meta']['booking_form_id'];
        
        $dictionary=$BookingForm->getDictionary(array('booking_form_id'=>$bookingFormId));
        if(count($dictionary)!=1) return(false);
        
        $bookingForm=$dictionary[$bookingFormId];
        
        if((int)$bookingForm['meta']['google_calendar_enable']!==1) return(false);
        
        if(($Validation->isEmpty($bookingForm['meta']['google_calendar_id'])) || ($Validation->isEmpty($bookingForm['meta']['google_calendar_settings']))) return(false);
        
        /***/
        
		$this->token=get_option(PLUGIN_CHBS_CONTEXT.'_google_calendar_token','');
		$this->expiration=get_option(PLUGIN_CHBS_CONTEXT.'_google_calendar_expiration','');
        
        $this->calendar_id=$bookingForm['meta']['google_calendar_id']; 
        $this->settings=json_decode($bookingForm['meta']['google_calendar_settings']); 
        
        /***/
        
		$token=$this->getToken();
		if(!$token) return(false);
		
        /***/

        $Timezone=new DateTimeZone($this->getTimezoneString());
        
        /***/
        
        $duration=$booking['meta']['duration'];
        $bookingExtraTime=(int)$booking['meta']['extra_time_value'];
        
        if($bookingReturn)
        {
            $start=$booking['meta']['return_date'].' '.$booking['meta']['return_time'];
            $startDate=new DateTime($start,$Timezone);
        
            $endDate=clone $startDate;
            $endDate->modify('+'.($duration+ceil($bookingExtraTime/2)).' minutes');   
            
            $bookingReturn=false;
        }
        else
        {
            if(in_array($booking['meta']['service_type_id'],array(1,3)))
            {
                if(in_array($booking['meta']['transfer_type_id'],array(2)))
                    $duration*=2;
                if(in_array($booking['meta']['transfer_type_id'],array(3)))
                    $bookingReturn=true;
            }    
            
            if($bookingReturn)
                $bookingExtraTime=ceil($bookingExtraTime/2);
            
            $start=$booking['meta']['pickup_date'].' '.$booking['meta']['pickup_time'];
            $startDate=new DateTime($start,$Timezone);
        
            $endDate=clone $startDate;
            $endDate->modify('+'.($duration+$bookingExtraTime).' minutes');   
        }

		$bookingDescription=sprintf(__('<a href="%s" target="_blank">%s</a><br>Client: %s %s','chauffeur-booking-system'),admin_url('post.php').'?post='.$bookingId.'&action=edit',$booking['post']->post_title,$booking['meta']['client_contact_detail_first_name'],$booking['meta']['client_contact_detail_last_name']);
		
		$bookingDetails=array
        (
			'summary'															=>  $booking['post']->post_title,
			'description'														=>  $bookingDescription,
			'start'                                                             =>  array
            (
				'dateTime'														=>  $startDate->format(DateTime::RFC3339),
			),
			'end'                                                               =>  array
            (
				'dateTime'														=>  $endDate->format(DateTime::RFC3339),
			),
		);
                
        /***/

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,'https://www.googleapis.com/calendar/v3/calendars/'.$this->calendar_id.'/events?access_token='.$token);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($bookingDetails));
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: application/json')); 
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        
		$response=curl_exec($ch);
		$responseDecoded=json_decode($response);
        
        $LogManager=new CHBSLogManager();
        $LogManager->add('google_calendar',1,print_r($responseDecoded,true));   
        
		curl_close($ch);
		
		if((is_object($responseDecoded)) && (property_exists($responseDecoded,'kind')) && ($responseDecoded->kind=='calendar#event'))
        {
            if($bookingReturn)
                $this->sendBooking($bookingId,true);
            
			return(true);
        }
        
		return(false);
	}
	
	/**************************************************************************/
	
	function getCalendarList()
	{
		$token=$this->getToken();
		if(!$token) return(false);
		
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,'https://www.googleapis.com/calendar/v3/users/me/calendarList?access_token='.$token);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        
		$response=curl_exec($ch);
		$responseDecoded=json_decode($response);
        
		curl_close($ch);
		
		if((is_object($responseDecoded)) && (property_exists($responseDecoded,'kind')) && ($responseDecoded->kind=='calendar#calendarList'))
			return($responseDecoded);
		
        return(false);
	}
	
	/**************************************************************************/
	
	function getToken()
	{
		if(($this->token) && ($this->expiration) && ($this->expiration>time()))
			return($this->token);
		
        /***/
        
		$header='{"alg":"RS256","typ":"JWT"}';
		$headerEncoded=$this->base64URLEncode($header);
		
        /***/
        
		$assertionTime=time();
		$expirationTime=$assertionTime+3600;
        
		$claimSet='{
		  "iss":"'.$this->settings->client_email.'",
		  "scope":"https://www.googleapis.com/auth/calendar",
		  "aud":"https://www.googleapis.com/oauth2/v4/token",
		  "exp":'.$expirationTime.',
		  "iat":'.$assertionTime.'
		}';
        
		$claimSetEncoded=$this->base64URLEncode($claimSet);

        /***/
        
		$signature='';
		openssl_sign($headerEncoded.'.'.$claimSetEncoded,$signature,$this->settings->private_key,'SHA256');
		$signatureEncoded=$this->base64URLEncode($signature);
		$assertion=$headerEncoded.'.'.$claimSetEncoded.'.'.$signatureEncoded;

        /***/
        
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,'https://www.googleapis.com/oauth2/v4/token');
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch,CURLOPT_POSTFIELDS,'grant_type=urn%3Aietf%3Aparams%3Aoauth%3Agrant-type%3Ajwt-bearer&assertion='.$assertion);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		
        $response=curl_exec($ch);
		$responseDecoded=json_decode($response);
		
        curl_close($ch);
		
		if((is_object($responseDecoded)) && (property_exists($responseDecoded,'access_token')))
		{
			$this->token=$responseDecoded->access_token;
			$this->expiration=$expirationTime;
            
			update_option(PLUGIN_CHBS_CONTEXT.'_google_calendar_token',$this->token);
			update_option(PLUGIN_CHBS_CONTEXT.'_google_calendar_expiration',$this->expiration);
            
			return($this->token);
		}
            
        return(false);
	}
	
	/**************************************************************************/
	
	function base64URLEncode($data)
	{
		return(rtrim(strtr(base64_encode($data),'+/','-_'),'='));
	}
    
    /**************************************************************************/
    
    function getTimezoneString()
    {
        $timezoneString=get_option('timezone_string');
        if(!$timezoneString)
        {
            $gmtOffset=get_option('gmt_offset');
            $timezoneString=timezone_name_from_abbr('',$gmtOffset*3600,false);
            if($timezoneString===false)
                $timezoneString=timezone_name_from_abbr('',0,false);
        }
        return($timezoneString);        
    }
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/