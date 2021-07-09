<?php
/**
 * Car Rental Booking System
 *
 * Overriding the 5th step of the booking wizard. Instead of doing the checkout,
 * it will add the rental car items to the cart and create a CRBS booking draft.
 *
 * @package CarRentalBookingSystem
 */

if ( class_exists( 'CRBSPlugin' ) ) {
	if ( ! defined( 'PLUGIN_CRBS_CONTEXT' ) ) {
		define( 'PLUGIN_CRBS_CONTEXT', 'crbs' );
	}

	add_action( 'init', 'ot_crbs_init' );
}

/**
 * Add Temp Product product's category, and add actions and filters.
 */
function ot_crbs_init() {
	if ( ! term_exists( 'ot-temp-product', 'product_cat' ) ) {
		// Add new category for temp rental services (in cart).
		wp_insert_term(
			'Temp Product',
			'product_cat',
			array(
				'description' => 'Temporary products that are created for the cart',
				'slug'        => 'ot-temp-product',
			)
		);
	}

	add_action( 'wp_ajax_' . PLUGIN_CRBS_CONTEXT . '_go_to_step', 'ot_crbs_go_to_step', 1, 0 );
	add_action( 'wp_ajax_nopriv_' . PLUGIN_CRBS_CONTEXT . '_go_to_step', 'ot_crbs_go_to_step', 1, 0 );

	add_action( 'woocommerce_cart_item_removed', 'ot_wc_cart_item_removed', 10, 2 );
	add_filter( 'woocommerce_get_item_data', 'ot_crbs_wc_get_item_data', 10, 2 );
	add_action( 'woocommerce_checkout_create_order_line_item', 'ot_crbs_wc_checkout_create_order_line_item', 20, 4 );
	add_filter( 'woocommerce_cart_item_permalink', 'ot_crbs_wc_cart_item_permalink', 10, 3 );

	add_action( 'wp_footer', 'ot_crbs_remove_form' );

	// Vehicles Availability by location.
	add_filter( 'rwmb_meta_boxes', 'ot_crbs_vehicles_block_meta_box' );

	add_action( 'wp_ajax_' . PLUGIN_CRBS_CONTEXT . '_vehicle_filter', 'ot_vehicle_filter', 1, 2 );
	add_action( 'wp_ajax_nopriv_' . PLUGIN_CRBS_CONTEXT . '_vehicle_filter', 'ot_vehicle_filter', 1, 2 );
}

/**
 * Workaround to override the step 5 of CRBS reservation wizard, in order to
 * add the bookings' items to the chart before doing the checkout manually.
 *
 * Also, to override the step 2, updating the available vehicles list according
 * custom settings.
 */
function ot_crbs_go_to_step() {
	if ( class_exists( 'CRBSHelper' ) && class_exists( 'CRBSBooking' ) ) {
		$booking_form = new CRBSBookingForm();
		$booking_form->init();

		$data = CRBSHelper::getPostOption();

		$response = array();

		$form = $booking_form->checkBookingForm( $data['booking_form_id'] );

		if ( ! is_array( $form ) ) {
			if ( -3 === $form ) {
				$response['step'] = 1;
				CRBSBooking::setErrorGlobal( $response, __( 'Cannot find at least one vehicle available in selected time period.', 'car-rental-booking-system' ) );
				CRBSHelper::createJSONResponse( $response );
			}
		}

		// Step 5 - Checkout.
		if ( 5 == $data['step_request'] && 4 == $data['step'] ) {
			$booking      = new CRBSBooking();
			$woo_commerce = new CRBSWooCommerce();

			if ( $woo_commerce->isEnable( $form['meta'] ) ) {
				$booking_id = $booking->sendBooking( $data, $form );

				// Temporary product category.
				$term = get_term_by( 'slug', 'ot-temp-product', 'product_cat' );

				if ( ! empty( $booking_id ) ) {
					$product_name = get_the_title( $data['vehicle_id'] );
					$image_id     = get_post_meta( $data['vehicle_id'], '_thumbnail_id', true );

					$billing = $booking->createBilling( $booking_id );

					foreach ( $billing['detail'] as $detail ) {
						if ( 'rental_per_day' === $detail['type'] ) {
							$name = $product_name;
						} else {
							$name = 'Extra: ' . $detail['name'];
						}

						$product = $woo_commerce->prepareProduct(
							array(
								'post' => array( 'post_title' => $name ),
								'meta' => array(
									'crbs_booking_id'  => $booking_id,
									'crbs_price_gross' => $detail['value_gross'],
									'crbs_tax_value'   => $detail['tax_value'],
									'_regular_price'   => $detail['value_net'],
									'_sale_price'      => $detail['value_net'],
									'_price'           => $detail['value_net'],
								),
							)
						);

						$product_id = $woo_commerce->createProduct( $product );
						wp_set_object_terms( $product_id, $term->term_id, 'product_cat' );

						// Add featured image.
						if ( isset( $image_id ) ) {
							update_post_meta( $product_id, '_thumbnail_id', $image_id );
						}

						// Set category.
						wp_set_object_terms( $product_id, $term_id, 'product_cat' );

						// Add to cart.
						WC()->cart->add_to_cart( $product_id );
					}

					// Update booking status to draft, so we can delete them if client
					// didn't checkout.
					wp_update_post(
						array(
							'ID'          => $booking_id,
							'post_status' => 'draft',
						)
					);
				}
			}

			// Response with Cart url.
			$response['payment']     = null;
			$response['payment_id']  = -1;
			$response['payment_url'] = wc_get_cart_url();

			$response['step'] = $data['step_request'];

			$response['thank_you_page_enable'] = $form['meta']['thank_you_page_enable'];

			$response['summary'] = array( null, null, null );

			CRBSHelper::createJSONResponse( $response );
		}

		// Step 2 - Vehicles.
		if ( 2 == $data['step_request'] ) {
			list( $pickupLocationId, $pickupLocationCustomerAddress )= $booking_form->getBookingFormPickupLocation( $form );

			$response['booking_extra'] = $booking_form->createBookingExtra( $data, $form );
			$response['payment'] = $booking_form->createPayment( $form['dictionary']['payment'], $form['dictionary']['payment_woocommerce'], $data['payment_id'], $form['dictionary']['location'][$pickupLocationId]['meta']);
			$response['step'] = $data['step_request'];
			$response['summary'] = $booking_form->createSummary( $data, $form );

			$vehicleHtml = ot_vehicle_filter( false, $form );
			if ( ( $vehicleHtml ) !== false ) {
				$response['vehicle'] = $vehicleHtml;
			}

			CRBSHelper::createJSONResponse( $response );
		}
	}
}

