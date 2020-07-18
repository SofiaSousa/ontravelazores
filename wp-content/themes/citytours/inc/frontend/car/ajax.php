<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Update Cart content when there's changes on Cart page.
 */
if ( ! function_exists( 'ct_car_update_cart' ) ) {
    function ct_car_update_cart() {
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'car_update_cart' ) ) {
            print esc_html__( 'Sorry, your nonce did not verify.', 'citytours' );
            exit;
        }

        // validation
        if ( ! isset( $_POST['car_id'] ) || ! isset( $_POST['date'] ) ) {
            wp_send_json( array( 
                'success' => 0, 
                'message' => __( 'Some validation error is occurred while calculate price.', 'citytours' ) 
            ) );
        }

        // init variables
        $car_id     = $_POST['car_id'];
        $date        = $_POST['date'];
        $time        = $_POST['time'];
        $pickup_location = $_POST['pickup_location'];
        $dropoff_location = $_POST['dropoff_location'];
        $uid         = $car_id . $date;
        $adults      = ( isset( $_POST['adults'] ) ) ? $_POST['adults'] : 1;
        $kids        = ( isset( $_POST['kids'] ) ) ? $_POST['kids'] : 0;
        $total_price = ct_car_calc_car_price( $car_id, $date, $adults, $kids );

        $cart_data = array();
        $cart_data['car'] = array(
            'adults' => $adults,
            'kids'   => $kids,
            'total'  => $total_price
        );

        if ( ! empty( $_POST['add_service'] ) ) {
            global $wpdb;
            
            foreach ( $_POST['add_service'] as $key => $value ) {
                $services = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . CT_ADD_SERVICES_TABLE . ' WHERE id=%d AND post_id=%d', $key, $car_id ) );

                if ( ! empty( $services ) ) {
                    $cart_add_service_data = array();
                    $cart_add_service_data['title'] = $services->title;
                    $cart_add_service_data['price'] = $services->price;
                    $cart_add_service_data['qty']   = isset( $_POST['add_service_' . $key] ) ? $_POST['add_service_' . $key] : 1;
                    $cart_add_service_data['total'] = $cart_add_service_data['price'] * $cart_add_service_data['qty'];

                    $cart_data['add_service'][$key] = $cart_add_service_data;
                    $total_price += $cart_add_service_data['total'];
                }
            }
        }

        $cart_data['total_price'] = $total_price;
        $cart_data['total_adults'] = $adults;
        $cart_data['total_kids'] = $kids;
        $cart_data['car_id'] = $car_id;
        $cart_data['date'] = $date;
        $cart_data['time'] = $time;
        $cart_data['pickup_location'] = $pickup_location;
        $cart_data['dropoff_location'] = $dropoff_location;

        CT_Hotel_Cart::set( $uid, $cart_data );

        wp_send_json( array( 
            'success' => 1, 
            'message' => 'success' 
        ) );
    }
}

/*
 * Handle submit booking ajax request
 */
