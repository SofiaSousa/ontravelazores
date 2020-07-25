<?php
/**
 * Car Rental Booking System
 *
 * Overriding the 5th step of the booking wizard. Instead of doing the checkout,
 * it will add the rental car items to the cart and create a CRBS booking draft.
 *
 * @package CarRentalBookingSystem
 */


add_action( 'init', 'ot_crbs_init' );

/**
 * Add Temp Product product's category and add actions.
 */
function ot_crbs_init() {
	if ( ! term_exists( 'ot-temp-product', 'product_cat' ) ) {
		// Add new category for temp rental services (in cart).
		wp_insert_term(
			'Temp Product',
			'product_cat',
			array(
				'description' => 'Temporary products that are created for the cart',
				'slug'        => 'ot-temp-product',
			)
		);
	}

	add_action( 'wp_ajax_' . PLUGIN_CRBS_CONTEXT . '_go_to_step', 'ot_go_to_step', 1, 0 );
	add_action( 'wp_ajax_nopriv_' . PLUGIN_CRBS_CONTEXT . '_go_to_step', 'ot_go_to_step', 1, 0 );
	add_action( 'woocommerce_cart_item_removed', 'ot_wc_cart_item_removed', 10, 2 );
}

/**
 * Workaround to override the step 5 of CRBS reservation wizard, in order to
 * add the bookings' items to the chart before doing the checkout manually.
 */
function ot_go_to_step() {
	if ( class_exists( 'CRBSHelper' ) && class_exists( 'CRBSBooking' ) ) {
		$booking_form = new CRBSBookingForm();
		$booking_form->init();

		$data = CRBSHelper::getPostOption();

		if ( 5 == $data['step_request'] && 4 == $data['step'] ) {
			$response = array();

			$form = $booking_form->checkBookingForm( $data['booking_form_id'] );

			if ( ! is_array( $form ) ) {
				if ( -3 === $form ) {
					$response['step'] = 1;
					CRBSBooking::setErrorGlobal( $response, __( 'Cannot find at least one vehicle available in selected time period.', 'car-rental-booking-system' ) );
					CRBSHelper::createJSONResponse( $response );
				}
			}

			$booking      = new CRBSBooking();
			$woo_commerce = new CRBSWooCommerce();

			if ( $woo_commerce->isEnable( $form['meta'] ) ) {
				$booking_id = $booking->sendBooking( $data, $form );

				if ( ! empty( $booking_id ) ) {
					$billing = $booking->createBilling( $booking_id );

					foreach ( $billing['detail'] as $detail ) {
						$product = $woo_commerce->prepareProduct(
							array(
								'post' => array( 'post_title' => $detail['name'] ),
								'meta' => array(
									'crbs_price_gross' => $detail['value_gross'],
									'crbs_tax_value'   => $detail['tax_value'],
									'_regular_price'   => $detail['value_net'],
									'_sale_price'      => $detail['value_net'],
									'_price'           => $detail['value_net'],
								),
							)
						);

						$product_id = $woo_commerce->createProduct( $product );

						// Add featured image.
						$image_id = get_post_meta( $data['vehicle_id'], '_thumbnail_id', true);
						if ( isset( $image_id ) ) {
							update_post_meta( $product_id, '_thumbnail_id', $image_id );
						}

						// Set category.
						$term_id = get_term_by( 'id', 'ot-temp-product', 'product_cat' );
						wp_set_object_terms( $product_id, $term_id, 'product_cat' );

						WC()->cart->add_to_cart( $product_id );
					}

					// Update booking status to draft, so we can delete them if client
					// didn't checkout.
					wp_update_post(
						array(
							'ID'          => $booking_id,
							'post_status' => 'draft',
						)
					);
				}
			}

			// Response with Cart url.
			$response['payment']     = null;
			$response['payment_id']  = -1;
			$response['payment_url'] = wc_get_cart_url();

			$response['step'] = $data['step_request'];

			$response['thank_you_page_enable'] = $form['meta']['thank_you_page_enable'];

			$response['summary'] = array( null, null, null );

			CRBSHelper::createJSONResponse( $response );
		}
	}
}

/**
 * Remove temporary product after its item being removed from cart.
 *
 * @param String  $cart_item_key The cart item key.
 * @param Objbect $cart          The cart instance.
 */
function ot_wc_cart_item_removed( $cart_item_key, $cart ) {
	$line_item  = $cart->removed_cart_contents[ $cart_item_key ];
	$product_id = $line_item['product_id'];

	if ( isset( $product_id ) && has_term( 'ot-temp-product', 'product_cat', $product_id ) ) {
		wp_delete_post( $product_id );
	}
};
