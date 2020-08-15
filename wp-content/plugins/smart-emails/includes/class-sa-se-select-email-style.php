<?php
/**
 * Allows to select email styles in Customizer
 *
 * @package     smart-emails/includes
 * @author      StoreApps
 * @version     1.0.2
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SA_SE_Select_Email_Style' ) ) {

	/**
	 * Adds custom fields to the Email style section
	 */
	class SA_SE_Select_Email_Style extends WP_Customize_Control {

		/**
		 * Custom control type is needed as we are not using any of the customizer default controls to show available email styles.
		 *
		 * @var string
		 */
		public $type = 'select_style';

		/**
		 * Displays active and available email style to use.
		 */
		public function render_content() {
			global $se_current_style;

			$styles = array(
				'classic' => SA_SE_PLUGIN_URL . 'assets/images/classic/classic.png',
				'deluxe'  => SA_SE_PLUGIN_URL . 'assets/images/deluxe/deluxe.png',
				'elegant' => SA_SE_PLUGIN_URL . 'assets/images/elegant/elegant.png',
				'simple'  => SA_SE_PLUGIN_URL . 'assets/images/simple/simple.png',
			);
			?>

			<!-- Display name of active email style -->
			<p class='active_email_theme'><?php esc_html_e( 'Active email style', 'smart-emails' ); ?>
				<br>
				<span class='email_theme_name'>
					<?php echo esc_html( ucfirst( $se_current_style ) ); ?>
				</span>
			</p>

			<!-- Display list of available email syles to use-->
			<div class='email_style_container'>
				<ul class='email_styles'> 
					<?php
					foreach ( $styles as $style_name => $image_url ) {
						?>
						<li class='list_styles' id=<?php echo esc_attr( 'style_' . $style_name ); ?>>
							<div class='styles' id=<?php echo esc_attr( $style_name ); ?>>
								<div id=<?php echo esc_attr( 'image_' . $style_name ); ?>>
									<img src=<?php echo esc_url( $image_url ); ?>> 
								</div> 
								<?php
								if ( $style_name !== $se_current_style ) {
									?>
									<span class='use_this_button' id=<?php echo esc_attr( $style_name ); ?>>
										<?php esc_html_e( 'Use this', 'smart-emails' ); ?>
									</span> 
									<?php
								}
								?>
								<div class='style_name'>
									<h3><?php echo esc_html( ucfirst( $style_name ) ); ?></h3>
								</div>
							</div>
						</li>
						<?php
					}
					?>
				</ul>
			</div> 
			<?php
		}
	}
}
