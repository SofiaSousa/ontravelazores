<?php
add_action( 'init', 'ot_ctb_init' );

/**
 * Extending CT Booking tours.
 */
function ot_ctb_init() {
	add_action( 'woocommerce_add_cart_item_data', 'ot_add_discount_category', 10, 3 );
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
