<?php
/* 
Plugin Name: Chauffeur Booking System for WordPress
Plugin URI: https://codecanyon.net/item/chauffeur-booking-system-for-wordpress/21072773?ref=quanticalabs
Description: Chauffeur Booking System is a powerful limo reservation WordPress plugin for companies of all sizes. It can be used by both limo and shuttle operators. It provides a simple, step-by-step booking process and an intuitive backend administration. With Chauffeur Booking System you can easily take online reservations for any route defined e.g. airport transfer or city tour, hourly or point-to-point with support for intermediate points (stops). It will help you enhance customer service and manage your limo rental business online.
Author: QuanticaLabs
Version: 5.0
Author URI: https://codecanyon.net/user/quanticalabs/portfolio?ref=quanticalabs
*/

load_plugin_textdomain('chauffeur-booking-system',false,dirname(plugin_basename(__FILE__)).'/languages/');

require_once('include.php');

$Plugin=new CHBSPlugin();
$WooCommerce=new CHBSWooCommerce();

register_activation_hook(__FILE__,array($Plugin,'pluginActivation'));

add_action('init',array($Plugin,'init'));
add_action('after_setup_theme',array($Plugin,'afterSetupTheme'));
add_filter('woocommerce_locate_template',array($WooCommerce,'locateTemplate'),1,3);

$WidgetBookingForm=new CHBSWidgetBookingForm();
$WidgetBookingForm->register();