/**
 * Workaround to take in account new settings for vehicles availability.
 */
function ot_vehicle_filter( $ajax = true, $bookingForm = null ) {
	if ( ! is_bool( $ajax ) ) {
		$ajax = true;
	}

	$html     = null;
	$response = array();

	$Validation = new CRBSValidation();

	$booking_form = new CRBSBookingForm();
	$booking_form->init();

	$data = CRBSHelper::getPostOption();
	$data = CRBSBookingHelper::formatDateTimeToStandard( $data );

	CRBSHelper::removeUIndex( $data, 'driver_age' );

	if ( is_null( $bookingForm ) ) {
		if ( ! is_array( $bookingForm = $booking_form->checkBookingForm( $data['booking_form_id'] ) ) ) {
			if ( ! $ajax ) {
				return false;
			}

			$booking_form->setErrorGlobal( $response, __( 'There are no vehicles which match your filter criteria.', 'car-rental-booking-system' ) );
			CRBSHelper::createJSONResponse( $response );
		}
	}

	list( $data['pickup_location_id'] ) = $booking_form->getBookingFormPickupLocation( $bookingForm );
	list( $data['return_location_id'] ) = $booking_form->getBookingFormReturnLocation( $bookingForm );

	if ( ! $Validation->isNumber( $data['vehicle_bag_count'], 1, 99 ) ) {
		$data['vehicle_bag_count'] = 1;
	}

	if ( ! $Validation->isNumber( $data['vehicle_passenger_count'], 1, 99 ) ) {
		$data['vehicle_passenger_count'] = 1;
	}

	/***/

	$vehicleHtml  = array();
	$vehiclePrice = array();

	$categoryId = (int) $data['vehicle_category'];

	foreach ( $bookingForm['dictionary']['vehicle'] as $index => $value ) {
		if ( $categoryId > 0 ) {
			if ( ! has_term( $categoryId, CRBSVehicle::getCPTCategoryName(), $index ) ) {
				continue;
			}
		}

		if ( ! ( ( $value['meta']['passenger_count'] >= $data['vehicle_passenger_count'] ) && ( $value['meta']['bag_count'] >= $data['vehicle_bag_count'] ) ) ) {
			continue;
		}

		////
		$locations_blocked = rwmb_meta( 'vehicles_location', array(), $value['post']->ID );

		if ( in_array( $data['pickup_location_id'], $locations_blocked , true ) ) {
			$is_blocked = false;

			$start_dates = rwmb_meta( 'vehicles_blocked_start_dates', array(), $value['post']->ID );
			$end_dates   = rwmb_meta( 'vehicles_blocked_end_dates', array(), $value['post']->ID );

			$pickup_date = date( 'Y-m-d', strtotime( $data['pickup_date'] ) );
			$return_date = date( 'Y-m-d', strtotime( $data['return_date'] ) );

			foreach ( $locations_blocked as $i => $loc_id ) {
				if ( (int) $data['pickup_location_id'] === (int) $loc_id ) {
					$start_date = date( 'Y-m-d', strtotime( $start_dates[$i] ) );
					$end_date   = date( 'Y-m-d', strtotime( $end_dates[$i] ) );

					if ( $start_date <= $pickup_date && $end_date >= $pickup_date || $start_date <= $return_date && $end_date >= $return_date ) {
						$is_blocked = true;
						continue;
					}
				}
			}

			if ( $is_blocked ) {
				continue;
			}
		}
		////

		$argument = array (
			'booking_form_id'     => $bookingForm['post']->ID,
			'vehicle'             => $value,
			'vehicle_id'          => $value['post']->ID,
			'vehicle_selected_id' => $data['vehicle_id'],
			'pickup_location_id'  => $data['pickup_location_id'],
			'pickup_date'         => $data['pickup_date'],
			'pickup_time'         => $data['pickup_time'],
			'return_location_id'  => $data['return_location_id'],
			'return_date'         => $data['return_date'],
			'return_time'         => $data['return_time'],
			'driver_age'          => $data['driver_age']
		);

		$price = 0;

		$vehicleHtml[$index]  = $booking_form->createVehicle( $argument, $bookingForm, $price );
		$vehiclePrice[$index] = $price;
	}

	if ( in_array( (int) $bookingForm['meta']['vehicle_sorting_type'], array( 1, 2 ) ) ) {
		asort( $vehiclePrice );
		if ( (int) $bookingForm['meta']['vehicle_sorting_type'] === 2 ) {
			$vehiclePrice = array_reverse( $vehiclePrice, true );
		}
	}

	foreach ( $vehiclePrice as $index => $value ) {
		$html .= '<li>' . $vehicleHtml[$index] . '</li>';
	}

	$response['html'] = $html;

	if ( $Validation->isEmpty( $html ) ) {
		if ( $ajax ) {
			$booking_form->setErrorGlobal( $response, __( 'There are no vehicles which match your filter criteria.', 'car-rental-booking-system' ) );
			CRBSHelper::createJSONResponse( $response );
		}
	}

	if ( ! $ajax ) {
		return $html;
	}

	CRBSHelper::createJSONResponse( $response );
}

