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
