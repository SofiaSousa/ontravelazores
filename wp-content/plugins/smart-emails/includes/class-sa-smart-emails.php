<?php
/**
 * Main class for Smart Emails
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

if ( ! class_exists( 'SA_Smart_Emails' ) ) {

	/**
	 * SA Smart Emails - Main class
	 *
	 * @author StoreApps
	 */
	class SA_Smart_Emails {

		/**
		 * Constructor
		 *
		 * @param array $active_plugins active plugins list.
		 */
		public function __construct( $active_plugins ) {
			global $se_default_template, $se_default_style, $se_customizer_url, $se_active_conflicting_plugins;

			if ( ! $this->is_wc_gte_30() ) {
				add_action( 'admin_notices', array( $this, 'admin_notice_sa_needs_wc_30_above' ) );
			}

			add_action( 'admin_init', array( $this, 'smart_emails_db_update' ) );

			$se_is_wc_active = $this->se_check_if_wc_is_active( $active_plugins );

			if ( $se_is_wc_active ) {
				$se_active_conflicting_plugins = $this->se_check_for_conflicting_plugins( $active_plugins );

				if ( $se_active_conflicting_plugins ) {
					add_action( 'admin_notices', array( $this, 'se_show_conflicting_notice' ) );
				}

				$wc_version          = get_option( 'woocommerce_version' );
				$se_default_template = version_compare( $wc_version, '2.6.0', '<' ) ? 'customer_processing_order' : 'customer_on_hold_order';
				$se_default_style    = 'simple';
				$se_customizer_url   = $this->get_customizer_url();

				// For Adding Menu.
				add_action( 'admin_menu', array( $this, 'sa_se_admin_menu' ) );

				// For enqueing scripts and styles.
				add_action( 'admin_enqueue_scripts', array( $this, 'sa_se_enqueue_admin_scripts_styles' ) );

				// For loading main customizer api class.
				$this->load_customizer_api();

				// Modify email styles by adding custom CSS.
				// This is done to make sure center layout is applied to only WC+Sub emails because other email templates will have their own style written.
				if ( $this->is_wc_gte_36() ) {
					add_filter( 'woocommerce_email_styles', array( $this, 'sa_se_add_css_to_emails' ), 9999, 2 );
				}
			}
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
		 * Function for database updation
		 */
		public function smart_emails_db_update() {
			$se_db_version = get_option( 'smart_emails_db_version' );

			/**
			 * In SE-v1.1 the option to add menu bar to emails was moved to brand identity settings
			 * so the menu bar settings of style deluxe ( maybe added by user ) was moved to brand identity settings
			 */

			if ( empty( $se_db_version ) ) {
				$deluxe_settings = get_option( 'se_deluxe' );
				$global_settings = get_option( 'se_brand_identity' );

				if ( ! empty( $deluxe_settings ) ) {
					foreach ( $deluxe_settings as $name => $value ) {
						if ( ( preg_match( '/url/', $name ) || preg_match( '/text/', $name ) ) && ! empty( $value ) ) {
							$global_settings[ $name ] = $value;
							unset( $deluxe_settings[ $name ] );
						}
					}

					update_option( 'se_deluxe', $deluxe_settings );
					update_option( 'se_brand_identity', $global_settings );
					update_option( 'smart_emails_db_version', '1.0' );
				}
			}
		}

		/**
		 * Function to show admin notice that Smart Emails works with WC 3.0+.
		 */
		public function admin_notice_sa_needs_wc_30_above() {
			?>
			<div class="updated error">
				<p>
				<?php
					printf(
						'<strong>%1$s</strong> %2$s <a href="%3$s">%4$s</a>',
						esc_html__( 'Important - ', 'smart-emails' ),
						esc_html__( 'Email Customizer For WooCommerce (Smart Emails) is active but it will only work with WooCommerce 3.0+.', 'smart-emails' ),
						esc_url( admin_url( 'plugins.php?plugin_status=upgrade' ), 'smart-emails' ),
						esc_html__( 'Please update WooCommerce to the latest version', 'smart-emails' )
					);
				?>
				</p>
			</div>
			<?php
		}

		/**
		 * Function to check if WooCommerce is active
		 *
		 * @param array $active_plugins active plugins list.
		 * @return is woocommerce active
		 */
		public function se_check_if_wc_is_active( $active_plugins ) {
			if ( ! ( in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins ) ) ) {
				add_action( 'admin_notices', array( $this, 'sa_se_woocommerce_inactive_notice' ) );
				return false;
			}

			return true;
		}

		/**
		 * Function to show notice if WooCommerce is inactive
		 */
		public function sa_se_woocommerce_inactive_notice() {
			if ( current_user_can( 'activate_plugins' ) ) {
				if ( ! class_exists( 'WooCommerce' ) ) {
					?>
					<div id="message" class="error">
						<p>
						<?php
							printf(
								'<strong>%1$s</strong><br> <a href="%2$s" target="_blank">%3$s</a> %4$s. %5$s',
								esc_html__( 'Smart Emails needs WooCommerce', 'smart-emails' ),
								esc_url( 'https://wordpress.org/plugins/woocommerce/', 'smart-emails' ),
								esc_html__( 'WooCommerce', 'smart-emails' ),
								esc_html__( 'must be active for Smart Emails to work', 'smart-emails' ),
								esc_html__( 'Please install & activate WooCommerce.', 'smart-emails' )
							);
						?>
						</p>
					</div>
					<?php
				}
			}
		}

		/**
		 * Get list of active conflicting plugins if any
		 *
		 * @param array $active_plugins active plugins list.
		 * @return conflicting plugins
		 */
		public function se_check_for_conflicting_plugins( $active_plugins ) {
			$conflicting_plugins = array(
				'woocommerce-email-control/ec-email-control.php' => 'WooCoomerce Email Customizer by cxThemes',
				'woocommerce-email-customizer/woocommerce-email-customizer.php' => 'WooCoomerce Email Customizer by WooThemes',
				'yith-woocommerce-email-templates-premium/init.php' => 'YITH WooCommerce Email Templates',
				'woocommerce-pretty-emails/emailplus.php' => 'WooCommerce Pretty Emails by MB Creation',
				'kadence-woocommerce-email-designer/kadence-woocommerce-email-designer.php' => 'Kadence WooCommerce Email Designer by Kadence Themes',
			);

			foreach ( $conflicting_plugins as $plugin_file => $plugin_name ) {
				if ( in_array( $plugin_file, $active_plugins, true ) ) {
					$sa_se_active_conflicting_plugins[] = $plugin_name;
				}
			}

			if ( ! empty( $sa_se_active_conflicting_plugins ) ) {
				return $sa_se_active_conflicting_plugins;
			}

			return false;
		}

		/**
		 * Function to show notice if any conflicting plugin is active
		 */
		public function se_show_conflicting_notice() {
			global $se_active_conflicting_plugins;
			?>
			<div id="message" class="error">
				<p>
				<?php
					printf(
						'<strong>%1$s</strong><br> %2$s: <em>%3$s</em>',
						esc_html__( 'Email Customizer For WooCommerce (Smart Emails) is inactive due to conflicts', 'smart-emails' ),
						esc_html__( 'It may not function properly with these plugins and cannot be used while they are active', 'smart-emails' ),
						esc_html( implode( ', ', $se_active_conflicting_plugins ) )
					);
				?>
				</p>
			</div> 
			<?php
		}

		/**
		 * Displays Smart Emails Menu
		 */
		public function sa_se_admin_menu() {
			global $se_customizer_url;

			add_menu_page( __( 'Smart Emails', 'smart-emails' ), __( 'Smart Emails', 'smart-emails' ), 'manage_options', $se_customizer_url, '', SA_SE_PLUGIN_URL . 'assets/images/se-menu-icon.png', 58 );
		}

		/**
		 * Returns customizer url needed to load the customizer api
		 *
		 * @return customizer url
		 */
		public function get_customizer_url() {
			$se_customizer_url = add_query_arg(
				array(
					'url'             => rawurlencode( site_url( '/?sa_smart_emails=true' ) ),
					'return'          => rawurlencode( admin_url() ),
					'sa_smart_emails' => true,
				),
				'customize.php'
			);

			return $se_customizer_url;
		}

		/**
		 * Get plugin's metadata
		 */
		public static function get_smart_emails_plugin_data() {
			return get_plugin_data( SA_SE_PLUGIN_FILE );
		}


		/**
		 * Enqueues scripts and styles needed for template and style selection
		 */
		public function sa_se_enqueue_admin_scripts_styles() {
			global $se_customizer_url, $se_default_template, $se_default_style, $se_current_template, $se_current_style;

			if ( isset( $_GET['sa_smart_emails'] ) && true == $_GET['sa_smart_emails'] ) { // phpcs:ignore

				$plugin_data = self::get_smart_emails_plugin_data();
				$version     = $plugin_data['Version'];

				$se_template_ids = $this->get_list_of_woocommerce_emails();

				wp_register_script( 'sa-se-admin-js', SA_SE_PLUGIN_URL . 'assets/js/sa-se-admin.js', array( 'jquery' ), $version, true );

				if ( ! wp_script_is( 'sa-se-admin-js' ) ) {
					wp_enqueue_script( 'sa-se-admin-js' );
				}

				wp_register_style( 'sa-se-admin-css', SA_SE_PLUGIN_URL . 'assets/css/sa-se-admin.css', '', $version );

				if ( ! wp_style_is( 'sa-se-admin-css' ) ) {
					wp_enqueue_style( 'sa-se-admin-css' );
				}
				$currently_editing_style = ucfirst( ! empty( $se_current_style ) ? $se_current_style : $se_default_style );
				wp_localize_script(
					'sa-se-admin-js',
					'sa_se_params',
					array(
						'ajax_url'               => admin_url( 'admin-ajax.php' ),
						'se_template_ids'        => $se_template_ids,
						'se_customizer_url'      => admin_url() . $se_customizer_url,
						'se_current_template'    => ! empty( $se_current_template ) ? $se_current_template : $se_default_template,
						'se_current_style'       => ! empty( $se_current_style ) ? $se_current_style : $se_default_style,
						/* translators: placeholder is active emails style */
						'se_customizer_title'    => sprintf( __( 'WooCommerce Emails - %s!', 'smart-emails' ), $currently_editing_style ),
						'se_description'         => __( 'The Customizer allows you to preview live changes to your WooCommere Emails. You can select different WooCommerce Email Templates and Styles to preview and customize.', 'smart-emails' ),
						'se_email_confirm_style' => __( 'Are you sure you want to use this email theme for all of the WooCommmere emails sent from your site?', 'smart-emails' ),
						'se_email_send_notice'   => __( 'Test email sent successfully.', 'smart-emails' ),
						'se_email_error_notice'  => __( 'Please enter a valid email id.', 'smart-emails' ),
					)
				);
			}
		}

		/**
		 * Returns list of woocommerce emails that are send to only customers and not admin
		 *
		 * @return emails sent to customers
		 */
		public function get_list_of_woocommerce_emails() {
			global $woocommerce;

			// Exclude a few of WC subscription emails + PIP + Stripe email templates.
			$exclude_emails = array(
				'customer_payment_retry',
				'pip_email_invoice',
				'failed_renewal_authentication',
				'failed_preorder_sca_authentication',
				'failed_authentication_requested',
			);

			$mailer = $woocommerce->mailer();
			$mails  = $mailer->get_emails();

			foreach ( $mails as $mail_type ) {
				if ( empty( $mail_type->recipient ) ) {
					if ( ! in_array( $mail_type->id, $exclude_emails, true ) ) {
						$mail_titles[ $mail_type->id ] = $mail_type->title;
					}
				}
			}

			return $mail_titles;
		}

		/**
		 * Initialize customizer api class
		 */
		public function load_customizer_api() {
			include 'class-sa-se-customizer.php';
			new SA_SE_Customizer();
		}

		/**
		 * Modify CSS of body content only for WooCommerce & WooCommerce Subscription emails.
		 *
		 * @param string $css CSS to modify.
		 * @param object $email Object for email to be sent.
		 *
		 * @return conflicting plugins
		 */
		public function sa_se_add_css_to_emails( $css, $email ) {

			global $se_current_style;

			$se_styles = array( 'classic', 'deluxe', 'simple' );

			if ( in_array( $se_current_style, $se_styles, true ) ) {
				$se_wc_emails  = array( 'cancelled_order', 'failed_order', 'new_order', 'customer_completed_order', 'customer_invoice', 'customer_new_account', 'customer_note', 'customer_on_hold_order', 'customer_processing_order', 'customer_refunded_order', 'customer_reset_password' );
				$se_wcs_emails = array( 'new_renewal_order', 'new_switch_order', 'payment_retry', 'cancelled_subscription', 'customer_completed_renewal_order', 'customer_completed_switch_order', 'customer_payment_retry', 'customer_processing_renewal_order', 'customer_renewal_invoice', 'expired_subscription', 'on_hold_subscription' );
				$se_emails     = array_merge( $se_wc_emails, $se_wcs_emails );

				if ( in_array( $email->id, $se_emails, true ) ) {
					$css .= '#body_content_inner p { text-align: center !important; }';
				}
			}

			return $css;
		}

	}
}
