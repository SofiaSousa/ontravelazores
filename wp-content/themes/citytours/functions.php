<?php
if ( ! session_id() ) {
session_start();
}

//constants
define( 'CT_VERSION', '3.2.4' );
define( 'CT_DB_VERSION', '1.5.1' );
define( 'CT_TEMPLATE_DIRECTORY_URI', get_template_directory_uri() );
define( 'CT_IMAGE_URL', CT_TEMPLATE_DIRECTORY_URI . '/img' );
define( 'CT_INC_DIR', get_template_directory() . '/inc' );

global $wpdb;
define( 'CT_HOTEL_VACANCIES_TABLE', $wpdb->prefix . 'ct_hotel_vacancies' );
define( 'CT_HOTEL_BOOKINGS_TABLE', $wpdb->prefix . 'ct_hotel_bookings' );
define( 'CT_HOTEL_VACANCY_PRICE_TABLE', $wpdb->prefix . 'ct_hotel_vacancy_price' );
define( 'CT_REVIEWS_TABLE', $wpdb->prefix . 'ct_reviews' );
define( 'CT_ADD_SERVICES_TABLE', $wpdb->prefix . 'ct_add_services' );
define( 'CT_ADD_SERVICES_BOOKINGS_TABLE', $wpdb->prefix . 'ct_add_service_bookings' );
define( 'CT_TOUR_SCHEDULES_TABLE', $wpdb->prefix . 'ct_tour_schedules' );
define( 'CT_TOUR_SCHEDULE_META_TABLE', $wpdb->prefix . 'ct_tour_schedule_meta' );
define( 'CT_TOUR_BOOKINGS_TABLE', $wpdb->prefix . 'ct_tour_bookings' );
define( 'CT_CAR_BOOKINGS_TABLE', $wpdb->prefix . 'ct_car_bookings' );
define( 'CT_CURRENCIES_TABLE', $wpdb->prefix . 'ct_currencies' );
define( 'CT_ORDER_TABLE', $wpdb->prefix . 'ct_order' );
define( 'CT_MODE', 'dev' );

if ( ! isset( $redux_demo ) ) {
    require_once( CT_INC_DIR . '/lib/redux-framework/config.php' );
}

// WooCommerce Integration
require_once( CT_INC_DIR . '/woocommerce/woocommerce.php');

require_once( CT_INC_DIR . '/admin/main.php');
require_once( CT_INC_DIR . '/lib/multiple_sidebars.php' );
require_once( CT_INC_DIR . '/functions/main.php' );
require_once( CT_INC_DIR . '/js_composer/init.php' );
require_once( CT_INC_DIR . '/frontend/main.php');

// Translation
load_theme_textdomain( 'citytours', get_template_directory() . '/languages' );

//theme supports
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'woocommerce' );

global $wp_version;

if ( version_compare( $wp_version, '4.1', '>=' ) ) {
	add_theme_support( 'title-tag' );
	add_filter( 'wp_title', 'ct_wp_title', 10, 2 );
} else {
	add_filter( 'wp_title', 'ct_wp_title_old', 10, 2 );
}

if ( ! isset( $content_width ) ) $content_width = 900;

/* Add custom Image sizes */
add_image_size( 'ct-room-gallery', 200, 133, true );
add_image_size( 'ct-list-thumb', 400, 267, true );
add_image_size( 'ct-map-thumb', 280, 140, true );
add_image_size( 'ct-thumbnails', 270, 150, true );

