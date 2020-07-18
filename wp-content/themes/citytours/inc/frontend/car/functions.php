<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'ct_car_get_schedules' ) ) {
	function ct_car_get_schedules( $car_id ) {
		// validation

		// init variables
		global $wpdb;
		$car_id = ct_car_org_id( $car_id );

		$sql = "SELECT schedules.from, schedules.to, schedule_meta.* FROM " . CT_car_SCHEDULES_TABLE . " AS schedules 
				LEFT JOIN " . CT_car_SCHEDULE_META_TABLE . " AS schedule_meta 
					ON schedules.id = schedule_meta.schedule_id
				WHERE schedules.car_id = %d
				ORDER BY schedules.ts_id ASC, schedule_meta.day ASC";

		$schedules = $wpdb->get_results( $wpdb->prepare( $sql, $car_id ), ARRAY_A );

		return $schedules;
	}
}

if ( ! function_exists( 'ct_get_car_cart_page' ) ) {
	function ct_get_car_cart_page() {
		global $ct_options;
		if ( ! empty( $ct_options['car_cart_page'] ) ) {
			return ct_get_permalink_clang( $ct_options['car_cart_page'] );
		}
		return false;
	}
}
if ( ! function_exists( 'ct_get_car_checkout_page' ) ) {
	function ct_get_car_checkout_page() {
		global $ct_options;
		if ( ! empty( $ct_options['car_checkout_page'] ) ) {
			return ct_get_permalink_clang( $ct_options['car_checkout_page'] );
		}
		return false;
	}
}
if ( ! function_exists( 'ct_get_car_thankyou_page' ) ) {
	function ct_get_car_thankyou_page() {
		global $ct_options;
		if ( ! empty( $ct_options['car_thankyou_page'] ) ) {
			return ct_get_permalink_clang( $ct_options['car_thankyou_page'] );
		}
		return false;
	}
}

if ( ! function_exists( 'ct_car_check_availability' ) ) {
	function ct_car_check_availability( $post_id, $date='', $time='', $adults=1, $kids=0 ) {
		return true;
		// validation
		/*if ( empty( $post_id ) || 'car' != get_post_type( $post_id )  ) return esc_html__( 'Invalide car ID.', 'citytours' ); //invalid data
		$max_people =  get_post_meta( $post_id, '_car_max_people', true );
		if ( empty( $max_people ) ) return true; // max people empty means no limit
		$is_repeated =  get_post_meta( $post_id, '_car_repeated', true );

		$post_id = esc_sql( ct_car_org_id( $post_id ) );
		if ( ! empty( $is_repeated ) && ! ct_strtotime( $date ) ) {
			return esc_html__( 'Invalid date. Please check your booking date again.', 'citytours' ); //invalid data
		}

		global $wpdb;
		$where = "1=1";
		$where .= " AND car_id=" . $post_id;
		if ( ! empty( $is_repeated ) ) $where .= " AND car_date='" . esc_sql( date( 'Y-m-d', ct_strtotime( $date ) ) ) . "'";
		
		$sql = "SELECT SUM(adults) FROM " . CT_CAR_BOOKINGS_TABLE . " WHERE $where";
		$booked_count = $wpdb->get_var( $sql );
		if ( $booked_count + $adults <= $max_people ) {
			return true;
		} elseif ( $booked_count >= $max_people ) {
			return esc_html__( 'Sold Out', 'citytours' );
		} else {
			return sprintf( esc_html__( 'Exceed Persons. Only %d persons available', 'citytours' ), $max_people - $booked_count );
		}*/
	}
}

if ( ! function_exists( 'ct_car_calc_car_price' ) ) {
	function ct_car_calc_car_price( $post_id, $date='', $adults=1, $kids=0 ) {

		$is_repeated =  get_post_meta( $post_id, '_car_repeated', true );
		if ( ! empty( $is_repeated ) ) {
			$weekly_price = ct_car_get_price_per_day( $post_id );
			$day = date( 'w', ct_strtotime( $date ) );
			$person_price = $weekly_price[$day];
		} else {
			$person_price = get_post_meta( $post_id, '_car_price', true );
		}

		if ( empty( $person_price ) ) {
			$person_price = 0;
		}
	
		$charge_child = get_post_meta( $post_id, '_car_charge_child', true );
		$child_price = get_post_meta( $post_id, '_car_price_child', true );
		if ( empty( $charge_child ) || empty( $child_price ) ) {
			$child_price = 0;
		}
		$total = $person_price * $adults + $child_price * $kids;	
		return $total;
	}
}

