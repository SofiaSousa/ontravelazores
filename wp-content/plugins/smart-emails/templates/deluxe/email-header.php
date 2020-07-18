<?php
/**
 * Email Header
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-header.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 2.4.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $se_brand_identity, $se_style_settings;

$wrapper = 'background-color:' . $se_style_settings['background_color'] . ';
					   margin: 0;
					   padding: 70px 0 70px 0;
					   -webkit-text-size-adjust: none !important;
					   width: 100%;';

$template_container = 'background-color: #ffffff;
					   border: 1px solid;
					   border-color:' . $se_style_settings['border_color'] . ';
					   border-radius: 3px !important;';

$template_header = 'border-radius: 3px 3px 0 0 !important;
					   text-align:center;
					   border-bottom: 0;
					   font-weight: bold;
					   line-height: 100%;
					   vertical-align: middle;
					   font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;';


$header_wrapper = 'padding-top: 20px ;
					   padding-bottom: 20px;
					   display: block;';

$template_header_image = 'margin:20px';

$header_menu = 'text-align: center; 
				background-color:' . $se_style_settings['body_color'] . ';
				padding: 0; 
				vertical-align: middle;';
?>

<!DOCTYPE html>
<html dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>" style="background: #fff; font-family: 'Avenir Next', Avenir, Roboto, 'Century Gothic', 'Franklin Gothic Medium', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-style: normal">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<title><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></title>
	</head>
	<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>" style="<?php echo esc_attr( $wrapper ); ?>">
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
				<tr>
					<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="<?php echo esc_attr( $template_container ); ?>">
							<tr>
								<td align="center" valign="top">
									<!-- Header -->
									<div id="template_header_image" style="<?php echo esc_attr( $template_header_image ); ?>">
										<?php
										$img = get_option( 'woocommerce_email_header_image' );
										if ( ! empty( $se_brand_identity['header_logo'] ) ) {
											echo '<p style="margin:0;"><img src="' . esc_url( $se_brand_identity['header_logo'] ) . '"></p>';
										} elseif ( $img ) {
											echo '<p style="margin:0;"><img src="' . esc_url( $img ) . '"/></p>';
										} else {
											echo '<h1>' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '</h1>';
										}
										?>
									</div>
									<!-- End Header -->
								</td>
							</tr>
							<tr>
								<td>
									<table class="" width="100%" border="0" cellspacing="0" cellpadding="0" id="header_menu">
										<tr style="<?php echo esc_attr( $header_menu ); ?>">
											<td width="100%" align="center" valign="middle"> 
												<?php
												if ( ! empty( $se_brand_identity['url1'] ) && ! empty( $se_brand_identity['text1'] ) ) {
													?>
													<!--Link1-->
													<a id='url1' href="<?php echo esc_url( $se_brand_identity['url1'] ); ?>" style="font-size: 10px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; display: inline-block; padding-left: 5px; padding-right: 8px; padding-top: 10px; padding-bottom: 10px; height: 10px;">
														<span id='text1' class="link_text" style="font-size: 12px; line-height: 10px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; letter-spacing:1px"><?php echo esc_html( $se_brand_identity['text1'] ); ?></span>
													</a> 
													<?php
												}

												if ( ! empty( $se_brand_identity['url2'] ) && ! empty( $se_brand_identity['text2'] ) ) {
													?>
													<!--Link2-->
													<a id='url2' href="<?php echo esc_url( $se_brand_identity['url2'] ); ?>" style="font-size: 10px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; display: inline-block; padding-left: 5px; padding-right: 8px; padding-top: 10px; padding-bottom: 10px; height: 10px;">
														<span id='text2' class="link_text" style="font-size: 12px; line-height: 10px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; letter-spacing:1px"><?php echo esc_html( $se_brand_identity['text2'] ); ?></span>
													</a> 
													<?php
												}

												if ( ! empty( $se_brand_identity['url3'] ) && ! empty( $se_brand_identity['text3'] ) ) {
													?>
													<!--Link3-->  
													<a id='url3' href="<?php echo esc_url( $se_brand_identity['url3'] ); ?>" style="font-size: 10px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; display: inline-block; padding-left: 5px; padding-right: 8px; padding-top: 10px; padding-bottom: 10px; height: 10px;">
														<span id='text3' class="link_text" style="font-size: 12px; line-height: 10px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; letter-spacing:1px"><?php echo esc_html( $se_brand_identity['text3'] ); ?></span>
													</a> 
													<?php
												}

												if ( ! empty( $se_brand_identity['url4'] ) && ! empty( $se_brand_identity['text4'] ) ) {
													?>
													<!--Link4-->
													<a id='url4' href="<?php echo esc_url( $se_brand_identity['url4'] ); ?>" style="font-size: 10px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; display: inline-block; padding-left: 5px; padding-right: 8px; padding-top: 10px; padding-bottom: 10px; height: 10px;">
														<span id='text4' class="link_text" style="font-size: 12px; line-height: 10px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; letter-spacing:1px"><?php echo esc_html( $se_brand_identity['text4'] ); ?></span>
													</a> 
													<?php
												}

												if ( ! empty( $se_brand_identity['url5'] ) && ! empty( $se_brand_identity['text5'] ) ) {
													?>
													<!--Link5--> 
													<a id='url5' href="<?php echo esc_url( $se_brand_identity['url5'] ); ?>" style="font-size: 10px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; display: inline-block; padding-left: 5px; padding-right: 8px; padding-top: 10px; padding-bottom: 10px; height: 10px;">
														<span id='text5'  class="link_text" style="font-size: 12px; line-height: 10px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; letter-spacing:1px"><?php echo esc_html( $se_brand_identity['text5'] ); ?></span>
													</a> 
													<?php
												}
												?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<!-- Body -->
									<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
										<tr>
											<td valign="top" id="body_content">
												<!-- Content -->
												<table border="0" cellpadding="20" cellspacing="0" width="100%">
													<tr>
														<td valign="top">
															<div id="body_content_inner">
