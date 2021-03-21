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
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';
$address    = $order->get_formatted_billing_address();
$shipping   = $order->get_formatted_shipping_address();

?><table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding:0;" border="0">
	<tr>
		<td style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">
			<h2><?php esc_html_e( 'Customer Details', 'citytours' ); ?></h2>

			<div>
				<?php
				if ( $order->get_billing_first_name() ) {
					?>
					<div>
						<b><?php _e( 'First Name', 'citytours' ); ?>:</b>
						<span><?php echo $order->get_billing_first_name(); ?></span>
					</div>
					<?php
				}

				if ( $order->get_billing_last_name() ) {
					?>
					<div>
						<b><?php _e( 'Last Name', 'citytours' ); ?>:</b>
						<span><?php echo $order->get_billing_last_name(); ?></span>
					</div>
					<?php
				}

				if ( $order->get_billing_email() ) {
					?>
					<div>
						<b><?php _e( 'Email', 'citytours' ); ?>:</b>
						<span><?php echo $order->get_billing_email(); ?></span>
					</div>
					<?php
				}

				if ( $order->get_billing_phone() ) {
					?>
					<div>
						<b><?php _e( 'Phone', 'citytours' ); ?>:</b>
						<span><?php echo $order->get_billing_phone(); ?></span>
					</div>
					<?php
				}

				if ( $order->get_billing_company() ) {
					?>
					<div>
						<b><?php _e( 'Company', 'citytours' ); ?>:</b>
						<span><?php echo $order->get_billing_company(); ?></span>
					</div>
					<?php
				}

				$vat = get_post_meta( $order->get_id(), 'vat', true );
				if ( $vat ) {
					?>
					<div>
						<b><?php _e( 'VAT No.', 'citytours' ); ?>:</b>
						<span><?php echo $vat ?></span>
					</div>
					<?php
				}

				if ( $order->get_billing_address_1() ) {
					?>
					<div>
						<b><?php _e( 'Address', 'citytours' ); ?>:</b>
						<span><?php echo $order->get_billing_address_1(); ?></span>
					</div>
					<?php
				}

				if ( $order->get_billing_postcode() ) {
					?>
					<div>
						<b><?php _e( 'Postcode', 'citytours' ); ?>:</b>
						<span><?php echo $order->get_billing_postcode(); ?></span>
					</div>
					<?php
				}

				if ( $order->get_billing_city() ) {
					?>
					<div>
						<b><?php _e( 'City', 'citytours' ); ?>:</b>
						<span><?php echo $order->get_billing_city(); ?></span>
					</div>
					<?php
				}

				$invoice = get_post_meta( $order->get_id(), 'invoice', true );
				if ( $invoice ) {
					?>
					<div>
						<b><?php echo __('I want to receive an invoice by email', 'citytours' ); ?></b>
					</div>
					<?php
				}
				?>
			</div>
		</td>
		<?php
		$show_shipping = apply_filters( 'woocommerce_cart_needs_shipping_address', ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() );

		if ( $show_shipping ) {
			?>
			<td style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding:0;" valign="top" width="50%">
				<h2><?php esc_html_e( 'Traveler Details', 'citytours' ); ?></h2>
				<div>
					<?php
					if ( $order->get_shipping_first_name() ) {
						?>
						<div>
							<b><?php _e( 'First Name', 'citytours' ); ?>:</b>
							<span><?php echo $order->get_shipping_first_name(); ?></span>
						</div>
						<?php
					}

					if ( $order->get_shipping_last_name() ) {
						?>
						<div>
							<b><?php _e( 'Last Name', 'citytours' ); ?>:</b>
							<span><?php echo $order->get_shipping_last_name(); ?></span>
						</div>
						<?php
					}

					$email = get_post_meta( $order->get_id(), 'shipping_email', true );
					if ( $email ) {
						?>
						<div>
							<b><?php _e( 'Email', 'citytours' ); ?>:</b>
							<span><?php echo $email; ?></span>
						</div>
						<?php
					}

					$phone = get_post_meta( $order->get_id(), 'shipping_phone', true );
					if ( $phone ) {
						?>
						<div>
							<b><?php _e( 'Phone', 'citytours' ); ?>:</b>
							<span><?php echo $phone; ?></span>
						</div>
						<?php
					}

					$hotel = get_post_meta( $order->get_id(), 'hotel', true );
					if ( $hotel ) {
						?>
						<div>
							<b><?php _e( 'Hotel /Airbnb', 'citytours' ); ?>:</b>
							<span><?php echo esc_attr( $hotel ); ?></span>
						</div>
						<?php
					}

					$language = get_post_meta( $order->get_id(), 'language', true );
					if ( $phone ) {
						?>
						<div>
							<b><?php _e( 'Language', 'citytours' ); ?>:</b>
							<span><?php echo esc_attr( $language ); ?></span>
						</div>
						<?php
					}
					?>
				</div>
			</td>
			<?php
		}
		?>
	</tr>
	<tr>
		<td style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">

			<?php
			$extra_fields = ot_get_tours_extra_fields();
			$extra_data   = get_post_meta( $order->get_id(), 'extra_fields', true );

			if ( isset( $extra_data ) ) {
				?>
				<div class="woocommerce-column woocommerce-column--1 col-1">
					<?php
					foreach ( $extra_data as $i => $tour_data ) {
						?>
						<h2><?php echo esc_attr( $tour_data['name'] ); ?></h2>
						<div>
							<?php
							$extra_fields_groups = explode( ',', $tour_data['groups'] );

							foreach ( $extra_fields_groups as $group ) {
								$fields = $extra_fields[ $group ];

								if ( $group && ! empty( $fields ) ) {
									foreach ( $fields as $k_field => $field ) {
										echo '<div><b>' . esc_html( $extra_fields[ $group ][ $k_field ]['label'] ) . ':</b> ' . '<span>' . esc_attr( $tour_data['fields'][ $k_field ] ) . '</span></div>';
									}
								}
							}
							?>
						</div>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</td>
		<td style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">
		<?php
		if ( $order->get_customer_note() ) {
			?>
			<h2><?php _e( 'Additional information', 'citytours' ); ?></h2>
			<div>
				<?php echo wptexturize( $order->get_customer_note() ); ?>
			</div>
			<?php
		}
		?>
		</td>
	</tr>
</table>



