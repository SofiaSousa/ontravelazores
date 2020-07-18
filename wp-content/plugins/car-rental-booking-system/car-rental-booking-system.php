<?php
/* 
Plugin Name: Car Rental Booking System for WordPress
Plugin URI: https://1.envato.market/car-rental-booking-system-for-wordpress
Description: Car Rental Booking System is a powerful online reservation WordPress plugin which provides all the tools and features needed to run your car rental business. The booking process is based on simple step-by-step navigation and you can manage it using the intuitive administration panel.
Author: QuanticaLabs
Version: 2.2
Author URI: https://1.envato.market/quanticalabs-portfolio
*/
	
load_plugin_textdomain('car-rental-booking-system',false,dirname(plugin_basename(__FILE__)).'/languages/');

require_once('include.php');

$Plugin=new CRBSPlugin();
$WooCommerce=new CRBSWooCommerce();

register_activation_hook(__FILE__,array($Plugin,'pluginActivation'));

add_action('init',array($Plugin,'init'));
add_action('after_setup_theme',array($Plugin,'afterSetupTheme'));
add_filter('woocommerce_locate_template',array($WooCommerce,'locateTemplate'),1,3);

$WidgetBookingForm=new CRBSWidgetBookingForm();
$WidgetBookingForm->register();