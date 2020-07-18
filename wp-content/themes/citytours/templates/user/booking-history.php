<?php $user_id = get_current_user_id(); ?>

<div id="tools">
	<div class="row">
		<div class="col-md-3 col-sm-3 col-xs-6">
			<form class="booking-type-filter">
				<div class="styled-select-filters">
					<input type="hidden" name="action" value="update_booking_list">
					<?php 
						$available_modules = ct_get_available_modules();
					?>
					<select name="filter_type" id="filter_type">
						<option value="" selected><?php esc_html_e( 'Filter by type', 'citytours' ); ?></option>
						<?php if ( in_array( 'tour', $available_modules ) ) { ?>
							<option value="tour"><?php esc_html_e( 'Tours', 'citytours' ); ?></option>
						<?php } ?>
						<?php if ( in_array( 'hotel', $available_modules ) ) { ?>
							<option value="hotel"><?php esc_html_e( 'Hotels', 'citytours' ); ?></option>
						<?php } ?>
						<?php if ( in_array( 'car', $available_modules ) ) { ?>
							<option value="car"><?php esc_html_e( 'Transfers', 'citytours' ); ?></option>
						<?php } ?>
					</select>
				</div>
			</form>
		</div>
	</div>
</div>
<!--/tools -->
<div class="booking-history">
	<?php echo ct_get_user_booking_list( $user_id, '', -1, 'created', 'desc' ); ?>
</div>

<script>
jQuery(document).ready(function(){

	jQuery('#filter_type').on( 'change', function(){
		update_booking_list();
	});

	function update_booking_list(){
		jQuery.ajax({
			url: ajaxurl,
			type: "POST",
			data: jQuery('.booking-type-filter').serialize(),
			success: function(response){
				if ( response.success == 1 ) {
					jQuery('.booking-history').html(response.result);
				} else {
					jQuery('.booking-history').html(response.result);
				}
			}
		});
	}
});
</script>