<?php

add_action( 'wp_enqueue_scripts', 'citytours_child_enqueue_styles' );
function citytours_child_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ), wp_get_theme()->get('Version') );
}

// Loading theme includes.
require_once get_stylesheet_directory() . '/plugins/loader.php';

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
		'page_title' => 'Settings (Tours)',
		'menu_title' => 'Settings (Tours)',
		'menu_slug'  => 'theme-general-settings',
		'capability' => 'edit_posts',
		'icon_url'   => 'dashicons-schedule',
		'redirect'   => false
	));
}


function translate_woocommerce_emails($text, $code_language) {

}

// Adicionar folha de estilo personalizada (hotfix)
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles');
function child_enqueue_styles() {
	wp_enqueue_style( 'style-exclude-autoptimize', get_stylesheet_directory_uri() . '/style-exclude-autoptimize.css', array(), '11.25');
}

// Criar um novo widget personalizado para as imagens do footer (logos)
add_action( 'widgets_init', 'custom_sidebar_footer_column01' );
function custom_sidebar_footer_column01() {
	register_sidebar(
		array (
			'name'          => __( 'Footer Widget 01 (Logo)', 'ontravelazores' ),
			'id'            => 'footer-widget-logo-01',
			'description'   => __( 'Insira a logo para Widget 1', 'ontravelazores' ),
			'before_widget' => '<div class="widget-content">',
			'after_widget'  => "</div>",
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}

add_action( 'widgets_init', 'custom_sidebar_footer_column02' );
function custom_sidebar_footer_column02() {
	register_sidebar(
		array (
			'name'          => __( 'Footer Widget 02 (Logo)', 'ontravelazores' ),
			'id'            => 'footer-widget-logo-02',
			'description'   => __( 'Insira a logo para Widget 2', 'ontravelazores' ),
			'before_widget' => '<div class="widget-content">',
			'after_widget'  => "</div>",
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}

add_action( 'widgets_init', 'custom_sidebar_footer_column03' );
function custom_sidebar_footer_column03() {
	register_sidebar(
		array (
			'name'          => __( 'Footer Widget 03 (Logo)', 'ontravelazores' ),
			'id'            => 'footer-widget-logo-03',
			'description'   => __( 'Insira a logo para Widget 3', 'ontravelazores' ),
			'before_widget' => '<div class="widget-content">',
			'after_widget'  => "</div>",
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}

add_action( 'widgets_init', 'custom_sidebar_footer_column04' );
function custom_sidebar_footer_column04() {
	register_sidebar(
		array (
			'name'          => __( 'Footer Widget 04 (Logo)', 'ontravelazores' ),
			'id'            => 'footer-widget-logo-04',
			'description'   => __( 'Insira a logo para Widget 4', 'ontravelazores' ),
			'before_widget' => '<div class="widget-content">',
			'after_widget'  => "</div>",
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}

if ( ! function_exists( 'ct_tour_generate_conf_mail' ) ) {
	/**
	 * To avoid sending confirmation email from theme.
	 */
	function ct_tour_generate_conf_mail() {
		return false;
	}
}

add_action(
	'woocommerce_after_cart_table',
	function() {
		if ( is_plugin_active( 'customer-upload-files-for-woocommerce/customer-upload-files-for-woocommerce.php' ) ) {
			?>
			<br>
			<br>
			<h3><?php echo esc_html__( 'Adicione o seu voucher Açores Seguro', 'ontravelazores' ); ?></h3>
			<br>
			<?php
		}
	}
);
