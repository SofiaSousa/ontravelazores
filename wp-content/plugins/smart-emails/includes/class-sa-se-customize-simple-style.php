<?php
/**
 * Include Email Style = SIMPLE specific controls
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

if ( ! class_exists( 'SA_SE_Customize_Simple_Style' ) ) {

	/**
	 * Style SIMPLE Customizer class.
	 */
	class SA_SE_Customize_Simple_Style {

		/**
		 * Get default values for Email Style = SIMPLE
		 *
		 * @var array
		 */
		public $default_values = array();

		/**
		 * Constructor
		 *
		 * @param object $wp_customize Currently active theme's object.
		 * @param string $se_current_template active state of the email template.
		 */
		public function __construct( $wp_customize, $se_current_template ) {
			$this->default_values = self::get_default_values_simple();

			// Adds style dependent sections - SIMPLE.
			$this->add_sections_simple( $wp_customize, $se_current_template );
		}

		/**
		 * Add sections for email style SIMPLE
		 *
		 * @param object $wp_customize Currently active theme's object.
		 * @param string $se_current_template active state of the email template.
		 */
		public function add_sections_simple( $wp_customize, $se_current_template ) {
			if ( isset( $_GET['sa_smart_emails'] ) && true == $_GET['sa_smart_emails'] ) { // phpcs:ignore

				// Header Panel.
				$wp_customize->add_panel(
					'simple_header',
					array(
						'title'       => __( 'Header', 'smart-emails' ),
						'description' => '',
						'priority'    => 40,
					)
				);

				// Header Style  ( Common to an email style ).
				$wp_customize->add_section(
					'simple_header_style',
					array(
						'title'       => __( 'Header Style', 'smart-emails' ),
						'priority'    => 50,
						'panel'       => 'simple_header',
						'capability ' => 'edit_theme_options',
					)
				);

				// WooCommerce email template specific Header Text and Content.
				$wp_customize->add_section(
					'simple_' . $se_current_template . '_header',
					array(
						'title'       => __( 'Header Text', 'smart-emails' ),
						'priority'    => 60,
						'panel'       => 'simple_header',
						'capability ' => 'edit_theme_options',
					)
				);

				// Email Background Color ( Common to an email style ).
				$wp_customize->add_section(
					'simple_email_background_settings',
					array(
						'title'       => __( 'Email Background', 'smart-emails' ),
						'priority'    => 70,
						'capability ' => 'edit_theme_options',
					)
				);
			}

			$this->add_settings_and_controls_simple( $wp_customize, $se_current_template );
		}

		/**
		 * Adds style = SIMPLE dependent settings and controls
		 *
		 * @param object $wp_customize Currently active theme's object.
		 * @param string $se_current_template active state of the email template.
		 */
		public function add_settings_and_controls_simple( $wp_customize, $se_current_template ) {

			// Header Color.
			$wp_customize->add_setting(
				'se_simple[header_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['header_color'],
					'transport' => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_simple_header_color',
					array(
						'label'    => __( 'Header Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 10,
						'section'  => 'simple_header_style',
						'settings' => 'se_simple[header_color]',
					)
				)
			);

			// Header Text Color.
			$wp_customize->add_setting(
				'se_simple[header_text_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['header_text_color'],
					'transport' => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_simple_header_text_color',
					array(
						'label'    => __( 'Header Text Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 10,
						'section'  => 'simple_header_style',
						'settings' => 'se_simple[header_text_color]',
					)
				)
			);

			// Email Background Color.
			$wp_customize->add_setting(
				'se_simple[background_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['background_color'],
					'transport' => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_simple_background_color',
					array(
						'label'    => __( 'Email Background Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 10,
						'section'  => 'simple_email_background_settings',
						'settings' => 'se_simple[background_color]',
					)
				)
			);

			// Header Text.
			$wp_customize->add_setting(
				'se_simple[' . $se_current_template . '_header_text]',
				array(
					'type'      => 'option',
					'transport' => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_' . $se_current_template . '_header_text',
					array(
						'label'    => __( 'Header Text', 'smart-emails' ),
						'type'     => 'text',
						'priority' => 10,
						'section'  => 'simple_' . $se_current_template . '_header',
						'settings' => 'se_simple[' . $se_current_template . '_header_text]',
					)
				)
			);

			// Header Content - Will be added in the future version when adding shortcode in the customizer will come into picture
			// $wp_customize->add_setting( 'se_simple['.$se_current_template.'_header_content]', array(
			// 'type'      => 'option',
			// 'transport' => 'refresh'
			// ));
			// $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'customize_'.$se_current_template.'_header_content', array(
			// 'label'     => __( 'Content', 'smart-emails' ),
			// 'type'      => 'textarea',
			// 'priority'  => 10,
			// 'section'   => 'simple_'.$se_current_template.'_header',
			// 'settings'  => 'se_simple['.$se_current_template.'_header_content]'
			// )));.
		}

		/**
		 * Returns the updated values after customizing style SIMPLE
		 *
		 * @return updated values
		 */
		public static function get_updated_values_simple() {
			$default_values     = self::get_default_values_simple();
			$se_simple_settings = get_option( 'se_simple', $default_values );

			return wp_parse_args( $se_simple_settings, $default_values );
		}

		/**
		 * Returns defaults values for style SIMPLE
		 *
		 * @return default values
		 */
		public static function get_default_values_simple() {
			$default_values = array(
				'header_color'      => '#6b609e',
				'header_text_color' => '#ffffff',
				'background_color'  => '#ffffff',
			);

			return $default_values;
		}

	}

}
