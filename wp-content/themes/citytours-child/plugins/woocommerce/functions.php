<?php
/**
 * WooCommerce
 *
 * @package WooCommerce
 */

add_action( 'init', 'ot_wc_init' );

/**
 * Initial setup.
 */
function ot_wc_init() {
	add_filter( 'woocommerce_endpoint_order-received_title', 'ot_wc_order_received_title', 10, 2 );
	add_filter( 'woocommerce_order_button_text', 'ot_wc_order_button_text' );
	add_filter( 'woocommerce_get_item_data', 'ot_wc_extra_booking_info', 10, 2 );

	add_filter( 'woocommerce_form_field_args', 'ot_wc_form_field_args', 10, 3 );

	// Remove actions.
	remove_action( 'woocommerce_order_status_changed', 'ct_woocommerce_payment_complete', 50, 4 );
}

/**
 * Change 'Order Received' title.
 *
 * @param string $title    The old title.
 * @param string $endpoint The endpoint.
 *
 * @return string
 */
function ot_wc_order_received_title( $title, $endpoint ) {
	return __( 'Booking Received', 'woocommerce' );
}

/**
 * Change order button text.
 *
 * @return string
 */
function ot_wc_order_button_text() {
	return __( 'Confirm Booking', 'woocommerce' );
}

/**
 * Add Infants and additional services to Cart.
 */
function ot_wc_extra_booking_info( $item_data, $cart_item ) {
	$product_id        = $cart_item['product_id'];
	$is_custom_product = false;
	$post_type         = false;

	$hotel_tour_id = get_post_meta( $product_id, '_ct_post_id', true );
	if ( ! empty( $hotel_tour_id ) ) {
			$is_custom_product = true;
			$post_type         = get_post_type( $hotel_tour_id );
	}

	if ( $is_custom_product && $post_type ) {
		// Change Adults and Kids labels.
		foreach ( $item_data as $key => $item ) {
			if ( __( 'Adults', 'citytours' ) === $item['name'] ) {
				$item_data[ $key ]['name'] = __( 'Adults (+11 Years)', 'citytours' );
			} elseif ( __( 'Kids', 'citytours' ) === $item['name'] ) {
				$item_data[ $key ]['name'] = __( 'Children (3 - 10 Years)', 'citytours' );
			}
		}

		if ( 'tour' === $post_type ) {
			$booking_details = get_post_meta( $product_id, '_ct_booking_info', true );

			// Add Infants.
			$item_data[] = array(
				'name'  => __( 'Infants (0 - 2 Years)', 'citytours' ),
				'value' => ( '' == $booking_details['infants'] ? '0' : $booking_details['infants'] ),
			);

			// Aditional Services.
			$add_services = get_post_meta( $product_id, '_ct_add_service' );
			$add_services = $add_services[0];

			if ( ! empty( $add_services ) ) {
				foreach ( $add_services as $service ) {
					$service_id = esc_attr( $service['service_id'] );
					$title      = esc_attr( $service['title'] );
					$quantity   = esc_attr( $service['qty'] );
					$price      = esc_attr( $service['price'] );

					$item_data[] = array(
						'name'  => __( $title . '&nbsp;(+'.$price.'&nbsp;€)', 'citytours' ),
						'value' => $quantity,
					);
				}
			}
		}
	}

	return $item_data;
}

/**
 * Add custom classes intp billing form fields.
 */
function ot_wc_form_field_args( $args, $key, $value ) {
	$args['class'][] = 'form-group';

	switch ( $key ) {
		case 'billing_postcode':
		case 'billing_country':
		case 'billing_company':
		case 'vat':
		case 'hotel':
		case 'language':
		case 'billing_address_1':
			$args['class'][] = 'col-sm-6';
			break;

		case 'account_password':
			break;

		default:
			$args['class'][] = 'col-sm-12';
			break;
	}

	$args['input_class'][] = 'form-control';

	return $args;
}