/**
 * Remove temporary product after its item being removed from cart.
 *
 * @param String  $cart_item_key The cart item key.
 * @param Objbect $cart          The cart instance.
 */
function ot_wc_cart_item_removed( $cart_item_key, $cart ) {
	$line_item  = $cart->removed_cart_contents[ $cart_item_key ];
	$product_id = $line_item['product_id'];

	if ( isset( $product_id ) && has_term( 'ot-temp-product', 'product_cat', $product_id ) ) {
		wp_delete_post( $product_id );
	}
};

/**
 * Add extra data to rental booking item in cart
 * (pick up and drop off data).
 *
 * @param Array $item_data The item data.
 * @param Array $cart_item The cart item.
 */
function ot_crbs_wc_get_item_data( $item_data, $cart_item ) {
	$product_id = $cart_item['product_id'];

	// CRBS booking.
	$booking_id = get_post_meta( $product_id, 'crbs_booking_id', true );

	if ( ! empty( $booking_id ) ) {
		$pickup_date = get_post_meta( $booking_id, 'crbs_pickup_datetime', true );
		$item_data[] = array(
			'name'  => __( 'Pick Up Date', 'citytours' ),
			'value' => $pickup_date,
		);

		$pickup_loc  = get_post_meta( $booking_id, 'crbs_pickup_location_name', true );
		$item_data[] = array(
			'name'  => __( 'Pick Up Location', 'citytours' ),
			'value' => $pickup_loc,
		);

		$return_date = get_post_meta( $booking_id, 'crbs_return_date', true );
		$return_time = get_post_meta( $booking_id, 'crbs_return_time', true );
		$item_data[] = array(
			'name'  => __( 'Drop Off Date', 'citytours' ),
			'value' => $return_date . ' ' . $return_time,
		);

		$return_loc  = get_post_meta( $booking_id, 'crbs_return_location_name', true );
		$item_data[] = array(
			'name'  => __( 'Drop Off Location', 'citytours' ),
			'value' => $return_loc,
		);

		$element_field = get_post_meta( $booking_id, 'crbs_form_element_field', true );
		foreach ( $element_field as $field ) {
			if ( $field['value'] ) {
				$item_data[] = array(
					'name'  => $field['label'],
					'value' => $field['value'],
				);
			}
		}
	}

	return $item_data;
}