/*
 * send booking confirmation email function
 */
if ( ! function_exists( 'ct_car_generate_conf_mail' ) ) {
	function ct_car_generate_conf_mail( $order, $type='new' ) {
		global $wpdb, $ct_options;
		$order_data = $order->get_order_info();
		if ( ! empty( $order_data ) ) {
			// server variables
			$admin_email = get_option('admin_email');
			$home_url = esc_url( home_url('/') );
			$site_name = filter_input( INPUT_SERVER, 'SERVER_NAME' );
			$logo_url = esc_url( ct_logo_url() );
			$order_data['car_id'] = ct_car_clang_id( $order_data['post_id'] );

			// car info
			$car_name = get_the_title( $order_data['car_id'] );
			if ( ! empty( $order_data['date_from'] ) && '0000-00-00' != $order_data['date_from'] ) $car_name .= ' - ' . date_i18n( 'j F Y', strtotime( $order_data['date_from'] ) );
			$car_url = esc_url( ct_get_permalink_clang( $order_data['car_id'] ) );
			$car_thumbnail = get_the_post_thumbnail( $order_data['car_id'], 'medium' );
			$car_address = get_post_meta( $order_data['car_id'], '_car_address', true );
			$car_email = get_post_meta( $order_data['car_id'], '_car_email', true );
			$car_phone = get_post_meta( $order_data['car_id'], '_car_phone', true );

			// services info
			$booking_services = '<table><tbody><tr><th>' . esc_html__( 'Service Name', 'citytours' ) . '</th><th>' . esc_html__( 'Quantity', 'citytours' ) . '</th><th>' . esc_html__( 'Total Price', 'citytours' ) . '</th></tr>';
			$services_booking_data = $order->get_services();
			if ( ! empty( $services_booking_data ) ) {
				foreach ( $services_booking_data as $key => $service_booking_data ) {
					$service_data = ct_get_add_service( $service_booking_data['add_service_id'] );
					$service_qty = $service_booking_data['qty'];
					$service_total_price = $service_booking_data['total_price'];
					$booking_services .= '<tr><td>' . $service_data->title . '</td><td>' . $service_qty . '</td><td>' . $service_total_price . '</td></tr>';
				}
			}
			$booking_services .= '</tbody></table>';

			// booking info
			$booking_date = date_i18n( 'j F Y', strtotime( $order_data['date_from'] ) );
			$booking_data = $order->get_cars();
			$booking_time = $booking_data['car_time'];
			$booking_adults = $order_data['total_adults'];
			$booking_kids = $order_data['total_kids'];
			$booking_total_price = esc_html( ct_price( $order_data['total_price'] * $order_data['exchange_rate'], "", $order_data['currency_code'], 0 ) );
			$booking_deposit_price = esc_html( $order_data['deposit_price'] . $order_data['currency_code'] );
			$booking_deposit_paid = esc_html( empty( $order_data['deposit_paid'] ) ? 'No' : 'Yes' );
			$booking_no = $order_data['booking_no'];
			$booking_pincode = $order_data['pin_code'];

			// customer info
			$customer_first_name = $order_data['first_name'];
			$customer_last_name = $order_data['last_name'];
			$customer_email = $order_data['email'];
			$customer_country_code = $order_data['country'];
			$customer_phone = $order_data['phone'];
			$customer_address1 = $order_data['address1'];
			$customer_address2 = $order_data['address2'];
			$customer_city = $order_data['city'];
			$customer_zip = $order_data['zip'];
			$customer_country = $order_data['country'];
			$customer_special_requirements = $order_data['special_requirements'];

			$variables = array( 'home_url',
								'site_name',
								'logo_url',
								'car_name',
								'car_url',
								'car_thumbnail',
								'car_address',
								'car_email',
								'car_phone',
								'booking_services',
								'booking_no',
								'booking_pincode',
								'booking_date',
								'booking_time',
								'booking_adults',
								'booking_kids',
								'booking_total_price',
								'booking_deposit_paid',
								'booking_deposit_price',
								'customer_first_name',
								'customer_last_name',
								'customer_email',
								'customer_country_code',
								'customer_phone',
								'customer_address1',
								'customer_address2',
								'customer_city',
								'customer_zip',
								'customer_country',
								'customer_special_requirements',
							);

			if ( empty( $subject ) ) {
				$subject = empty( $ct_options['car_confirm_email_subject'] ) ? 'Booking Confirmation Email Subject' : $ct_options['car_confirm_email_subject'];
			}

			if ( empty( $description ) ) {
				$description = empty( $ct_options['car_confirm_email_description'] ) ? 'Booking Confirmation Email Description' : $ct_options['car_confirm_email_description'];
			}

			foreach ( $variables as $variable ) {
				$subject = str_replace( "[" . $variable . "]", $$variable, $subject );
				$description = str_replace( "[" . $variable . "]", $$variable, $description );
			}

			$mail_sent = ct_send_mail( $site_name, $admin_email, $customer_email, $subject, $description );

			/* mailing function to admin */
			if ( ! empty( $ct_options['car_booked_notify_admin'] ) ) {
				$subject = empty( $ct_options['car_admin_email_subject'] ) ? 'You received a booking' : $ct_options['car_admin_email_subject'];
				$description = empty( $ct_options['car_admin_email_description'] ) ? 'Booking Details' : $ct_options['car_admin_email_description'];

				foreach ( $variables as $variable ) {
					$subject = str_replace( "[" . $variable . "]", $$variable, $subject );
					$description = str_replace( "[" . $variable . "]", $$variable, $description );
				}

				ct_send_mail( $site_name, $admin_email, $admin_email, $subject, $description );
			}
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'ct_car_get_search_result_count' ) ) {
	function ct_car_get_search_result_count( $args ) {
		global $ct_options, $wpdb;

		$car_type = array();
		$price_filter = array();
		$rating_filter = array();
		$facility_filter = array();
		extract( $args );
		$tbl_posts = esc_sql( $wpdb->posts );
		$tbl_postmeta = esc_sql( $wpdb->postmeta );
		$tbl_terms = esc_sql( $wpdb->prefix . 'terms' );
		$tbl_term_taxonomy = esc_sql( $wpdb->prefix . 'term_taxonomy' );
		$tbl_term_relationships = esc_sql( $wpdb->prefix . 'term_relationships' );
		$temp_tbl_name = ct_get_temp_table_name();
		$result = array();

		if ( $by == 'car_type' ) {

			$sql = "SELECT tt.term_id as car_type, COUNT(*) AS counts FROM {$temp_tbl_name} AS t1  
					INNER JOIN {$tbl_posts} post_s1 ON (t1.car_id = post_s1.ID) AND (post_s1.post_status = 'publish') AND (post_s1.post_type = 'car')
					INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = t1.car_id 
					INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
			$where = " WHERE 1=1 AND tt.taxonomy = 'car_type'";

			if ( ! empty( $price_filter ) && trim( implode( '', $price_filter ) ) != "" ) {
				$sql .= " LEFT JOIN {$tbl_postmeta} AS meta_price ON t1.car_id = meta_price.post_id AND meta_price.meta_key = '_car_price'";
				$price_where = array();
				$price_steps = empty( $ct_options['car_price_filter_steps'] ) ? '50,80,100' : $ct_options['car_price_filter_steps'];
				$step_arr = explode( ',', $price_steps );
				array_unshift($step_arr, 0);
				foreach ( $price_filter as $index ) {
					if ( $index < count( $step_arr ) -1 ) {
						// 80 ~ 100 case
						$price_where[] = "( cast(meta_price.meta_value as unsigned) BETWEEN " . esc_sql( $step_arr[$index] ) . " AND " . esc_sql( $step_arr[$index+1] ) . " )";
					} else {
						// 200+ case
						$price_where[] = "( cast(meta_price.meta_value as unsigned) >= " . esc_sql( $step_arr[$index] ) . " )";
					}
				}
				$where .= " AND ( " . implode( ' OR ', $price_where ) . " )";
			}

			if ( ! empty( $rating_filter ) && trim( implode( '', $rating_filter ) ) != "" ) {
				$sql .= " LEFT JOIN {$tbl_postmeta} AS meta_rating ON t1.car_id = meta_rating.post_id AND meta_rating.meta_key = '_review'";
				$where .= " AND round( cast( IFNULL( meta_rating.meta_value, 0 ) AS decimal(2,1) ) ) IN ( " . esc_sql( implode( ',', $rating_filter) ) . " )";
			}

			if ( ! empty( $facility_filter ) && trim( implode( '', $facility_filter ) ) != "" ) {
				$where .= " AND (( SELECT COUNT(1) FROM {$tbl_term_relationships} AS tr1 
						INNER JOIN {$tbl_term_taxonomy} AS tt1 ON ( tr1.term_taxonomy_id= tt1.term_taxonomy_id )
						WHERE tt1.taxonomy = 'car_facility' AND tt1.term_id IN (" . esc_sql( implode( ',', $facility_filter ) ) . ") AND tr1.object_id = t1.car_id ) = " . count( $facility_filter ) . ")";
			}

			$sql .= $where . " GROUP BY tt.term_id";
			$result = $wpdb->get_results( $sql, ARRAY_A );
			$keys = array_map( function($a){return $a['car_type'];}, $result );
			$values = array_map( function($a){return $a['counts'];}, $result );
			$result = array_combine( $keys , $values );
		}
		return $result;
	}
}

