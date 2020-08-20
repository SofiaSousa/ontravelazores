<?php
/**
 * Allow previewing email styles on last order.
 *
 * @package     smart-emails/templates
 * @author      StoreApps
 * @version     1.1.1
 * @since       1.0.0
 */

if ( ! current_user_can( 'manage_woocommerce' ) ) {
	wp_die( esc_html_e( 'You do not have permission to access this page', 'smart-emails' ) );
}

global $se_current_template, $woocommerce;

$mails = $woocommerce->mailer()->get_emails();

// Ensure gateways are loaded in case they need to insert data into the emails.
$woocommerce->payment_gateways(); // e.g Pay with cash on delivery.
$woocommerce->shipping();

// Fetch the most recent order to add data into the templates.
$order_collection = new WP_Query(
	array(
		'post_type'      => 'shop_order',
		'post_status'    => array_keys( wc_get_order_statuses() ),
		'posts_per_page' => 1,
	)
);

$order_collection = $order_collection->posts;

if ( empty( $order_collection ) ) {
	esc_html_e( 'You should have at least one order placed to preview and customize WooCommerce emails', 'smart-emails' );
	return;
}

$latest_order  = current( $order_collection )->ID;
$current_order        = new WC_Order( $latest_order ); // phpcs:ignore

if ( ! empty( $mails ) ) {
	foreach ( $mails as $mail ) {

		if ( $mail->id === $se_current_template ) {

			// Get the Customer user_id from the order, or the current user ID if guest.
			$user_id = (int) get_post_meta( $latest_order, '_customer_user', true );
			if ( 0 === $user_id ) {
				$user_id = get_current_user_id();
			}

			// Get a product from the order. If it doesnt exist anymore then get the latest product.
			$items = $current_order->get_items();
			foreach ( $items as $item ) {
				$product_id = $item['product_id'];
				if ( null !== get_post( $product_id ) ) {
					break;
				}
			}

			if ( null === get_post( $product_id ) ) {
				$products_array = new WP_Query(
					array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => 1,
						'orderby'        => 'date',
					)
				);

				if ( $products_array->posts ) {
					foreach ( $products_array->posts as $product_array ) {
						$product_id = $product_array->ID;
					}
				}
			}

			// Disable trigger sending mail - empty recipients so wp_mail doesn't send anything on next step.
			add_filter( 'woocommerce_email_recipient_' . $mail->id, '__return_empty_string', 100 );

			// trigger() is the only way to init a mail, there is no other init method.
			switch ( $mail->id ) {

				// All the default WooCommerce Emails sent to Customers.
				case 'customer_on_hold_order':
				case 'customer_processing_order':
				case 'customer_completed_order':
				case 'customer_refunded_order':
				case 'customer_invoice':
				case 'customer_note':
				case 'customer_reset_password':
				case 'customer_new_account':
				case 'customer_processing_renewal_order':
				case 'customer_completed_renewal_order':
				case 'customer_renewal_invoice':
				case 'customer_completed_switch_order':
				case 'customer_on_hold_renewal_order':
					$mail->object = $current_order;
					break;
			}

			// Get the email contents.
			$email_render = $mail->get_content();

			// Apply inline styling.
			$email_render = $mail->style_inline( $email_render );

			// Convert shortcodes.
			$email_render = do_shortcode( $email_render );

			// Display the email.
			echo $email_render;	// phpcs:ignore

		}
	}
}

if ( is_customize_preview() ) {
	wp_footer();
}
