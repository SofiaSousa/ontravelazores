<div class="row">
	<div class="col-md-6 col-sm-6 add_bottom_30">
		<form id="update_password_form" method="post">
			<h4><?php echo __( 'Change your password', 'citytours' ); ?></h4>
			<div class="alert alert-error" style="display:none;" data-success="<?php echo __( 'Password successfully changed.', 'citytours' ) ?>" data-mismatch="<?php echo __( 'Password mismatch.', 'citytours' ) ?>" data-empty="<?php echo __( 'Password cannot be empty.', 'citytours' ) ?>"><span class="message"></span><span class="close"></span></div>
			<input type="hidden" name="action" value="update_password">
			<?php wp_nonce_field( 'update_password' ); ?>
			<div class="form-group">
				<label><?php echo __( 'Old password', 'citytours' ); ?></label>
				<input class="form-control" name="old_pass" id="old_password" type="password">
			</div>
			<div class="form-group">
				<label><?php echo __( 'New password', 'citytours' ); ?></label>
				<input class="form-control" name="pass1" id="new_password" type="password">
			</div>
			<div class="form-group">
				<label><?php echo __( 'Confirm new password', 'citytours' ); ?></label>
				<input class="form-control" name="pass2" id="confirm_new_password" type="password">
			</div>
			<button type="submit" class="btn_1 green"><?php echo __( 'Update Password', 'citytours' ); ?></button>
		</form>
	</div>
	<div class="col-md-6 col-sm-6 add_bottom_30">
		<form id="update_email_form" method="post">
			<h4><?php echo __( 'Change your email', 'citytours' ); ?></h4>
			<div class="alert alert-error" style="display:none;" data-success="<?php echo __( 'Email successfully changed.', 'citytours' ) ?>" data-mismatch="<?php echo __( 'Email mismatch.', 'citytours' ) ?>" data-empty="<?php echo __( 'Email cannot be empty.', 'citytours' ) ?>"><span class="message"></span><span class="close"></span></div>
			<input type="hidden" name="action" value="update_email">
			<?php wp_nonce_field( 'update_email' ); ?>			
			<div class="form-group">
				<label><?php echo __( 'New email', 'citytours' ); ?></label>
				<input class="form-control" name="email1" id="new_password" type="email">
			</div>
			<div class="form-group">
				<label><?php echo __( 'Confirm new email', 'citytours' ); ?></label>
				<input class="form-control" name="email2" id="confirm_new_password" type="email">
			</div>
			<button type="submit" class="btn_1 green"><?php echo __( 'Update Email', 'citytours' ); ?></button>
		</form>
	</div>
</div>
<!-- End row -->

<script>
	jQuery(document).ready(function(){
		jQuery('#update_password_form input').change(function(){
			jQuery('#update_password_form .alert').hide();
		});
		jQuery('#update_password_form').submit(function(){

			var pass1 = jQuery('input[name="pass1"]').val();
			var pass2 = jQuery('input[name="pass2"]').val();

			// validation
			if ( pass1 == '' || pass2 == '' ) {
				jQuery('#update_password_form .alert').removeClass('alert-success');
				jQuery('#update_password_form .alert').addClass('alert-error');
				jQuery('#update_password_form .alert .message').text( jQuery('#update_password_form .alert').data('empty') );
				jQuery('#update_password_form .alert').show();
				return false;
			}

			if ( pass1 != pass2 ) {
				jQuery('#update_password_form .alert').removeClass('alert-success');
				jQuery('#update_password_form .alert').addClass('alert-error');
				jQuery('#update_password_form .alert .message').text( jQuery('#update_password_form .alert').data('mismatch') );
				jQuery('#update_password_form .alert').show();
				return false;
			}

			update_data = jQuery("#update_password_form").serialize();
			jQuery.ajax({
				url: ajaxurl,
				type: "POST",
				data: update_data,
				success: function(response){
					if ( response.success == 1 ) {
						jQuery('#update_password_form .alert').removeClass('alert-error');
						jQuery('#update_password_form .alert').addClass('alert-success');
						jQuery('#update_password_form .alert .message').text( jQuery('#update_password_form .alert').data('success') );
						jQuery('#update_password_form .alert').show();
					} else {
						jQuery('#update_password_form .alert').removeClass('alert-success');
						jQuery('#update_password_form .alert').addClass('alert-error');
						jQuery('#update_password_form .alert .message').text( response.result );
						jQuery('#update_password_form .alert').show();
					}
				}
			});
			return false;
		});

		jQuery('#update_email_form input').change(function(){
			jQuery('#update_email_form .alert').hide();
		});
		jQuery('#update_email_form').submit(function(){

			var email1 = jQuery('input[name="email1"]').val();
			var email2 = jQuery('input[name="email2"]').val();

			// validation
			if ( email1 == '' || email2 == '' ) {
				jQuery('#update_email_form .alert').removeClass('alert-success');
				jQuery('#update_email_form .alert').addClass('alert-error');
				jQuery('#update_email_form .alert .message').text( jQuery('#update_email_form .alert').data('empty') );
				jQuery('#update_email_form .alert').show();
				return false;
			}

			if ( email1 != email2 ) {
				jQuery('#update_email_form .alert').removeClass('alert-success');
				jQuery('#update_email_form .alert').addClass('alert-error');
				jQuery('#update_email_form .alert .message').text( jQuery('#update_email_form .alert').data('mismatch') );
				jQuery('#update_email_form .alert').show();
				return false;
			}

			update_data = jQuery("#update_email_form").serialize();
			jQuery.ajax({
				url: ajaxurl,
				type: "POST",
				data: update_data,
				success: function(response){
					if ( response.success == 1 ) {
						jQuery('#update_email_form .alert').removeClass('alert-error');
						jQuery('#update_email_form .alert').addClass('alert-success');
						jQuery('#update_email_form .alert .message').text( jQuery('#update_email_form .alert').data('success') );
						jQuery('#update_email_form .alert').show();
					} else {
						jQuery('#update_email_form .alert').removeClass('alert-success');
						jQuery('#update_email_form .alert').addClass('alert-error');
						jQuery('#update_email_form .alert .message').text( response.result );
						jQuery('#update_email_form .alert').show();
					}
				}
			});
			return false;
		});
	});
</script>