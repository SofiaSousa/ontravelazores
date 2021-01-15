<?php
add_action( 'wp_enqueue_scripts', 'citytours_child_enqueue_styles', 11 );
function citytours_child_enqueue_styles() {
	$version = '20210101';

	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ), $version );
}

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
