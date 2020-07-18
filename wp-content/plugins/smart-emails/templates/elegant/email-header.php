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

$template_container = 'background-color:' . $se_style_settings['body_background_color'] . ';
					   border: 1px solid #dcdcdc;
					   border-radius: 20px !important;';

$header_wrapper = 'padding: 10px ;
					   display: block;';


$header_offer_image = 'width:95% !important;
					   background-color:#7d98b5;
					   text-align:center;
					   line-height: 100%;
					   margin-bottom:5px;';

$template_body = 'width: 93%;
				  margin-bottom: 5px;
				  background-color:' . $se_style_settings['body_color'] . ';
				  border: 1px solid #dcdcdc;
				  border-radius: 10px;';


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
						<table border="0" cellpadding="0" cellspacing="0" width="620">
							<tr>
								<td align="right" valign="top">
									<span style="margin-right:10px"> 
										<?php
										if ( ! empty( $se_brand_identity['url1'] ) && ! empty( $se_brand_identity['text1'] ) ) {
											?>
											<a id='url1' href=<?php echo esc_url( $se_brand_identity['url1'] ); ?> style="font-size: 10px; font-family: Arial, sans-serif; color: #000000; text-decoration: none; display: inline-block; padding-top: 10px; padding-bottom: 10px; height: 10px;">
												<span id='text1' class="link_text" style="font-size: 11px; font-style: normal; line-height: 10px; font-family: Arial, sans-serif; color: #3f51b5; text-decoration: none; letter-spacing:1px"><?php echo esc_attr( $se_brand_identity['text1'] ); ?></span>
											</a> 
											<?php
										}

										if ( ! empty( $se_brand_identity['url2'] ) && ! empty( $se_brand_identity['text2'] ) ) {
											?>
											<a id='url2' href=<?php echo esc_url( $se_brand_identity['url2'] ); ?> style="font-size: 10px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; display: inline-block; padding-top: 10px; padding-bottom: 10px; height: 10px;">
												<span id='text2' class="link_text" style="font-size: 11px; font-style: normal; line-height: 10px; font-family: Arial, sans-serif; color: #3f51b5; text-decoration: none; letter-spacing:1px"><?php ( $se_brand_identity['text2'] ) ? printf( '<span class="menu_separator">%s</span> %s', esc_html( '|&nbsp;' ), esc_html( $se_brand_identity['text2'] ) ) : ''; ?></span>
											</a> 
											<?php
										}

										if ( ! empty( $se_brand_identity['url3'] ) && ! empty( $se_brand_identity['text3'] ) ) {
											?>
											<a id='url3' href=<?php echo esc_url( $se_brand_identity['url3'] ); ?> style="font-size: 10px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; display: inline-block; padding-top: 10px; padding-bottom: 10px; height: 10px;">
												<span id='text3' class="link_text" style="font-size: 11px; font-style: normal; line-height: 10px; font-family: Arial, sans-serif; color: #3f51b5; text-decoration: none; letter-spacing:1px"><?php ( $se_brand_identity['text3'] ) ? printf( '<span class="menu_separator">%s</span> %s', esc_html( '|&nbsp;' ), esc_html( $se_brand_identity['text3'] ) ) : ''; ?></span>
											</a> 
											<?php
										}

										if ( ! empty( $se_brand_identity['url4'] ) && ! empty( $se_brand_identity['text4'] ) ) {
											?>
											<a id='url4' href=<?php echo esc_url( $se_brand_identity['url4'] ); ?> style="font-size: 10px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; display: inline-block; padding-top: 10px; padding-bottom: 10px; height: 10px;">
												<span id='text4' class="link_text" style="font-size: 11px; font-style: normal; line-height: 10px; font-family: Arial, sans-serif; color: #3f51b5; text-decoration: none; letter-spacing:1px"><?php ( $se_brand_identity['text4'] ) ? printf( '<span class="menu_separator">%s</span> %s', esc_html( '|&nbsp;' ), esc_html( $se_brand_identity['text4'] ) ) : ''; ?></span>
											</a> 
											<?php
										}

										if ( ! empty( $se_brand_identity['url5'] ) && ! empty( $se_brand_identity['text5'] ) ) {
											?>
											<a id='url5' href=<?php echo esc_url( $se_brand_identity['url5'] ); ?> style="font-size: 10px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; display: inline-block; padding-top: 10px; padding-bottom: 10px; height: 10px;">
												<span id='text5' class="link_text" style="font-size: 11px; font-style: normal; line-height: 10px; font-family: Arial, sans-serif; color: #3f51b5; text-decoration: none; letter-spacing:1px"><?php ( $se_brand_identity['text5'] ) ? printf( '<span class="menu_separator">%s</span> %s', esc_html( '|&nbsp;' ), esc_html( $se_brand_identity['text5'] ) ) : ''; ?></span>
											</a> 
											<?php
										}
										?>
									</span>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="620" id="template_container" style="<?php echo esc_attr( $template_container ); ?>">
							<!-- Header -->
							<tr>
								<td>
									<div id="template_header_image" style="text-align: center;">
										<?php
										$img = get_option( 'woocommerce_email_header_image' );
										if ( ! empty( $se_brand_identity['header_logo'] ) ) {
											echo '<p style="margin:10px 0px 10px 20px;"><img style="width:40%" src="' . esc_url( $se_brand_identity['header_logo'] ) . '"></p>';
										} elseif ( $img ) {
											echo '<p style="margin:10px 0px 10px 20px;"><img style="width:40%" src="' . esc_url( $img ) . '"/></p>';
										} else {
											echo '<h1 style="color:#ffffff;margin:10px">' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '</h1>';
										}
										?>
									</div>
								</td>
							</tr>
							<!-- End Header -->
							<tr>
								<td align="center" valign="top" colspan="2">
									<!-- Body -->
									<table border="0" cellpadding="0" cellspacing="0" width="750" id="template_body" style="<?php echo esc_attr( $template_body ); ?>">
										<tr>
											<td valign="top" id="body_content">
												<!-- Content -->
												<table border="0" cellpadding="20" cellspacing="0" width="100%">
													<tr>
														<td valign="top">
															<div id="body_content_inner">