//actions
add_action( 'init', 'ct_init' );
add_action( 'wp_enqueue_scripts', 'ct_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'ct_admin_scripts' );
add_action( 'wp_head', 'ct_load_custom_styles', 99 );
add_action( 'wp_footer', 'ct_inline_script' );
add_action( 'tgmpa_register', 'ct_register_required_plugins' );
add_action( 'admin_menu', 'ct_remove_redux_menu',12 );
add_action( 'widgets_init', 'ct_register_sidebar' );
add_action( 'wp_login_failed', 'ct_login_failed' );
add_action( 'lost_password', 'ct_lost_password' );
add_action( 'comment_form_before', 'ct_enqueue_comment_reply' );

add_filter( '404_template', 'ct_show404' );
add_filter( 'authenticate', 'ct_authenticate', 1, 3);
add_filter( 'get_default_comment_status', 'ct_open_comments_for_myposttype', 10, 3 );

remove_action( 'admin_enqueue_scripts', 'wp_auth_check_load' );



/** ************************************************ **/

// Ir pegar nos IDS dos posts que existirem campos personalizados (tours)
function check_product_category(){
	$arr_ids = array();
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		$product_title = get_the_title($product_id);
		$product_permalink = get_permalink($product_id);

		$args = array('post_type'=>'tour', 'posts_per_page'=>'-1');
		$result = new WP_Query($args);
		if ($result->have_posts()) :
			while ($result->have_posts()) : $result->the_post();
				$tour_permalink = get_permalink();
				$tour_id = get_the_ID();
				if ($tour_permalink == $product_permalink) {
					$check_exists_fields = have_rows('tours_extraFields_repeater',  $tour_id);
					if ($check_exists_fields) {
						array_push($arr_ids, $tour_id);
					}
					break;
				}
			endwhile;
		endif;
    }
    return $arr_ids;
}


// Adicionar campos personalizados no checkout
add_action( 'woocommerce_before_order_notes', 'sdc_custom_checkout_field' );
function sdc_custom_checkout_field( $checkout ) {
	$wpml_language_code = ICL_LANGUAGE_CODE;
	$products_with_extrafields_ids = check_product_category();

	if ( count($products_with_extrafields_ids) ) {

		foreach ($products_with_extrafields_ids as $key => $value) {
			/*
			if ($wpml_language_code == 'pt-pt') {
				echo '<div class="col-sm-12"><div class="default-title"><h2>' . __( 'Mais informações - "'. get_the_title($value) . '"</h2></div></div><div>');
			} else if ($wpml_language_code == 'en') {
				echo '<div class="col-sm-12"><div class="default-title"><h2>' . __( 'More information - "'. get_the_title($value) . '"</h2></div></div><div>');
			}
			*/

			$checkout_mais_informacoes = get_field('checkout_mais_informacoes', 'option');

			if ( !empty($checkout_mais_informacoes) ):
				echo '<div class="col-sm-12"><div class="default-title"><h2>' . $checkout_mais_informacoes . ' - "' .  get_the_title($value) . '"</h2></div></div><div>';
			else:
				echo '<div class="col-sm-12"><div class="default-title"><h2>' . get_the_title($value) . '</h2></div></div><div>';
			endif;

				if( have_rows('tours_extraFields_repeater', $value) ):
					while ( have_rows('tours_extraFields_repeater', $value) ) : the_row();
						$title = get_sub_field('tours_extraFields_title', $value);
						$type = get_sub_field('tours_extraFields_type', $value);
						$required = get_sub_field('tours_extraFields_required', $value);

						$type = ($type == 'number' ? 'number' : 'text');
						$required = ($required == 'yes' ? true : false);

						$field_name = substr( md5( serialize( $value.$title ) ), 0, 8 );

						woocommerce_form_field( $field_name, array(
							'type'  => $type,
							'required'  => $required,
							'class' => array( $field_name . ' form-row-wide' ),
							'label' => __( $title ),
						), $checkout->get_value( $field_name ) );

					endwhile;
				endif;
			echo '</div>';
		}
    }
}

