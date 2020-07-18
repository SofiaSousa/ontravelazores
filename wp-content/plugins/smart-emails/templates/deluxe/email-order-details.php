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

global $se_style_settings;

$table_head = 'background-color:' . $se_style_settings['body_color'] . ';
               color:white;';

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
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
		<thead style="<?php echo esc_attr( $table_head ); ?>">
			<tr>
				<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Product', 'smart-emails' ); ?></th>
				<th></th>
				<th class="td" scope="col" style="text-align:center;"><?php esc_html_e( 'Quantity', 'smart-emails' ); ?></th>
				<th class="td" scope="col" style="text-align:right;"><?php esc_html_e( 'Price', 'smart-emails' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$order,
					array(
						'show_sku'      => $sent_to_admin,
						'show_image'    => true,
						'image_size'    => array( 80, 100 ),
						'plain_text'    => $plain_text,
						'sent_to_admin' => $sent_to_admin,
					)
				);
				?>
		</tbody>
		<tfoot>
			<?php
			$border_color = wc_hex_lighter( $se_style_settings['body_color'], 60 );
			$item_totals  = $order->get_order_item_totals();

			if ( $item_totals ) {
				$i = 0;
				foreach ( $item_totals as $total ) {
					$i++;
					?>
					<tr>
						<td class="td" colspan="2" width="50%" style="text-align:right; border-bottom:1px dotted <?php echo esc_attr( $border_color ); ?>; padding-right:15px;"><b><?php echo wp_kses_post( str_replace( ':', '', $total['label'] ) ); ?></b></td>
						<td class="td" colspan="2" style="text-align:left; border-bottom:1px dotted <?php echo esc_attr( $border_color ); ?>; padding-left:15px;"><?php echo wp_kses_post( $total['value'] ); ?></td>
					</tr>
					<?php
				}
			}
			if ( $order->get_customer_note() ) {
				?>
				<tr>
					<td class="td" scope="row" colspan="2" width="50%" style="text-align:right; border-top:1px dotted <?php echo esc_attr( $border_color ); ?>; padding-right:15px;"><b><?php esc_html_e( 'Note', 'smart-emails' ); ?></b></td>
					<td class="td" colspan="2" style="text-align:left; border-top:1px dotted <?php echo esc_attr( $border_color ); ?>; padding-left:15px;"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
				</tr>
				<?php
			}
			?>
		</tfoot>
	</table>
</div>

<?php
do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email );