if ( ! function_exists( 'ct_car_submit_booking' ) ) {
    function ct_car_submit_booking() {
        global $wpdb, $ct_options;

        $result_json = array( 
            'success'   => 0, 
            'result'    => '', 
            'order_id'  => 0 
        );
        
        if ( isset( $_POST['order_id'] ) && empty( $_POST['order_id'] ) ) {
            // validation
            if ( ! isset( $_POST['uid'] ) || ! CT_Hotel_Cart::get( $_POST['uid'] ) ) {
                $result_json['result'] = esc_html__( 'Sorry, some error occurred on input data validation.', 'citytours' );

                wp_send_json( $result_json );
            }

            if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'checkout' ) ) {
                $result_json['result'] = esc_html__( 'Sorry, your nonce did not verify.', 'citytours' );

                wp_send_json( $result_json );
            }

            // init variables
            $uid             = $_POST['uid'];
            $latest_order_id = $wpdb->get_var( 'SELECT id FROM ' . CT_ORDER_TABLE . ' ORDER BY id DESC LIMIT 1' );
            $booking_no      = mt_rand( 1000, 9999 ) . $latest_order_id;
            $pin_code        = mt_rand( 1000, 9999 );
            $post_fields     = array( 
                'first_name', 
                'last_name', 
                'email', 
                'phone', 
                'country', 
                'address1', 
                'address2', 
                'city', 
                'state', 
                'zip' 
            );

            $order_info = ct_order_default_order_data( 'new' );
            foreach ( $post_fields as $post_field ) {
                if ( ! empty( $_POST[ $post_field ] ) ) {
                    $order_info[ $post_field ] = sanitize_text_field( $_POST[ $post_field ] );
                }
            }

            $cart_data = CT_Hotel_Cart::get( $uid );
            $order_info['total_price']  = $cart_data['total_price'];
            $order_info['total_adults'] = $cart_data['total_adults'];
            $order_info['total_kids']   = $cart_data['total_kids'];
            $order_info['status']       = 'new'; 
            $order_info['deposit_paid'] = 1;
            $order_info['mail_sent']    = 0;
            $order_info['post_id']      = $cart_data['car_id'];

            if ( ! empty( $cart_data['date'] ) ) {
                $order_info['date_from'] = date( 'Y-m-d', ct_strtotime( $cart_data['date'] ) );
            }

            $order_info['booking_no']   = $booking_no;
            $order_info['pin_code']     = $pin_code;
            $order_info['created']      = date( 'Y-m-d H:i:s' );
            $order_info['post_type']    = 'car';

            // calculate deposit payment
            $deposit_rate = get_post_meta( $cart_data['car_id'], '_car_security_deposit', true );

            // if woocommerce enabled change currency_code and exchange rate as default
            if ( ! empty( $deposit_rate ) && ct_is_woocommerce_integration_enabled() ) {
                $order_info['currency_code'] = ct_get_def_currency();
                $order_info['exchange_rate'] = 1;
            } else {
                if ( ! isset( $_SESSION['exchange_rate'] ) ) {
                    ct_init_currency();
                }
                $order_info['exchange_rate'] = $_SESSION['exchange_rate'];
                $order_info['currency_code'] = ct_get_user_currency();
            }

            // if payment enabled set deposit price field
            if ( ! empty( $deposit_rate ) && ct_is_payment_enabled() ) {
                $decimal_prec = isset( $ct_options['decimal_prec'] ) ? $ct_options['decimal_prec'] : 2;

                $order_info['deposit_price'] = round( $deposit_rate / 100 * $order_info['total_price'] * $order_info['exchange_rate'], $decimal_prec );
                $order_info['deposit_paid']  = 0; // set unpaid if payment enabled
                $order_info['status']        = 'pending';
            }

            if ( is_user_logged_in() ) {
                $order_info['user_id'] = get_current_user_id();
            }
            
            if ( $wpdb->insert( CT_ORDER_TABLE, $order_info ) ) {
                CT_Hotel_Cart::_unset( $uid );
                $order_id = $wpdb->insert_id;

                if ( ! empty( $cart_data['car'] ) ) {
                    $car_booking_info = array();
                    $car_booking_info['order_id']    = $order_id;
                    $car_booking_info['car_id']     = $cart_data['car_id'];
                    $car_booking_info['car_date']   = $cart_data['date'];
                    $car_booking_info['car_time']   = $cart_data['time'];
                    $car_booking_info['pickup_location'] = $cart_data['pickup_location'];
                    $car_booking_info['dropoff_location'] = $cart_data['dropoff_location'];
                    $car_booking_info['adults']      = $cart_data['car']['adults'];
                    $car_booking_info['kids']        = $cart_data['car']['kids'];
                    $car_booking_info['total_price'] = $cart_data['car']['total'];

                    $wpdb->insert( CT_CAR_BOOKINGS_TABLE, $car_booking_info );
                }

                if ( ! empty( $cart_data['add_service'] ) ) {
                    foreach ( $cart_data['add_service'] as $service_id => $service_data ) {
                        $service_booking_info = array();
                        $service_booking_info['order_id']       = $order_id;
                        $service_booking_info['add_service_id'] = $service_id;
                        $service_booking_info['qty']            = $service_data['qty'];
                        $service_booking_info['total_price']    = $service_data['total'];

                        $wpdb->insert( CT_ADD_SERVICES_BOOKINGS_TABLE, $service_booking_info );
                    }
                }

                if ( ( isset( $_POST['payment_info'] ) && $_POST['payment_info'] == 'paypal' ) || ( ! isset( $_POST['payment_info'] ) ) ) { 
                    $result_json['success'] = 1;

                    $result_json['result'] = array();
                    $result_json['result']['order_id']   = $order_id;
                    $result_json['result']['booking_no'] = $booking_no;
                    $result_json['result']['pin_code']   = $pin_code;
                } else if ( isset( $_POST['payment_info'] ) && 'cc' == $_POST['payment_info'] ) { 
                    $payment_process_result = ct_credit_card_paypal_process_payment( $order_info );

                    if ( 1 == $payment_process_result['success'] ) { 
                        $result_json['success'] = 1;

                        $result_json['result'] = array();
                        $result_json['result']['order_id']   = $order_id;
                        $result_json['result']['booking_no'] = $booking_no;
                        $result_json['result']['pin_code']   = $pin_code;
                    } else { 
                        $result_json['success']  = 0;
                        $result_json['result']   = $payment_process_result['errormsg'];
                        $result_json['order_id'] = $order_id;
                    }
                }
            } else {
                $result_json['success'] = 0;
                $result_json['result']  = esc_html__( 'Sorry, An error occurred while add your order.', 'citytours' );
            }
        } else if ( isset( $_POST['order_id'] ) && ! empty( $_POST['order_id'] ) && isset( $_POST['payment_info'] ) && 'cc' == $_POST['payment_info'] ) { 
            $order                  = new CT_Hotel_Order( $_POST['order_id'] );
            $order_info             = $order->get_order_info();
            $payment_process_result = ct_credit_card_paypal_process_payment( $order_info );

            if ( 1 == $payment_process_result['success'] ) { 
                $result_json['success'] = 1;

                $result_json['result'] = array();
                $result_json['result']['order_id']   = $order->order_id;
                $result_json['result']['booking_no'] = $booking_no;
                $result_json['result']['pin_code']   = $pin_code;
            } else { 
                $result_json['success']  = 0;
                $result_json['result']   = $payment_process_result['errormsg'];
                $result_json['order_id'] = $order->order_id;
            }
        }

        wp_send_json( $result_json );
    }
}


