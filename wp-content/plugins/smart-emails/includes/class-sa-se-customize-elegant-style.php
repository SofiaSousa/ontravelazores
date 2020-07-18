<?php
/**
 * Include Email Style = ELEGANT specific controls
 *
 * @category    Class
 * @package     smart-emails/includes
 * @author      StoreApps
 * @version     1.0.0
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SA_SE_Customize_Elegant_Style' ) ) {

	/**
	 * Style ELEGANT Customizer class
	 */
	class SA_SE_Customize_Elegant_Style {

		/**
		 * Get default values for Email Style = ELEGANT.
		 *
		 * @var array
		 */
		public $default_values = array();

		/**
		 * Constructor
		 *
		 * @param object $wp_customize Currently active theme's object.
		 */
		public function __construct( $wp_customize ) {
			$this->default_values = self::get_default_values_elegant();

			// Add style dependent sections - ELEGANT.
			$this->add_sections_elegant( $wp_customize );
		}

		/**
		 * Add sections for email style ELEGANT
		 *
		 * @param object $wp_customize Currently active theme's object.
		 */
		public function add_sections_elegant( $wp_customize ) {
			if ( isset( $_GET['sa_smart_emails'] ) && true == $_GET['sa_smart_emails'] ) { // phpcs:ignore
				$wp_customize->add_section(
					'elegant_email_background_settings',
					array(
						'title'       => __( 'Email Background', 'smart-emails' ),
						'priority'    => 40,
						'capability ' => 'edit_theme_options',
					)
				);

				$wp_customize->add_section(
					'elegant_email_body_settings',
					array(
						'title'       => __( 'Email Body', 'smart-emails' ),
						'priority'    => 60,
						'capability ' => 'edit_theme_options',
					)
				);
			}

			$this->add_settings_and_controls_elegant( $wp_customize );
		}

		/**
		 * Adds style( ELEGANT ) dependent settings and controls
		 *
		 * @param object $wp_customize Currently active theme's object.
		 */
		public function add_settings_and_controls_elegant( $wp_customize ) {

			// Elegant Background Color.
			$wp_customize->add_setting(
				'se_elegant[background_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['background_color'],
					'transport' => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_elegant_background_color',
					array(
						'label'    => __( 'Email Background Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 10,
						'section'  => 'elegant_email_background_settings',
						'settings' => 'se_elegant[background_color]',
					)
				)
			);

			// Elegant Body Background Color.
			$wp_customize->add_setting(
				'se_elegant[body_background_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['body_background_color'],
					'transport' => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_elegant_body_background_color',
					array(
						'label'    => __( 'Body Background Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 10,
						'section'  => 'elegant_email_body_settings',
						'settings' => 'se_elegant[body_background_color]',
					)
				)
			);

			// Elegant Body Color.
			$wp_customize->add_setting(
				'se_elegant[body_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['body_color'],
					'transport' => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_elegant_body_color',
					array(
						'label'    => __( 'Body Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 20,
						'section'  => 'elegant_email_body_settings',
						'settings' => 'se_elegant[body_color]',
					)
				)
			);

		}

		/**
		 * Returns the updated values after customizing style ELEGANT
		 *
		 * @return updated values
		 */
		public static function get_updated_values_elegant() {
			$default_values      = self::get_default_values_elegant();
			$se_elegant_settings = get_option( 'se_elegant', $default_values );

			return wp_parse_args( $se_elegant_settings, $default_values );
		}

		/**
		 * Returns defaults values for style ELEGANT
		 *
		 * @return default values
		 */
		public static function get_default_values_elegant() {
			$default_values = array(
				'background_color'      => '#e0e0e0',
				'body_background_color' => '#377fb2',
				'body_color'            => '#ffffff',
			);

			return $default_values;
		}
	}

}
