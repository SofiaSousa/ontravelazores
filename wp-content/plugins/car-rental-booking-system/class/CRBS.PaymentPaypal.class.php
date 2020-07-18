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
	
	function createPaymentForm($postId,$locationId,$paypalEmailAddress,$paypalSandboxModeEnable)
	{
        $formURL='https://www.paypal.com/cgi-bin/webscr';
        if($paypalSandboxModeEnable==1)
            $formURL='https://www.sandbox.paypal.com/cgi-bin/webscr';
        
		$html=
		'
			<form action="'.esc_attr($formURL).'" method="post" name="crbs-form-paypal" data-location-id="'.(int)$locationId.'">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="'.esc_attr($paypalEmailAddress).'">				
				<input type="hidden" name="item_name" value="">
				<input type="hidden" name="item_number" value="0">
				<input type="hidden" name="amount" value="0.00">	
				<input type="hidden" name="currency_code" value="'.esc_attr(CRBSOption::getOption('currency')).'">
				<input type="hidden" value="1" name="no_shipping">
				<input type="hidden" value="'.get_the_permalink($postId).'?action=ipn" name="notify_url">				
				<input type="hidden" value="'.get_the_permalink($postId).'?action=success" name="return">
				<input type="hidden" value="'.get_the_permalink($postId).'?action=cancel" name="cancel_return">
			</form>
		';
		
		return($html);
	}
    
    /**************************************************************************/
    
	function handle()
	{
		$action=CRBSHelper::getGetValue('action',false);
		if(!in_array($action,array('ipn','success','cancel'))) return(false);
		
		$Booking=new CRBSBooking();
        $Location=new CRBSLocation();
        
		$bookingId=(int)$_POST['item_number'];
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
		$response=curl_exec($ch);
         
		if(curl_errno($ch)) return;
		if(!strcmp($response,'VERIFIED')==0) return;
		
        $meta=CRBSPostMeta::getPostMeta($bookingId);
		        
        $paymentData=array
        (
            'txn_id'                                                            =>  $postData['txn_id'],
            'payment_type'                                                      =>  $postData['payment_type'],
            'payment_date'                                                      =>  date('Y-m-d H:i:s',strtotime($postData['payment_date'])),
            'payment_status'                                                    =>  $postData['payment_status'],
            'mc_gross'                                                          =>  $postData['mc_gross'],
            'mc_currency'                                                       =>  $postData['mc_currency']        
        );
		
        if(!((array_key_exists('payment_data',$meta)) && (is_array($meta['payment_data']))))
            $meta['payment_data']=array();
        
        array_push($meta['payment_data'],$paymentData);
        
        CRBSPostMeta::updatePostMeta($bookingId,'payment_data',$meta['payment_data']);
		
		$Booking->sendEmailBooking($bookingId,'AFTER_PAYMENT');
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/