<?php
add_action( 'init', 'ot_ctb_init' );
add_filter( 'mb_settings_pages', 'ot_tours_settings_page' );

/**
 * Extending CT Booking tours.
 */
function ot_ctb_init() {
	add_action( 'woocommerce_add_cart_item_data', 'ot_add_discount_category', 10, 3 );
	add_action( 'save_post', 'ot_set_coupon_string_translation', 10, 3 );
	add_filter( 'woocommerce_cart_totals_coupon_label', 'ot_cart_totals_smart_coupons_label', 10, 2 );
	add_action( 'wp_enqueue_scripts', 'ot_remove_conflicted_assets', 99 );

	add_filter( 'rwmb_meta_boxes', 'ot_tour_meta_box' );
	add_filter( 'rwmb_meta_boxes', 'ot_tours_settings_meta_box' );

	ot_register_tour_extra_fields_taxonomy();
}


/**
 * Add discount category for the tours that have a discount set.
 *
 * @param array $cart_item_data Cart item data.
 * @param int   $product_id     Product id.
 * @param int   $variation_id   Variant id.
 *
 * @return array
 */
function ot_add_discount_category( $cart_item_data, $product_id, $variation_id ) {
	$cats = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'slugs' ) );

	// Isn't it a tour product?
	if ( ! in_array( 'tour', $cats, true ) ) {
		return $cart_item_data;
	}

	// Get tour_id in booking_info.
	$meta = get_post_meta( $product_id, '_ct_booking_info', true );

	if ( ! isset( $meta['tour_id'] ) ) {
		return $cart_item_data;
	}

	// Get tour meta.
	$tour_meta = get_post_meta( $meta['tour_id'] );

	if ( isset( $tour_meta['_tour_hot'] ) && isset( $tour_meta['_tour_discount_rate'] ) ) {
		$tour_hot      = $tour_meta['_tour_hot'][0];
		$tour_discount = (int) $tour_meta['_tour_discount_rate'][0];

		// Is tour discount set?
		if ( $tour_hot && 0 < $tour_discount ) {
			$cat_slug = 'with-discount-' . $tour_discount;

			// Does the tour already have the discount category?
			if ( in_array( $cat_slug, $cats, true ) ) {
				return $cart_item_data;
			}

			$discount_cat = get_term_by( 'slug', $cat_slug, 'product_cat' );

			if ( $discount_cat ) {
				// Add discount category.
				wp_set_post_terms( $product_id, $discount_cat->term_id, 'product_cat', true );
			}
		}
	}

	return $cart_item_data;
}

/**
 * Set coupon translation.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an existing post being updated.
 */
function ot_set_coupon_string_translation( $post_id, $post, $update ) {
	// Only set for Coupons.
	if ( 'shop_coupon' !== $post->post_type ) {
		return;
	}

	if ( function_exists( 'icl_register_string' ) ) {
		icl_register_string( 'citytours', '', $post->post_title );
	}
}

/**
 * Add smart_coupons translated label in cart total
 *
 * @param string    $default_label Default label.
 * @param WC_Coupon $coupon The coupon object.
 *
 * @return string New label
 */
function ot_cart_totals_smart_coupons_label( $default_label = '', $coupon = null ) {
	if ( empty( $coupon ) ) {
		return $default_label;
	}

	if ( 'percent' === $coupon->get_discount_type() && ! empty( $coupon->get_code() ) ) {
		$default_label = __( esc_html( get_the_title( $coupon->get_id() ) ), 'citytours' );
	}

	return $default_label;
}

/**
 * Remove conflicted script.
 */
function ot_remove_conflicted_assets() {
	if ( is_singular( 'tour' ) || is_singular( 'product' ) ) {
		// Remove styles.
		wp_dequeue_script( 'jquery-ui-datepicker' );
	}
}

/**
 * Register Tour Extra Fields taxonomy
 */
