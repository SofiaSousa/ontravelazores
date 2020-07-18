<?php
/**
 * Subscription information template
 *
 * @author  Brent Shepherd / Chuck Mac
 * @package WooCommerce_Subscriptions/Templates/Emails
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php if ( ! empty( $subscriptions ) ) : ?>
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
		<tr>
			<td class="td" scope="row" style="text-align:left;"><a href="<?php echo esc_url( ( $is_admin_email ) ? wcs_get_edit_post_link( $subscription->get_id() ) : $subscription->get_view_order_url() ); ?>"><?php /* translators: %s: Subscription ID. */ echo sprintf( esc_html_x( '#%s', 'subscription number in email table. (eg: #106)', 'smart-emails' ), esc_html( $subscription->get_order_number() ) ); ?></a></td>
			<td class="td" scope="row" style="text-align:center;"><?php echo esc_html( date_i18n( wc_date_format(), $subscription->get_time( 'start_date', 'site' ) ) ); ?></td>
			<td class="td" scope="row" style="text-align:center;"><?php echo esc_html( ( 0 < $subscription->get_time( 'end' ) ) ? date_i18n( wc_date_format(), $subscription->get_time( 'end', 'site' ) ) : _x( 'When cancelled', 'Used as end date for an indefinite subscription', 'smart-emails' ) ); ?></td>
			<td class="td" scope="row" style="text-align:right;"><?php echo wp_kses_post( $subscription->get_formatted_order_total() ); ?></td>
		</tr>
	<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