if ( ! function_exists( 'ct_car_get_search_result' ) ) {
	function ct_car_get_search_result( $args ) {
		global $ct_options, $wpdb;
		$s = '';
		$date = '';
		$adults = 1;
		$kids = 0;
		$car_type = array();
		$price_filter = array();
		$rating_filter = array();
		$facility_filter = array();
		$order_by = '';
		$order = '';
		$last_no = 0;
		$per_page = ( isset( $ct_options['car_posts'] ) && is_numeric($ct_options['car_posts']) )?$ct_options['car_posts']:6;
		extract( $args );

		$order_array = array( 'ASC', 'DESC' );
		$order_by_array = array(
				'' => '',
				'price' => 'convert(meta_price.meta_value, decimal)',
				'rating' => 'meta_rating.meta_value'
			);
		if ( ! array_key_exists( $order_by , $order_by_array) ) $order_by = '';
		if ( ! in_array( $order , $order_array) ) $order = 'ASC';

		$tbl_posts = esc_sql( $wpdb->posts );
		$tbl_postmeta = esc_sql( $wpdb->postmeta );
		$tbl_terms = esc_sql( $wpdb->prefix . 'terms' );
		$tbl_term_taxonomy = esc_sql( $wpdb->prefix . 'term_taxonomy' );
		$tbl_term_relationships = esc_sql( $wpdb->prefix . 'term_relationships' );
		$tbl_icl_translations = esc_sql( $wpdb->prefix . 'icl_translations' );
		$temp_tbl_name = ct_get_temp_table_name();

		$s_query = "SELECT DISTINCT post_s1.ID AS car_id FROM {$tbl_posts} AS post_s1 LEFT JOIN {$tbl_postmeta} AS meta_c1 ON post_s1.ID = meta_c1.post_id WHERE (post_s1.post_status = 'publish') AND (post_s1.post_type = 'car')";

		// search filter
		if ( ! empty( $s ) ) {
			$s_query .= " AND ((post_s1.post_title LIKE '%{$s}%') OR (post_s1.post_content LIKE '%{$s}%') OR ( (meta_c1.meta_value LIKE '%{$s}%') AND ( meta_c1.meta_key = '_car_pickup_location' OR meta_c1.meta_key = '_car_dropoff_location' ) ) )";
		}

		// if wpml is enabled do search by default language post
		if ( defined('ICL_LANGUAGE_CODE') && ( ct_get_lang_count() > 1 ) ) {
			$s_query = "SELECT DISTINCT it2.element_id AS car_id FROM ({$s_query}) AS t0
						INNER JOIN {$tbl_icl_translations} it1 ON (it1.element_type = 'post_car') AND it1.element_id = t0.car_id
						INNER JOIN {$tbl_icl_translations} it2 ON (it2.element_type = 'post_car') AND it2.language_code='" . ct_get_default_language() . "' AND it2.trid = it1.trid ";
		}

		$sql = "SELECT t1.* FROM ( {$s_query} ) AS t1 WHERE 1 = 1 ";

		// if wpml is enabled return current language posts
		if ( defined('ICL_LANGUAGE_CODE') && ( ct_get_lang_count() > 1 ) && ( ct_get_default_language() != ICL_LANGUAGE_CODE ) ) {
			$sql = "SELECT it4.element_id AS car_id FROM ({$sql}) AS t5
					INNER JOIN {$tbl_icl_translations} it3 ON (it3.element_type = 'post_car') AND it3.element_id = t5.car_id
					INNER JOIN {$tbl_icl_translations} it4 ON (it4.element_type = 'post_car') AND it4.language_code='" . ICL_LANGUAGE_CODE . "' AND it4.trid = it3.trid";
		}

		$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS {$temp_tbl_name} AS " . $sql;
		$wpdb->query( $sql );

		$sql = " FROM {$temp_tbl_name} as t1
				INNER JOIN {$tbl_posts} post_s1 ON (t1.car_id = post_s1.ID) AND (post_s1.post_status = 'publish') AND (post_s1.post_type = 'car')";
		$where = ' WHERE 1=1';

		// car_type filter
		if ( ! empty( $car_type ) && trim( implode( '', $car_type ) ) != "" ) {
			$sql .= " INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = post_s1.ID 
					INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
			$where .= " AND tt.taxonomy = 'car_type' AND tt.term_id IN (" . esc_sql( implode( ',', $car_type ) ) . ")";
		}

		// price filter
		$sql .= " LEFT JOIN {$tbl_postmeta} AS meta_price ON post_s1.ID = meta_price.post_id AND meta_price.meta_key = '_car_price'";
		if ( ! empty( $price_filter ) && trim( implode( '', $price_filter ) ) != "" ) {
			$price_where = array();
			$price_steps = empty( $ct_options['car_price_filter_steps'] ) ? '50,80,100' : $ct_options['car_price_filter_steps'];
			$step_arr = explode( ',', $price_steps );
			array_unshift($step_arr, 0);
			foreach ( $price_filter as $index ) {
				if ( $index < count( $step_arr ) -1 ) {
					// 80 ~ 100 case
					$price_where[] = "( cast(meta_price.meta_value as unsigned) BETWEEN " . esc_sql( $step_arr[$index] ) . " AND " . esc_sql( $step_arr[$index+1] ) . " )";
				} else {
					// 200+ case
					$price_where[] = "( cast(meta_price.meta_value as unsigned) >= " . esc_sql( $step_arr[$index] ) . " )";
				}
			}
			$where .= " AND ( " . implode( ' OR ', $price_where ) . " )";
		}

		// review filter
		$sql .= " LEFT JOIN {$tbl_postmeta} AS meta_rating ON post_s1.ID = meta_rating.post_id AND meta_rating.meta_key = '_review'";
		if ( ! empty( $rating_filter ) && trim( implode( '', $rating_filter ) ) != "" ) {
			$where .= " AND round( cast( IFNULL( meta_rating.meta_value, 0 ) AS decimal(2,1) ) ) IN ( " . esc_sql( implode( ',', $rating_filter) ) . " )";
		}

		// facility filter
		if ( ! empty( $facility_filter ) && trim( implode( '', $facility_filter ) ) != "" ) {
			$where .= " AND (( SELECT COUNT(1) FROM {$tbl_term_relationships} AS tr1 
					INNER JOIN {$tbl_term_taxonomy} AS tt1 ON ( tr1.term_taxonomy_id= tt1.term_taxonomy_id )
					WHERE tt1.taxonomy = 'car_facility' AND tt1.term_id IN (" . esc_sql( implode( ',', $facility_filter ) ) . ") AND tr1.object_id = post_s1.ID ) = " . count( $facility_filter ) . ")";
		}

		$sql .= $where;
		$count_sql = "SELECT COUNT(DISTINCT t1.car_id)" . $sql;
		$count = $wpdb->get_var( $count_sql );

		if ( ! empty( $order_by ) ) {
			$sql .= " ORDER BY " . $order_by_array[$order_by] . " " . $order;
		}
		$sql .= " LIMIT {$last_no}, {$per_page};";
		$main_sql = "SELECT DISTINCT t1.car_id AS car_id" . $sql;

		$ids = $wpdb->get_results( $main_sql, ARRAY_A );

		return array( 'count' => $count, 'ids' => $ids );
	}
}

