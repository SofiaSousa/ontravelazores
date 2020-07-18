<?php
/**
 * Include Email Style = CLASSIC specific controls
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

if ( ! class_exists( 'SA_SE_Customize_Classic_Style' ) ) {

	/**
	 * Style CLASSIC Customizer class
	 */
	class SA_SE_Customize_Classic_Style {

		/**
		 * Get default values for Email Style = CLASSIC.
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
			$this->default_values = self::get_default_values_classic();

			// Add style dependent sections - CLASSIC.
			$this->add_sections_classic( $wp_customize );
		}

		/**
		 * Add sections for email style CLASSIC
		 *
		 * @param object $wp_customize Currently active theme's object.
		 */
		public function add_sections_classic( $wp_customize ) {
			if ( isset( $_GET['sa_smart_emails'] ) && true == $_GET['sa_smart_emails'] ) { // phpcs:ignore

				// Classic Background Section.
				$wp_customize->add_section(
					'classic_email_background_settings',
					array(
						'title'       => __( 'Email Background', 'smart-emails' ),
						'priority'    => 40,
						'capability ' => 'edit_theme_options',
					)
				);

				// Classic Header Section.
				$wp_customize->add_section(
					'classic_email_header_settings',
					array(
						'title'       => __( 'Header', 'smart-emails' ),
						'priority'    => 50,
						'capability ' => 'edit_theme_options',
					)
				);

				// Email Body.
				$wp_customize->add_section(
					'classic_email_body_settings',
					array(
						'title'       => __( 'Email Body', 'smart-emails' ),
						'priority'    => 60,
						'capability ' => 'edit_theme_options',
					)
				);

				// Classic Footer Section.
				$wp_customize->add_section(
					'classic_email_footer_settings',
					array(
						'title'       => __( 'Footer', 'smart-emails' ),
						'priority'    => 70,
						'capability ' => 'edit_theme_options',
					)
				);
			}

			$this->add_settings_and_controls_classic( $wp_customize );

		}

		/**
		 * Adds style = CLASSIC dependent settings and controls
		 *
		 * @param object $wp_customize Currently active theme's object.
		 */
		public function add_settings_and_controls_classic( $wp_customize ) {

			// Classic Background Color.
			$wp_customize->add_setting(
				'se_classic[background_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['background_color'],
					'transport' => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_classic_background_color',
					array(
						'label'    => __( 'Email Background Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 10,
						'section'  => 'classic_email_background_settings',
						'settings' => 'se_classic[background_color]',
					)
				)
			);

			// Classic Top Border Color.
			$wp_customize->add_setting(
				'se_classic[top_border_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['top_border_color'],
					'transport' => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_classic_top_border_color',
					array(
						'label'    => __( 'Top Border Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 10,
						'section'  => 'classic_email_header_settings',
						'settings' => 'se_classic[top_border_color]',
					)
				)
			);

			// Classic Header Color.
			$wp_customize->add_setting(
				'se_classic[header_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['header_color'],
					'transport' => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_classic_header_color',
					array(
						'label'    => __( 'Header Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 20,
						'section'  => 'classic_email_header_settings',
						'settings' => 'se_classic[header_color]',
					)
				)
			);

			// Classic Border Color.
			$wp_customize->add_setting(
				'se_classic[border_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['border_color'],
					'transport' => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_classic_email_border_color',
					array(
						'label'    => __( 'Email Border Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 10,
						'section'  => 'classic_email_body_settings',
						'settings' => 'se_classic[border_color]',
					)
				)
			);

			// Classic Body Color.
			$wp_customize->add_setting(
				'se_classic[body_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['body_color'],
					'transport' => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_classic_body_color',
					array(
						'label'    => __( 'Body Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 20,
						'section'  => 'classic_email_body_settings',
						'settings' => 'se_classic[body_color]',
					)
				)
			);

			// Promotional Image.
			$wp_customize->add_setting(
				'se_classic[promotional_image]',
				array(
					'type'      => 'option',
					'transport' => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'customize_classic_promotional_image',
					array(
						'label'       => __( 'Promotional Image', 'smart-emails' ),
						'type'        => 'image',
						'priority'    => 30,
						'description' => __( 'This image will be added to all of the emails that are sent from your store. You can add offers, discounts, documentation, guides and any other promotional content image.', 'smart-emails' ),
						'section'     => 'classic_email_body_settings',
						'settings'    => 'se_classic[promotional_image]',
					)
				)
			);

			// Promotional Image Link.
			$wp_customize->add_setting(
				'se_classic[promotional_image_link]',
				array(
					'type'      => 'option',
					'transport' => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_classic_promotional_image_link',
					array(
						'label'       => __( 'Promotional Image Link', 'smart-emails' ),
						'type'        => 'text',
						'priority'    => 40,
						'description' => __( 'On clicking the Promotional Image the user will be redirected to the following link.', 'smart-emails' ),
						'section'     => 'classic_email_body_settings',
						'settings'    => 'se_classic[promotional_image_link]',
					)
				)
			);

			// Classic Footer Color.
			$wp_customize->add_setting(
				'se_classic[footer_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['footer_color'],
					'transport' => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_classic_footer_color',
					array(
						'label'    => __( 'Footer Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 30,
						'section'  => 'classic_email_footer_settings',
						'settings' => 'se_classic[footer_color]',
					)
				)
			);

			// Classic Footer Text Color.
			$wp_customize->add_setting(
				'se_classic[footer_text_color]',
				array(
					'type'      => 'option',
					'default'   => $this->default_values['footer_text_color'],
					'transport' => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_classic_footer_text_color',
					array(
						'label'    => __( 'Footer Text Color', 'smart-emails' ),
						'type'     => 'color',
						'priority' => 40,
						'section'  => 'classic_email_footer_settings',
						'settings' => 'se_classic[footer_text_color]',
					)
				)
			);
		}

		/**
		 * Returns the updated values after customizing style CLASSIC
		 *
		 * @return updated values
		 */
		public static function get_updated_values_classic() {
			$default_values      = self::get_default_values_classic();
			$se_classic_settings = get_option( 'se_classic', $default_values );

			return wp_parse_args( $se_classic_settings, $default_values );
		}

		/**
		 * Returns defaults values for style CLASSIC
		 *
		 * @return default values
		 */
		public static function get_default_values_classic() {
			$default_values = array(
				'background_color'  => '#e0e0e0',
				'top_border_color'  => '#29a0c4',
				'header_color'      => '#843f6d',
				'footer_color'      => '#843f6d',
				'border_color'      => '#dcdcdc',
				'body_color'        => '#f2f2f2',
				'footer_text_color' => '#ffffff',
			);

			return $default_values;
		}
	}

}
