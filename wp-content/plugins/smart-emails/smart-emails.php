<?php
/**
 * Plugin Name: Email Customizer For WooCommerce (Smart Emails)
 * Plugin URI: https://www.storeapps.org/product/email-customizer-for-woocommerce/
 * Description: Customize the emails - styling, colors, logo and text - sent from your store.
 * Version: 1.5.0
 * Author: StoreApps
 * Author URI: https://www.storeapps.org/
 * Requires at least: 4.9.0
 * Tested up to: 5.3.2
 * WC requires at least: 3.0.0
 * WC tested up to: 3.8.1
 * Text Domain: smart-emails
 * Domain Path: /languages/
 * Copyright (c) 2016-2020 StoreApps. All rights reserved.
 *
 * @package smart-emails/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'get_latest_storeapps_upgrade_class' ) ) {
	/**
	 * Find latest StoreApps Upgrade file
	 *
	 * @return string classname
	 */
	function get_latest_storeapps_upgrade_class() {

		$available_classes         = get_declared_classes();
		$available_upgrade_classes = array_filter(
			$available_classes,
			function ( $class_name ) {
				return strpos( $class_name, 'StoreApps_Upgrade_' ) === 0;
			}
		);
		$latest_class              = 'StoreApps_Upgrade_3_5';
		$latest_version            = 0;
		foreach ( $available_upgrade_classes as $class ) {
			$exploded    = explode( '_', $class );
			$get_numbers = array_filter(
				$exploded,
				function ( $value ) {
					return is_numeric( $value );
				}
			);
			$version     = implode( '.', $get_numbers );
			if ( version_compare( $version, $latest_version, '>' ) ) {
				$latest_version = $version;
				$latest_class   = $class;
			}
		}

		return $latest_class;
	}
}

/**
 * Initialize Smart Emails
 */
function initialize_smart_emails() {
	global $smart_emails;

	$active_plugins = (array) get_option( 'active_plugins', array() );
	if ( is_multisite() ) {
		$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
	}

	if ( ! defined( 'SA_SE_PLUGIN_DIRNAME' ) ) {
		define( 'SA_SE_PLUGIN_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );
	}

	if ( ! defined( 'SA_SE_PLUGIN_URL' ) ) {
		define( 'SA_SE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	}

	if ( ! defined( 'SA_SE_PLUGIN_DIR' ) ) {
		define( 'SA_SE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	}

	if ( ! defined( 'SA_SE_PLUGIN_FILE' ) ) {
		define( 'SA_SE_PLUGIN_FILE', __FILE__ );
	}

	require 'includes/class-sa-smart-emails.php';
	require_once 'includes/compat/class-sa-wc-compatibility-3-0.php';
	require_once 'includes/compat/class-sa-wc-compatibility-3-1.php';
	require_once 'includes/compat/class-sa-wc-compatibility-3-2.php';
	require_once 'includes/compat/class-sa-wc-compatibility-3-3.php';
	require_once 'includes/compat/class-sa-wc-compatibility-3-4.php';
	require_once 'includes/compat/class-sa-wc-compatibility-3-5.php';
	require_once 'includes/compat/class-sa-wc-compatibility-3-6.php';
	require_once 'includes/compat/class-sa-wc-compatibility-3-7.php';

	// Instance of main class.
	$smart_emails = new SA_Smart_Emails( $active_plugins );

	if ( is_admin() ) {
		require_once 'includes/class-sa-se-admin-notifications.php';
	}

	if ( ! class_exists( 'StoreApps_Upgrade_3_5' ) ) {
		require_once 'sa-includes/class-storeapps-upgrade-3-5.php';
	}

	$latest_upgrade_class = get_latest_storeapps_upgrade_class();

	$sku                = 'se';
	$prefix             = 'sa_smart_emails';
	$plugin_name        = 'Email Customizer For WooCommerce';
	$text_domain        = 'smart-emails';
	$documentation_link = 'https://www.storeapps.org/knowledgebase_category/smart-emails/';
	$se_upgrader        = new $latest_upgrade_class( __FILE__, $sku, $prefix, $plugin_name, $text_domain, $documentation_link );
}

add_action( 'plugins_loaded', 'initialize_smart_emails' );
