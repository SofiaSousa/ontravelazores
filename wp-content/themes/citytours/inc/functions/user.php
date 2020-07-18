<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * get user booking list function
 */
if ( ! function_exists( 'ct_get_user_booking_list' ) ) {
    function ct_get_user_booking_list( $user_id, $filter_type='', $status = 1, $sortby = 'created', $order='desc' ) {
    	global $wpdb, $ct_options;
        $sql = '';
        $order = ( $order == 'desc' ) ? 'desc' : 'asc';
        $order_by = ' ORDER BY ' . esc_sql( $sortby ) . ' ' . $order;
        $join = ' LEFT JOIN ' . CT_ORDER_TABLE . ' AS ct_bookings ON order_id = ct_bookings.id ';
        $where = ' WHERE 1=1';
        $where .= ' AND user_id=' . esc_sql( $user_id );
        if ( $status != -1 ) {
            $where .= ' AND status=' . esc_sql( $status );
        }
        $filters = array();
        if ( empty( $filter_type ) ) {
        	$filters = array( 'hotel', 'tour', 'car' );
        } else {
        	$filters[] = $filter_type;
        }
        // $sql = sprintf( 'SELECT * FROM ' . CT_HOTEL_BOOKINGS_TABLE . $where . $order_by, $user_id );
        $available_modules = ct_get_available_modules();
        $sqls = array();
        foreach ( $filters as $filter ) {
	        if ( $filter == 'hotel' && in_array( 'hotel', $available_modules ) ) {
	            $sqls[] = "SELECT 'hotel' AS post_type, booking_no, pin_code, ct_hotel_bookings.total_price, created, status, hotel_id AS post_id, date_from AS event_date, adults, kids, rooms AS tickets FROM " . CT_HOTEL_BOOKINGS_TABLE . ' AS ct_hotel_bookings ' . $join . $where;
	        }
	        if ( $filter == 'tour' && in_array( 'tour', $available_modules ) ) {
	            $sqls[] = "SELECT 'tour' AS post_type, booking_no, pin_code, ct_tour_bookings.total_price, created, status, tour_id AS post_id, tour_date AS event_date, adults, kids, NULL AS tickets FROM " . CT_TOUR_BOOKINGS_TABLE . ' AS ct_tour_bookings ' . $join . $where;
	        }
			if ( $filter == 'car' && in_array( 'car', $available_modules ) ) {
				$sqls[] = "SELECT 'car' AS post_type, booking_no, pin_code, ct_car_bookings.total_price, created, status, car_id AS post_id, date_from AS event_date, adults, kids, NULL AS tickets FROM " . CT_CAR_BOOKINGS_TABLE . ' AS ct_car_bookings ' . $join . $where;
			}	
        }
        
        $sql = implode( ' UNION ALL ', $sqls );
        $sql .= $order_by;
        //return $sql;

        $booking_list = $wpdb->get_results( $sql );

        if ( empty( $booking_list ) ) return __( 'You don\'t have any booked trips yet.', 'citytours' ); // if empty return false

        $hotel_book_conf_url = ct_get_hotel_thankyou_page();
        $tour_book_conf_url = ct_get_tour_thankyou_page();
		$car_book_conf_url = ct_get_car_thankyou_page();

        $html = '';
        foreach ( $booking_list as $booking_data ) {
            $class = '';
            $label = 'NEW';
            if ( $booking_data->status == 'cancelled' ) { $class = ' cancelled'; $label = 'CANCELLED'; }
            if ( $booking_data->status == 'confirmed' ) { $class = ' confirmed'; $label = 'CONFIRMED'; }
            if ( $booking_data->status == 'pending' ) { $class = ' pending'; $label = 'PENDING'; }
            // if ( ( $booking_data->status == 1 ) && ( ct_strtotime( $booking_data->event_date ) < ct_strtotime(date('Y-m-d')) ) ) { $class = ' completed'; $label = 'COMPLETED'; }
            $html .= '<div class="strip_booking ' . $class . '"><div class="row">';
            $html .= '<div class="col-md-2 col-sm-2"><div class="date">
                            <span class="month">' . date( 'M', ct_strtotime( $booking_data->event_date ) ) . '</span>
                            <span class="day"><strong>' . date( 'd', ct_strtotime( $booking_data->event_date ) ) . '</strong>' . date( 'D', ct_strtotime( $booking_data->event_date ) ) . '</span>
                        </div></div>';
            $conf_url = '';
            $icon_class = '';
            if ( 'hotel' == $booking_data->post_type ) {
                $conf_url = $hotel_book_conf_url;
                $class = 'hotel_booking';
            } elseif ( 'tour' == $booking_data->post_type ) {
                $conf_url = $tour_book_conf_url;
                $class = 'tours_booking';
			} elseif ( 'car' == $booking_data->post_type ) {
				$conf_url = $car_book_conf_url;
				$class = 'transfers_booking';
            }
            $html .= '<div class="col-md-6 col-sm-5">';
            $url = empty($conf_url)?'':( add_query_arg( array( 'booking_no' => $booking_data->booking_no, 'pin_code' => $booking_data->pin_code ), $conf_url ) );
            $html .= '<h3 class="' . $class . '">';
            $html .= '<a href="' . esc_url( $url ) . '">' . get_the_title( ct_hotel_clang_id( $booking_data->post_id ) ) . '</a>';
			$html .= '<span>';
            if ( 'hotel' == $booking_data->post_type ) {
                $html .= $booking_data->tickets . ' ' . __( 'rooms', 'citytours' ) . ' ';
            }
            $html .= $booking_data->adults . ' ' . __( 'adults', 'citytours' );
            if ( ! empty( $booking_data->kids ) ) {
            	$html .= ' / ' . $booking_data->kids . ' ' . __( 'childs', 'citytours' );
            }
            $html .= '</span>';
            $html .= '</h3>';
            $html .= '</div>';
            $html .= '<div class="col-md-2 col-sm-3"><ul class="info_booking">';
            $html .= '<li><strong>' . __( 'BOOKING NO', 'citytours' ) . '</strong>' . $booking_data->booking_no . '</li>';
            $html .= '<li><strong>' . __( 'PIN CODE', 'citytours' ) . '</strong>' . $booking_data->pin_code . '</li>';
            $html .= '</ul></div>';
            $html .= '<div class="col-md-2 col-sm-2"><ul class="info_booking last">';
            $html .= '<li><strong>' . __( 'Booked on', 'citytours' ) . '</strong>' . date( 'l, M, j, Y', ct_strtotime( $booking_data->created ) ) . '</li>';
            $html .= '<li><strong>' . __( 'Status', 'citytours' ) . '</strong>' . __( $label, 'citytours' ) . '</li>';
            $html .= '</ul></div>';
            $html .= '</div></div>';
        }
        return $html;
    }
}

