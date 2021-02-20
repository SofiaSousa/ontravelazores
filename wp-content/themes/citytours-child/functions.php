<?php
add_action(
	'wp_footer',
	function() {
		?>
		<script>
		(function($){
			setTimeout(function(){
				$('select.mobileMenu option:contains("Filter by category")').text('<?php echo esc_attr( __( 'Filter by category', 'woocommerce' ) ); ?>');
			}, 100);
		})(jQuery);
		</script>
		<?php
	},
	99
);

// Loading theme includes.
require_once get_stylesheet_directory() . '/plugins/loader.php';
require_once get_stylesheet_directory() . '/inc/position-field.php';

/**
 * Remove related products output
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

/**
 * Allow shortcodes in product excerpts
 */
if (!function_exists('woocommerce_template_single_excerpt')) {
   function woocommerce_template_single_excerpt( $post ) {
   	   global $post;
       if ($post->post_excerpt) echo '<div itemprop="description">' . do_shortcode(wpautop(wptexturize($post->post_excerpt))) . '</div>';
   }
}

/**
 * @snippet       Change return to shop link, send to homepage instead
 * @how-to        Get CustomizeWoo.com FREE
 * @sourcecode    https://businessbloomer.com/?p=603
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.5.6
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

add_filter( 'woocommerce_return_to_shop_redirect', 'bbloomer_change_return_shop_url' );

function bbloomer_change_return_shop_url() {
return home_url();
}

add_filter( 'woocommerce_default_address_fields' , 'custom_override_default_address_fields' );
function custom_override_default_address_fields($address_fields) {


    $address_fields['address_1']['required'] = false;
    $address_fields['address_1']['placeholder'] = '';
    $address_fields['address_2']['required'] = false;
    $address_fields['address_2']['placeholder'] = '';
    $address_fields['postcode']['required'] = false;
    $address_fields['city']['required'] = false;

return $address_fields;
}

add_filter( 'woocommerce_checkout_fields' , 'remove_postcode_validation', 99 );

function remove_postcode_validation( $fields ) {

    unset($fields['billing']['billing_postcode']['validate']);
    unset($fields['shipping']['shipping_postcode']['validate']);

	return $fields;
}

add_filter( 'woocommerce_cart_totals_order_total_html', 'custom_total_message_html', 10, 1 );
function custom_total_message_html( $value ) {
    if( is_checkout() )
        $value .= __('Inc. VAT') . '<br />';

    return $value;
}


/** Disable Ajax Call from WooCommerce on front page and posts*/
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11);
function dequeue_woocommerce_cart_fragments() {
if (is_front_page() || is_single() ) wp_dequeue_script('wc-cart-fragments');
}

function woocommerce_disable_shop_page() {
    global $post;
    if (is_shop()):
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    endif;
}
add_action( 'wp', 'woocommerce_disable_shop_page' );

add_action('jigoshop\cart\save', function() {
	$_SESSION['cart_expiration_time'] = time() + (60 * 1); // 1h
});

add_action('init', function() {
	if(isset($_SESSION['cart_expiration_time']) && $_SESSION['cart_expiration_time'] < time()) {
		unset($_SESSION['cart_expiration_time']);
		setcookie('jigoshop_cart_id', '',  time() - 10, '/');
		setcookie('jigoshop_session_key', '',  time() - 10, '/');
	}
});

/**
 * @snippet       Remove Additional Information Tab @ WooCommerce Single Product Page
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.8
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

add_filter( 'woocommerce_product_tabs', 'bbloomer_remove_product_tabs', 9999 );

function bbloomer_remove_product_tabs( $tabs ) {
	unset( $tabs['additional_information'] );
	return $tabs;
}

// From parent theme - old implementation
/** ************************************************ **/

if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page(
		array(
			'page_title' => 'Settings (Tours)',
			'menu_title' => 'Settings (Tours)',
			'menu_slug'  => 'theme-general-settings',
			'capability' => 'edit_posts',
			'icon_url'   => 'dashicons-schedule',
			'redirect'   => false,
		)
	);
}

// Adicionar folha de estilo personalizada (hotfix)
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles');
function child_enqueue_styles() {
	wp_enqueue_style( 'style-exclude-autoptimize', get_stylesheet_directory_uri() . '/style-exclude-autoptimize.css', array(), '11.25');
}

