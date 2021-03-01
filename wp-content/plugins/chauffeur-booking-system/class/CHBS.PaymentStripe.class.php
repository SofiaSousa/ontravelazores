<?php

/******************************************************************************/
/******************************************************************************/

class CHBSPaymentStripe
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->paymentMethod=array
		(
			'alipay'															=>	array(__('Alipay','chauffeur-booking-system')),
			'card'																=>	array(__('Cards','chauffeur-booking-system')),			
			'ideal'																=>	array(__('iDEAL','chauffeur-booking-system')),
			'fpx'																=>	array(__('FPX','chauffeur-booking-system')),
			'bacs_debit'														=>	array(__('Bacs Direct Debit','chauffeur-booking-system')),
			'bancontact'														=>	array(__('Bancontact','chauffeur-booking-system')),
			'giropay'															=>	array(__('Giropay','chauffeur-booking-system')),
			'p24'																=>	array(__('Przelewy24','chauffeur-booking-system')),
			'eps'																=>	array(__('EPS','chauffeur-booking-system')),
			'sofort'															=>	array(__('Sofort','chauffeur-booking-system')),
			'sepa_debit'														=>	array(__('SEPA Direct Debit','chauffeur-booking-system'))
		);
		
		$this->event=array
		(
			'payment_intent.canceled',
			'payment_intent.created',
			'payment_intent.payment_failed',
			'payment_intent.processing',
			'payment_intent.requires_action',
			'payment_intent.succeeded',
			'payment_method.attached'
		);
		
		asort($this->paymentMethod);
	}
	
	/**************************************************************************/
	
	function getPaymentMethod()
	{
		return($this->paymentMethod);
	}
	
	/**************************************************************************/
	
	function isPaymentMethod($paymentMethod)
	{
		return(array_key_exists($paymentMethod,$this->paymentMethod) ? true : false);
	}
	
	/**************************************************************************/
	
	function getWebhookEndpointUrlAdress()
	{
		$address=add_query_arg('action','payment_stripe',home_url().'/');
		return($address);
	}
	
	/**************************************************************************/
	
	function createWebhookEndpoint($bookingForm)
	{
		$StripeClient=new \Stripe\StripeClient($bookingForm['meta']['payment_stripe_api_key_secret']);
		
		$webhookEndpoint=$StripeClient->webhookEndpoints->create(['url'=>$this->getWebhookEndpointUrlAdress(),'enabled_events'=>$this->event]);		
		
		CHBSOption::updateOption(array('payment_stripe_webhook_endpoint_id'=>$webhookEndpoint->id));
	}
	
	/**************************************************************************/
	
	function updateWebhookEndpoint($bookingForm,$webhookEndpointId)
	{
		$StripeClient=new \Stripe\StripeClient($bookingForm['meta']['payment_stripe_api_key_secret']);
		
		$StripeClient->webhookEndpoints->update($webhookEndpointId,['url'=>$this->getWebhookEndpointUrlAdress()]);
	}
	
	/**************************************************************************/
	
	function createSession($booking,$bookingBilling,$bookingForm)
	{
		$Validation=new CHBSValidation();
		
		$currentURLAddress=home_url();
		
		/***/
		
		Stripe\Stripe::setApiKey($bookingForm['meta']['payment_stripe_api_key_secret']);

		/***/
		
		$webhookEndpointId=CHBSOption::getOption('payment_stripe_webhook_endpoint_id');
		
		if($Validation->isEmpty($webhookEndpointId)) $this->createWebhookEndpoint($bookingForm);
		else
		{
			try
			{
				$this->updateWebhookEndpoint($bookingForm,$webhookEndpointId);
			} 
			catch (Exception $ex) 
			{
				$this->createWebhookEndpoint($bookingForm);
			}
		}
		
		/***/
		
		$productId=$bookingForm['meta']['payment_stripe_product_id'];
		
		if($Validation->isEmpty($productId))
		{
			$product=\Stripe\Product::create(
			[
				'name'															=> __('Chauffeur service','chauffeur-booking-system')
			]);		
			
			$productId=$product->id;
			
			CHBSPostMeta::updatePostMeta($bookingForm['post']->ID,'payment_stripe_product_id',$productId);
		}
		
		/***/
		
		$price=\Stripe\Price::create(
		[
			'product'															=>	$productId,
			'unit_amount'														=>	$bookingBilling['summary']['pay']*100,
			'currency'															=>	$booking['meta']['currency_id'],
		]);

		/***/
		
		
		if($Validation->isEmpty($bookingForm['meta']['payment_stripe_success_url_address']))
			$bookingForm['meta']['payment_stripe_success_url_address']=$currentURLAddress;
		if($Validation->isEmpty($bookingForm['meta']['payment_stripe_cancel_url_address']))
			$bookingForm['meta']['payment_stripe_cancel_url_address']=$currentURLAddress;
		
		$session=\Stripe\Checkout\Session::create
		(
			[
				'payment_method_types'											=>	$bookingForm['meta']['payment_stripe_method'],
				'mode'															=>	'payment',
				'line_items'													=>
				[
					[
						'price'													=>	$price->id,
						'quantity'												=>	1
					]
				],
				'success_url'													=>	$bookingForm['meta']['payment_stripe_success_url_address'],
				'cancel_url'													=>	$bookingForm['meta']['payment_stripe_cancel_url_address']
			]		
		);
		
		CHBSPostMeta::updatePostMeta($booking['post']->ID,'payment_stripe_intent_id',$session->payment_intent);
		
		return($session->id);
	}
	
	/**************************************************************************/
	
	function receivePayment()
	{
		if(!array_key_exists('action',$_REQUEST)) return(false);
		
		if($_REQUEST['action']=='payment_stripe')
		{
			global $post;
			
			$event=null;
			$content=@file_get_contents('php://input');
	
			try 
			{
				$event=\Stripe\Event::constructFrom(json_decode($content,true));
			} 
			catch(\UnexpectedValueException $e) 
			{
				http_response_code(400);
				exit();
			}	
			
			if(in_array($event->type,$this->event))
			{
				$argument=array
				(
                    'post_type'                                                 =>	CHBSBooking::getCPTName(),
                    'posts_per_page'                                            =>	-1,
                    'meta_query'                                                =>  array
                    (
                        array
                        (
                            'key'                                               =>  PLUGIN_CHBS_CONTEXT.'_payment_stripe_intent_id',
                            'value'                                             =>  $event->data->object->id
                        )                      
                    )
				);
				
                CHBSHelper::preservePost($post,$bPost);
				
	            $query=new WP_Query($argument);
                if($query!==false) 
                {
					while($query->have_posts())
					{
						$query->the_post();
                    
						$meta=CHBSPostMeta::getPostMeta($post);
						
						if(!array_key_exists('payment_stripe_data',$meta)) $meta['payment_stripe_data']=array();
						
						$meta['payment_stripe_data'][]=$event;
						
						CHBSPostMeta::updatePostMeta($post->ID,'payment_stripe_data',$meta['payment_stripe_data']);
						
						if($event->type=='payment_intent.succeeded')
						{
							if(CHBSOption::getOption('booking_status_payment_success')!=-1)
							{
								$oldBookingStatusId=$meta['booking_status_id'];
								$newBookingStatusId=CHBSOption::getOption('booking_status_payment_success');
								
								if($oldBookingStatusId!==$newBookingStatusId)
								{
									CHBSPostMeta::updatePostMeta($post->ID,'booking_status_id',$newBookingStatusId);
								
									if((int)CHBSOption::getOption('booking_status_synchronization')===3)
									{
										$WooCommerce=new CHBSWooCommerce();
										$WooCommerce->changeStaus($post->ID,CHBSOption::getOption('booking_status_synchronization'));
									}
									
									$Booking=new CHBSBooking();
								
									$BookingStatus=new CHBSBookingStatus();
									$bookingStatus=$BookingStatus->getBookingStatus($newBookingStatusId);

									$recipient=array();
									$recipient[0]=array($meta['client_contact_detail_email_address']);

									$subject=sprintf(__('Booking "%s" has changed status to "%s"','chauffeur-booking-system'),$post->post_title,$bookingStatus[0]);

									global $chbs_logEvent;
									
									$chbs_logEvent=4;
									$Booking->sendEmail($post->ID,CHBSOption::getOption('sender_default_email_account_id'),'booking_change_status',$recipient[0],$subject);           
								}
							}
						}
						
						break;
					}
                }
			
				CHBSHelper::preservePost($post,$bPost,0);
			}
		
			http_response_code(200);
			exit();
		}
	}
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/