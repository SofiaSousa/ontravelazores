<?php
/**
 * Chauffeur Booking System
 *
 * Overriding the 5th step of the booking wizard. Instead of doing the checkout,
 * it will add the rental car items to the cart and create a CHBS booking draft.
 *
 * @package ChauffeurBookingSystem
 */

if ( ! define( 'PLUGIN_CHBS_CONTEXT' ) ) {
	define( 'PLUGIN_CHBS_CONTEXT', 'chbs' );
}

add_action( 'init', 'ot_chbs_init' );

/**
 * Initial setup.
 */
function ot_chbs_init() {
	add_action( 'wp_ajax_' . PLUGIN_CHBS_CONTEXT . '_go_to_step', 'ot_chbs_go_to_step', 1, 0 );
	add_action( 'wp_ajax_nopriv_' . PLUGIN_CHBS_CONTEXT . '_go_to_step', 'ot_chbs_go_to_step', 1, 0 );

	add_filter( 'woocommerce_get_item_data', 'ot_chbs_wc_get_item_data', 10, 2 );
	add_action( 'woocommerce_checkout_create_order_line_item', 'ot_chbs_wc_checkout_create_order_line_item', 20, 4 );
	add_filter( 'woocommerce_cart_item_permalink', 'ot_chbs_wc_cart_item_permalink', 10, 3 );

	add_action( 'wp_footer', 'ot_chbs_remove_fields' );
}

/**
 * Workaround to override the step 5 of CHBS reservation wizard, in order to
 * add the bookings' items to the chart before doing the checkout manually.
 */