// Widget for Footer logo 1.
add_action(
	'widgets_init',
	function() {
		register_sidebar(
			array(
				'name'          => __( 'Footer Widget 01 (Logo)', 'citytours' ),
				'id'            => 'footer-widget-logo-01',
				'description'   => __( 'Insira a logo para Widget 1', 'citytours' ),
				'before_widget' => '<div class="widget-content">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
);

// Widget for Footer logo 2.
add_action(
	'widgets_init',
	function() {
		register_sidebar(
			array(
				'name'          => __( 'Footer Widget 02 (Logo)', 'citytours' ),
				'id'            => 'footer-widget-logo-02',
				'description'   => __( 'Insira a logo para Widget 2', 'citytours' ),
				'before_widget' => '<div class="widget-content">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
);

// Widget for Footer logo 3.
add_action(
	'widgets_init',
	function() {
		register_sidebar(
			array(
				'name'          => __( 'Footer Widget 03 (Logo)', 'citytours' ),
				'id'            => 'footer-widget-logo-03',
				'description'   => __( 'Insira a logo para Widget 3', 'citytours' ),
				'before_widget' => '<div class="widget-content">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
);

// Widget for Footer logo 4.
add_action(
	'widgets_init',
	function() {
		register_sidebar(
			array(
				'name'          => __( 'Footer Widget 04 (Logo)', 'citytours' ),
				'id'            => 'footer-widget-logo-04',
				'description'   => __( 'Insira a logo para Widget 4', 'citytours' ),
				'before_widget' => '<div class="widget-content">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
);

// Widget for Footer Sponsors.
add_action(
	'widgets_init',
	function() {
		register_sidebar(
			array(
				'name'          => __( 'Footer Sponsors', 'citytours' ),
				'id'            => 'footer-sponsors-widget',
				'description'   => __( 'Insira o conteudo para o widget Footer Sponsors', 'citytours' ),
				'before_widget' => '<div class="widget-content text-center">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
);

if ( ! function_exists( 'ct_tour_generate_conf_mail' ) ) {
	/**
	 * To avoid sending confirmation email from theme.
	 */
	function ct_tour_generate_conf_mail() {
		return false;
	}
}

// Add heading for upload files section in cart page.
// add_action(
// 	'woocommerce_after_cart_table',
// 	function() {
// 		if ( is_plugin_active( 'customer-upload-files-for-woocommerce/customer-upload-files-for-woocommerce.php' ) ) {
			/* ?>
			<br>
			<br>
			<h3><b><?php echo esc_html__( 'Add your Azores Safe Vouchers', 'ontravelazores' ); ?></b></h3>
			<br>
			<?php */
// 		}
// 	}
// );

// Remove '(discount:-35%)' from Açores Voucher name.
// add_action(
// 	'woocommerce_cart_calculate_fees',
// 	function( $cart ) {
// 		$fees = $cart->fees_api()->get_fees();

// 		if ( isset( $fees ) && ! empty( $fees ) ) {
// 			$update_fees = false;
// 			$fee_str     = '(discount:-35%)';

// 			foreach ( $fees as &$f ) {
// 				if ( false !== strpos( $f->name, $fee_str ) ) {
// 					$update_fees = true;
// 					$f->name     = str_replace( $fee_str, '', $f->name );
// 				}
// 			}
// 		}
// 	}
// );


// Script for FB chat.
add_action(
	'wp_footer',
	function() {
		echo '<!-- Load Facebook SDK for JavaScript -->
		<div id="fb-root"></div>
		<script>
		window.fbAsyncInit = function() {
			FB.init({
				xfbml            : true,
				version          : "v9.0"
			});
		};

		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, "script", "facebook-jssdk"));</script>

		<!-- Your Chat Plugin code -->
		<div class="fb-customerchat"
			attribution=install_email
			page_id="1757371964491287"
			theme_color="#1cbbb4">
		</div>';
	}
);

// EGO API script.
// _egoiaq.push(["setClientId", "419232"]);
// _egoiaq.push(["setTrackerUrl", u+"collect"]);
add_action(
	'wp_footer',
	function() {
		echo '<script type="text/javascript">
		var _egoiaq = _egoiaq || [];
		(function(){
			var u=(("https:" == document.location.protocol) ? "https://egoimmerce.e-goi.com/" : "http://egoimmerce.e-goi.com/");
			var u2=(("https:" == document.location.protocol) ? "https://cdn-te.e-goi.com/" : "http://cdn-te.e-goi.com/");

			_egoiaq.push(["trackPageView"]);
			_egoiaq.push(["enableLinkTracking"]);
			var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0];
			g.type="text/javascript";
			g.defer=true;
			g.async=true;
			g.src=u2+"egoimmerce.js";
			s.parentNode.insertBefore(g,s);
		})();
		</script>';
	}
);


/**
 * Sortinf search results by date.
 */
if ( ! function_exists( 'ct_tour_get_search_result' ) ) {
	function ct_tour_get_search_result( $args ) {
		global $ct_options, $wpdb;
		$s = '';
		$date = '';
		$adults = 1;
		$kids = 0;
		$tour_type = array();
		$price_filter = array();
		$rating_filter = array();
		$facility_filter = array();
		$order_by = '';
		$order = '';
		$last_no = 0;
		$per_page = ( isset( $ct_options['tour_posts'] ) && is_numeric($ct_options['tour_posts']) )?$ct_options['tour_posts']:6;
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

		$s_query = "SELECT DISTINCT post_s1.ID AS tour_id FROM {$tbl_posts} AS post_s1 WHERE (post_s1.post_status = 'publish') AND (post_s1.post_type = 'tour')";

		// search filter
		if ( ! empty( $s ) ) {
			$s_query .= " AND ((post_s1.post_title LIKE '%{$s}%') OR (post_s1.post_content LIKE '%{$s}%') )";
		}

		// if wpml is enabled do search by default language post
		if ( defined('ICL_LANGUAGE_CODE') && ( ct_get_lang_count() > 1 ) ) {
			$s_query = "SELECT DISTINCT it2.element_id AS tour_id FROM ({$s_query}) AS t0
						INNER JOIN {$tbl_icl_translations} it1 ON (it1.element_type = 'post_tour') AND it1.element_id = t0.tour_id
						INNER JOIN {$tbl_icl_translations} it2 ON (it2.element_type = 'post_tour') AND it2.language_code='" . ct_get_default_language() . "' AND it2.trid = it1.trid ";
		}

		$sql = "SELECT t1.* FROM ( {$s_query} ) AS t1 ";

		if ( ! empty( $date ) ) {
			$date = esc_sql( date( 'Y-m-d', ct_strtotime( $date ) ) );
			$day_of_week = esc_sql( date( 'w', ct_strtotime( $date ) ) );

			$sql .= " LEFT JOIN {$tbl_postmeta} AS meta_c1 ON (meta_c1.meta_key = '_tour_max_people') AND (t1.tour_id = meta_c1.post_id)";
			$sql .= " LEFT JOIN {$tbl_postmeta} AS meta_c2 ON (meta_c2.meta_key = '_tour_repeated') AND (t1.tour_id = meta_c2.post_id)";
			$sql .= " LEFT JOIN {$tbl_postmeta} AS meta_c3 ON (meta_c3.meta_key = '_tour_date') AND (t1.tour_id = meta_c3.post_id)";
			$sql .= " LEFT JOIN {$tbl_postmeta} AS meta_c4 ON (meta_c4.meta_key = '_tour_start_date') AND (t1.tour_id = meta_c4.post_id)";
			$sql .= " LEFT JOIN {$tbl_postmeta} AS meta_c5 ON (meta_c5.meta_key = '_tour_end_date') AND (t1.tour_id = meta_c5.post_id)";
			$sql .= " LEFT JOIN {$tbl_postmeta} AS meta_c6 ON (meta_c6.meta_key = '_tour_available_days') AND (t1.tour_id = meta_c6.post_id)";
			$sql .= " LEFT JOIN ( SELECT tour_booking.tour_id, SUM( tour_booking.adults ) as adults, SUM( tour_booking.kids ) as kids FROM " . CT_TOUR_BOOKINGS_TABLE . " AS tour_booking
						INNER JOIN " . CT_ORDER_TABLE . " as tour_order
						ON tour_order.id = tour_booking.order_id AND tour_order.status!='cancelled'
						WHERE tour_order.date_from = '{$date}'
						GROUP BY tour_booking.tour_id ) AS booking_info ON booking_info.tour_id = t1.tour_id";
			$sql .= " WHERE ((( meta_c2.meta_value=1 ) AND ( IFNULL(meta_c4.meta_value, '0000-00-00') < '{$date}' ) AND ( IFNULL(meta_c5.meta_value, '9999-12-31') > '{$date}' ) AND ( IFNULL(meta_c6.meta_value, '{$day_of_week}') = '{$day_of_week}' )) OR ( meta_c2.meta_value=0 AND meta_c3.meta_value='{$date}' ))
						AND ((meta_c1.meta_value IS NULL) OR (meta_c1.meta_value='') OR (meta_c1.meta_value-IFNULL(booking_info.adults, 0) > {$adults}) )";
		}

		// if wpml is enabled return current language posts
		if ( defined('ICL_LANGUAGE_CODE') && ( ct_get_lang_count() > 1 ) && ( ct_get_default_language() != ICL_LANGUAGE_CODE ) ) {
			$sql = "SELECT it4.element_id AS tour_id FROM ({$sql}) AS t5
					INNER JOIN {$tbl_icl_translations} it3 ON (it3.element_type = 'post_tour') AND it3.element_id = t5.tour_id
					INNER JOIN {$tbl_icl_translations} it4 ON (it4.element_type = 'post_tour') AND it4.language_code='" . ICL_LANGUAGE_CODE . "' AND it4.trid = it3.trid";
		}

		$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS {$temp_tbl_name} AS " . $sql;
		$wpdb->query( $sql );

		$sql = " FROM {$temp_tbl_name} as t1
				INNER JOIN {$tbl_posts} post_s1 ON (t1.tour_id = post_s1.ID) AND (post_s1.post_status = 'publish') AND (post_s1.post_type = 'tour')";
		$where = ' WHERE 1=1';

		// tour_type filter
		if ( ! empty( $tour_type ) && trim( implode( '', $tour_type ) ) != "" ) {
			$sql .= " INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = post_s1.ID
					INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
			$where .= " AND tt.taxonomy = 'tour_type' AND tt.term_id IN (" . esc_sql( implode( ',', $tour_type ) ) . ")";
		}

		// price filter
		$sql .= " LEFT JOIN {$tbl_postmeta} AS meta_price ON post_s1.ID = meta_price.post_id AND meta_price.meta_key = '_tour_price'";
		if ( ! empty( $price_filter ) && trim( implode( '', $price_filter ) ) != "" ) {
			$price_where = array();
			$price_steps = empty( $ct_options['tour_price_filter_steps'] ) ? '50,80,100' : $ct_options['tour_price_filter_steps'];
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
					WHERE tt1.taxonomy = 'tour_facility' AND tt1.term_id IN (" . esc_sql( implode( ',', $facility_filter ) ) . ") AND tr1.object_id = post_s1.ID ) = " . count( $facility_filter ) . ")";
		}

		$sql .= $where;
		$count_sql = "SELECT COUNT(DISTINCT t1.tour_id)" . $sql;
		$count = $wpdb->get_var( $count_sql );

		// sorting by date and selected filter.
		if ( ! empty( $order_by ) ) {
			$sql .= " ORDER BY " . $order_by_array[$order_by] . " " . $order . ", post_s1.post_date DESC";
		} else {
			$sql .= " ORDER BY post_s1.post_date DESC";
		}
		$sql .= " LIMIT {$last_no}, {$per_page};";
		$main_sql = "SELECT DISTINCT t1.tour_id AS tour_id" . $sql;

		//$main_sql = " SELECT tour_id, post_date FROM ({$main_sql}) AS tttt, {$tbl_posts} tttt1 WHERE tttt.tour_id = tttt1.ID ORDER BY tttt1.post_date ASC";
		//$main_sql .= " LIMIT {$last_no}, {$per_page};";

		$ids = $wpdb->get_results( $main_sql, ARRAY_A );

		return array( 'count' => $count, 'ids' => $ids );
	}
}