/*
 * Handle ajax user password update.
 */
if ( ! function_exists( 'ct_ajax_update_password' ) ) {
	function ct_ajax_update_password() {
		$result_json = array();
		//validation
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update_password' ) ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Sorry, your nonce did not verify.', 'citytours' );
			wp_send_json( $result_json );
		}

		if ( ! is_user_logged_in() ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Please log in first.', 'citytours' );
			wp_send_json( $result_json );
		}

		if ( ! isset( $_POST['pass1'] ) || ! isset( $_POST['pass2'] ) || ! isset( $_POST['old_pass'] ) ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Invalid input data.', 'citytours' );
			wp_send_json( $result_json );
		}

		if ( $_POST['pass1'] != $_POST['pass2'] ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Password mismatch.', 'citytours' );
			wp_send_json( $result_json );
		}

		$user = wp_get_current_user();
		if ( $user && wp_check_password( $_POST['old_pass'], $user->data->user_pass, $user->ID) ) {
			wp_set_password( $_POST['pass1'], $user->ID );
			wp_cache_delete( $user->ID, 'users');
			wp_cache_delete( $user->user_login, 'userlogins');
			wp_signon(array('user_login' => $user->user_login, 'user_password' => $_POST['pass1']));
			$result_json['success'] = 1;
			$result_json['result'] = __( 'Password is changed successfully.', 'citytours' );
			wp_send_json( $result_json );
		} else {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Old password is incorrect.', 'citytours' );
			wp_send_json( $result_json );
		}
	}
}

/*
 * Handle ajax user email update.
 */
if ( ! function_exists( 'ct_ajax_update_email' ) ) {
	function ct_ajax_update_email() {
		//validation
		$result_json = array();
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update_email' ) ) { 
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Sorry, your nonce did not verify.', 'citytours' );
			wp_send_json( $result_json );
		}

		if ( ! is_user_logged_in() ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Please log in first.', 'citytours' );
			wp_send_json( $result_json );
		}

		if ( ! isset( $_POST['email1'] ) || ! isset( $_POST['email2'] ) ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Invalid input data', 'citytours' );
			wp_send_json( $result_json );
		}

		if ( $_POST['email1'] != $_POST['email2'] ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Email mismatch.', 'citytours' );
			wp_send_json( $result_json );
		}

		$user = wp_get_current_user();
		if ( $user ) {
			$user_id = wp_update_user( array( 'ID' => $user->ID, 'user_email' => sanitize_email( $_POST['email1'] ) ) );
			if ( is_wp_error( $user_id ) ) {
				$result_json['success'] = 0;
				$result_json['result'] = __( 'An error occurred.', 'citytours' );
				wp_send_json( $result_json );
			} else {
				$result_json['success'] = 1;
				$result_json['result'] = __( 'Success.', 'citytours' );
				wp_send_json( $result_json );
			}
		}

		$result_json['success'] = 0;
		$result_json['result'] = __( 'An error occurred.', 'citytours' );
		wp_send_json( $result_json );
	}
}