/**
 * Add extra data to rental booking item on checkout
 * (pick up and drop off data).
 *
 * @param Object $item           The item.
 * @param String $cart_item_key  The cart item key.
 * @param Array  $values         The values.
 * @param Array  $order          The order.
 */
function ot_crbs_wc_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
	$product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();

	// CRBS booking.
	$booking_id = get_post_meta( $product_id, 'crbs_booking_id', true );

	if ( ! empty( $booking_id ) ) {
		$pickup_date = get_post_meta( $booking_id, 'crbs_pickup_datetime', true );
		$item->update_meta_data( __( 'Pick Up Date', 'citytours' ), $pickup_date );

		$pickup_loc = get_post_meta( $booking_id, 'crbs_pickup_location_name', true );
		$item->update_meta_data( __( 'Pick Up Location', 'citytours' ), $pickup_loc );

		$return_date = get_post_meta( $booking_id, 'crbs_return_date', true );
		$return_time = get_post_meta( $booking_id, 'crbs_return_time', true );
		$item->update_meta_data( __( 'Drop Off Date', 'citytours' ), $return_date . ' ' . $return_time );

		$return_loc = get_post_meta( $booking_id, 'crbs_return_location_name', true );
		$item->update_meta_data( __( 'Drop Off Location', 'citytours' ), $return_loc );

		$element_field = get_post_meta( $booking_id, 'crbs_form_element_field', true );
		foreach ( $element_field as $field ) {
			if ( $field['value'] ) {
				$item->update_meta_data( $field['label'], $field['value'] );
			}
		}
	}
}

/**
 * Remove permalink for rental booking cart item.
 *
 * @param String $product_get_permalink_cart_item The item.
 * @param Array  $cart_item     The cart item.
 * @param String $cart_item_key The cart item key.
 */
function ot_crbs_wc_cart_item_permalink( $product_get_permalink_cart_item, $cart_item, $cart_item_key ) {
	$product_id = $cart_item['product_id'];

	// CRBS booking.
	$booking_id = get_post_meta( $product_id, 'crbs_booking_id', true );

	if ( ! empty( $booking_id ) ) {
		return false;
	}

	return $product_get_permalink_cart_item;
}

/**
 * Remove billing form from dom (step 3 in wizard).
 */
function ot_crbs_remove_form() {
	?>
	<script type="text/javascript">
		jQuery( document ).ajaxComplete(function() {
			var step = jQuery('.crbs-main-content-step-3');

			if (step) {
				var billing = step.find('[name="crbs_client_billing_detail_enable"]');
				var comment = step.find('[name="crbs_comment"]');

				if (billing) {
					billing.val(0);
					billing.parent().parent().remove(); // remove form.
				}

				if (comment) {
					comment.parent().parent().remove(); // remove textarea.
				}
			}
		});
	</script>
	<?php
}

/**
 * Block dates metabox for vehicle.
 *
 * @param array $meta_boxes Array of MetaBoxes.
 *
 * @return array
 */
 function ot_crbs_vehicles_block_meta_box( $meta_boxes ) {
	$location_instance = new CRBSLocation();
	$locations = $location_instance->getDictionary();

	$options = array_map(
		function ( $loc ) {
			return $loc['post']->post_title;
		},
		$locations
	);

	// Block dates.
	$meta_boxes[] = array(
		'id'       => 'blocking_dates',
		'class'    => 'ot-meta-box',
		'title'    => 'Exclude dates by Location',
		'pages'    => array( 'crbs_vehicle' ),
		'context'  => 'normal',
		'priority' => 'core',
		'fields'   => array(
			array(
				'name'        => 'Location',
				'id'          => 'vehicles_location',
				'class'       => 'ot-meta-box__col ot-meta-box__col--1-3 ot-meta-box__col--left',
				'type'        => 'select',
				'clone'       => true,
				'add_button'  => '+ Add Location',
				'options'     => $options,
				'placeholder' => 'Select an Item',
			),
			array(
				'name'       => 'Start Date',
				'id'         => 'vehicles_blocked_start_dates',
				'class'      => 'ot-meta-box__col ot-meta-box__col--1-3',
				'type'       => 'date',
				'clone'      => true,
				'add_button' => '+ Add Start Date',
			),
			array(
				'name'       => 'End Date',
				'id'         => 'vehicles_blocked_end_dates',
				'class'      => 'ot-meta-box__col ot-meta-box__col--1-3 ot-meta-box__col--right',
				'type'       => 'date',
				'clone'      => true,
				'add_button' => '+ Add End Date',
			),
		),
	);

	return $meta_boxes;
}
