<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates/Emails
 * @version     2.3.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $se_brand_identity;

$footer_offer_image = 'width:100% !important;
				background-color:#e2aeae;
				text-align:center;
				line-height: 100%;
				margin-bottom:15px;
				color:white;
				'
?>
<style>
#template_footer {
	/*background-color: #f57171;*/
	width:100%;
	color:white;
	padding-bottom: 10px;
}
#template_footer td {
	padding: 0;
	-webkit-border-radius: 6px;
	margin-bottom: 15px;
}
#template_footer #credit {
	border: 0;
	font-family: Arial;
	font-size:12px;
	line-height:125%;
	text-align:center;
	padding: 0 48px 0px 48px;
}
#credit a { 
	display: inline-block;
	padding: 0px;
}
#credit a img {
	border: 0; 
	display: inline-block; 
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
</style>
															</div>
														</td>
													</tr>
												</table>
												<!-- End Content -->
											</td>
										</tr>
									</table>
									<!-- End Body -->
								</td>
							<tr>
								<td colspan="2" align="center" valign="top">
									<!-- Footer -->
									<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
										<tr>
											<td valign="top">
												<table border="0" cellpadding="10" cellspacing="0" width="100%">
													<tr>
														<td colspan="2" valign="middle" id="credit" class="se_footer" >
															<?php
															if ( ! empty( $se_brand_identity['footer_text'] ) ) {
																echo wp_kses_post( wpautop( wptexturize( $se_brand_identity['footer_text'] ) ) );
															}
															?>
														</td>
													</tr>
													<tr>
														<td valign="middle" id="credit"> 
															<?php
															if ( ! empty( $se_brand_identity['twitter_link'] ) ) {
																?>
																<a id="twitter_link" href=<?php echo esc_url( $se_brand_identity['twitter_link'] ); ?>>
																	<?php
																	if ( ! empty( $se_brand_identity['twitter_logo'] ) ) {
																		$twitter_logo = $se_brand_identity['twitter_logo'];
																	} else {
																		$twitter_logo = SA_SE_PLUGIN_URL . '/assets/images/deluxe/twitter.png';
																	}
																	?>
																	<img alt="Twitter logo" id="se_twitter_logo" src=<?php echo esc_url( $twitter_logo ); ?>>
																</a> 
																<?php
															}
															if ( ! empty( $se_brand_identity['facebook_link'] ) ) {
																?>
																<a id="facebook_link" href=<?php echo esc_url( $se_brand_identity['facebook_link'] ); ?>>
																	<?php
																	if ( ! empty( $se_brand_identity['facebook_logo'] ) ) {
																		$facebook_logo = $se_brand_identity['facebook_logo'];
																	} else {
																		$facebook_logo = SA_SE_PLUGIN_URL . '/assets/images/deluxe/facebook.png';
																	}
																	?>
																	<img alt="Facebook logo" id="se_facebook_logo" src=<?php echo esc_url( $facebook_logo ); ?>>
																</a> 
																<?php
															}
															if ( ! empty( $se_brand_identity['instagram_link'] ) ) {
																?>
																<a id="instagram_link" href=<?php echo esc_url( $se_brand_identity['instagram_link'] ); ?>>
																	<?php
																	if ( ! empty( $se_brand_identity['instagram_logo'] ) ) {
																		$instagram_logo = $se_brand_identity['instagram_logo'];
																	} else {
																		$instagram_logo = SA_SE_PLUGIN_URL . '/assets/images/deluxe/instagram.png';
																	}
																	?>
																	<img alt="Instagram logo" id="se_instagram_logo" src=<?php echo esc_url( $instagram_logo ); ?>>
																</a> 
																<?php
															}
															?>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<!-- End Footer -->
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
