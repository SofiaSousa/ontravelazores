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
					   border-left: 1px solid ' . $se_style_settings['border_color'] . ';
					   border-right: 1px solid ' . $se_style_settings['border_color'] . '; 
					   border-bottom: 1px solid ' . $se_style_settings['border_color'] . ';
					   border-radius: 10px !important;
					   border-top: 5px solid ' . $se_style_settings['top_border_color'] . ';';

$template_header = 'background-color:' . $se_style_settings['header_color'] . ';
					   width:95% !important; 
					   text-align:center;
					   border-bottom: 0;
					   font-weight: bold;
					   line-height: 100%;
					   vertical-align: middle;
					   margin:15px;';


$header_wrapper = 'padding-top: 20px ;
					   padding-bottom: 20px;
					   display: block;
					   text-align:left';


$header_offer_image = 'width:95% !important;
				text-align:center;
				line-height: 100%;
				margin-bottom:15px;
				';

$template_body = 'width: 95%;
			margin-bottom: 15px;
			background-color: ' . $se_style_settings['body_color'] . ';';

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>" style="background: #fff; font-family: 'Avenir Next', Avenir, Roboto, 'Century Gothic', 'Franklin Gothic Medium', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-style: normal">
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
									<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header" style="<?php echo esc_attr( $template_header ); ?>">
										<tr>
											<td id="header_wrapper" style="<?php echo esc_attr( $header_wrapper ); ?>">
												<div id="template_header_image" style="padding-left: 10px;">
													<?php
													$img = get_option( 'woocommerce_email_header_image' );
													if ( ! empty( $se_brand_identity['header_logo'] ) ) {
														echo '<p style="margin:0;"><img style="width:40%" src="' . esc_url( $se_brand_identity['header_logo'] ) . '"></p>';
													} elseif ( $img ) {
														echo '<p style="margin:0;"><img style="width:40%" src="' . esc_url( $img ) . '"/></p>';
													} else {
														echo '<h1 style="color:#ffffff;">' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '</h1>';
													}
													?>
												</div>
											</td>
										</tr>
									</table>
									<!-- End Header -->
								</td>
							</tr>
								<?php
								// Promotional Image.
								if ( ! empty( $se_style_settings['promotional_image'] ) ) {
									?>
										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="0" cellspacing="0" width="600" id="promotional_image" style="<?php echo esc_attr( $header_offer_image ); ?>">
													<td style="height:220px" > 
														<a id="promotional_image_link" href=<?php echo esc_url( $se_style_settings['promotional_image_link'] ); ?> target="_blank">
															<img style="width:100%;height:100%" src=<?php echo esc_attr( $se_style_settings['promotional_image'] ); ?>>
														</a>
													</td>  
												</table>
											</td>
										</tr>
								<?php } ?>
							<tr>
								<td align="center" valign="top">
									<!-- Body -->
									<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body" style="<?php echo esc_attr( $template_body ); ?>">
										<tr>
											<td valign="top" id="body_content">
												<!-- Content -->
												<table border="0" cellpadding="20" cellspacing="0" width="100%">
													<tr>
														<td valign="top">
															<div id="body_content_inner">
