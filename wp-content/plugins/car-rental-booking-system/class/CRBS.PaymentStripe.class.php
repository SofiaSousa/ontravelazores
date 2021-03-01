<?php

/******************************************************************************/
/******************************************************************************/

class CRBSPaymentStripe
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->paymentMethod=array
		(
			'alipay'															=>	array(__('Alipay','car-rental-booking-system')),
			'card'																=>	array(__('Cards','car-rental-booking-system')),			
			'ideal'																=>	array(__('iDEAL','car-rental-booking-system')),
			'fpx'																=>	array(__('FPX','car-rental-booking-system')),
			'bacs_debit'														=>	array(__('Bacs Direct Debit','car-rental-booking-system')),
			'bancontact'														=>	array(__('Bancontact','car-rental-booking-system')),
			'giropay'															=>	array(__('Giropay','car-rental-booking-system')),
			'p24'																=>	array(__('Przelewy24','car-rental-booking-system')),
			'eps'																=>	array(__('EPS','car-rental-booking-system')),
			'sofort'															=>	array(__('Sofort','car-rental-booking-system')),
			'sepa_debit'														=>	array(__('SEPA Direct Debit','car-rental-booking-system'))
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
	
	function createWebhookEndpoint($pickupLocation)
	{
		$StripeClient=new \Stripe\StripeClient($pickupLocation['meta']['payment_stripe_api_key_secret']);
		
		$webhookEndpoint=$StripeClient->webhookEndpoints->create(['url'=>$this->getWebhookEndpointUrlAdress(),'enabled_events'=>$this->event]);		
		
		CRBSOption::updateOption(array('payment_stripe_webhook_endpoint_id'=>$webhookEndpoint->id));
	}
	
	/**************************************************************************/
	
	function updateWebhookEndpoint($pickupLocation,$webhookEndpointId)
	{
		$StripeClient=new \Stripe\StripeClient($pickupLocation['meta']['payment_stripe_api_key_secret']);
		
		$StripeClient->webhookEndpoints->update($webhookEndpointId,['url'=>$this->getWebhookEndpointUrlAdress()]);
	}
	
	/**************************************************************************/
	
	function createSession($booking,$bookingBilling,$bookingForm)
	{
		$Validation=new CRBSValidation();
		
		$pickupLocationId=$booking['meta']['pickup_location_id'];
		
		$pickupLocation=$bookingForm['dictionary']['location'][$pickupLocationId];
		
		/***/
		
		Stripe\Stripe::setApiKey($pickupLocation['meta']['payment_stripe_api_key_secret']);

		/***/
		
		$webhookEndpointId=CRBSOption::getOption('payment_stripe_webhook_endpoint_id');
		
		if($Validation->isEmpty($webhookEndpointId)) $this->createWebhookEndpoint($pickupLocation);
		else
		{
			try
			{
				$this->updateWebhookEndpoint($pickupLocation,$webhookEndpointId);
			} 
			catch (Exception $ex) 
			{
				$this->createWebhookEndpoint($pickupLocation);
			}
		}
		
		/***/
		
		$productId=$pickupLocation['meta']['payment_stripe_product_id'];
		
		if($Validation->isEmpty($productId))
		{
			$product=\Stripe\Product::create(
			[
				'name'															=> __('Car rental service','car-rental-booking-system')
			]);		
			
			$productId=$product->id;
			
			CRBSPostMeta::updatePostMeta($pickupLocationId,'payment_stripe_product_id',$productId);
		}
		
		/***/
		
		$price=\Stripe\Price::create(
		[
			'product'															=>	$productId,
			'unit_amount'														=>	$bookingBilling['summary']['pay']*100,
			'currency'															=>	$booking['meta']['currency_id'],
		]);

		/***/
		
		$currentURLAddress=home_url();
		if($Validation->isEmpty($pickupLocation['meta']['payment_stripe_success_url_address']))
			$pickupLocation['meta']['payment_stripe_success_url_address']=$currentURLAddress;
		if($Validation->isEmpty($pickupLocation['meta']['payment_stripe_cancel_url_address']))
			$pickupLocation['meta']['payment_stripe_cancel_url_address']=$currentURLAddress;
		
		$session=\Stripe\Checkout\Session::create
		(
			[
				'payment_method_types'											=>	$pickupLocation['meta']['payment_stripe_method'],
				'mode'															=>	'payment',
				'line_items'													=>
				[
					[
						'price'													=>	$price->id,
						'quantity'												=>	1
					]
				],
				'success_url'													=>	$pickupLocation['meta']['payment_stripe_success_url_address'],
				'cancel_url'													=>	$pickupLocation['meta']['payment_stripe_cancel_url_address']
			]		
		);
		
		CRBSPostMeta::updatePostMeta($booking['post']->ID,'payment_stripe_intent_id',$session->payment_intent);
		
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
                    'post_type'                                                 =>	CRBSBooking::getCPTName(),
                    'posts_per_page'                                            =>	-1,
                    'meta_query'                                                =>  array
                    (
                        array
                        (
                            'key'                                               =>  PLUGIN_CRBS_CONTEXT.'_payment_stripe_intent_id',
                            'value'                                             =>  $event->data->object->id
                        )                      
                    )
				);
				
                CRBSHelper::preservePost($post,$bPost);
				
	            $query=new WP_Query($argument);
                if($query!==false) 
                {
					while($query->have_posts())
					{
						$query->the_post();
                    
						$meta=CRBSPostMeta::getPostMeta($post);
						
						if(!array_key_exists('payment_stripe_data',$meta)) $meta['payment_stripe_data']=array();
						
						$meta['payment_stripe_data'][]=$event;
						
						CRBSPostMeta::updatePostMeta($post->ID,'payment_stripe_data',$meta['payment_stripe_data']);
						
						if($event->type=='payment_intent.succeeded')
						{
							if(CRBSOption::getOption('booking_status_payment_success')!=-1)
							{
								$oldBookingStatusId=$meta['booking_status_id'];
								$newBookingStatusId=CRBSOption::getOption('booking_status_payment_success');
								
								if($oldBookingStatusId!==$newBookingStatusId)
								{
									CRBSPostMeta::updatePostMeta($post->ID,'booking_status_id',$newBookingStatusId);
								
									if((int)CRBSOption::getOption('booking_status_synchronization')===3)
									{
										$WooCommerce=new CRBSWooCommerce();
										$WooCommerce->changeStaus(-1,$post->ID);
									}
									
									$Booking=new CRBSBooking();
								
									$BookingStatus=new CRBSBookingStatus();
									$bookingStatus=$BookingStatus->getBookingStatus($newBookingStatusId);

									$recipient=array();
									$recipient[0]=array($meta['client_contact_detail_email_address']);

									$subject=sprintf(__('Booking "%s" has changed status to "%s"','car-rental-booking-system'),$post->post_title,$bookingStatus[0]);

									global $crbs_logEvent;
									
									$crbs_logEvent=4;
									$Booking->sendEmail($post->ID,CRBSOption::getOption('sender_default_email_account_id'),'booking_change_status',$recipient[0],$subject);           
								}
							}
						}
						
						break;
					}
                }
			
				CRBSHelper::preservePost($post,$bPost,0);
			}
		
			http_response_code(200);
			exit();
		}
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/