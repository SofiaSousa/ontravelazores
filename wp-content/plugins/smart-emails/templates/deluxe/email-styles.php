<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 2.3.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$base            = '#000000';
$base_lighter_20 = wc_hex_lighter( $base, 20 );

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
?>
#body_content_inner {
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
	font-size: 14px;
	line-height: 150%;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

#body_content_inner p {
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
	color: #000;
}

.text {
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

.link {
	color: <?php echo esc_attr( $base ); ?>;
}

h1 {
	color: <?php echo esc_attr( $base ); ?>;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
	font-size: 30px;
	font-weight: 300;
	line-height: 150%;
	margin: 0;
	text-align: center;
	text-shadow: 0 1px 0 <?php echo esc_attr( $base_lighter_20 ); ?>;
	-webkit-font-smoothing: antialiased;
}

h2 {
	color: <?php echo esc_attr( $base ); ?>;
	display: block;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
	font-size: 18px;
	font-weight: bold;
	line-height: 130%;
	margin: 16px 0 8px;
}

h3 {
	color: <?php echo esc_attr( $base ); ?>;
	display: block;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
	font-size: 16px;
	font-weight: bold;
	line-height: 130%;
	margin: 16px 0 8px;
}

a {
	color: <?php echo esc_attr( $base ); ?>;
	font-weight: normal;
	text-decoration: underline;
}

img {
	border: none;
	display: inline;
	font-size: 14px;
	font-weight: bold;
	height: auto;
	line-height: 100%;
	outline: none;
	text-decoration: none;
	text-transform: capitalize;
}

#se_show_order_id h2 {
	text-align: center;
	padding: 5px;
}

#customer_details {
	margin:10px auto;
	text-align:center;
	width:100%;
}

#addresses {
	width: 100% !important; 
	margin-top:10px;";
}

#template_footer td {
	padding: 0;
}

#template_footer #credit {
	border: 0;
	font-family: Arial;
	font-size:12px;
	line-height:125%;
	text-align:center;
	padding: 5px 48px 5px 48px;
}

#credit a { 
	display: inline-block;
	padding: 0px;
}

#credit a img {
	border: 0; 
	display: inline-block; 
	outline: none; 
	text-decoration: none
}

#credit a#twitter_link,
#credit a#facebook_link,
#credit a#instagram_link {
	padding: 0px 10px;
}

.se_footer p {
	margin: 16px 0;
}

#body_content td ul.wc-item-meta {
	font-size: small;
	margin: 1em 0 0;
	padding: 0;
	list-style: none;
}

#body_content td ul.wc-item-meta li {
	margin: 0.5em 0 0;
	padding: 0;
}

#body_content td ul.wc-item-meta li .wc-item-meta-label {
	float: left;
	margin-right: .25em;
	clear: both;
}

#body_content td ul.wc-item-meta li p {
	margin: 0;
	text-align: unset !important;
}

<?php
