<?php
/**
 * Customer completed renewal order email
 *
 * @author  Brent Shepherd
 * @package WooCommerce_Subscriptions/Templates/Emails
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'smart-emails' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
<p><?php esc_html_e( 'We have finished processing your subscription renewal order.', 'smart-emails' ); ?></p>

<?php
do_action( 'woocommerce_subscriptions_email_order_details', $order, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

if ( SA_WC_Compatibility_3_7::is_wc_gte_37() ) {
	/**
	 * Show user-defined additional content - this is set in each email's settings.
	 */
	if ( $additional_content ) {
		echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
	}
} else {
	?>
	<p>
	<?php esc_html_e( 'Thanks for shopping with us.', 'smart-emails' ); ?>
	</p>
	<?php
}

do_action( 'woocommerce_email_footer', $email );