/*
 * Get cars from ids
 */
if ( ! function_exists( 'ct_car_get_cars_from_id' ) ) {
	function ct_car_get_cars_from_id( $ids ) {
		if ( ! is_array( $ids ) ) return false;
		$results = array();
		foreach( $ids as $id ) {
			$result = get_post( $id );
			if ( ! empty( $result ) && ! is_wp_error( $result ) ) {
				if ( $result->post_type == 'car' ) $results[] = $result;
			}
		}
		return $results;
	}
}

/*
 * Get discounted(hot) cars and return data
 */
if ( ! function_exists( 'ct_car_get_hot_cars' ) ) {
    function ct_car_get_hot_cars( $count = 10, $exclude_ids, $car_type=array() ) {
        $args = array(
            'post_type'  => 'car',
            'orderby'    => 'rand',
            'posts_per_page' => $count,
            'suppress_filters' => 0,
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => '_car_hot',
                    'value'   => '1',
                ),
                array(
                    'key'     => '_car_discount_rate',
                    'value'   => array( 0, 100 ),
                    'type'    => 'numeric',
                    'compare' => 'BETWEEN',
                )
            ),
        );

        if ( ! empty( $car_type ) ) {
            if ( is_numeric( $car_type[0] ) ) {
                $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'car_type',
                            'field' => 'term_id',
                            'terms' => $car_type
                            )
                    );
            } else {
                $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'car_type',
                            'field' => 'name',
                            'terms' => $car_type
                            )
                    );
            }
        }
        return get_posts( $args );
    }
}

