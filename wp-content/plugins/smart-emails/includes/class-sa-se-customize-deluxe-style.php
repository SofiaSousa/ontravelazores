<?php
/**
 * Include Email Style = DELUXE specific controls
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

if ( ! class_exists( 'SA_SE_Customize_Deluxe_Style' ) ) {

	/**
	 * Style DELUXE Customizer class
	 */
	class SA_SE_Customize_Deluxe_Style {

		/**
		 * Get default values for Email Style = DELUXE.
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
			$this->default_values = self::get_default_values_deluxe();

			// Add theme dependent sections - DELUXE.
			$this->add_sections_deluxe( $wp_customize );
		}

		/**
		 * Add sections for email style DELUXE
		 *
		 * @param object $wp_customize Currently active theme's object.
		 */
		public function add_sections_deluxe( $wp_customize ) {
			if ( isset( $_GET['sa_smart_emails'] ) && true == $_GET['sa_smart_emails'] ) { // phpcs:ignore

				// Email Background color.
				$wp_customize->add_section(
					'deluxe_email_background_settings',
					array(
						'title'       => __( 'Email Background', 'smart-emails' ),
						'priority'    => 40,
						'capability ' => 'edit_theme_options',
					)
				);

				// Email Body.
				$wp_customize->add_section(
					'deluxe_email_body_settings',
					array(
						'title'       => __( 'Email Body', 'smart-emails' ),
						'priority'    => 50,
						'capability ' => 'edit_theme_options',
					)
				);
			}

			$this->add_settings_and_controls_deluxe( $wp_customize );
		}

		/**
		 * Adds style = DELUXE dependent settings and controls
		 *
		 * @param object $wp_customize Currently active theme's object.
		 */
		public function add_settings_and_controls_deluxe( $wp_customize ) {

			// Email Background Color.
			$wp_customize->add_setting(
				'se_deluxe[background_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['background_color'],
					'transport' => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_deluxe_background_color',
					array(
						'label'    => __( 'Email Background Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 10,
						'section'  => 'deluxe_email_background_settings',
						'settings' => 'se_deluxe[background_color]',
					)
				)
			);

			// Border Color.
			$wp_customize->add_setting(
				'se_deluxe[border_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['border_color'],
					'transport' => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_deluxe_email_border_color',
					array(
						'label'    => __( 'Email Border Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 10,
						'section'  => 'deluxe_email_body_settings',
						'settings' => 'se_deluxe[border_color]',
					)
				)
			);

			// Body Color.
			$wp_customize->add_setting(
				'se_deluxe[body_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['body_color'],
					'transport' => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_deluxe_body_color',
					array(
						'label'    => __( 'Body Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 10,
						'section'  => 'deluxe_email_body_settings',
						'settings' => 'se_deluxe[body_color]',
					)
				)
			);
		}

		/**
		 * Returns the updated values after customizing style DELUXE
		 *
		 * @return updated values
		 */
		public static function get_updated_values_deluxe() {
			$default_values     = self::get_default_values_deluxe();
			$se_deluxe_settings = get_option( 'se_deluxe', $default_values );

			return wp_parse_args( $se_deluxe_settings, $default_values );
		}

		/**
		 * Returns defaults values for style DELUXE
		 *
		 * @return default values
		 */
		public static function get_default_values_deluxe() {
			$default_values = array(
				'background_color' => '#f5f5f5',
				'border_color'     => '#5f9ea0',
				'body_color'       => '#5f9ea0',
			);

			return $default_values;
		}
	}

}
