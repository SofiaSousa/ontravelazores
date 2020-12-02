<?php
/**
 * WooCommerce
 *
 * @package WooCommerce
 */

add_action( 'init', 'ot_wc_init' );

/**
 * Initial setup.
 */
function ot_wc_init() {
	add_filter( 'woocommerce_endpoint_order-received_title', 'ot_wc_order_received_title', 10, 2 );
	add_filter( 'woocommerce_order_button_text', 'ot_wc_order_button_text' );
	add_filter( 'woocommerce_get_item_data', 'ot_wc_extra_booking_info', 10, 2 );
	add_filter( 'woocommerce_form_field_args', 'ot_wc_form_field_args', 10, 3 );
	add_action( 'woocommerce_checkout_create_order_line_item', 'ot_wc_checkout_create_order_line_item', 20, 4 );

	add_action( 'woocommerce_before_order_notes', 'ot_wc_custom_checkout_fields', 1 );
	add_action( 'woocommerce_checkout_process', 'ot_wc_checkout_process', 1 );
	add_action( 'woocommerce_checkout_update_order_meta', 'ot_wc_checkout_update_order_meta', 10, 2 );
	add_action( 'woocommerce_admin_order_data_after_billing_address', 'ot_wc_admin_order_meta_data', 10, 1 );
	add_action( 'woocommerce_email_after_order_table', 'ot_wc_admin_order_meta_data', 10, 1 );
	add_action( 'woocommerce_order_details_after_order_table', 'ot_wc_order_details_after_order_table', 10, 1 );

	add_action( 'woocommerce_cart_calculate_fees', 'ot_wc_add_checkout_fee_for_paypal' );
	add_action( 'woocommerce_review_order_before_payment', 'ot_wc_refresh_checkout_on_payment_methods_change'  );

	// Remove actions.
	remove_action( 'woocommerce_order_status_changed', 'ct_woocommerce_payment_complete', 50, 4 );
}

/**
 * Change 'Order Received' title.
 *
 * @param string $title    The old title.
 * @param string $endpoint The endpoint.
 *
 * @return string
 */
function ot_wc_order_received_title( $title, $endpoint ) {
	return __( 'Booking Received', 'woocommerce' );
}

/**
 * Change order button text.
 *
 * @return string
 */
function ot_wc_order_button_text() {
	return __( 'Confirm Booking', 'woocommerce' );
}

/**
 * Add Infants and additional services to Cart.
 * (Cart and Checkout Pages)
 *
 * @param array $item_data The item data.
 * @param array $cart_item The cart item.
 *
 * @return string
 */
function ot_wc_extra_booking_info( $item_data, $cart_item ) {
	$product_id        = $cart_item['product_id'];
	$is_custom_product = false;
	$post_type         = false;

	$post_id = get_post_meta( $product_id, '_ct_post_id', true );
	if ( ! empty( $post_id ) ) {
			$is_custom_product = true;
			$post_type         = get_post_type( $post_id );
	}

	if ( $is_custom_product && $post_type ) {
		// Change Adults and Kids labels.
		foreach ( $item_data as $key => $item ) {
			if ( __( 'Adults', 'citytours' ) === $item['name'] ) {
				$item_data[ $key ]['name'] = __( 'Adults (+11 Years)', 'citytours' );
			} elseif ( __( 'Kids', 'citytours' ) === $item['name'] ) {
				$item_data[ $key ]['name'] = __( 'Children (3 - 10 Years)', 'citytours' );
			}
		}

		if ( 'tour' === $post_type ) {
			$booking_details = get_post_meta( $product_id, '_ct_booking_info', true );

			// Add Infants.
			$item_data[] = array(
				'name'  => __( 'Infants (0 - 2 Years)', 'citytours' ),
				'value' => ( '' == $booking_details['infants'] ? '0' : $booking_details['infants'] ),
			);

			// Aditional Services.
			$add_services = get_post_meta( $product_id, '_ct_add_service' );
			$add_services = $add_services[0];

			if ( ! empty( $add_services ) ) {
				foreach ( $add_services as $service ) {
					$service_id = esc_attr( $service['service_id'] );
					$title      = esc_attr( $service['title'] );
					$quantity   = esc_attr( $service['qty'] );
					$price      = esc_attr( $service['price'] );

					$item_data[] = array(
						'name'  => __( $title . '&nbsp;(+' . $price . '&nbsp;€)', 'citytours' ),
						'value' => $quantity,
					);
				}
			}
		}
	}

	return $item_data;
}

/**
 * Add custom classes intp billing form fields.
 */
function ot_wc_form_field_args( $args, $key, $value ) {
	$args['class'][] = 'form-group';

	switch ( $key ) {
		case 'billing_postcode':
		case 'billing_country':
		case 'billing_company':
		case 'vat':
		case 'hotel':
		case 'language':
		case 'billing_address_1':
			$args['class'][] = 'col-sm-6';
			break;

		case 'account_password':
			break;

		default:
			$args['class'][] = 'col-sm-12';
			break;
	}

	$args['input_class'][] = 'form-control';

	return $args;
}