/*
 * Get special( latest or featured ) cars and return data
 */
if ( ! function_exists( 'ct_car_get_special_cars' ) ) {
	function ct_car_get_special_cars( $type = 'latest', $count = 6, $exclude_ids, $car_type = array() ) {
		$args = array(
				'post_type'  => 'car',
				'suppress_filters' => 0,
				'posts_per_page' => $count,
				'post_status' => 'publish',
			);

		if ( ! empty( $exclude_ids ) ) {
			$args['post__not_in'] = $exclude_ids;
		}

		if ( ! empty( $car_type ) ) {
			if ( is_numeric( $car_type[0] ) ) {
				$args['tax_query'] = array(
						array(
							'taxonomy' => 'car_type',
							'field' => 'term_id',
							'terms' => $car_type
							)
					);
			} else {
				$args['tax_query'] = array(
						array(
							'taxonomy' => 'car_type',
							'field' => 'name',
							'terms' => $car_type
							)
					);
			}
		}

		if ( $type == 'featured'  ) {
			$args = array_merge( $args, array(
				'orderby'    => 'rand',
				'meta_key'     => '_car_featured',
				'meta_value'   => '1',
			) );

			return get_posts( $args );

		} elseif ( $type == 'latest' ) {
			$args = array_merge( $args, array(
				'orderby' => 'post_date',
				'order' => 'DESC',
			) );

			return get_posts( $args );

		} elseif ( $type == 'popular' ) {
			global $wpdb;

			$tbl_postmeta = esc_sql( $wpdb->prefix . 'postmeta' );
			$tbl_terms = esc_sql( $wpdb->prefix . 'terms' );
			$tbl_term_taxonomy = esc_sql( $wpdb->prefix . 'term_taxonomy' );
			$tbl_term_relationships = esc_sql( $wpdb->prefix . 'term_relationships' );

			$date = date( 'Y-m-d', strtotime( '-30 days' ) );

			$sql = 'SELECT car_id, SUM(booking.adults) AS booking_count FROM ' . CT_CAR_BOOKINGS_TABLE . ' AS booking
					INNER JOIN ' . CT_ORDER_TABLE . ' as _order ON _order.id = booking.order_id';
			$where = ' WHERE (_order.status <> "cancelled") AND (_order.created > %s)';

			if ( ! empty( $car_type ) ) {
				$sql .= " INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = t1.car_id 
						INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
				$where .= " AND tt.taxonomy = 'car_type' AND tt.term_id =" . esc_sql( $car_type ) . ")";
			}

			$sql .= $where . ' GROUP BY booking.car_id ORDER BY booking_count desc LIMIT %d';
			$sql = $wpdb->prepare( $sql, $date, $count );

			$popular_car = $wpdb->get_results( $sql );

			$result = array();
			if ( ! empty( $popular_car ) ) {
				foreach ( $popular_car as $car ) {
					if ( $car_obj = get_post( ct_car_clang_id( $car->car_id ) ) ) { 
						$result[] = $car_obj;
					}
				}
			}

			// if booked room number in last month is smaller than count then add latest car
			if ( count( $result ) < $count ) {
				foreach ( $result as $car ) {
					$exclude_ids[] = $car->ID;
				}
				$result = array_merge( $result, ct_car_get_special_cars( 'latest', $count - count( $result ), $exclude_ids, $car_type ) );
			}

			return $result;
		}
	}
}

