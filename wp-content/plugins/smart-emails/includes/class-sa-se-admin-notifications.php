<?php
/**
 * Notification file for Smart Emails
 *
 * @author      StoreApps
 * @since       1.3.2
 * @version     1.0.1
 * @package     Smart Emails
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SA_SE_Admin_Notifications' ) ) {

	/**
	 * Class for handling and showing notifications in Smart Emails
	 */
	class SA_SE_Admin_Notifications {

		/**
		 * Constructor
		 */
		public function __construct() {

			add_filter( 'sa_is_page_for_notifications', array( $this, 'sa_se_is_page_for_notifications' ), 10, 2 );

			add_action( 'admin_notices', array( $this, 'smart_emails_admin_notice' ) );
			add_action( 'admin_init', array( $this, 'smart_emails_dismiss_admin_notice' ) );

			$this->may_be_show_sa_in_app_offer();

		}

		/**
		 * To determine whether to show notification on a page or not.
		 *
		 * @param bool  $is_page True/false.
		 * @param mixed $upgrader StoreApps Upgrader object.
		 * @return bool $is_page True/false.
		 */
		public function sa_se_is_page_for_notifications( $is_page = false, $upgrader = null ) {
			global $pagenow;

			if ( ( 'customize.php' === $pagenow ) && ( isset( $_GET ) && isset( $_GET['sa_smart_emails'] ) && sanitize_text_field( wp_unslash( true == $_GET['sa_smart_emails'] ) ) ) ) { // phpcs:ignore
				$is_page = true;
			}

			return $is_page;
		}

		/**
		 * Function to show admin notice
		 */
		public function smart_emails_admin_notice() {

			$sa_text_change_notice_smart_emails = get_option( 'sa_text_change_notice_smart_emails' );
			if ( 'no' === $sa_text_change_notice_smart_emails ) {
				return;
			}

			global $typenow;
			if ( 'product' === $typenow || 'shop_order' === $typenow ) {
				?>
				<style type="text/css">
					a.se-admin-btn-secondary {
						font-weight: 400;
						text-decoration: none;
						color: #aeb3b5;
						float: right;
					}
				</style>
				<?php

				$admin_notice_text = __( '<a href="https://www.storeapps.org/product/smart-emails/" target="_blank">Email Customizer For WooCommerce</a> plugin has updated text in emails. If you have translated any email text, you might want to <b>check and update your translations</b>.', 'smart-emails' );

				echo '<div class="notice notice-warning se-upgrade"><p>' . wp_kses_post( $admin_notice_text ) . '<a style="display:inline-block" class="se-admin-btn-secondary" href="?smart_emails_dismiss_admin_notice=1&option_name=sa_text_change_notice">' . esc_html__( 'Okay, got it', 'smart-emails' ) . '</a></p></div>'; // phpcs:ignore
			}

		}

		/**
		 * Function to dismiss admin notice
		 */
		public function smart_emails_dismiss_admin_notice() {

			if ( isset( $_GET['smart_emails_dismiss_admin_notice'] ) && $_GET['smart_emails_dismiss_admin_notice'] == '1' && isset( $_GET['option_name'] ) ) { // phpcs:ignore
				$option_name = sanitize_text_field( wp_unslash( $_GET['option_name'] ) ); // phpcs:ignore
				update_option( $option_name . '_smart_emails', 'no', 'no' );
				$referer = wp_get_referer();
				wp_safe_redirect( $referer );
				exit();
			}
		}

		/**
		 * Function to show Halloween offer in app
		 */
		public function may_be_show_sa_in_app_offer() {
			if ( ! class_exists( 'SA_In_App_Offer' ) ) {
				include_once dirname( __FILE__ ) . '/../sa-includes/class-sa-in-app-offer.php';
				$args = array(
					'file'        => SA_SE_PLUGIN_FILE,
					'prefix'      => 'se',
					'option_name' => 'sa_offer_halloween_2018',
					'campaign'    => 'sa_halloween_2018',
					'start'       => '2018-10-30 00:00:00',
					'end'         => '2018-11-03 10:00:00',
				);

				SA_In_App_Offer::get_instance( $args );
			}
		}

	}

	return new SA_SE_Admin_Notifications();

}
