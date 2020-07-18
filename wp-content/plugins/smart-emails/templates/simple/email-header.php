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

global $se_brand_identity, $se_style_settings, $se_triggered_mail_id;

$wrapper = 'background-color:' . $se_style_settings['background_color'] . ';
					   margin: 0;
					   padding: 70px 0 70px 0;
					   -webkit-text-size-adjust: none !important;
					   width: 100%;';

$template_container = 'background-color: #ffffff;
					   border: 1px solid #dcdcdc;
					   border-radius: 3px !important;
					   border-bottom:5px solid ' . $se_style_settings['header_color'] . ';';

$template_header = 'background-color:' . $se_style_settings['header_color'] . ";
					   border-radius: 3px 3px 0 0 !important;
					   width:100% !important; 
					   text-align:center;
					   border-bottom: 0;
					   font-weight: bold;
					   line-height: 100%;
					   vertical-align: middle;
					   font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;";


$header_wrapper = 'padding-top: 20px ;
					   padding-bottom: 20px;
					   display: block;';

$template_header_h1 = 'color:' . $se_style_settings['header_text_color'] . ';';

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
						<div id="template_header_image">
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
						<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="<?php echo esc_attr( $template_container ); ?>">
							<tr>
								<td align="center" valign="top">
									<!-- Header -->
									<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header" style="<?php echo esc_attr( $template_header ); ?>">
										<tr>
											<td id="header_wrapper" style="<?php echo esc_attr( $header_wrapper ); ?>">
												<h1 id="header_text" style="<?php echo esc_attr( $template_header_h1 ); ?>">
													<?php
													// get the modified header text.
													if ( ! empty( $se_style_settings[ $se_triggered_mail_id . '_header_text' ] ) ) {
														echo wp_kses_post( $se_style_settings[ $se_triggered_mail_id . '_header_text' ], 'smart-emails' );
													} elseif ( ! empty( $email_heading ) ) {
														echo wp_kses_post( $email_heading );
													}
													?>
												</h1>
											</td>
										</tr>
									</table>
									<!-- End Header -->
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