/**
 * Add extra data to tour booking item on booking details after checkout.
 *
 * @param Object $item           The item.
 * @param String $cart_item_key  The cart item key.
 * @param Array  $values         The values.
 * @param Array  $order          The order.
 */
function ot_wc_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
	$product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();

	$is_custom_product = false;
	$post_type         = false;

	$post_id = get_post_meta( $product_id, '_ct_post_id', true );
	if ( ! empty( $post_id ) ) {
			$is_custom_product = true;
			$post_type         = get_post_type( $post_id );
	}

	if ( $is_custom_product && $post_type ) {
		if ( 'tour' === $post_type ) {
			// Date and Time.
			$booking_date = get_post_meta( $product_id, '_ct_booking_date', true );
			$item->update_meta_data( __( 'Date', 'citytours' ), $booking_date );
			$booking_time = get_post_meta( $product_id, '_ct_booking_time', true );
			$item->update_meta_data( __( 'Time', 'citytours' ), $booking_time );

			// Adults, Children and Infants.
			$booking_details = get_post_meta( $product_id, '_ct_booking_info', true );
			$item->update_meta_data( __( 'Adults (+11 Years)', 'citytours' ), $booking_details['adults'] );
			$item->update_meta_data( __( 'Children (3 - 10 Years)', 'citytours' ), $booking_details['kids'] );
			$item->update_meta_data( __( 'Infants (0 - 2 Years)', 'citytours' ), $booking_details['infants'] );

			// Aditional services.
			$add_services = get_post_meta( $product_id, '_ct_add_service' );
			$add_services = $add_services[0];

			if ( ! empty( $add_services ) ) {
				foreach ( $add_services as $service ) {
					$service_id = esc_attr( $service['service_id'] );
					$title      = esc_attr( $service['title'] );
					$quantity   = esc_attr( $service['qty'] );
					$price      = esc_attr( $service['price'] );

					$item->update_meta_data( __( $title . '&nbsp;(+'.$price.'&nbsp;€)', 'citytours' ), $quantity );
				}
			}
		}
	}
}

/**
 * Add tours extra fields to checkout page.
 */
function ot_wc_custom_checkout_fields( $checkout ) {
	$extra_fields = ot_get_tours_extra_fields();

	foreach ( WC()->cart->get_cart() as $cart_item ) {
		$product_id = $cart_item['product_id'];
		$post_id    = get_post_meta( $product_id, '_ct_post_id', true );
		$post_type  = get_post_type( $post_id );

		if ( 'tour' === $post_type && 'product' === $cart_item['data']->post_type ) {
			$extra_fields_groups = wp_get_post_terms( $post_id, 'tour_extra_fields', array( 'fields' => 'slugs' ) );

			if ( ! empty( $extra_fields_groups ) ) {
				// Heading.
				echo '<div class="col-sm-12"><div class="default-title"><h2>' . esc_attr( $cart_item['data']->get_name() ) . '</h2></div></div>';

				// Hidden field - tour name.
				woocommerce_form_field(
					'extra[' . $cart_item['data']->id . '][name]',
					array(
						'type'     => 'hidden',
						'default'  => $cart_item['data']->get_name(),
						'required' => true,
					)
				);

				foreach ( $extra_fields_groups as $group ) {
					$fields = $extra_fields[ $group ];
					if ( $group && ! empty( $fields ) ) {

						foreach ( $fields as $k_field => $field ) {
							woocommerce_form_field(
								'extra[' . $cart_item['data']->id . '][fields][' . $k_field . ']',
								array(
									'type'        => 'text',
									'class'       => array( 'form-row-wide' ),
									'label'       => $field['label'],
									'placeholder' => '',
									'required'    => true,
								),
								$checkout->get_value( $k_field )
							);
						}
					}
				}

				// Hidden field - extra fields groups.
				woocommerce_form_field(
					'extra[' . $cart_item['data']->id . '][groups]',
					array(
						'type'     => 'hidden',
						'default'  => implode( ',', $extra_fields_groups ),
						'required' => true,
					)
				);
			}
		}
	}
}

/**
 * Validate extra fields presence.
 */
function ot_wc_checkout_process() {
	if ( isset( $_POST['extra'] ) ) {
		$extra_fields = ot_get_tours_extra_fields();

		foreach ( $_POST['extra'] as $id => $tour_data ) {
			$extra_fields_groups = explode( ',', $tour_data['groups'] );

			if ( ! empty( $extra_fields_groups ) ) {
				foreach ( $extra_fields_groups as $group ) {
					$fields = $extra_fields[ $group ];

					if ( $group && ! empty( $fields ) ) {
						foreach ( $fields as $k_field => $field ) {
							$field_name     = $field['label'];
							$field_required = $field['required'];

							if ( ( ! isset( $field_required ) || ! ! $field_required ) && empty( $tour_data['fields'][ $k_field ] ) ) {
								/* translators: %s: Field name. */
								wc_add_notice( $tour_data['name'] . ' - ' . sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_name ) . '</strong>' ), 'error' );
							}
						}
					}
				}
			}
		}
	}
}

