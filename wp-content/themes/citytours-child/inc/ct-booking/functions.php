<?php
add_action( 'init', 'ot_ctb_init' );

/**
 * Extending CT Booking tours.
 */
function ot_ctb_init() {
	add_action( 'woocommerce_add_cart_item_data', 'ot_add_discount_category', 10, 3 );
}

/**
 * Add discount category for tours with discount
 *
 * @param array $cart_item_data Cart item data.
 * @param int   $product_id     Product id.
 * @param int   $variation_id   Variant id.
 *
 * @return array
 */
function ot_add_discount_category( $cart_item_data, $product_id, $variation_id ) {
	$cats = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'slugs' ) );

	// Is it a tour?
	if ( in_array( 'tour', $cats, true ) && ! in_array( 'with-discount-10', $cats, true ) ) {
		$meta = get_post_meta( $product_id, '_ct_booking_info', true );

		if ( isset( $meta['tour_id'] ) ) {
			$tour_meta     = get_post_meta( $meta['tour_id'] );
			$tour_hot      = $tour_meta['_tour_hot'];
			$tour_discount = $tour_meta['_tour_discount_rate'];

			if ( $tour_hot[0] && 10 === (int) $tour_discount[0] ) {
				$discount_cat = get_term_by( 'slug', 'with-discount-10', 'product_cat' );

				if ( $discount_cat ) {
					// Add discount category.
					wp_set_post_terms( $product_id, $discount_cat->term_id, 'product_cat', true );
				}
			}
		}
	}

	return $cart_item_data;
}
