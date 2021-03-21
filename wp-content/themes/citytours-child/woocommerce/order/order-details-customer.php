<?php
/**
 * Order Customer Details
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="woocommerce-columns woocommerce-columns--2 col2-set">

	<div class="woocommerce-column woocommerce-column--1 col-1">
		<div><h3><?php _e( 'Customer Details', 'citytours' ); ?></h3></div>

		<div class="customer_details">
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
	</div>

	<?php
	$show_shipping = apply_filters( 'woocommerce_cart_needs_shipping_address', ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() );

	if ( $show_shipping ) {
		?>
		<div class="woocommerce-column woocommerce-column--2 col-2">
			<div><h3><?php _e( 'Traveler Details', 'citytours' ); ?></h3></div>

			<div class="customer_details">
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
		</div>
		<?php
	}
	?>
</section>

<div class="clearfix"></div>

<section class="woocommerce-columns woocommerce-columns--2 col2-set">
	<?php
	$extra_fields = ot_get_tours_extra_fields();
	$extra_data   = get_post_meta( $order->get_id(), 'extra_fields', true );

	if ( isset( $extra_data ) ) {
		?>
		<div class="woocommerce-column woocommerce-column--1 col-1">
			<?php
			foreach ( $extra_data as $i => $tour_data ) {
				?>
				<div><h3><?php echo esc_attr( $tour_data['name'] ); ?></h3></div>
				<div class="customer_details">
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


	if ( $order->get_customer_note() ) {
		?>
		<div class="woocommerce-column woocommerce-column--2 col-2">
			<div><h3><?php _e( 'Additional information', 'citytours' ); ?></h3></div>
			<div class="customer_details">
				<?php echo wptexturize( $order->get_customer_note() ); ?>
			</div>
		</div>
		<?php
	}
	?>
</section>

<div class="clearfix"></div>
