<?php
/**
 * SMS Orders Alert/Notifications for WooCommerce
 *
 * @package Smart_Marketing_Addon_Sms_Order
 */

add_action( 'init', 'ot_smart_sms_init' );

/**
 * Add filters and action to handle SMS Orders Alert/Notifications for WooCommerce.
 */
function ot_smart_sms_init() {
	add_filter( 'woocommerce_form_field_args', 'ot_wc_sms_notification_field_args', 10, 2 );
}

/**
 * Change sms notification checkbox label.
 *
 * @param Array  $args The args.
 * @param String $key  The key.
 *
 * @return Array The args.
 */
function ot_wc_sms_notification_field_args( $args, $key ) {
	if ( 'egoi_notification_option' === $key ) {
		$args['label'] = __( 'Notify me by sms about booking status', 'citytours' );
	}

	return $args;
}