// Validação dos campos personalizados
add_action( 'woocommerce_checkout_process', 'bbloomer_validate_new_checkout_field' );
function bbloomer_validate_new_checkout_field() {
	$wpml_language_code = ICL_LANGUAGE_CODE;
	$products_with_extrafields_ids = check_product_category();
	if ( count($products_with_extrafields_ids) ) {
		foreach ($products_with_extrafields_ids as $key => $value) {
			if( have_rows('tours_extraFields_repeater', $value) ):
				while ( have_rows('tours_extraFields_repeater', $value) ) : the_row();
					$title = get_sub_field('tours_extraFields_title', $value);
					$type = get_sub_field('tours_extraFields_type', $value);
					$required = get_sub_field('tours_extraFields_required', $value);

					$required = ($required == 'yes' ? true : false);
					$field_name = substr( md5( serialize( $value.$title ) ), 0, 8 );

					if ( $required && !$_POST[$field_name] ) {
						if ($wpml_language_code == 'pt-pt') {
							wc_add_notice( 'Por favor preencha o campo "'.$title.'"', 'error' );
						} else if ($wpml_language_code == 'en') {
							wc_add_notice( 'Please fill in the "'.$title.'" field', 'error' );
						}
					}
				endwhile;
			endif;
		}
	}
}

/*Save to DB as post meta*/
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );
function my_custom_checkout_field_update_order_meta( $order_id ) {
	$products_with_extrafields_ids = check_product_category();

	if ( count($products_with_extrafields_ids) ) {
		foreach ($products_with_extrafields_ids as $key => $value) {
			if( have_rows('tours_extraFields_repeater', $value) ):
				while ( have_rows('tours_extraFields_repeater', $value) ) : the_row();
					$title = get_sub_field('tours_extraFields_title', $value);
					$type = get_sub_field('tours_extraFields_type', $value);
					$required = get_sub_field('tours_extraFields_required', $value);

					$field_name = substr( md5( serialize( $value.$title ) ), 0, 8 );

					if ( ! empty( $_POST[$field_name] ) ) {
						update_post_meta( $order_id, $field_name, sanitize_text_field( $_POST[$field_name] ) );
					}
				endwhile;
			endif;
		}
    }
}

// Mostrar os valores dos campos personalizados - na Administração (Woocommerce)
add_action( 'woocommerce_admin_order_data_after_billing_address', 'bbloomer_show_new_checkout_field_order', 10, 1 );
function bbloomer_show_new_checkout_field_order( $order ) {

	$order_id = $order->get_id();

	foreach ($order->get_items() as $item_key => $item ):
		$item_id 	= $item->get_id();
		$product    = $item->get_product();
		$product_id	= $item->get_product_id();

		$tour_id = 0;

		$product_title = get_the_title($product_id);
		$product_permalink = get_permalink($product_id);

		$args = array('post_type'=>'tour', 'posts_per_page'=>'-1');
		$result = new WP_Query($args);
		if ($result->have_posts()) :
			while ($result->have_posts()) : $result->the_post();
				$tour_permalink	= get_permalink();
				$temp_tour_id 		= get_the_ID();
				if ($tour_permalink == $product_permalink) {
					$check_exists_fields = have_rows('tours_extraFields_repeater',  $temp_tour_id);
					if ($check_exists_fields) {
						$tour_id = $temp_tour_id;
					}
					break;
				}
			endwhile;
		endif;

		if( have_rows('tours_extraFields_repeater', $tour_id) ):
			while ( have_rows('tours_extraFields_repeater', $tour_id) ) : the_row();

				$title = get_sub_field('tours_extraFields_title', $tour_id);
				$type = get_sub_field('tours_extraFields_type', $tour_id);
				$required = get_sub_field('tours_extraFields_required', $tour_id);
				$field_name = substr( md5( serialize( $tour_id.$title ) ), 0, 8 );

				if ( get_post_meta( $order_id, $field_name, true ) ) echo '<p><strong>'.$title.':</strong> ' . get_post_meta( $order_id, $field_name, true ) . '</p>';

			endwhile;
		endif;

	endforeach;
}