if ( ! function_exists( 'ct_get_car_available_days' ) ) {
	function ct_get_car_available_days( $post_id ) {
		$monday_available = get_post_meta( $post_id, '_car_monday_available', true );
		$tuesday_available = get_post_meta( $post_id, '_car_tuesday_available', true );
		$wednesday_available = get_post_meta( $post_id, '_car_wednesday_available', true );
		$thursday_available = get_post_meta( $post_id, '_car_thursday_available', true );
		$friday_available = get_post_meta( $post_id, '_car_friday_available', true );
		$saturday_available = get_post_meta( $post_id, '_car_saturday_available', true );
		$sunday_available = get_post_meta( $post_id, '_car_sunday_available', true );

		$car_available_days = array();
		
		if ( ! empty( $monday_available ) ) {
			$car_available_days[] = 1;
		}
		if ( ! empty( $tuesday_available ) ) {
			$car_available_days[] = 2;
		}
		if ( ! empty( $wednesday_available ) ) {
			$car_available_days[] = 3;
		}
		if ( ! empty( $thursday_available ) ) {
			$car_available_days[] = 4;
		}
		if ( ! empty( $friday_available ) ) {
			$car_available_days[] = 5;
		}
		if ( ! empty( $saturday_available ) ) {
			$car_available_days[] = 6;
		}
		if ( ! empty( $sunday_available ) ) {
			$car_available_days[] = 0;
		}

		return $car_available_days;
	}
}

