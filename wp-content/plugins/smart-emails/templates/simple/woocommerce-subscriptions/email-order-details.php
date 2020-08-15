<?php
/**
 * Order/Subscription details table shown in emails.
 *
 * @author  Prospress
 * @package WooCommerce_Subscriptions/Templates/Emails
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_email_before_' . $order_type . '_table', $order, $sent_to_admin, $plain_text, $email );

if ( 'cancelled_subscription' !== $email->id ) {
	echo '<div id="se_show_order_id"><h2>';

	$link_element_url = ( $sent_to_admin ) ? wcs_get_edit_post_link( wcs_get_objects_property( $order, 'id' ) ) : $order->get_view_order_url();

	if ( 'order' === $order_type ) {
		// translators: $1-$2: opening and closing <a> tags $3: order's order number $4: date of order in <time> element.
		printf( esc_html_x( '[Order #%1$s] (%2$s)', 'Used in email notification', 'smart-emails' ), esc_html( $order->get_order_number() ), sprintf( '<time datetime="%s">%s</time>', esc_attr( wcs_get_objects_property( $order, 'date_created' )->format( 'c' ) ), esc_html( wcs_format_datetime( wcs_get_objects_property( $order, 'date_created' ) ) ) ) );
	} else {
		// translators: $1-$3: opening and closing <a> tags $2: subscription's order number.
		printf( esc_html_x( 'Subscription %1$s#%2$s%3$s', 'Used in email notification', 'smart-emails' ), '<a href="' . esc_url( $link_element_url ) . '">', esc_html( $order->get_order_number() ), '</a>' );
	}
	echo '</h2></div>';
}
?>
<div style="margin-bottom: 20px; margin-top: 10px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; border-bottom: 1px solid #dcdcdc; border-top: 1px solid #dcdcdc; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:left;"><?php echo esc_html_x( 'Product', 'table headings in notification email', 'smart-emails' ); ?></th>
				<th></th>
				<th class="td" scope="col" style="text-align:center;"><?php echo esc_html_x( 'Quantity', 'table headings in notification email', 'smart-emails' ); ?></th>
				<th class="td" scope="col" style="text-align:right;"><?php echo esc_html_x( 'Price', 'table headings in notification email', 'smart-emails' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				echo wp_kses_post(
					WC_Subscriptions_Email::email_order_items_table(
						$order,
						array(
							'show_sku'      => $sent_to_admin,
							'show_image'    => true,
							'image_size'    => array( 80, 100 ),
							'plain_text'    => $plain_text,
							'sent_to_admin' => $sent_to_admin,
						)
					)
				);
				?>
		</tbody>
		<tfoot>
			<?php
			$item_totals = $order->get_order_item_totals();

			if ( $item_totals ) {
				$i = 0;
				foreach ( $item_totals as $total ) {
					if ( 'Total:' === $total['label'] ) {
						continue;
					}

					$i++;
					?>
					<tr>
						<td class="td" scope="row" colspan="3" style="text-align:left; border-top: 1px solid #dcdcdc; padding: 5px 5px;"><b><?php echo wp_kses_post( str_replace( ':', '', $total['label'] ) ); ?></b></td>
						<td class="td" style="border-top:1px solid #dcdcdc; text-align:right; padding: 0px 0px 0px 30px;"><?php echo wp_kses_post( $total['value'] ); ?></td>
					</tr>
					<?php
				}
			}
			if ( $order->get_customer_note() ) {
				?>
				<tr>
					<td class="td" scope="row" colspan="3" style="text-align:left; border-top: 1px solid #dcdcdc; padding: 5px 5px;"><b><?php esc_html_e( 'Note', 'smart-emails' ); ?></b></td>
					<td class="td" style="border-top:1px solid #dcdcdc; text-align:right; padding: 0px 0px 0px 30px;"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
				</tr>
				<?php
			}
			?>
		</tfoot>
	</table>
</div>

<?php do_action( 'woocommerce_email_after_' . $order_type . '_table', $order, $sent_to_admin, $plain_text, $email ); ?>