add_action( 'woocommerce_email_after_order_table', 'bbloomer_show_new_checkout_field_emails', 20, 4 );
function bbloomer_show_new_checkout_field_emails( $order, $sent_to_admin, $plain_text, $email ) {

	$order_id = $order->get_id();

	$array_fields = array();

	foreach ($order->get_items() as $item_key => $item ):
		$item_id 	= $item->get_id();
		$product    = $item->get_product();
		$product_id	= $item->get_product_id();

		$tour_id = 0;

		$product_title = get_the_title($product_id);
		$product_permalink = get_permalink($product_id);

		$args = array('post_type'=>'tour', 'posts_per_page'=>'-1', 'suppress_filters'=>true);
		$result = new WP_Query($args);
		if ($result->have_posts()) :
			while ($result->have_posts()) : $result->the_post();
				$tour_permalink	= get_permalink();
				$temp_tour_id 		= get_the_ID();
				if ($tour_permalink == $product_permalink) {
					$check_exists_fields = have_rows('tours_extraFields_repeater',  $temp_tour_id);
					if ($check_exists_fields) {
						$tour_id = $temp_tour_id;
					}
					break;
				}
			endwhile;
		endif;

		if( have_rows('tours_extraFields_repeater', $tour_id) ):
			while ( have_rows('tours_extraFields_repeater', $tour_id) ) : the_row();

				$title = get_sub_field('tours_extraFields_title', $tour_id);
				$type = get_sub_field('tours_extraFields_type', $tour_id);
				$required = get_sub_field('tours_extraFields_required', $tour_id);
				$field_name = substr( md5( serialize( $tour_id.$title ) ), 0, 8 );
				$field_value = get_post_meta( $order_id, $field_name, true );

				if ( get_post_meta( $order_id, $field_name, true ) ) {
					echo '<p><strong>'.$title.':</strong> ' . $field_value . '</p>';
					array_push( $array_fields, array($title, $field_value) );
				}

			endwhile;
		endif;

	endforeach;

}



$wpml_language_code = ICL_LANGUAGE_CODE;

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Settings (Tours)',
		'menu_title'	=> 'Settings (Tours)',
		'menu_slug' 	=> 'theme-general-settings',
		'capability' 	=> 'edit_posts',
		'icon_url' 		=> 'dashicons-schedule',
		'redirect'		=> false
	));

	acf_add_options_page(array(
		'page_title' 	=> 'Woocommerce (Emails)',
		'menu_title'	=> 'Woocommerce (Emails)',
		'menu_slug' 	=> 'theme-general-settings-wc',
		'capability' 	=> 'edit_posts',
		'icon_url' 		=> 'dashicons-schedule',
		'redirect'		=> false
	));
}


function translate_woocommerce_emails($text, $code_language) {

}



