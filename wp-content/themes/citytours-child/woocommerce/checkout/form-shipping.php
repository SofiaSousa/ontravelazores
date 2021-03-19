<?php
/**
 * Checkout shipping information form
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

?>
<div class="woocommerce-shipping-fields row">
    <?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

        <div class="default-title col-sm-12">
            <h2><?php _e( 'Traveler Details', 'citytours' ); ?></h2>
        </div>

        <div id="ship-to-different-address" style="display: none;">
            <input id="ship-to-different-address-checkbox" class="input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" />
            <label for="ship-to-different-address-aux-checkbox" class="checkbox"><?php _e( 'Same as billing details', 'citytours' ); ?></label>
        </div>

        <div id="ship-to-different-address-aux">
            <p class="form-row input-checkbox form-group col-sm-12">
                <input id="ship-to-different-address-aux-checkbox" class="input-checkbox" <?php checked( ! apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address_aux" value="1" />
                <label for="ship-to-different-address-aux-checkbox" class="checkbox" style="display: inline"><?php _e( 'Same as billing details', 'citytours' ); ?></label>
            </p>
        </div>

        <div class="shipping_address">

            <?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

            <?php
                $fields = $checkout->get_checkout_fields( 'shipping' );

                foreach ( $fields as $key => $field ) {
                    if ( isset( $field['country_field'], $fields[ $field['country_field'] ] ) ) {
                        $field['country'] = $checkout->get_value( $field['country_field'] );
                    }
                    woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                }
            ?>

            <?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

        </div>

    <?php endif; ?>

    <?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

    <?php if ( apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) ) : ?>

        <?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>

            <?php if ( 'order_comments' === $key && ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>

                <h3 class="col-sm-12"><?php _e( 'Additional information', 'citytours' ); ?></h3>

            <?php endif; ?>

            <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

        <?php endforeach; ?>

    <?php endif; ?>

    <?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
</div>
