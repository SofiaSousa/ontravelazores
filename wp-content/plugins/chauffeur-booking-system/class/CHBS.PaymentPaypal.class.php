<?php

/******************************************************************************/
/******************************************************************************/

class CHBSPaymentPaypal
{
	/**************************************************************************/
	
	function __construct()
	{

	}
	
	/**************************************************************************/
	
	function createPaymentForm($postId,$bookingForm)
	{
		$Validation=new CHBSValidation();
		
        $formUrl='https://www.paypal.com/cgi-bin/webscr';
        if((int)$bookingForm['meta']['payment_paypal_sandbox_mode_enable']===1)
            $formUrl='https://www.sandbox.paypal.com/cgi-bin/webscr';
        
		$successUrl=$bookingForm['meta']['payment_paypal_success_url_address'];
		if($Validation->isEmpty($successUrl)) $successUrl=add_query_arg('action','success',get_the_permalink($postId));
		
		$cancelUrl=$bookingForm['meta']['payment_paypal_cancel_url_address'];
		if($Validation->isEmpty($cancelUrl)) $cancelUrl=add_query_arg('action','cancel',get_the_permalink($postId));		
		
		$html=
		'
			<form action="'.esc_url($formUrl).'" method="post" name="chbs-form-paypal">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="'.esc_attr($bookingForm['meta']['payment_paypal_email_address']).'">				
				<input type="hidden" name="item_name" value="">
				<input type="hidden" name="item_number" value="0">
				<input type="hidden" name="amount" value="0.00">	
				<input type="hidden" name="currency_code" value="">
				<input type="hidden" value="1" name="no_shipping">
				<input type="hidden" value="'.esc_url(get_the_permalink($postId)).'?action=ipn" name="notify_url">				
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
		
		$Booking=new CHBSBooking();
		$booking=$Booking->getBooking($bookingId);
        
		if(!count($booking)) return;
        
        $BookingForm=new CHBSBookingForm();
        $bookingForm=$BookingForm->getDictionary(array('booking_form_id'=>$booking['meta']['booking_form_id']));
        
        if(!count($bookingForm)) return;
        
        $bookingForm=$bookingForm[$booking['meta']['booking_form_id']];
        
		$request='cmd='.urlencode('_notify-validate');
        
        $postData=array_map('stripslashes',$_POST);
        
		foreach($postData as $key=>$value) 
			$request.='&'.$key.'='.urlencode($value);

        $address='https://ipnpb.paypal.com/cgi-bin/webscr';
        if($bookingForm['meta']['payment_paypal_sandbox_mode_enable']==1)
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
		
        $meta=CHBSPostMeta::getPostMeta($bookingId);
		        		
        if(!((array_key_exists('payment_paypal_data',$meta)) && (is_array($meta['payment_paypal_data']))))
            $meta['payment_paypal_data']=array();
		
		$meta['payment_paypal_data'][]=$postData;
		
        CHBSPostMeta::updatePostMeta($bookingId,'payment_paypal_data',$meta['payment_paypal_data']);
		
		if($postData['payment_status']=='Completed')
		{
			if(CHBSOption::getOption('booking_status_payment_success')!=-1)
			{
				$oldBookingStatusId=$meta['booking_status_id'];
				$newBookingStatusId=CHBSOption::getOption('booking_status_payment_success');

				if($oldBookingStatusId!==$newBookingStatusId)
				{
					CHBSPostMeta::updatePostMeta($bookingId,'booking_status_id',$newBookingStatusId);

					if((int)CHBSOption::getOption('booking_status_synchronization')===3)
					{
						$WooCommerce=new CHBSWooCommerce();
						$WooCommerce->changeStaus($bookingId,CHBSOption::getOption('booking_status_synchronization'));
					}

					$Booking=new CHBSBooking();

					$BookingStatus=new CHBSBookingStatus();
					$bookingStatus=$BookingStatus->getBookingStatus($newBookingStatusId);

					$recipient=array();
					$recipient[0]=array($meta['client_contact_detail_email_address']);

					$subject=sprintf(__('Booking "%s" has changed status to "%s"','chauffeur-booking-system'),$booking['post']->post_title,$bookingStatus[0]);

					global $chbs_logEvent;

					$chbs_logEvent=4;
					$Booking->sendEmail($bookingId,CHBSOption::getOption('sender_default_email_account_id'),'booking_change_status',$recipient[0],$subject);           
				}
			}
		}
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/