/*
 * Handle booking list filter and sorting action.
 */
if ( ! function_exists( 'ct_ajax_update_booking_list' ) ) {
	function ct_ajax_update_booking_list() {
		$result_json = array();
		$user_id = get_current_user_id();

		$status = isset($_POST['status']) ? sanitize_text_field( $_POST['status'] ) : -1;
		$sortby = isset($_POST['sort_by']) ? sanitize_text_field( $_POST['sort_by'] ) : 'created';
		$order = isset($_POST['order']) ? sanitize_text_field( $_POST['order'] ) : 'desc';
		$filter_type = isset($_POST['filter_type']) ? sanitize_text_field( $_POST['filter_type'] ) : '';
		
		$booking_list = ct_get_user_booking_list( $user_id, $filter_type, $status, $sortby, $order );
		if ( ! empty( $booking_list ) ) {
			$result_json['success'] = 1;
			$result_json['result'] = $booking_list;
			wp_send_json( $result_json );
		} else {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'empty', 'citytours' );
			wp_send_json( $result_json );
		}
	}
}

/*
 * Update user profile on dashboard edit
 */
if ( ! function_exists( 'ct_user_update_profile' ) ) {
	function ct_user_update_profile() {
		$user_id = get_current_user_id();
		if ( isset( $_POST['action'] ) && $_POST['action'] == 'update_profile' ) {
			if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'update_profile' ) ) {
				$update_data = array(
					'ID' => $user_id,
					'first_name' => isset($_POST['first_name']) ? sanitize_text_field( $_POST['first_name'] ) : '',
					'last_name' => isset($_POST['last_name']) ? sanitize_text_field( $_POST['last_name'] ) : '',
					'user_email' => isset($_POST['email']) ? sanitize_email( $_POST['email'] ) : '',
					'birthday' => isset($_POST['birthday']) ? sanitize_text_field( $_POST['birthday'] ) : '',
					'country_code' => isset($_POST['country_code']) ? sanitize_text_field( $_POST['country_code'] ) : '',
					'phone' => isset($_POST['phone']) ? sanitize_text_field( $_POST['phone'] ) : '',
					'address' => isset($_POST['address']) ? sanitize_text_field( $_POST['address'] ) : '',
					'city' => isset($_POST['city']) ? sanitize_text_field( $_POST['city'] ) : '',
					'country' => isset($_POST['country']) ? sanitize_text_field( $_POST['country'] ) : '',
					'description' => isset($_POST['description']) ? sanitize_text_field( $_POST['description'] ) : '',
					);
				if ( ! isset( $_FILES['photo'] ) || ( $_FILES['photo']['size'] == 0 ) ) {
					if ( ! empty( $_POST['remove_photo'] ) ) {
						$update_data['photo_url'] = '';
					}
				} else {
					if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
					$uploadedfile = $_FILES['photo'];
					$upload_overrides = array( 'test_form' => false );
					$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
					$update_data['photo_url'] = $movefile['url'];
				}
				wp_update_user( $update_data );
				echo '<div class="alert alert-success">' . __( 'Your profile is updated successfully.', 'citytours' ) . '<span class="close"></span></div>';
			} else {
				echo '<div class="alert alert-error">' . __( 'Sorry, your nonce did not verify.', 'citytours' ) . '<span class="close"></span></div>';
			}
		}
	}
}


add_action( 'wp_ajax_update_password', 'ct_ajax_update_password' );
add_action( 'wp_ajax_nopriv_update_password', 'ct_ajax_update_password' );

add_action( 'wp_ajax_update_email', 'ct_ajax_update_email' );
add_action( 'wp_ajax_nopriv_update_email', 'ct_ajax_update_email' );

add_action( 'wp_ajax_update_booking_list', 'ct_ajax_update_booking_list' );
add_action( 'wp_ajax_nopriv_update_booking_list', 'ct_ajax_update_booking_list' );

add_action( 'ct_before_dashboard', 'ct_user_update_profile' );