function ot_register_tour_extra_fields_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Tour Extra Fields', 'taxonomy general name', 'citytours' ),
		'singular_name'              => _x( 'Tour Extra Fields', 'taxonomy singular name', 'citytours' ),
		'menu_name'                  => __( 'Tour Extra Fields', 'citytours' ),
		'all_items'                  => __( 'All Tour Extra Fields', 'citytours' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'new_item_name'              => __( 'New Tour Extra Fields Group', 'citytours' ),
		'add_new_item'               => __( 'Add New Tour Extra Fields Group', 'citytours' ),
		'edit_item'                  => __( 'Edit Tour Extra Fields Group', 'citytours' ),
		'update_item'                => __( 'Update Tour Extra Fields Group', 'citytours' ),
		'separate_items_with_commas' => __( 'Separate tour extra fields with commas', 'citytours' ),
		'search_items'               => __( 'Search Tour Extra Fields', 'citytours' ),
		'add_or_remove_items'        => __( 'Add or remove tour extra fields', 'citytours' ),
		'choose_from_most_used'      => __( 'Choose from the most used tour extra fields', 'citytours' ),
		'not_found'                  => __( 'No tour extra fields found.', 'citytours' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_admin_column' => true,
	);

	register_taxonomy( 'tour_extra_fields', array( 'tour' ), $args );
}

/**
 * Block dates metabox for tours.
 *
 * @param array $meta_boxes Array of MetaBoxes.
 *
 * @return array
 */
function ot_tour_meta_box( $meta_boxes ) {
	// Block dates.
	$meta_boxes[] = array(
		'id'       => 'blocking_dates',
		'class'    => 'ot-meta-box',
		'title'    => 'Tour dates blocked',
		'pages'    => array( 'tour' ),
		'context'  => 'normal',
		'priority' => 'core',
		'fields'   => array(
			array(
				'name'       => 'Start Date',
				'id'         => 'tour_blocked_start_dates',
				'class'      => 'ot-meta-box__col ot-meta-box__col--1-2',
				'type'       => 'date',
				'clone'      => true,
				'add_button' => '+ Add Start Date',
			),
			array(
				'name'       => 'End Date',
				'id'         => 'tour_blocked_end_dates',
				'class'      => 'ot-meta-box__col ot-meta-box__col--1-2 ot-meta-box__col--right',
				'type'       => 'date',
				'clone'      => true,
				'add_button' => '+ Add End Date',
			),
			array(
				'name'  => 'Number of days to block',
				'id'    => 'tours_number_days_to_block',
				'class' => 'ot-meta-box__col ot-meta-box__col--1-2',
				'type'  => 'number',
				'desc'  => 'Block next days from today (optional).',
			),
		),
	);

	return $meta_boxes;
}

/**
 * Settings page for tours.
 *
 * @param array $settings_pages Array of settings page.
 *
 * @return array
 */
function ot_tours_settings_page( $settings_pages ) {
	$settings_pages[] = array(
		'id'          => 'tours-settings',
		'option_name' => 'tours_settings',
		'menu_title'  => 'Tours Settings',
		'icon_url'    => 'dashicons-edit',
		'parent'      => 'edit.php?post_type=tour',
		'style'       => 'boxes',
		'columns'     => 2,
		'tabs'        => array(
			'general' => 'General Settings',
		),
	);

	return $settings_pages;
}

/**
 * Global block dates metabox for tours.
 *
 * @param array $meta_boxes Array of MetaBoxes.
 *
 * @return array
 */
function ot_tours_settings_meta_box( $meta_boxes ) {
	// Global block dates.
	$meta_boxes[] = array(
		'id'             => 'global_blocking_dates',
		'class'          => 'ot-meta-box',
		'title'          => 'Dates blocked',
		'settings_pages' => 'tours-settings',
		'tab'            => 'general',
		'fields'         => array(
			array(
				'name'       => 'Start Date',
				'id'         => 'tours_blocked_start_dates',
				'class'      => 'ot-meta-box__col ot-meta-box__col--1-2',
				'type'       => 'date',
				'clone'      => true,
				'add_button' => '+ Add Start Date',
			),
			array(
				'name'       => 'End Date',
				'id'         => 'tours_blocked_end_dates',
				'class'      => 'ot-meta-box__col ot-meta-box__col--1-2 ot-meta-box__col--right',
				'type'       => 'date',
				'clone'      => true,
				'add_button' => '+ Add End Date',
			),
		),
	);

	return $meta_boxes;
}
