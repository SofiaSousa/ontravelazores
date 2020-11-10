<?php
/**
 * WooCommerce Smart Coupons
 */

add_action( 'init', 'ot_smart_coupons_init' );
add_action( 'admin_init', 'ot_smart_coupons_admin_init' );

/**
 * Initial setup.
 */
function ot_smart_coupons_init() {
	add_filter( 'wc_get_template', 'ot_smart_coupons_templates', 10, 5 );

	remove_action( 'woocommerce_checkout_after_customer_details', array( WC_SC_Purchase_Credit::get_instance(), 'gift_certificate_receiver_detail_form' ) );
	add_action( 'woocommerce_checkout_after_customer_details', 'ot_smart_coupons_gift_certificate_receiver_detail_form' );
}

/**
 * Admin init.
 */
function ot_smart_coupons_admin_init() {
	if ( is_plugin_active( 'woocommerce-gateway-paypal-express/woocommerce-gateway-paypal-express.php' ) ) {
		remove_action( 'woocommerce_ppe_checkout_order_review', array( WC_SC_Purchase_Credit::get_instance(), 'gift_certificate_receiver_detail_form' ), 9 );
		add_action( 'woocommerce_ppe_checkout_order_review', 'ot_smart_coupons_gift_certificate_receiver_detail_form' );
	}
}

/**
 *
 */
function ot_smart_coupons_templates( $template, $template_name, $args, $template_path, $default_path ) {
	$custom_templates = array( 'call-for-credit-form.php' );

	if ( in_array( $template_name, $custom_templates, true ) ) {
		$template = get_stylesheet_directory() . '/inc/woocommerce-smart-coupons/templates/' . $template_name;
	}

	return $template;
}

/**
 * Function to display form for entering details of the gift certificate's receiver
 */
