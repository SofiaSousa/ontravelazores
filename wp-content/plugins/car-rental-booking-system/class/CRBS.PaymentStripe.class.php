<?php

/******************************************************************************/
/******************************************************************************/

class CRBSPaymentStripe
{
	/**************************************************************************/
	
	function __construct()
	{

	}
	
	/**************************************************************************/
	
	function createPaymentForm($postId,$bookingId,$bookingTitle,$amount,$publishableKey,$currency)
	{
		$html=
		'
            <form action="'.get_the_permalink($postId).'?bookingId='.(int)$bookingId.'" method="POST" name="crbs-form-stripe" >
                <script
                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
					data-key="'.esc_attr($publishableKey).'"
					data-amount="'.esc_attr($amount*100).'"
					data-name="'.esc_attr($bookingTitle).'"
					data-description="'.esc_attr__('New booking','car-rental-booking-system').'"
					data-currency="'.esc_attr($currency).'"
					data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
					data-locale="auto">
				</script>
				<button type="submit" formtarget="_blank" style="display:none !important;"></button>
            </form>
		';
		
		return($html);
	}
	
	/**************************************************************************/
	
	function handle()
	{
		$Booking=new CRBSBooking();
		$Location=new CRBSLocation();
		$Validation=new CRBSValidation();
		
        $bookingId=CRBSHelper::getGetValue('bookingId',false);
        $stripeToken=CRBSHelper::getPostValue('stripeToken',false);
        
		if($Validation->isEmpty($stripeToken)) return(false);

		$booking=$Booking->getBooking($bookingId);
		if($booking===false) return(false);

		if($booking['meta']['payment_id']!=2) return(false);

		$locationId=$booking['meta']['pickup_location_id'];
		if(($dictionary=$Location->getDictionary(array('location_id'=>$locationId)))===false) return(false);
		if(count($dictionary)!=1) return(false);

		$bookingBilling=$Booking->createBilling($bookingId);

		$data=array
		(
			'source'                                                            =>	$stripeToken,
			'description'                                                       =>	$booking['post']->post_title,
			'amount'                                                            =>	$bookingBilling['summary']['value_gross']*100,
			'currency'                                                          =>	$booking['meta']['currency_id']
		);
		
		$string=http_build_query($data);

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,'https://api.stripe.com/v1/charges');
		curl_setopt($ch,CURLOPT_USERPWD,$dictionary[$locationId]['meta']['payment_stripe_api_key_secret']);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$string);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
		$result=curl_exec($ch);

		if($result)
		{
			$result=json_decode($result);
			if(property_exists($result,'error')) return(false);

			$meta=CRBSPostMeta::getPostMeta($bookingId);

			$paymentData=array
			(
				'txn_id'                                                        =>  $result->id,
				'payment_type'                                                  =>  $result->source->object,
				'payment_date'                                                  =>  date('Y-m-d H:i:s',$result->created),
				'payment_status'                                                =>  $result->status,
				'mc_gross'                                                      =>  $result->amount/100,
				'mc_currency'                                                   =>  $result->currency        
			);

			if(!((array_key_exists('payment_data',$meta)) && (is_array($meta['payment_data']))))
				$meta['payment_data']=array();

			array_push($meta['payment_data'],$paymentData);

			CRBSPostMeta::updatePostMeta($bookingId,'payment_data',$meta['payment_data']);
		}
			
		$Booking->sendEmailBooking($bookingId,'AFTER_PAYMENT');
        
		if($Validation->isNotEmpty($dictionary[$locationId]['meta']['payment_stripe_redirect_url_address']))
		{
			wp_redirect($dictionary[$locationId]['meta']['payment_stripe_redirect_url_address']);
			exit();
		}
	}
   
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/