/*
 * Add car product to WooCommerce Cart
 */
if ( ! function_exists( 'ct_add_car_to_woo_cart' ) ) { 
    function ct_add_car_to_woo_cart() { 
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'car_update_cart' ) ) {
            print esc_html__( 'Sorry, your nonce did not verify.', 'citytours' );
            exit;
        }

        // init variables
        $car_id = $_POST['car_id'];
        $car_date = $_POST['date'];
        $uid = $car_id . $car_date;

        $car_product_id = ct_create_car_product( $car_id, $uid ); 

        if ( ! $car_product_id || is_wp_error( $car_product_id ) ) { 
            wp_send_json( array( 
                'success' => 0, 
                'message' => __( 'Can not add car to Cart. Please try again later', 'citytours' ) 
            ) );
        }

        $cart = WC()->cart->get_cart();
        $in_cart = false;

        // check if product already in cart
        if ( count( $cart ) > 0 ) {
            foreach ( $cart as $cart_item_key => $values ) {
                $_product = $values['data'];

                if ( $_product->id == $car_product_id ) {
                    $in_cart = true;
                    break;
                }
            }
        }

        if ( ! $in_cart ) { 
            WC()->cart->add_to_cart( $car_product_id );
        }

        wp_send_json( array( 
            'success' => 1, 
            'message' => 'success' 
        ) );
    }
}

/*
 * Add Car product to WooCommerce Cart
 */