/**
 * Save order extra fields.
 *
 * @param int $order_id The order id.
 */
function ot_wc_checkout_update_order_meta( $order_id ) {
	$extra_fields = ot_get_tours_extra_fields();

	if ( isset( $_POST['extra'] ) ) {
		update_post_meta( $order_id, 'extra_fields', $_POST['extra'] );
	}
}

/**
 * Display field value on the order edit page.
 *
 * @param Array $order The order.
 */
function ot_wc_admin_order_meta_data( $order ) {
	$extra_fields = ot_get_tours_extra_fields();
	$extra_data   = get_post_meta( $order->get_id(), 'extra_fields', true );

	if ( isset( $extra_data ) ) {
		foreach ( $extra_data as $id => $tour_data ) {
			$extra_fields_groups = explode( ',', $tour_data['groups'] );

			echo '<div>';
			echo '<h3>' . esc_attr( $tour_data['name'] ) . '</h3>';

			foreach ( $extra_fields_groups as $group ) {
				$fields = $extra_fields[ $group ];

				if ( $group && ! empty( $fields ) ) {
					foreach ( $fields as $k_field => $field ) {
						echo '<p><strong>' . esc_html( $extra_fields[ $group ][ $k_field ]['label'] ) . ':</strong> ' . esc_attr( $tour_data['fields'][ $k_field ] ) . '</p>';
					}
				}
			}

			echo '</div>';
		}
	}
}

/**
 * Display field value on the order edit page.
 *
 * @param Array $order The order.
 */
function ot_wc_order_details_after_order_table( $order ) {
	$extra_fields = ot_get_tours_extra_fields();
	$extra_data   = get_post_meta( $order->get_id(), 'extra_fields', true );

	if ( isset( $extra_data ) ) {
		foreach ( $extra_data as $id => $tour_data ) {
			$extra_fields_groups = explode( ',', $tour_data['groups'] );

			echo '<div>';
			echo '<h2>' . esc_attr( $tour_data['name'] ) . '</h2>';
			echo '</div>';

			echo '<table class="shop_table">';
			echo '<tbody>';

			foreach ( $extra_fields_groups as $group ) {
				$fields = $extra_fields[ $group ];

				if ( $group && ! empty( $fields ) ) {
					foreach ( $fields as $k_field => $field ) {
						echo '<tr><th style="width: 1px; white-space: nowrap;">' . esc_html( $extra_fields[ $group ][ $k_field ]['label'] ) . ':</th><td>' . esc_attr( $tour_data['fields'][ $k_field ] ) . '</td></tr>';
					}
				}
			}

			echo '</tbody>';
			echo '</table>';
		}
	}
}

/**
 * List of extra fields for tours.
 */
function ot_get_tours_extra_fields() {
	return array(
		'fields-group-1' => array(
			'participants_names' => array(
				'label' => __( 'Name & Surname - All Participants', 'citytours' ),
			),
			'participants_ids'   => array(
				'label' => __( 'ID No. - All Participants', 'citytours' ),
			),
			'participants_birth' => array(
				'label' => __( 'Date of Birth - All Participants', 'citytours' ),
			),
		),
		'fields-group-2' => array(
			'participants_height'   => array(
				'label' => __( 'Height - All Participants', 'citytours' ),
			),
			'participants_weight'   => array(
				'label' => __( 'Weight - All Participants', 'citytours' ),
			),
			'participants_footsize' => array(
				'label' => __( 'Foot Size - All Participants', 'citytours' ),
			),
		),
	);
}

/**
 * Add new fee for PayPal payments.
 */
function ot_wc_add_checkout_fee_for_paypal() {
	global $woocommerce;

	$chosen_gateway = $woocommerce->session->get( 'chosen_payment_method' );

	if ( 'paypal' === $chosen_gateway ) {
		$cart_total = $woocommerce->cart->cart_contents_total;
		$paypal_fee = 0.032;
		$amount     = $cart_total * $paypal_fee;

		$woocommerce->cart->add_fee( 'PayPal Fee - 3,1%', $amount );
	}
}

/**
 * Refresh Checkout when selected payment gateway is changed.
 */
function ot_wc_refresh_checkout_on_payment_methods_change() {
	?>
	<script type="text/javascript">
		(function($){
			$( 'form.checkout' ).on( 'change', 'input[name^="payment_method"]', function() {
				$('body').trigger('update_checkout');
			});
		})(jQuery);
	</script>
	<?php
}
