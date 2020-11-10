<?php
/*
 * Plugin Name: Customer Upload Files for WooCommerce
   Description: Simple interface to upload files from a page.
   Author: FME Addons
   TextDomain: Fme_Upload_Files
   Version: 1.0.1
* Woo: 6443244:f4874aedbc2ae309f8a8579427a1db9e
*/
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

/**
 * Check if WooCommerce is active
 * if wooCommerce is not active ext Tabs module will not work.
 **/
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) ) {

	/**
	* Check WooCommerce is installed and active
	*
	* This function will check that woocommerce is installed and active
	* and returns true or false
	*
	* @return true or false
	*/
	function fme_upload_files_admin_notice() {

			// Deactivate the plugin
				deactivate_plugins(__FILE__);

				$allowed_tags = array(
				'a' => array(
					'class' => array(),
					'href'  => array(),
					'rel'   => array(),
					'title' => array(),
				),
				'abbr' => array(
					'title' => array(),
				),
				'b' => array(),
				'blockquote' => array(
					'cite'  => array(),
				),
				'cite' => array(
					'title' => array(),
				),
				'code' => array(),
				'del' => array(
					'datetime' => array(),
					'title' => array(),
				),
				'dd' => array(),
				'div' => array(
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'dl' => array(),
				'dt' => array(),
				'em' => array(),
				'h1' => array(),
				'h2' => array(),
				'h3' => array(),
				'h4' => array(),
				'h5' => array(),
				'h6' => array(),
				'i' => array(),
				'img' => array(
					'alt'    => array(),
					'class'  => array(),
					'height' => array(),
					'src'    => array(),
					'width'  => array(),
				),
				'li' => array(
					'class' => array(),
				),
				'ol' => array(
					'class' => array(),
				),
				'p' => array(
					'class' => array(),
				),
				'q' => array(
					'cite' => array(),
					'title' => array(),
				),
				'span' => array(
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'strike' => array(),
				'strong' => array(),
				'ul' => array(
					'class' => array(),
				),
				);
				
				$wooextmm_message = '<div id="message" class="error">
				<p><strong>Extendons: WooCommerce Mix & Match Plugin is inactive.</strong> The <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce plugin</a> must be active for this plugin to work. Please install &amp; activate WooCommerce »</p></div>';

				echo wp_kses(__($wooextmm_message, 'exthwsm'), $allowed_tags);

	}
	add_action('admin_notices', 'fme_upload_files_admin_notice');
}

if ( !class_exists( 'Fme_Ext_Upload_Files' ) ) {

	class Fme_Ext_Upload_Files {
		
		public function __construct() {
			$this->Fme_upload_files_module_constants();
			if (is_admin()) {
				require_once( FMEUF_PLUGIN_DIR . 'admin/fme-upload-files-admin.php' );
			} else {
				require_once( FMEUF_PLUGIN_DIR . 'front/fme-upload-files-front.php' );
			}
		}

		public function Fme_upload_files_module_constants() {
		
			if ( !defined( 'FMEUF_URL' ) ) {
				define( 'FMEUF_URL', plugin_dir_url( __FILE__ ) );
			}

			if ( !defined( 'FMEUF_BASENAME' ) ) {
				define( 'FMEUF_BASENAME', plugin_basename( __FILE__ ) );
			}

			if ( ! defined( 'FMEUF_PLUGIN_DIR' ) ) {
				define( 'FMEUF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}
		}

	} 

	new Fme_Ext_Upload_Files();
}