/*
 * get car price array for week 
 */
if ( ! function_exists( 'ct_car_get_price_per_day' ) ) {
	function ct_car_get_price_per_day( $post_id ) {

		$prices = array();
		
		$person_price = get_post_meta( $post_id, '_car_price', true );
		if ( empty( $person_price ) ) {
			$person_price = 0;
		}

		$prices[0] = get_post_meta( $post_id, '_car_sunday_price', true );
		if ( empty( $prices[0] ) ) {
			$prices[0] = $person_price;
		}
		$prices[1] = get_post_meta( $post_id, '_car_monday_price', true );
		if ( empty( $prices[1] ) ) {
			$prices[1] = $person_price;
		}
		$prices[2] = get_post_meta( $post_id, '_car_tuesday_price', true );
		if ( empty( $prices[2] ) ) {
			$prices[2] = $person_price;
		}
		$prices[3] = get_post_meta( $post_id, '_car_wednesday_price', true );
		if ( empty( $prices[3] ) ) {
			$prices[3] = $person_price;
		}
		$prices[4] = get_post_meta( $post_id, '_car_thursday_price', true );
		if ( empty( $prices[4] ) ) {
			$prices[4] = $person_price;
		}
		$prices[5] = get_post_meta( $post_id, '_car_friday_price', true );
		if ( empty( $prices[5] ) ) {
			$prices[5] = $person_price;
		}
		$prices[6] = get_post_meta( $post_id, '_car_saturday_price', true );
		if ( empty( $prices[6] ) ) {
			$prices[6] = $person_price;
		}

		return $prices;
	}
}