function ot_chbs_go_to_step() {
	if ( class_exists( 'CHBSHelper' ) && class_exists( 'CHBSBooking' ) ) {
		$booking_form = new CHBSBookingForm();
		$booking_form->init();

		$data = CHBSHelper::getPostOption();

		if ( 5 == $data['step_request'] && 4 == $data['step'] ) {
			$response = array();

			$form = $booking_form->checkBookingForm( $data['booking_form_id'] );

			if ( ! is_array( $form ) ) {
				if ( -3 === $form ) {
					$response['step'] = 1;
					CHBSBooking::setErrorGlobal( $response, __( 'Cannot find at least one vehicle available in selected time period.', 'chauffeur-booking-system' ) );
					CHBSBooking::createFormResponse( $response );
				}
			}

			$booking      = new CHBSBooking();
			$woo_commerce = new CHBSWooCommerce();

			if ( $woo_commerce->isEnable( $form['meta'] ) ) {
				$booking_id = $booking->sendBooking( $data, $form );

				// Temporary product category.
				$term = get_term_by( 'slug', 'ot-temp-product', 'product_cat' );

				if ( ! empty( $booking_id ) ) {
					$product_name = 'Transfer - ' . get_the_title( $data['vehicle_id'] );
					$image_id     = get_post_meta( $data['vehicle_id'], '_thumbnail_id', true );

					$billing = $booking->createBilling( $booking_id );

					$products_details = array(
						array(
							'name'        => $product_name,
							'value_gross' => 0,
							'tax_value'   => 0,
							'value_net'   => 0,
						),
					);

					foreach ( $billing['detail'] as $detail ) {
						if ( 'initial_fee' === $detail['type'] || 'chauffeur_service' === $detail['type'] || 'chauffeur_service_return' === $detail['type'] ) {
							$products_details[0]['value_gross'] += floatval( $detail['value_gross'] );
							$products_details[0]['tax_value']   += floatval( $detail['tax_value'] );
							$products_details[0]['value_net']   += floatval( $detail['value_net'] );
						} else {
							$products_details[] = array(
								'name'        => 'Extra: ' . $detail['name'],
								'value_gross' => floatval( $detail['value_gross'] ),
								'tax_value'   => floatval( $detail['tax_value'] ),
								'value_net'   => floatval( $detail['value_net'] ),
							);
						}
					}

					foreach ( $products_details as $detail ) {
						$product = $woo_commerce->prepareProduct(
							array(
								'post' => array( 'post_title' => $detail['name'] ),
								'meta' => array(
									'chbs_booking_id'  => $booking_id,
									'chbs_price_gross' => $detail['value_gross'],
									'chbs_tax_value'   => $detail['tax_value'],
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

			CHBSHelper::createJSONResponse( $response );
		}
	}
}

/**
 * Add extra data to chauffeur service item in cart
 * (pick up and drop off data).
 *
 * @param Array $item_data The item data.
 * @param Array $cart_item The cart item.
 */
function ot_chbs_wc_get_item_data( $item_data, $cart_item ) {
	$product_id = $cart_item['product_id'];

	// CHBS booking.
	$booking_id = get_post_meta( $product_id, 'chbs_booking_id', true );

	if ( ! empty( $booking_id ) && strpos( $cart_item['data']->get_name(), 'Extra' ) === false ) {
		$pickup_date = get_post_meta( $booking_id, 'chbs_pickup_date', true );
		$pickup_time = get_post_meta( $booking_id, 'chbs_pickup_time', true );
		$item_data[] = array(
			'name'  => __( 'Pick Up Date', 'citytours' ),
			'value' => $pickup_date . ' ' . $pickup_time,
		);

		$coordinate = get_post_meta( $booking_id, 'chbs_coordinate', true );

		foreach ( $coordinate as $key => $value ) {
			if ( $value ) {
				if ( 0 == $key ) {
					$name = __( 'Pick Up Location', 'citytours' );
				} elseif ( count( $coordinate ) - 1 == $key ) {
					$name = __( 'Drop Off Location', 'citytours' );
				} else {
					$name = __( 'Waypoint', 'citytours' );
				}

				$item_data[] = array(
					'name'  => $name,
					'value' => $coordinate[ $key ]['formatted_address'],
				);
			}
		}

		$return_date = get_post_meta( $booking_id, 'chbs_return_date', true );
		$return_time = get_post_meta( $booking_id, 'chbs_return_time', true );

		if ( $return_date && '00-00-0000' !== $return_date && $return_time ) {
			$item_data[] = array(
				'name'  => __( 'Return Date', 'citytours' ),
				'value' => $return_date . ' ' . $return_time,
			);
		}

		$element_field = get_post_meta( $booking_id, 'chbs_form_element_field', true );
		foreach ( $element_field as $field ) {
			$item_data[] = array(
				'name'  => $field['label'],
				'value' => $field['value'],
			);
		}
	}

	return $item_data;
}

/**
 * Add extra data to chauffeur service item on checkout
 * (pick up data).
 *
 * @param Object $item           The item.
 * @param String $cart_item_key  The cart item key.
 * @param Array  $values         The values.
 * @param Array  $order          The order.
 */
function ot_chbs_wc_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
	$product    = $values['data'];
	$product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();

	// CHBS booking.
	$booking_id = get_post_meta( $product_id, 'chbs_booking_id', true );

	if ( ! empty( $booking_id ) && strpos( $product->get_name(), 'Extra' ) === false ) {
		$pickup_date = get_post_meta( $booking_id, 'chbs_pickup_date', true );
		$pickup_time = get_post_meta( $booking_id, 'chbs_pickup_time', true );
		$item->update_meta_data( __( 'Pick Up Date', 'citytours' ), $pickup_date . ' ' . $pickup_time );

		$coordinate = get_post_meta( $booking_id, 'chbs_coordinate', true );

		foreach ( $coordinate as $key => $value ) {
			if ( $value ) {
				if ( 0 == $key ) {
					$name = __( 'Pick Up Location', 'citytours' );
				} elseif ( count( $coordinate ) - 1 == $key ) {
					$name = __( 'Drop Off Location', 'citytours' );
				} else {
					$name = __( 'Waypoint', 'citytours' );
				}

				$item->update_meta_data( $name, $coordinate[ $key ]['formatted_address'] );
			}
		}

		$return_date = get_post_meta( $booking_id, 'chbs_return_date', true );
		$return_time = get_post_meta( $booking_id, 'chbs_return_time', true );

		if ( $return_date && '00-00-0000' !== $return_date && $return_time ) {
			$item->update_meta_data( __( 'Drop Off Date', 'citytours' ), $return_date . ' ' . $return_time );
		}

		$element_field = get_post_meta( $booking_id, 'chbs_form_element_field', true );
		foreach ( $element_field as $field ) {
			$item->update_meta_data( $field['label'], $field['value'] );
		}
	}
}

/**
 * Remove permalink for rental booking cart item.
 *
 * @param String $product_get_permalink_cart_item The item.
 * @param Array  $cart_item     The cart item.
 * @param String $cart_item_key The cart item key.
 */
function ot_chbs_wc_cart_item_permalink( $product_get_permalink_cart_item, $cart_item, $cart_item_key ) {
	$product_id = $cart_item['product_id'];

	// CHBS booking.
	$booking_id = get_post_meta( $product_id, 'chbs_booking_id', true );

	if ( ! empty( $booking_id ) ) {
		return false;
	}

	return $product_get_permalink_cart_item;
}

/**
 * Remove billing form from dom (step 3 in wizard).
 */
function ot_chbs_remove_fields() {
	?>
	<script type="text/javascript">
		jQuery( document ).ajaxComplete(function() {
			var step = jQuery('.chbs-main-content-step-3');

			if (step) {
				var billing = step.find('[name="chbs_client_billing_detail_enable"]');
				var comment = step.find('[name="chbs_comment"]');

				if (billing) {
					billing.val(0);
					billing.parent().parent().remove(); // remove form.
				}

				if (comment) {
					comment.parent().parent().remove(); // remove textarea.
				}
			}
		});
	</script>
	<?php
}
