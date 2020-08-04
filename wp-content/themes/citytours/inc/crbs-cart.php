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
 * Add Temp Product product's category, and add actions and filters.
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

	add_action( 'wp_ajax_' . PLUGIN_CRBS_CONTEXT . '_go_to_step', 'ot_crbs_go_to_step', 1, 0 );
	add_action( 'wp_ajax_nopriv_' . PLUGIN_CRBS_CONTEXT . '_go_to_step', 'ot_crbs_go_to_step', 1, 0 );

	add_action( 'woocommerce_cart_item_removed', 'ot_wc_cart_item_removed', 10, 2 );
	add_filter( 'woocommerce_get_item_data', 'ot_crbs_wc_get_item_data', 10, 2 );
	add_action( 'woocommerce_checkout_create_order_line_item', 'ot_crbs_wc_checkout_create_order_line_item', 20, 4 );
	add_filter( 'woocommerce_cart_item_permalink', 'ot_crbs_wc_cart_item_permalink', 10, 3 );

	add_action( 'wp_footer', 'ot_crbs_remove_billing_form' );
}

/**
 * Workaround to override the step 5 of CRBS reservation wizard, in order to
 * add the bookings' items to the chart before doing the checkout manually.
 */
function ot_crbs_go_to_step() {
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

				// Temporary product category.
				$term = get_term_by( 'slug', 'ot-temp-product', 'product_cat' );

				if ( ! empty( $booking_id ) ) {
					$product_name = get_the_title( $data['vehicle_id'] );
					$image_id     = get_post_meta( $data['vehicle_id'], '_thumbnail_id', true );

					$billing = $booking->createBilling( $booking_id );

					foreach ( $billing['detail'] as $detail ) {
						if ( 'Rental fee per day' === $detail['name'] ) {
							$name = $product_name;
						} else {
							$name = $product_name . ' - Extra: ' . $detail['name'];
						}

						$product = $woo_commerce->prepareProduct(
							array(
								'post' => array( 'post_title' => $name ),
								'meta' => array(
									'crbs_booking_id'  => $booking_id,
									'crbs_price_gross' => $detail['value_gross'],
									'crbs_tax_value'   => $detail['tax_value'],
									'_regular_price'   => $detail['value_net'],
									'_sale_price'      => $detail['value_net'],
									'_price'           => $detail['value_net'],
								),
							)
						);

						$product_id = $woo_commerce->createProduct( $product );
						wp_set_object_terms( $product_id, $term->term_id, 'product_cat' );

						// Add featured image.
						if ( isset( $image_id ) ) {
							update_post_meta( $product_id, '_thumbnail_id', $image_id );
						}

						// Set category.
						wp_set_object_terms( $product_id, $term_id, 'product_cat' );

						// Add to cart.
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

/**
 * Add extra data to rental booking item in cart
 * (pick up and drop off data).
 *
 * @param Array $item_data The item data.
 * @param Array $cart_item The cart item.
 */
function ot_crbs_wc_get_item_data( $item_data, $cart_item ) {
	$product_id = $cart_item['product_id'];

	// CRBS booking.
	$booking_id = get_post_meta( $product_id, 'crbs_booking_id', true );

	if ( ! empty( $booking_id ) ) {
		$pickup_date = get_post_meta( $booking_id, 'crbs_pickup_datetime', true );
		$item_data[] = array(
			'name'  => __( 'Pick Up Date', 'citytours' ),
			'value' => $pickup_date,
		);

		$pickup_loc  = get_post_meta( $booking_id, 'crbs_pickup_location_name', true );
		$item_data[] = array(
			'name'  => __( 'Pick Up Location', 'citytours' ),
			'value' => $pickup_loc,
		);

		$return_date = get_post_meta( $booking_id, 'crbs_return_date', true );
		$return_time = get_post_meta( $booking_id, 'crbs_return_time', true );
		$item_data[] = array(
			'name'  => __( 'Drop Off Date', 'citytours' ),
			'value' => $return_date . ' ' . $return_time,
		);

		$return_loc  = get_post_meta( $booking_id, 'crbs_return_location_name', true );
		$item_data[] = array(
			'name'  => __( 'Drop Off Location', 'citytours' ),
			'value' => $return_loc,
		);
	}

	return $item_data;
}

/**
 * Add extra data to rental booking item on checkout
 * (pick up and drop off data).
 *
 * @param Object $item           The item.
 * @param String $cart_item_key  The cart item key.
 * @param Array  $values         The values.
 * @param Array  $order          The order.
 */
function ot_crbs_wc_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
	$product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();

	// CRBS booking.
	$booking_id = get_post_meta( $product_id, 'crbs_booking_id', true );

	if ( ! empty( $booking_id ) ) {
		$pickup_date = get_post_meta( $booking_id, 'crbs_pickup_datetime', true );
		$item->update_meta_data( __( 'Pick Up Date', 'citytours' ), $pickup_date );

		$pickup_loc = get_post_meta( $booking_id, 'crbs_pickup_location_name', true );
		$item->update_meta_data( __( 'Pick Up Location', 'citytours' ), $pickup_loc );

		$return_date = get_post_meta( $booking_id, 'crbs_return_date', true );
		$return_time = get_post_meta( $booking_id, 'crbs_return_time', true );
		$item->update_meta_data( __( 'Drop Off Date', 'citytours' ), $return_date . ' ' . $return_time );

		$return_loc = get_post_meta( $booking_id, 'crbs_return_location_name', true );
		$item->update_meta_data( __( 'Drop Off Location', 'citytours' ), $return_loc );
	}
}

/**
 * Remove permalink for rental booking cart item.
 *
 * @param String $product_get_permalink_cart_item The item.
 * @param Array  $cart_item     The cart item.
 * @param String $cart_item_key The cart item key.
 */
function ot_crbs_wc_cart_item_permalink( $product_get_permalink_cart_item, $cart_item, $cart_item_key ) {
	$product_id = $cart_item['product_id'];

	// CRBS booking.
	$booking_id = get_post_meta( $product_id, 'crbs_booking_id', true );

	if ( ! empty( $booking_id ) ) {
		return false;
	}

	return $product_get_permalink_cart_item;
}

/**
 * Remove billing form from dom (step 3 in wizard).
 */
function ot_crbs_remove_billing_form() {
	?>
	<script type="text/javascript">
		jQuery( document ).ajaxComplete(function() {
			var step = jQuery('.crbs-main-content-step-3');

			if (step) {
				var input = step.find('[name="crbs_client_billing_detail_enable"]');

				if (input) {
					input.val(0);
					input.parent().parent().remove();
				}
			}
		});
	</script>
	<?php
}
