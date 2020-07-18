<?php
/**
 * Allows to test send emails
 *
 * @package smart-emails/includes
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SA_SE_Send_Test_Email' ) ) {

	/**
	 * Adds custom fields to send test email
	 */
	class SA_SE_Send_Test_Email extends WP_Customize_Control {

		/**
		 * Custom control type is needed as we are not using any of the customizer default controls to send test emails.
		 *
		 * @var string
		 */
		public $type = 'test_email';

		/**
		 * Controls to send test email of the currently previewd template
		 */
		public function render_content() {
			?>
			<form id='se_send_email' method="post">
				<label for="se_test_email"><?php esc_html_e( 'Email Address', 'smart-emails' ); ?></label>
				<input type="email" id="se_test_email"> 
				<br><br>
				<button class="button button-primary " id="se_send_test_mail"><?php esc_html_e( 'Send', 'smart-emails' ); ?></button>
			</form>	
			<?php
		}
	}
}