// (Woocommerce) E-mails enviados da encomenda
add_action('woocommerce_checkout_create_order_line_item', 'save_custom_fields_as_order_item_meta', 20, 4);
function save_custom_fields_as_order_item_meta($item, $cart_item_key, $values, $order) {
	$wpml_language_code = ICL_LANGUAGE_CODE;
	$product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
	$add_services = get_post_meta( $product_id, '_ct_add_service' );
	$add_services = $add_services[0];

	$booking_details = get_post_meta( $product_id, '_ct_booking_info', true );
	$booking_date = get_post_meta( $product_id, '_ct_booking_date', true );
	$booking_time = get_post_meta( $product_id, '_ct_booking_time', true );

	/*
	if ($wpml_language_code == 'pt-pt') {
		$item->update_meta_data( __('Data', $text_domain), $booking_date );
		$item->update_meta_data( __('Hora', $text_domain), $booking_time );
		$item->update_meta_data( __('Adultos (+ 11 anos)', $text_domain), $booking_details['adults'] );
		$item->update_meta_data( __('Crianças (3 - 10 anos)', $text_domain), $booking_details['kids'] );
		$item->update_meta_data( __('Bebés (0 - 2 anos)', $text_domain), $booking_details['infants'] );
	} else if ($wpml_language_code == 'en') {
		$item->update_meta_data( __('Date', $text_domain), $booking_date );
		$item->update_meta_data( __('Time', $text_domain), $booking_time );
		$item->update_meta_data( __('Adults (+ 11 Years)', $text_domain), $booking_details['adults'] );
		$item->update_meta_data( __('Childrens (3 - 10 Years)', $text_domain), $booking_details['kids'] );
		$item->update_meta_data( __('Infants (0 - 2 Years)', $text_domain), $booking_details['infants'] );
	}
	*/

	$wc_email_data 				= get_field('data', 'option');
	$wc_email_hora				= get_field('hora', 'option');
	$wc_email_adultos11anos 	= get_field('adultos_11_anos', 'option');
	$wc_email_criancas310anos	= get_field('criancas_3_10_anos', 'option');
	$wc_email_bebes02anos		= get_field('bebes_0_2_anos', 'option');

	$wc_email_data 				= (!empty($wc_email_data) ? $wc_email_data : 'Data' );
	$wc_email_hora				= (!empty($wc_email_hora) ? $wc_email_hora : 'Hora' );
	$wc_email_adultos11anos 	= (!empty($wc_email_adultos11anos) ? $wc_email_adultos11anos : 'Adultos (+ 11 anos)' );
	$wc_email_criancas310anos	= (!empty($wc_email_criancas310anos) ? $wc_email_criancas310anos : 'Crianças (3 - 10 anos)' );
	$wc_email_bebes02anos		= (!empty($wc_email_bebes02anos) ? $wc_email_bebes02anos : 'Bebés (0 - 2 anos)' );

	$item->update_meta_data( $wc_email_data, $booking_date );
	$item->update_meta_data( $wc_email_hora, $booking_time );
	$item->update_meta_data( $wc_email_adultos11anos, $booking_details['adults'] );
	$item->update_meta_data( $wc_email_criancas310anos, $booking_details['kids'] );
	$item->update_meta_data( $wc_email_bebes02anos, $booking_details['infants'] );


	if ( ! empty( $add_services ) ) :

		foreach ( $add_services as $service ) :
			$service_id = esc_attr( $service['service_id'] );
			$title = esc_attr( $service['title'] );
			$quantity = esc_attr( $service['qty'] );
			$price = esc_attr( $service['price'] );

			$item->update_meta_data( __($title . '&nbsp;(+'.$price.'&nbsp;€)', $text_domain), $quantity );

		endforeach;

	endif;
}

// Adicionar folha de estilo personalizada (hotfix)
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles');
function child_enqueue_styles() {
	wp_enqueue_style( 'style-exclude-autoptimize', get_template_directory_uri() . '/style-exclude-autoptimize.css', array(), '11.25');
}


// Criar um novo widget personalizado para as imagens do footer (logos)
add_action( 'widgets_init', 'custom_sidebar_footer_column01' );
function custom_sidebar_footer_column01() {
    register_sidebar(
        array (
            'name' => __( 'Footer Widget 01 (Logo)', 'ontravelazores' ),
            'id' => 'footer-widget-logo-01',
            'description' => __( 'Insira a logo para Widget 1', 'ontravelazores' ),
            'before_widget' => '<div class="widget-content">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );
}

add_action( 'widgets_init', 'custom_sidebar_footer_column02' );
function custom_sidebar_footer_column02() {
    register_sidebar(
        array (
            'name' => __( 'Footer Widget 02 (Logo)', 'ontravelazores' ),
            'id' => 'footer-widget-logo-02',
            'description' => __( 'Insira a logo para Widget 2', 'ontravelazores' ),
            'before_widget' => '<div class="widget-content">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );
}

add_action( 'widgets_init', 'custom_sidebar_footer_column03' );
function custom_sidebar_footer_column03() {
    register_sidebar(
        array (
            'name' => __( 'Footer Widget 03 (Logo)', 'ontravelazores' ),
            'id' => 'footer-widget-logo-03',
            'description' => __( 'Insira a logo para Widget 3', 'ontravelazores' ),
            'before_widget' => '<div class="widget-content">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );
}

add_action( 'widgets_init', 'custom_sidebar_footer_column04' );
function custom_sidebar_footer_column04() {
    register_sidebar(
        array (
            'name' => __( 'Footer Widget 04 (Logo)', 'ontravelazores' ),
            'id' => 'footer-widget-logo-04',
            'description' => __( 'Insira a logo para Widget 4', 'ontravelazores' ),
            'before_widget' => '<div class="widget-content">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );
}
