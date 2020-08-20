<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<div id='se_show_order_id'>
	<h2>
		<?php
		if ( SA_WC_Compatibility_3_3::is_wc_gte_33() ) {
			if ( $sent_to_admin ) {
				$before = '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
				$after  = '</a>';
			} else {
				$before = '';
				$after  = '';
			}
			/* translators: %s: Order ID. */
			echo wp_kses_post( $before . sprintf( __( '[Order #%s]', 'smart-emails' ) . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );
		} else {
			if ( ! $sent_to_admin ) {
				?>
				<h2>
					<?php
					/* translators: %s: Order ID. */
					printf( esc_html__( '[Order #%s]', 'smart-emails' ), wp_kses_post( $order->get_order_number() ) );
					?>
					(<?php printf( '<time datetime="%s">%s</time>', wp_kses_post( $order->get_date_created()->format( 'c' ) ), wp_kses_post( wc_format_datetime( $order->get_date_created() ) ) ); ?>)
				</h2>
			<?php } else { ?>
				<h2>
					<a class="link" href="<?php echo esc_url( admin_url( 'post.php?post=' . $order->get_id() . '&action=edit' ) ); ?>">
						<?php
						/* translators: %s: Order ID. */
						printf( esc_html__( '[Order #%s]', 'smart-emails' ), wp_kses_post( $order->get_order_number() ) );
						?>
					</a>
					(<?php printf( '<time datetime="%s">%s</time>', wp_kses_post( $order->get_date_created()->format( 'c' ) ), wp_kses_post( wc_format_datetime( $order->get_date_created() ) ) ); ?>)
				</h2>
				<?php
			}
		}
		?>
	</h2>
</div>

<div style="margin-bottom: 20px; margin-top: 10px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; border-bottom: 1px solid #dcdcdc; border-top: 1px solid #dcdcdc; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Product', 'smart-emails' ); ?></th>
				<th></th>
				<th class="td" scope="col" style="text-align:center;"><?php esc_html_e( 'Quantity', 'smart-emails' ); ?></th>
				<th class="td" scope="col" style="text-align:right;"><?php esc_html_e( 'Price', 'smart-emails' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				echo wp_kses_post(
					wc_get_email_order_items(
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

<?php
do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email );
