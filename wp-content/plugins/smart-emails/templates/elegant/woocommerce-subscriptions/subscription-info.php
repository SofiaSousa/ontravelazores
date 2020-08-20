<?php
/**
 * Subscription information template
 *
 * @author  Brent Shepherd / Chuck Mac
 * @package WooCommerce_Subscriptions/Templates/Emails
 * @version 3.0.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( empty( $subscriptions ) ) {
	return;
}

$has_automatic_renewal = false;
$is_parent_order       = wcs_order_contains_subscription( $order, 'parent' );
?>
<h2><?php esc_html_e( 'Subscription information', 'smart-emails' ); ?></h2>
<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; border-bottom: 1px solid #dcdcdc; border-top: 1px solid #dcdcdc; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
	<thead>
		<tr>
			<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Subscription', 'smart-emails' ); ?></th>
			<th class="td" scope="col" style="text-align:center;"><?php echo esc_html_x( 'Start date', 'table heading', 'smart-emails' ); ?></th>
			<th class="td" scope="col" style="text-align:center;"><?php echo esc_html_x( 'End date', 'table heading', 'smart-emails' ); ?></th>
			<th class="td" scope="col" style="text-align:right;"><?php echo esc_html_x( 'Price', 'table heading', 'smart-emails' ); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ( $subscriptions as $subscription ) : ?>
		<?php $has_automatic_renewal = $has_automatic_renewal || ! $subscription->is_manual(); ?>
		<tr>
			<td class="td" scope="row" style="text-align:left;"><a href="<?php echo esc_url( ( $is_admin_email ) ? wcs_get_edit_post_link( $subscription->get_id() ) : $subscription->get_view_order_url() ); ?>"><?php /* translators: %s: Subscription ID. */ echo sprintf( esc_html_x( '#%s', 'subscription number in email table. (eg: #106)', 'smart-emails' ), esc_html( $subscription->get_order_number() ) ); ?></a></td>
			<td class="td" scope="row" style="text-align:center;"><?php echo esc_html( date_i18n( wc_date_format(), $subscription->get_time( 'start_date', 'site' ) ) ); ?></td>
			<td class="td" scope="row" style="text-align:center;"><?php echo esc_html( ( 0 < $subscription->get_time( 'end' ) ) ? date_i18n( wc_date_format(), $subscription->get_time( 'end', 'site' ) ) : _x( 'When cancelled', 'Used as end date for an indefinite subscription', 'smart-emails' ) ); ?></td>
			<td class="td" scope="row" style="text-align:right;">
				<?php echo wp_kses_post( $subscription->get_formatted_order_total() ); ?>
				<?php if ( $is_parent_order && $subscription->get_time( 'next_payment' ) > 0 ) : ?>
					<br>
					<small>
						<?php
						// Translators: Placeholder is next payment date of subscription.
						printf( esc_html__( 'Next payment: %s', 'smart-emails' ), esc_html( date_i18n( wc_date_format(), $subscription->get_time( 'next_payment', 'site' ) ) ) );
						?>
						</small>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
</tbody>
</table>
<?php if ( $has_automatic_renewal && ! $is_admin_email ) {
	if ( count( $subscriptions ) === 1 ) {
		$subscription   = reset( $subscriptions );
		$my_account_url = $subscription->get_view_order_url();
	} else {
		$my_account_url = wc_get_endpoint_url( 'subscriptions', '', wc_get_page_permalink( 'myaccount' ) );
	}

	printf(
		'<small>%s</small>',
		wp_kses_post(
			sprintf(
				// Translators: Placeholders are opening and closing My Account link tags.
				_n(
					'This subscription is set to renew automatically using your payment method on file. You can manage or cancel this subscription from your %1$smy account page%2$s.',
					'These subscriptions are set to renew automatically using your payment method on file. You can manage or cancel your subscriptions from your %1$smy account page%2$s.',
					count( $subscriptions ),
					'smart-emails'
				),
				'<a href="' . $my_account_url . '">',
				'</a>'
			)
		)
	);
}
?>