if ( ! function_exists( 'ct_add_car_to_woo_cart' ) ) { 
    function ct_add_car_to_woo_cart() { 
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'car_update_cart' ) ) {
            print esc_html__( 'Sorry, your nonce did not verify.', 'citytours' );
            exit;
        }

        // init variables
        $car_id = $_POST['car_id'];
        $car_date = $_POST['date'];
        $uid = $car_id . $car_date;

        $car_product_id = ct_create_car_product( $car_id, $uid ); 

        if ( ! $car_product_id || is_wp_error( $car_product_id ) ) { 
            wp_send_json( array( 
                'success' => 0, 
                'message' => __( 'Can not add Car to Cart. Please try again later', 'citytours' ) 
            ) );
        }

        $cart = WC()->cart->get_cart();
        $in_cart = false;

        // check if product already in cart
        if ( count( $cart ) > 0 ) {
            foreach ( $cart as $cart_item_key => $values ) {
                $_product = $values['data'];

                if ( $_product->id == $car_product_id ) {
                    $in_cart = true;
                    break;
                }
            }
        }

        if ( ! $in_cart ) { 
            WC()->cart->add_to_cart( $car_product_id );
        }

        wp_send_json( array( 
            'success' => 1, 
            'message' => 'success' 
        ) );
    }
}

/*
 * Create product for selected Car
 */
if ( ! function_exists( 'ct_create_car_product' ) ) { 
    function ct_create_car_product( $car_id, $uid ) { 
        $deposit_rate = get_post_meta( $car_id, '_car_security_deposit', true );
        $deposit_rate = empty( $deposit_rate ) ? 0 : $deposit_rate;

        $car_product = array(
            'post_title'     => get_the_title( $car_id ),
            'post_content'   => '',
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'comment_status' => 'closed'
        );

        //Create Hotel Product
        $car_product_id = wp_insert_post( $car_product );

        if( $car_product_id ) { 
            $attach_id = get_post_meta( $car_id, "_thumbnail_id", true );
            update_post_meta( $car_product_id, '_thumbnail_id', $attach_id );

            wp_set_object_terms( $car_product_id, 'car', 'product_cat' );
            wp_set_object_terms( $car_product_id, 'simple_car', 'product_type' );

            $default_attributes = array();
            update_post_meta( $car_product_id, '_sku', 'sku' . $uid );
            update_post_meta( $car_product_id, '_stock_status', 'instock' );
            update_post_meta( $car_product_id, '_visibility', 'visible' );
            update_post_meta( $car_product_id, '_virtual', 'yes');
            update_post_meta( $car_product_id, '_default_attributes', $default_attributes );
            update_post_meta( $car_product_id, '_manage_stock', 'no' );
            update_post_meta( $car_product_id, '_backorders', 'no' );

            $cart           = new CT_Hotel_Cart();
            $cart_info      = $cart->get( $uid );
            $booking_price  = $cart_info['total_price'] * $deposit_rate / 100;
            update_post_meta( $car_product_id, '_regular_price', $booking_price );
            update_post_meta( $car_product_id, '_sale_price', $booking_price );
            update_post_meta( $car_product_id, '_price', $booking_price );

            update_post_meta( $car_product_id, '_ct_post_id', $car_id );
            update_post_meta( $car_product_id, '_ct_booking_date', $cart_info['date'] );
            update_post_meta( $car_product_id, '_ct_total_price', $cart_info['total_price'] );
            update_post_meta( $car_product_id, '_ct_booking_time', $cart_info['time'] );
            update_post_meta( $car_product_id, '_ct_booking_pickup_location', $cart_info['pickup_location'] );
            update_post_meta( $car_product_id, '_ct_booking_dropoff_location', $cart_info['dropoff_location'] );

            $booking_info = array();
            $booking_info['car_id'] = $cart_info['car_id'];
            $booking_info['adults']  = $cart_info['car']['adults'];
            $booking_info['kids']    = $cart_info['car']['kids'];
            $booking_info['total']   = $cart_info['car']['total'];

            update_post_meta( $car_product_id, '_ct_booking_info', $booking_info );

            $add_service = array();
            foreach ( $cart_info['add_service'] as $service_id => $service_info ) {
                $temp = array();
                $temp['service_id'] = $service_id;
                $temp['title']      = $service_info['title'];
                $temp['price']      = $service_info['price'];
                $temp['qty']        = $service_info['qty'];

                $add_service[] = $temp;
            }

            update_post_meta( $car_product_id, '_ct_add_service', $add_service );
        }

        return $car_product_id;
    }
}
