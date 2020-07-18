<?php
/**
 * Include Email Style specific controls
 *
 * @category    Class
 * @package     smart-emails/includes
 * @author      StoreApps
 * @version     1.1.0
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SA_SE_Customizer' ) ) {

	/**
	 * SA Smart Emails Customizer Class
	 *
	 * @author StoreApps
	 */
	class SA_SE_Customizer {

		/**
		 * Constructor
		 */
		public function __construct() {
			global $se_default_template, $se_default_style;

			$this->se_includes();

			$se_current_template = null;
			$se_current_style    = null;
			$this->get_current_template_and_style( $se_current_template, $se_current_style, $se_default_template, $se_default_style );

			// Removes existing sections and panels.
			add_filter( 'customize_panel_active', array( $this, 'remove_existing_panels' ), 10, 2 );
			add_filter( 'customize_section_active', array( $this, 'remove_existing_sections' ), 10, 2 );

			// Loads the template to preview.
			add_filter( 'template_include', array( $this, 'load_template_for_preview' ) );

			// Overrides the default woocommerce templates and return ours.
			add_filter( 'wc_get_template', array( $this, 'override_woocommerce_templates' ), 10, 5 );

			// Add section to select email styles.
			add_action( 'customize_register', array( $this, 'add_email_styles_selection_section' ) );

			// Add panel to customize brand idenity settings.
			add_action( 'customize_register', array( $this, 'add_brand_identity_panel' ) );

			// Add email style dependent sections and panels.
			add_action( 'customize_register', array( $this, 'customize_emails_styles' ) );

			// Add section to send test email.
			add_action( 'customize_register', array( $this, 'add_section_to_send_test_mail' ) );

			// Handles the live previewing and customizing of the templates.
			add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_live_preview_script' ) );

			// Sends test email.
			add_action( 'wp_ajax_sa_send_test_email', array( $this, 'sa_send_test_email' ) );

			// Adds customer details section inside the emails templates.
			add_filter( 'woocommerce_email_customer_details_fields', array( $this, 'se_insert_customer_details' ), 10, 3 );
		}

		/**
		 * Function to handle WC compatibility related function call from appropriate class.
		 *
		 * @param string $function_name Function to call.
		 * @param array  $arguments Array of arguments passed while calling $function_name.
		 * @return mixed Result of function call.
		 */
		public function __call( $function_name, $arguments = array() ) {
			if ( ! is_callable( 'SA_WC_Compatibility_3_7', $function_name ) ) {
				return;
			}

			if ( ! empty( $arguments ) ) {
				return call_user_func_array( 'SA_WC_Compatibility_3_7::' . $function_name, $arguments );
			} else {
				return call_user_func( 'SA_WC_Compatibility_3_7::' . $function_name );
			}
		}

		/**
		 * Include files that adds template and email theme/style dependent panels, sections, settings and controls
		 */
		public function se_includes() {

			include_once 'class-sa-se-customize-simple-style.php';
			include_once 'class-sa-se-customize-deluxe-style.php';
			include_once 'class-sa-se-customize-classic-style.php';
			include_once 'class-sa-se-customize-elegant-style.php';
		}

		/**
		 * Get selected email template and email theme/style ( which is used for live preview and customzing )
		 *
		 * @param string $se_current_template active state of the email template.
		 * @param string $se_current_style    active state of the email style.
		 * @param string $se_default_template first email template.
		 * @param string $se_default_style    current active email style.
		 */
		public function get_current_template_and_style( $se_current_template = null, $se_current_style = null, $se_default_template, $se_default_style ) {
			global $se_current_template, $se_current_style;

			$se_saved_template = get_option( 'se_current_template' );
			$se_saved_style    = get_option( 'se_current_style' );

			if ( ! empty( $_POST['sa_se_email_template'] ) && ! empty( $_POST['sa_se_email_style'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$se_current_template = sanitize_text_field( wp_unslash( $_POST['sa_se_email_template'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
				$se_current_style    = sanitize_text_field( wp_unslash( $_POST['sa_se_email_style'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
			} elseif ( ! empty( $se_saved_template ) && ! empty( $se_saved_style ) ) {
				$se_current_template = $se_saved_template;
				$se_current_style    = $se_saved_style;
			} else {
				$se_current_template = $se_default_template;
				$se_current_style    = $se_default_style;
			}

			update_option( 'se_current_template', $se_current_template );
			update_option( 'se_current_style', $se_current_style );
		}

		/**
		 * Remove already present sections from the customizer added by active theme and other plugins and return only our section
		 *
		 * @param boolen               $active Whether the Customizer section is active.
		 * @param WP_Customize_Section $section WP_Customize_Section instance.
		 *
		 * @return Smart Emails sections
		 */
		public function remove_existing_sections( $active, $section ) {
			global $se_current_template;

			if ( isset( $_GET['sa_smart_emails'] ) && sanitize_text_field( wp_unslash( true == $_GET['sa_smart_emails'] ) ) && $active ) { // phpcs:ignore
				$sections = array(
					'elegant_email_body_settings',
					'elegant_email_background_settings',
					'classic_email_body_settings',
					'classic_email_header_settings',
					'classic_email_footer_settings',
					'classic_email_background_settings',
					'deluxe_email_body_settings',
					'deluxe_email_background_settings',
					'se_send_test_email',
					'se_select_style',
					'simple_header_style',
					'se_brand_logo',
					'se_social_links',
					'se_footer_text',
					'simple_header_settings',
					'simple_email_background_settings',
					'simple_email_footer_settings',
					'se_menu_bar',
					'simple_' . $se_current_template . '_header',
				);

				if ( in_array( $section->id, $sections ) ) { // phpcs:ignore
					return true;
				}

				return false;
			}

			return true;
		}

		/**
		 * Remove already present panel from the customizer added by active theme and other plugins and return only our panels
		 *
		 * @param bool               $active Whether the Customizer section is active.
		 * @param WP_Customize_Panel $panel WP_Customize_Panel instance.
		 *
		 * @return Smart Emails panels
		 */
		public function remove_existing_panels( $active, $panel ) {
			if ( isset( $_GET['sa_smart_emails'] ) && sanitize_text_field( wp_unslash( true == $_GET['sa_smart_emails'] ) ) ) { // phpcs:ignore
				$panels = array(
					'simple_header',
					'se_brand_identity',
				);

				if ( in_array( $panel->id, $panels ) ) { // phpcs:ignore
					return true;
				}

				return false;
			}

			return true;
		}

		/**
		 * Enqueue script required for live preview and customizing
		 */
		public function enqueue_customizer_live_preview_script() {
			global $se_current_template;

			wp_register_script( 'sa-se-customizer', SA_SE_PLUGIN_URL . 'assets/js/sa-se-customizer.js', array( 'jquery', 'customize-preview' ), '1.3.0', true );

			if ( ! wp_script_is( 'sa-se-customizer' ) ) {
				wp_enqueue_script( 'sa-se-customizer' );
			}

			wp_localize_script( 'sa-se-customizer', 'sa_se_params', array( 'current_template' => $se_current_template ) );
		}

		/**
		 * Get selected template for live preview
		 *
		 * @param string $template The path of the template to include.
		 *
		 * @return overrided template and style
		 */
		public function load_template_for_preview( $template ) {
			global $se_current_template, $se_current_style, $se_brand_identity;

			if ( ! empty( $se_current_template ) && ! empty( $se_current_style ) && isset( $_GET['sa_smart_emails'] ) && is_customize_preview() ) { // phpcs:ignore WordPress.Security.NonceVerification
				return SA_SE_PLUGIN_DIR . 'templates/sa-se-preview.php';
			}

			return $template;
		}

		/**
		 * Override woocommerce default templates with ours
		 *
		 * @param string $located         Template to include.
		 * @param string $template_name   Template name.
		 * @param array  $args            Arguments. (default: array).
		 * @param string $template_path   Template path. (default: '').
		 * @param string $default_path    Default path. (default: '').
		 *
		 * @return new located template
		 */
		public function override_woocommerce_templates( $located, $template_name, $args, $template_path, $default_path ) {
			global $se_brand_identity, $se_current_style, $se_style_settings, $se_triggered_mail_id;

			$se_brand_identity = self::get_brand_identity_updated_values();

			switch ( $se_current_style ) {
				case 'simple':
						$se_style_settings = SA_SE_Customize_Simple_Style::get_updated_values_simple();
					break;
				case 'deluxe':
						$se_style_settings = SA_SE_Customize_Deluxe_Style::get_updated_values_deluxe();
					break;
				case 'classic':
						$se_style_settings = SA_SE_Customize_Classic_Style::get_updated_values_classic();
					break;
				case 'elegant':
						$se_style_settings = SA_SE_Customize_Elegant_Style::get_updated_values_elegant();
					break;
			}

			if ( ! empty( $args['email']->id ) ) {
				$se_triggered_mail_id = $args['email']->id;
			}

			if ( strpos( $located, 'woocommerce-subscriptions' ) ) {
				$new_template = str_replace( 'woocommerce-subscriptions/templates/emails', SA_SE_PLUGIN_DIRNAME . '/templates/' . $se_current_style . '/woocommerce-subscriptions', $located );
			} else {
				$new_template = str_replace( 'woocommerce/templates/emails', SA_SE_PLUGIN_DIRNAME . '/templates/' . $se_current_style, $located );
			}

			if ( file_exists( $new_template ) ) {
				return $new_template;
			}

			return $located;
		}

		/**
		 * Section to display available email themes to use
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function add_email_styles_selection_section( $wp_customize ) {
			if ( isset( $_GET['sa_smart_emails'] ) && sanitize_text_field( wp_unslash( true == $_GET['sa_smart_emails'] ) ) ) { // phpcs:ignore
				include_once 'class-sa-se-select-email-style.php';

				// Section to select email style.
				$wp_customize->add_section(
					'se_select_style',
					array(
						'title'      => __( 'Email Styles', 'smart-emails' ),
						'priority'   => 20,
						'capability' => 'edit_theme_options',
					)
				);

				// Custom control to select email theme.
				$wp_customize->add_setting(
					'se_select_style[selected_style]',
					array(
						'type'       => 'option',
						'transport'  => 'postMessage',
						'capability' => 'edit_theme_options',
					)
				);
				$wp_customize->add_control(
					new SA_SE_Select_Email_Style(
						$wp_customize,
						'customize_email_style',
						array(
							'label'    => __( 'Choose Style', 'smart-emails' ),
							'type'     => 'select_style',
							'section'  => 'se_select_style',
							'settings' => 'se_select_style[selected_style]',
						)
					)
				);
			}
		}

		/**
		 * Adds brand identity panel to edit global level setting which will be common to all email templates and styles
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function add_brand_identity_panel( $wp_customize ) {
			if ( isset( $_GET['sa_smart_emails'] ) && sanitize_text_field( wp_unslash( true == $_GET['sa_smart_emails'] ) ) ) { // phpcs:ignore

				// Brand Identity Panel.
				$wp_customize->add_panel(
					'se_brand_identity',
					array(
						'title'       => __( 'Brand Identity', 'smart-emails' ),
						'description' => '',
						'priority'    => 30,
					)
				);

				// Brand Logo Section.
				$wp_customize->add_section(
					'se_brand_logo',
					array(
						'title'       => __( 'Brand Logo', 'smart-emails' ),
						'priority'    => 10,
						'panel'       => 'se_brand_identity',
						'capability ' => 'edit_theme_options',
					)
				);

				// Email Menu.
				$wp_customize->add_section(
					'se_menu_bar',
					array(
						'title'       => __( 'Menu Bar', 'smart-emails' ),
						'priority'    => 20,
						'panel'       => 'se_brand_identity',
						'capability ' => 'edit_theme_options',
					)
				);

				// Social Links Section.
				$wp_customize->add_section(
					'se_social_links',
					array(
						'title'       => __( 'Social Links', 'smart-emails' ),
						'priority'    => 30,
						'panel'       => 'se_brand_identity',
						'capability ' => 'edit_theme_options',
					)
				);

				// Footer Text Section.
				$wp_customize->add_section(
					'se_footer_text',
					array(
						'title'       => __( 'Footer Text', 'smart-emails' ),
						'priority'    => 40,
						'panel'       => 'se_brand_identity',
						'capability ' => 'edit_theme_options',
					)
				);

			}

			/**
			 * Adding of settings and controls should be done outside the if condition or else the
			 * settings won't get updated after customizing and saving the changes
			 */
			$this->brand_identity_setting_and_controls( $wp_customize );
		}

		/**
		 * Settings and controls to manage brand logo, social icon links & footer text
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function brand_identity_setting_and_controls( $wp_customize ) {
			$brand_identity_defaults = self::get_brand_identity_default_values();

			// Header Logo.
			$wp_customize->add_setting(
				'se_brand_identity[header_logo]',
				array(
					'type'      => 'option',
					'transport' => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'customize_header_logo',
					array(
						'label'       => __( 'Logo', 'smart-emails' ),
						'type'        => 'image',
						'priority'    => 10,
						'description' => __( 'Add Your Brand Logo.', 'smart-emails' ),
						'section'     => 'se_brand_logo',
						'settings'    => 'se_brand_identity[header_logo]',
					)
				)
			);

			// Twitter Logo.
			$wp_customize->add_setting(
				'se_brand_identity[twitter_logo]',
				array(
					'type'      => 'option',
					'transport' => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'customize_twitter_logo',
					array(
						'label'    => __( 'Twitter Logo', 'smart-emails' ),
						'type'     => 'image',
						'priority' => 10,
						'section'  => 'se_social_links',
						'settings' => 'se_brand_identity[twitter_logo]',
					)
				)
			);

			// Twitter link.
			$wp_customize->add_setting(
				'se_brand_identity[twitter_link]',
				array(
					'type'      => 'option',
					'transport' => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_twitter_link',
					array(
						'label'    => __( 'Twitter Link', 'smart-emails' ),
						'type'     => 'text',
						'priority' => 10,
						'section'  => 'se_social_links',
						'settings' => 'se_brand_identity[twitter_link]',
					)
				)
			);

			// Facebook Logo.
			$wp_customize->add_setting(
				'se_brand_identity[facebook_logo]',
				array(
					'type'      => 'option',
					'transport' => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'customize_facebook_logo',
					array(
						'label'    => __( 'Facebook Logo', 'smart-emails' ),
						'type'     => 'image',
						'priority' => 10,
						'section'  => 'se_social_links',
						'settings' => 'se_brand_identity[facebook_logo]',
					)
				)
			);

			// Facebook link.
			$wp_customize->add_setting(
				'se_brand_identity[facebook_link]',
				array(
					'type'      => 'option',
					'transport' => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_facebook_link',
					array(
						'label'    => __( 'Facebook Link', 'smart-emails' ),
						'type'     => 'text',
						'priority' => 10,
						'section'  => 'se_social_links',
						'settings' => 'se_brand_identity[facebook_link]',
					)
				)
			);

			// Instagram Logo.
			$wp_customize->add_setting(
				'se_brand_identity[instagram_logo]',
				array(
					'type'      => 'option',
					'transport' => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'customize_instagram_logo',
					array(
						'label'    => __( 'Instagram Logo', 'smart-emails' ),
						'type'     => 'image',
						'priority' => 10,
						'section'  => 'se_social_links',
						'settings' => 'se_brand_identity[instagram_logo]',
					)
				)
			);

			// Instagram link.
			$wp_customize->add_setting(
				'se_brand_identity[instagram_link]',
				array(
					'type'      => 'option',
					'transport' => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_instagram_link',
					array(
						'label'    => __( 'Instagram Link', 'smart-emails' ),
						'type'     => 'text',
						'priority' => 10,
						'section'  => 'se_social_links',
						'settings' => 'se_brand_identity[instagram_link]',
					)
				)
			);

			// Footer Text.
			$wp_customize->add_setting(
				'se_brand_identity[footer_text]',
				array(
					'type'      => 'option',
					'default'   => $brand_identity_defaults['footer_text'],
					'transport' => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'customize_footer_text',
					array(
						'label'    => __( 'Footer Text', 'smart-emails' ),
						'type'     => 'textarea',
						'priority' => 10,
						'section'  => 'se_footer_text',
						'settings' => 'se_brand_identity[footer_text]',
					)
				)
			);

			// Email Menus.
			for ( $i = 1; $i <= 5; $i++ ) {
				// Adds URL.
				$wp_customize->add_setting(
					"se_brand_identity[url$i]",
					array(
						'type'      => 'option',
						'transport' => 'refresh',
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Control(
						$wp_customize,
						'menu_url' . $i,
						array(
							/* translators: placeholder is to indicate current url number */
							'label'    => sprintf( __( 'URL %s', 'smart-emails' ), $i ),
							'type'     => 'text',
							'priority' => 10,
							'section'  => 'se_menu_bar',
							'settings' => "se_brand_identity[url$i]",
						)
					)
				);

				// Adds Text for corresponding URL.
				$wp_customize->add_setting(
					"se_brand_identity[text$i]",
					array(
						'type'      => 'option',
						'transport' => 'refresh',
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Control(
						$wp_customize,
						'menu_text' . $i,
						array(
							/* translators: placeholder is to indicate current url number */
							'label'    => sprintf( __( 'Text %s', 'smart-emails' ), $i ),
							'type'     => 'text',
							'priority' => 10,
							'section'  => 'se_menu_bar',
							'settings' => "se_brand_identity[text$i]",
						)
					)
				);
			}
		}

		/**
		 * Adds sections and panels corresponding to a email style selected
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_emails_styles( $wp_customize ) {
			global $se_current_template, $se_current_style;

			// Add controls depending on email style selected.
			if ( ! empty( $se_current_style ) ) {
				switch ( $se_current_style ) {
					case 'simple':
						new SA_SE_Customize_Simple_Style( $wp_customize, $se_current_template );
						break;
					case 'deluxe':
						new SA_SE_Customize_Deluxe_Style( $wp_customize );
						break;
					case 'classic':
						new SA_SE_Customize_Classic_Style( $wp_customize );
						break;
					case 'elegant':
						new SA_SE_Customize_Elegant_Style( $wp_customize );
						break;
				}
			}
		}

		/**
		 * Adds send test email section
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function add_section_to_send_test_mail( $wp_customize ) {
			if ( isset( $_GET['sa_smart_emails'] ) && sanitize_text_field( wp_unslash( true == $_GET['sa_smart_emails'] ) ) ) { // phpcs:ignore
				include_once 'class-sa-se-send-test-email.php';

				$wp_customize->add_section(
					'se_send_test_email',
					array(
						'title'      => __( 'Send Test Email', 'smart-emails' ),
						'priority'   => 100,
						'capability' => 'edit_theme_options',
					)
				);

				// Custom control to send test email.
				$wp_customize->add_setting(
					'se_send_test_email[test_email]',
					array(
						'type'       => 'option',
						'transport'  => 'refresh',
						'capability' => 'edit_theme_options',
					)
				);

				$wp_customize->add_control(
					new SA_SE_Send_Test_Email(
						$wp_customize,
						'se_send_test_email',
						array(
							'label'       => __( 'Send Mail', 'smart-emails' ),
							'type'        => 'test_email',
							'description' => __( 'Sends test email of the currently previewed template', 'smart-emails' ),
							'section'     => 'se_send_test_email',
							'settings'    => 'se_send_test_email[test_email]',
						)
					)
				);
			}
		}

		/**
		 * Returns the updated values after customizing brand identity settings
		 *
		 * @return updated brand identity settings
		 */
		public static function get_brand_identity_updated_values() {
			$brand_identity_defaults   = self::get_brand_identity_default_values();
			$se_brand_identity_updated = get_option( 'se_brand_identity', $brand_identity_defaults );

			return wp_parse_args( $se_brand_identity_updated, $brand_identity_defaults );
		}

		/**
		 * Returns the defaults values for brand identity settings
		 *
		 * @return defaults brand identity settings
		 */
		public static function get_brand_identity_default_values() {
			$brand_identity_defaults = array( 'footer_text' => '&copy;&nbsp;' . date( 'Y' ) . '&nbsp;' . get_bloginfo( 'name', 'display' ) ); // phpcs:ignore

			return $brand_identity_defaults;
		}

		/**
		 * Sends test email of the currently previewed woocommerce template
		 */
		public function sa_send_test_email() {
			if ( ! empty( $_REQUEST['email_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$to_email_id = sanitize_email( wp_unslash( $_REQUEST['email_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification

				// Get currently previewed template in buffer.
				ob_start();
				include_once SA_SE_PLUGIN_DIR . 'templates/sa-se-preview.php';
				$email_content = ob_get_contents();
				ob_end_clean();

				$subject = __( 'WooCommerce Test Email', 'smart-emails' );
				$headers = "Content-Type: text/html\r\n";

				// Send email.
				wp_mail( $to_email_id, $subject, $email_content, $headers );
				exit();
			}
		}

		/**
		 * Adds customer details section inside the emails templates
		 * From WC-3.2 onwards this data was removed so we had to insert this through filter
		 *
		 * @since 1.3.0
		 * @param array    $fields Customer details fields.
		 * @param bool     $sent_to_admin If should sent to admin.
		 * @param WC_Order $order Order object.
		 *
		 * @return array $fields
		 */
		public function se_insert_customer_details( $fields, $sent_to_admin, $order ) {
			if ( $this->is_wc_gte_32() && $order instanceof WC_order ) {
				if ( $order->get_billing_email() ) {
					$fields['billing_email'] = array(
						'label' => __( 'Email address', 'smart-emails' ),
						'value' => wptexturize( $order->get_billing_email() ),
					);
				}

				if ( $order->get_billing_phone() ) {
					$fields['billing_phone'] = array(
						'label' => __( 'Phone', 'smart-emails' ),
						'value' => wptexturize( $order->get_billing_phone() ),
					);
				}
			}

			return $fields;
		}
	}
}