function ot_smart_coupons_gift_certificate_receiver_detail_form() {
	global $total_coupon_amount;

	$is_show = apply_filters( 'is_show_gift_certificate_receiver_detail_form', true, array() );

	if ( ! $is_show ) {
		return;
	}

	if ( ! wp_style_is( 'smart-coupon' ) ) {
		wp_enqueue_style( 'smart-coupon' );
	}

	if ( ! is_ajax() ) {
		add_action( 'wp_footer', array( WC_SC_Purchase_Credit::get_instance(), 'receiver_detail_form_styles_and_scripts' ) );
	}

	$form_started = false;

	$all_discount_types = wc_get_coupon_types();

	$schedule_store_credit = get_option( 'smart_coupons_schedule_store_credit' );

	foreach ( WC()->cart->cart_contents as $product ) {

		if ( empty( $product['product_id'] ) ) {
			$product['product_id'] = ( ! empty( $product['variation_id'] ) ) ? wp_get_post_parent_id( $product['variation_id'] ) : 0;
		}

		if ( empty( $product['product_id'] ) ) {
			continue;
		}

		$coupon_titles = get_post_meta( $product['product_id'], '_coupon_title', true );

		$_product = wc_get_product( $product['product_id'] );

		$price = $_product->get_price();

		if ( $coupon_titles ) {

			foreach ( $coupon_titles as $coupon_title ) {

				$coupon = new WC_Coupon( $coupon_title );
				if ( WC_SC_Purchase_Credit::get_instance()->is_wc_gte_30() ) {
					if ( ! is_object( $coupon ) || ! is_callable( array( $coupon, 'get_id' ) ) ) {
						continue;
					}
					$coupon_id = $coupon->get_id();
					if ( empty( $coupon_id ) ) {
						continue;
					}
					$discount_type = $coupon->get_discount_type();
					$coupon_amount = $coupon->get_amount();
				} else {
					$coupon_id     = ( ! empty( $coupon->id ) ) ? $coupon->id : 0;
					$discount_type = ( ! empty( $coupon->discount_type ) ) ? $coupon->discount_type : '';
					$coupon_amount = ( ! empty( $coupon->amount ) ) ? $coupon->amount : 0;
				}

				$pick_price_of_prod                              = get_post_meta( $coupon_id, 'is_pick_price_of_product', true );
				$smart_coupon_gift_certificate_form_page_text    = get_option( 'smart_coupon_gift_certificate_form_page_text' );
				$smart_coupon_gift_certificate_form_page_text    = ( ! empty( $smart_coupon_gift_certificate_form_page_text ) ) ? $smart_coupon_gift_certificate_form_page_text : __( 'Send gift voucher to...', 'woocommerce-smart-coupons' );
				$smart_coupon_gift_certificate_form_details_text = get_option( 'smart_coupon_gift_certificate_form_details_text' );
				$smart_coupon_gift_certificate_form_details_text = ( ! empty( $smart_coupon_gift_certificate_form_details_text ) ) ? $smart_coupon_gift_certificate_form_details_text : '';     // Enter email address and optional message for Gift Card receiver.

				// MADE CHANGES IN THE CONDITION TO SHOW FORM.
				if ( array_key_exists( $discount_type, $all_discount_types ) || ( 'yes' === $pick_price_of_prod && '' === $price ) || ( 'yes' === $pick_price_of_prod && '' !== $price && $coupon_amount > 0 ) ) {

					if ( ! $form_started ) {
						$is_show_coupon_receiver_form = get_option( 'smart_coupons_display_coupon_receiver_details_form', 'yes' );
						if ( 'no' === $is_show_coupon_receiver_form ) {
							?>
							<div class="gift-certificate sc_info_box">
								<p><?php echo esc_html__( 'Your order contains vouchers. You will receive them after completion of this order.', 'woocommerce-smart-coupons' ); ?></p>
							</div>
							<?php
						}
						?>
						<div class="gift-certificate sc_info_box" <?php echo ( 'no' === $is_show_coupon_receiver_form ) ? 'style="' . esc_attr( 'display: none;' ) . '"' : ''; ?>>
							<h3><?php echo esc_html( stripslashes( $smart_coupon_gift_certificate_form_page_text ) ); ?></h3>
								<?php if ( ! empty( $smart_coupon_gift_certificate_form_details_text ) ) { ?>
								<p><?php echo esc_html( stripslashes( $smart_coupon_gift_certificate_form_details_text ) ); ?></p>
								<?php } ?>
								<div class="gift-certificate-show-form">
									<p><?php echo esc_html__( 'Your order contains vouchers. What would you like to do?', 'woocommerce-smart-coupons' ); ?></p>
									<ul class="show_hide_list" style="list-style-type: none;">
										<li><input type="radio" id="hide_form" name="is_gift" value="no" checked="checked" /> <label for="hide_form"><?php echo esc_html__( 'Send to me', 'woocommerce-smart-coupons' ); ?></label></li>
										<li>
										<input type="radio" id="show_form" name="is_gift" value="yes" /> <label for="show_form"><?php echo esc_html__( 'Gift to someone else', 'woocommerce-smart-coupons' ); ?></label>
										<ul class="single_multi_list" style="list-style-type: none;">
										<li><input type="radio" id="send_to_one" name="sc_send_to" value="one" checked="checked" /> <label for="send_to_one"><?php echo esc_html__( 'Send to one person', 'woocommerce-smart-coupons' ); ?></label></li>
										<li><input type="radio" id="send_to_many" name="sc_send_to" value="many" /> <label for="send_to_many"><?php echo esc_html__( 'Send to different people', 'woocommerce-smart-coupons' ); ?></label></li>
										</ul>
										<?php if ( 'yes' === $schedule_store_credit ) { ?>
											<li class="wc_sc_schedule_gift_sending_wrapper">
												<?php echo esc_html__( 'Deliver gift voucher', 'woocommerce-smart-coupons' ); ?>
												<label class="wc-sc-toggle-check">
													<input type="checkbox" class="wc-sc-toggle-check-input" id="wc_sc_schedule_gift_sending" name="wc_sc_schedule_gift_sending" value="yes" />
													<span class="wc-sc-toggle-check-text"></span>
												</label>
											</li>
										<?php } ?>
										</li>
									</ul>
								</div>
						<div class="gift-certificate-receiver-detail-form">
						<div class="clear"></div>
						<div id="gift-certificate-receiver-form-multi">
						<?php

						$form_started = true;

					}

					WC_SC_Purchase_Credit::get_instance()->add_text_field_for_email( $coupon, $product );

				}
			}
		}
	}

	if ( $form_started ) {
		?>
		</div>
		<div id="gift-certificate-receiver-form-single">
			<div class="form_table">
				<div class="email_amount">
					<div class="amount"></div>
					<div class="email"><input class="gift_receiver_email" type="text" placeholder="<?php echo esc_attr__( 'Enter recipient e-mail address', 'woocommerce-smart-coupons' ); ?>..." name="gift_receiver_email[0][0]" value="" /></div>
				</div>
				<div class="email_sending_date_time_wrapper">
						<input class="gift_sending_date_time" type="text" placeholder="<?php echo esc_attr__( 'Pick a delivery date & time', 'woocommerce-smart-coupons' ); ?>..." name="gift_sending_date_time[0][0]" value="" autocomplete="off"/>
						<input class="gift_sending_timestamp" type="hidden" name="gift_sending_timestamp[0][0]" value=""/>
				</div>
				<div class="message_row">
					<div class="message"><textarea placeholder="<?php echo esc_attr__( 'Write a message', 'woocommerce-smart-coupons' ); ?>..." class="gift_receiver_message" name="gift_receiver_message[0][0]" cols="50" rows="5"></textarea></div>
				</div>
			</div>
		</div>
		</div></div>
		<?php
		do_action( 'wc_sc_gift_certificate_form_shown' );
	}
}
