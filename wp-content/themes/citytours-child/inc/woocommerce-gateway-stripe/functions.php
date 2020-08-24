<?php
add_action( 'init', 'ot_gateway_stripe_init' );

/**
 * Enqueue custom gateway stripe scripts.
 */
function ot_gateway_stripe_init() {
	// wp_set_script_translations( 'ct_theme_script', 'citytours', get_template_directory() . '/languages' );
	add_action( 'wp_footer', 'ot_gateway_stripe_label' );
}

/**
 * Change 'Credit and Debit card' label.
 */
function ot_gateway_stripe_label() {
	?>
	<script type="text/javascript">
		jQuery( document ).ajaxComplete(function() {
			var { __ } = wp.i18n;
			var label = $("label[for='card-element']");

			if (label) {
				label.text(__('Credit Card', 'citytours'));
			}
		});
	</script>
	<?php
}
