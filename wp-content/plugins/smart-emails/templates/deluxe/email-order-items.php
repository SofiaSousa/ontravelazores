<?php
/**
 * Email Order Items
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-items.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';

global $se_style_settings;

$border_color = wc_hex_lighter( $se_style_settings['body_color'], 60 );

foreach ( $items as $item_id => $item ) :
	$product       = $item->get_product();
	$sku           = '';
	$purchase_note = '';
	$image         = '';

	if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
		continue;
	}

	if ( is_object( $product ) ) {
		$sku           = $product->get_sku();
		$purchase_note = $product->get_purchase_note();
		$image         = $product->get_image( $image_size );
	}

	?>
	<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
		<td class="td" style="text-align:left; width:70px; vertical-align:middle; border-bottom: 1px dotted <?php echo esc_attr( $border_color ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;">
			<?php

			// Show title/image etc.
			if ( $show_image ) {
				echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );
			}
			?>
		</td>

		<td class="td" style="text-align:left; width:185px; vertical-align:middle; border-bottom: 1px dotted <?php echo esc_attr( $border_color ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;">
			<?php
			// Product name.
			echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );

			// SKU.
			if ( $show_sku && $sku ) {
				echo wp_kses_post( ' (#' . $sku . ')' );
			}

			// allow other plugins to add additional product information here.
			do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

			if ( SA_WC_Compatibility_3_3::is_wc_gte_31() ) {
				wc_display_item_meta(
					$item,
					array(
						'label_before' => '<strong class="wc-item-meta-label" style="float: left; margin-right: .25em; clear: both">',
					)
				);
			}

			// allow other plugins to add additional product information here.
			do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );
			?>
		</td>

		<td class="td" style="text-align:center; width:10px; vertical-align:middle; border-bottom: 1px dotted <?php echo esc_attr( $border_color ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
			<?php
				$qty          = $item->get_quantity();
				$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

			if ( $refunded_qty ) {
				$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
			} else {
				$qty_display = esc_html( $qty );
			}
				echo wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $qty_display, $item ) );
			?>
		</td>

		<td class="td" style="text-align:right; vertical-align:middle; border-bottom: 1px dotted <?php echo esc_attr( $border_color ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
			<?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
		</td>
	</tr>
	<?php

	if ( $show_purchase_note && $purchase_note ) {
		?>
		<tr>
			<td colspan="3" style="text-align:left; vertical-align:middle; border: 1px dotted <?php echo esc_attr( $border_color ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
				<?php
				echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) );
				?>
			</td>
		</tr>
		<?php
	}

endforeach;
