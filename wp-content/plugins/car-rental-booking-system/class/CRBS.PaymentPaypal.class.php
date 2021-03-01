<?php

/******************************************************************************/
/******************************************************************************/

class CRBSPaymentPaypal
{
	/**************************************************************************/
	
	function __construct()
	{

	}
	
	/**************************************************************************/
	
	function createPaymentForm($postId,$locationId,$location)
	{
		$Validation=new CRBSValidation();
		
        $formURL='https://www.paypal.com/cgi-bin/webscr';
        if((int)$location['meta']['payment_paypal_sandbox_mode_enable']===1)
            $formURL='https://www.sandbox.paypal.com/cgi-bin/webscr';
        
		$successUrl=$location['meta']['payment_paypal_success_url_address'];
		if($Validation->isEmpty($successUrl)) $successUrl=add_query_arg('action','success',get_the_permalink($postId));
		
		$cancelUrl=$location['meta']['payment_paypal_cancel_url_address'];
		if($Validation->isEmpty($cancelUrl)) $cancelUrl=add_query_arg('action','cancel',get_the_permalink($postId));	
		
		$html=
		'
			<form action="'.esc_url($formURL).'" method="post" name="crbs-form-paypal" data-location-id="'.(int)$locationId.'">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="'.esc_attr($location['meta']['payment_paypal_email_address']).'">				
				<input type="hidden" name="item_name" value="">
				<input type="hidden" name="item_number" value="0">
				<input type="hidden" name="amount" value="0.00">	
				<input type="hidden" name="currency_code" value="'.esc_attr(CRBSOption::getOption('currency')).'">
				<input type="hidden" value="1" name="no_shipping">
				<input type="hidden" value="'.get_the_permalink($postId).'?action=ipn" name="notify_url">				
				<input type="hidden" value="'.esc_url($successUrl).'" name="return">
				<input type="hidden" value="'.esc_url($cancelUrl).'" name="cancel_return">
			</form>
		';
		
		return($html);
	}
    
    /**************************************************************************/
    
	function handleIPN()
	{
		$bookingId=(int)$_POST['item_number'];
		
		$Booking=new CRBSBooking();
        $Location=new CRBSLocation();
		
		$booking=$Booking->getBooking($bookingId);
		if(!count($booking)) return;
        
        $locationId=$booking['meta']['pickup_location_id'];
        if(($dictionary=$Location->getDictionary(array('location_id'=>$locationId)))===false) return(false);
        if(count($dictionary)!=1) return(false);
		        
		$request='cmd='.urlencode('_notify-validate');
        
        $postData=array_map('stripslashes',$_POST);
        
		foreach($postData as $key=>$value) 
			$request.='&'.$key.'='.urlencode($value);

        $address='https://ipnpb.paypal.com/cgi-bin/webscr';
        if($dictionary[$locationId]['meta']['payment_paypal_sandbox_mode_enable']==1)
            $address='https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
        
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$address);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$request);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);
		curl_setopt($ch,CURLOPT_HTTPHEADER,array('Host: www.paypal.com'));
		$response=curl_exec($ch);
		
		if(curl_errno($ch)) return;
		if(!strcmp($response,'VERIFIED')==0) return;
		
        $meta=CRBSPostMeta::getPostMeta($bookingId);
		        		
        if(!((array_key_exists('payment_paypal_data',$meta)) && (is_array($meta['payment_paypal_data']))))
            $meta['payment_paypal_data']=array();
		
		$meta['payment_paypal_data'][]=$postData;
		
        CRBSPostMeta::updatePostMeta($bookingId,'payment_paypal_data',$meta['payment_paypal_data']);
		
		if($postData['payment_status']=='Completed')
		{
			if(CRBSOption::getOption('booking_status_payment_success')!=-1)
			{
				$oldBookingStatusId=$meta['booking_status_id'];
				$newBookingStatusId=CRBSOption::getOption('booking_status_payment_success');

				if($oldBookingStatusId!==$newBookingStatusId)
				{
					CRBSPostMeta::updatePostMeta($bookingId,'booking_status_id',$newBookingStatusId);

					if((int)CRBSOption::getOption('booking_status_synchronization')===3)
					{
						$WooCommerce=new CRBSWooCommerce();
						$WooCommerce->changeStaus(-1,$bookingId);
					}

					$Booking=new CRBSBooking();

					$BookingStatus=new CRBSBookingStatus();
					$bookingStatus=$BookingStatus->getBookingStatus($newBookingStatusId);

					$recipient=array();
					$recipient[0]=array($meta['client_contact_detail_email_address']);

					$subject=sprintf(__('Booking "%s" has changed status to "%s"','car-rental-booking-system'),$booking['post']->post_title,$bookingStatus[0]);

					global $crbs_logEvent;

					$crbs_logEvent=4;
					$Booking->sendEmail($bookingId,CRBSOption::getOption('sender_default_email_account_id'),'booking_change_status',$recipient[0],$subject);           
				}
			}
		}
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/