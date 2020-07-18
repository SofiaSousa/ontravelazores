<?php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates/Emails
 * @version     3.5.4
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$address  = $order->get_formatted_billing_address();
$shipping = $order->get_formatted_shipping_address();

?>
<table id="addresses" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="td" style="text-align:center; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" valign="top" width="50%">
			<h3><?php esc_html_e( 'Billing address', 'smart-emails' ); ?></h3>

			<p class="text"><?php echo wp_kses_post( $address ); ?></p>
		</td>
		<?php
		if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && ( $shipping ) ) :
			?>
			<td class="td" style="text-align:center;font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" valign="top">
				<h3><?php esc_html_e( 'Shipping address', 'smart-emails' ); ?></h3>

				<p class="text"><?php echo wp_kses_post( $shipping ); ?></p>
			</td>
		<?php endif; ?>
	</tr>
</table>
<table cellspacing="0" cellpadding="0" style="width: 100% !important; text-align: center; border-bottom:1px solid #dcdcdc; vertical-align: top;" border="0">
	<tr>
		<td class="td" style="font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" valign="top" width="100%">
			<?php
			$total       = $order->get_order_item_totals();
			$order_total = $total['order_total']['value'];
			?>
			<h4 style="color:grey"><?php esc_html_e( 'Order Total', 'smart-emails' ); ?></h4> 
		</td>
	</tr>
	<tr>    
		<td class="td" style="font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" valign="top" width="100%">
			<h1 style="line-height:10%;padding-bottom:30px; margin:0"><?php echo wp_kses_post( $order_total ); ?></h1>
		</td>
	</tr>
</table>
<?php
