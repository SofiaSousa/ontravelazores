/**
 * Smart Emails admin JS
 *
 * @package smart-emails/assets/js
 */

jQuery( document ).ready(
	function () {

		var template_ids     = sa_se_params.se_template_ids;
		var customizer_url   = sa_se_params.se_customizer_url;
		var current_template = sa_se_params.se_current_template;
		var current_style    = sa_se_params.se_current_style;
		var customizer_title = sa_se_params.se_customizer_title;
		var se_description   = sa_se_params.se_description;
		var confirm_style    = sa_se_params.se_email_confirm_style;
		var send_notice      = sa_se_params.se_email_send_notice;
		var error_notice     = sa_se_params.se_email_error_notice;
		var ajax_url         = sa_se_params.ajax_url;

		var sa_se_form = '<table class="customize_form">' +
							'<tr>' +
								'<td>' +
									'<form id="sa_se_form" method="post" action=' + customizer_url + '>' +
										'<table>' +
											'<tr>' +
												'<th>' +
													'<label for="sa_se_email_template">Select Template:</label>' +
												'</th>' +
												'<td>' +
													'<select id="sa_se_email_template" name="sa_se_email_template">' +
													'</select>' +
												'</td>' +
											'</tr>' +
										'</table>' +
											'<input type="hidden" value="' + current_style + '" id="sa_se_email_style" name="sa_se_email_style">' +
									'</form>' +
								'</td>' +
							'</tr>' +
						'</table>';

		// Add the template and style selection form in the customizer.
		jQuery( 'li#accordion-section-se_select_style' ).prepend( sa_se_form );

		// Add the list of available WooCommerce templates in the form dropdown.
		jQuery.each(
			template_ids,
			function ( template_id, template_name ) {
				jQuery( '#sa_se_email_template' ).append(
					jQuery(
						'<option>',
						{
							value: template_id,
							text: template_name
						}
					)
				);
			}
		);

		// Keep the template selected in the dropdown after the page refreshes on change of template.
		jQuery( '#sa_se_email_template' ).find( 'option[ value=' + current_template + ']' ).prop( 'selected', true );

		// Submit the form on change of email templates.
		jQuery( '#sa_se_email_template' ).on(
			'change',
			function() {
				this.form.submit();
			}
		);

		// Submit the form on change of email styles.
		jQuery( '.email_styles .styles .use_this_button' ).on(
			'click',
			function() {
				var use_theme = confirm( confirm_style );

				if ( use_theme == true  ) {
					var current_style = this.id;
					jQuery( '#sa_se_email_style' ).val( current_style );
					jQuery( '#sa_se_form' ).submit();
				}
			}
		);

		// Change cutomizer title and decription.
		if ( jQuery( '.customize_form' ).length == 1 ) {
			// Replace the text that shows the site name in the customizer.
			jQuery( '.accordion-section-title .preview-notice .panel-title' ).html( '<i>' + customizer_title + '</i>' );

			// Replace the description.
			jQuery( '.customize-panel-description' ).text( se_description );
		}

		// Initially disable the send mail button.
		jQuery( '#se_send_test_mail' ).prop( 'disabled', true );

		// Disable the send button if email field is empty.
		jQuery( '#se_test_email' ).on(
			'keyup',
			function() {
				if ( jQuery( this ).val().length > 0 ) {
					jQuery( '#se_send_test_mail' ).prop( 'disabled', false );
				} else {
					jQuery( '#se_send_test_mail' ).prop( 'disabled', true );
				}
			}
		);

		// Validate the email address.
		function isEmail( email_id ) {
			var regex = /^([a-zA-Z0-99_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			return regex.test( email_id );
		}

		// Send Test Email.
		jQuery( '#se_send_test_mail' ).on(
			'click',
			function(e) {
				e.preventDefault();

				email_id = jQuery( '#se_test_email' ).val();
				if ( isEmail( email_id ) ) {
					jQuery.ajax(
						{
							url: ajax_url,
							dataType: 'json',
							data: {
								action: 'sa_send_test_email',
								email_id: email_id
							},
							success: function( response ) {
								if ( response != 0  ) {
									alert( send_notice );
									jQuery( '#se_test_email' ).val( '' );
									jQuery( '#se_send_test_mail' ).prop( 'disabled', true );
								}
							},
						}
					);
				} else {
					alert( error_notice );
				}
			}
		);
	}
);
