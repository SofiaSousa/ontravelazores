<?php
/* Tour Cart Page Template */
if ( ! defined( 'ABSPATH' ) ) { 
    exit; 
}

global $ct_options;

// validation
$required_params = array( 'uid' );
foreach ( $required_params as $param ) {
	if ( ! isset( $_REQUEST[ $param ] ) ) {
		return;
		do_action( 'ct_tour_booking_wrong_data' ); // ct_redirect_home() - if data is not valid return to home
		exit;
	}
}

// init variables
$uid = $_REQUEST['uid'];
if ( ! CT_Hotel_Cart::get( $uid ) ) {
	do_action( 'ct_tour_booking_wrong_data' ); // ct_redirect_home() - if data is not valid return to home
	exit;
}

$cart = new CT_Hotel_Cart();
$tour_id = $cart->get_field( $uid, 'tour_id' );
$date = $cart->get_field( $uid, 'date' );
$time = $cart->get_field( $uid, 'time' );
$cart_tour = $cart->get_field( $uid, 'tour' );
$adults = $cart_tour['adults'];
$kids = $cart_tour['kids'];
$cart_service = $cart->get_field( $uid, 'add_service' );
$user_info = ct_get_current_user_info();
$_countries = ct_get_all_countries();
$deposit_rate = get_post_meta( $tour_id, '_tour_security_deposit', true );
$deposit_rate = empty( $deposit_rate ) ? 0 : $deposit_rate;

// function
if ( ! ct_get_tour_thankyou_page() ) { 
	?>

	<h5 class="alert alert-warning">
		<?php echo esc_html__( 'Please set booking confirmation page in theme options panel.', 'citytours' ) ?>
	</h5>

	<?php 
} else { 
	?>

	<form id="booking-form" action="<?php echo esc_url( ct_get_tour_thankyou_page() ); ?>">

		<div class="row">

			<div class="col-md-8">

				<?php do_action( 'tour_checkout_main_before' ); ?>

				<div class="form_title">
					<h3><strong>1</strong><?php echo esc_html__( 'Your Details', 'citytours' ) ?></h3>
					<p><?php echo esc_html__( 'Please fill your detail.', 'citytours' ) ?></p>
				</div>

				<div class="step">

					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<label><?php echo esc_html__( 'First name', 'citytours' ) ?></label>
								<input type="text" class="form-control" name="first_name" value="<?php echo esc_attr( $user_info['first_name'] ) ?>">
							</div>
						</div>

						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<label><?php echo esc_html__( 'Last name', 'citytours' ) ?></label>
								<input type="text" class="form-control" name="last_name" value="<?php echo esc_attr( $user_info['last_name'] ) ?>">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<label><?php echo esc_html__( 'Email', 'citytours' ) ?></label>
								<input type="email" name="email" class="form-control" value="<?php echo esc_attr( $user_info['email'] ) ?>">
							</div>
						</div>

						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<label><?php echo esc_html__( 'Confirm email', 'citytours' ) ?></label>
								<input type="email" name="email2" class="form-control">
							</div>
						</div>
					</div>

					 <div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<label><?php echo esc_html__( 'Telephone', 'citytours' ) ?></label>
								<input type="text" name="phone" class="form-control" value="<?php echo esc_attr( $user_info['phone'] ) ?>">
							</div>
						</div>
					</div>

				</div><!--End step -->

				<div class="form_title">
					<h3><strong>2</strong><?php echo esc_html__( 'Your Address', 'citytours' ) ?></h3>
					<p><?php echo esc_html__( 'Please write your address detail', 'citytours' ) ?></p>
				</div>

				<div class="step">

					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<label><?php echo esc_html__( 'Country', 'citytours' ) ?></label>

								<select class="form-control" name="country" id="country">
									<option value="" selected><?php echo esc_html__( 'Select your country', 'citytours' ) ?></option>
									<?php 
									foreach ( $_countries as $_country ) { 
										?>
										<option value="<?php echo esc_attr( $_country['code'] ) ?>" <?php selected( $user_info['country_code'], $_country['code'] ); ?>><?php echo esc_html( $_country['name'] ) ?></option>
										<?php 
									} 
									?>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<label><?php echo esc_html__( 'Street line 1', 'citytours' ) ?></label>
								<input type="text" name="address1" class="form-control" value="<?php echo esc_attr( $user_info['address1'] ) ?>">
							</div>
						</div>

						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<label><?php echo esc_html__( 'Street line 2', 'citytours' ) ?></label>
								<input type="text" name="address2" class="form-control" value="<?php echo esc_attr( $user_info['address2'] ) ?>">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label><?php echo esc_html__( 'City', 'citytours' ) ?></label>
								<input type="text" name="city" class="form-control" value="<?php echo esc_attr( $user_info['city'] ) ?>">
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label><?php echo esc_html__( 'State', 'citytours' ) ?></label>
								<input type="text" name="state" class="form-control" value="<?php echo esc_attr( $user_info['state'] ) ?>">
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label><?php echo esc_html__( 'Postal code', 'citytours' ) ?></label>
								<input type="text" name="zip" class="form-control" value="<?php echo esc_attr( $user_info['zip'] ) ?>">
							</div>
						</div>
					</div>

				</div><!--End step -->

			<?php if ( ! empty( $ct_options['pay_paypal'] ) ) : ?>
				<div class="form_title">
					<h3><strong>3</strong><?php echo esc_html__( 'Payment Information', 'citytours' ) ?></h3>

					<?php if ( ! empty( $ct_options['credit_card'] ) ) { ?>
					<p><?php echo esc_html__( 'Please select payment type', 'citytours' ) ?></p>
					<?php } else { ?>
					<p><?php echo esc_html__( 'You"ll be redirected to paypal to pay for this tour', 'citytours' ) ?></p>
					<?php } ?>
				</div>

				<div class="step">

					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<?php if ( ! empty( $ct_options['credit_card'] ) ) { ?>
								<input class="form-radio-control" type="radio" name="payment_info" id="paypal_payment" value="paypal" checked>
								<label for="paypal_payment"><?php echo esc_html__( 'Paypal', 'citytours' ) ?></label>
								<br/>
								<?php } ?>
								<img src="https://www.paypalobjects.com/webstatic/mktg/Logo/AM_SbyPP_mc_vs_ms_ae_UK.png" alt="PayPal Acceptance Mark">
								<a href="https://www.paypal.com/us/webapps/mpp/paypal-popup" class="about_paypal" onclick="javascript:window.open('https://www.paypal.com/us/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false;" title="What is PayPal?"><?php echo esc_html__( 'What is PayPal?', 'citytours' ) ?></a>
							</div>

							<div id="paypal-container">
								<p class="paypal_desc"><?php echo esc_html__( 'Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.', 'citytours' ) ?></p>
							</div>

							<?php if ( ! empty( $ct_options['credit_card'] ) ) { ?>

							<div class="form-group">
								<input class="form-radio-control" type="radio" name="payment_info" id="cc_payment" value="cc">
								<label for="cc_payment"><?php echo esc_html__( 'Credit Card', 'citytours' ) ?></label>
							</div>

							<?php $billing_credircard = isset($_REQUEST['billing_credircard'])? esc_attr($_REQUEST['billing_credircard']) : ''; ?>
							<!-- Credit Card Payment -->
							<div id="cc-container" style="display:none;">
								<div class="row">
									<div class="col-md-6 col-sm-6">
										<div class="form-group">
											<label><?php echo esc_html__( 'Card Number', 'citytours' ) ?></label>
											<input class="form-control" type="text" size="19" maxlength="19" name="billing_credircard" value="<?php echo esc_attr( $billing_credircard ); ?>" />
										</div>
									</div>

									<div class="col-md-6 col-sm-6">
										<div class="form-group">
											<label><?php echo esc_html__( 'Card Type', 'citytours' ) ?></label>
											<select name="billing_cardtype" class="form-control">
												<option value="Visa" selected="selected">Visa</option>
												<option value="MasterCard">MasterCard</option>
												<option value="Discover">Discover</option>
												<option value="Amex">American Express</option>
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 col-sm-6">
										<div class="form-group">
											<label><?php echo esc_html__( 'Expiration Date', 'citytours' ) ?></label>

											<div class="row">
												<div class="col-md-6 col-sm-6">
													<select name="billing_expdatemonth" class="form-control">
														<option value=1>01</option>
														<option value=2>02</option>
														<option value=3>03</option>
														<option value=4>04</option>
														<option value=5>05</option>
														<option value=6>06</option>
														<option value=7>07</option>
														<option value=8>08</option>
														<option value=9>09</option>
														<option value=10>10</option>
														<option value=11>11</option>
														<option value=12>12</option>
													</select>
												</div>

												<div class="col-md-6 col-sm-6">
													<select name="billing_expdateyear" class="form-control">
														<?php
														$today = (int)date('Y', time());
														for($i = 0; $i < 8; $i++) {
														?>
															<option value="<?php echo esc_attr( $today ); ?>"><?php echo esc_html( $today ); ?></option>
														<?php
															$today++;
														} ?>
													</select>
												</div>
											</div>
										</div>
									</div>

									<div class="col-md-6 col-sm-6">
										<div class="form-group">
											<label><?php echo esc_html__( 'Card Verification Number (CVV)', 'citytours' ) ?></label>
											<input class="form-control" type="text" size="4" maxlength="4" name="billing_ccvnumber" value="" />
										</div>
									</div>
								</div>
							</div>

							<?php } ?>

							<!-- End Credit Card Payment -->
						</div>

					</div>

				</div><!--End step -->
			<?php endif; ?>

				<div id="policy">

					<?php 
					if ( ! empty( $ct_options['tour_terms_page'] ) ) : 
						?>

						<h4><?php echo esc_html__( 'Cancellation policy', 'citytours' ) ?></h4>

						<div class="form-group">
							<label><input name="agree" value="agree" type="checkbox" checked><?php printf( __('By continuing, you agree to the <a href="%s" target="_blank"><span class="skin-color">Terms and Conditions</span></a>.', 'citytours' ), ct_get_permalink_clang( $ct_options['tour_terms_page'] ) ) ?></label>
						</div>

						<?php 
					endif; 
					?>

					<button type="submit" class="btn_1 green medium book-now-btn book-now-btn1"><?php echo esc_html__( 'Book now', 'citytours' ) ?></button>
				</div>

				<?php do_action( 'tour_checkout_main_after' ); ?>

			</div>
			

			<aside class="col-md-4">

				<?php do_action( 'tour_checkout_sidebar_before' ); ?>

				<div class="box_style_1">
					<h3 class="inner"><?php echo esc_html__( '- Summary -', 'citytours' ) ?></h3>

					<table class="table table_summary">
						<tbody>
							<?php if ( ! empty( $date ) ) : ?>
							<tr>
								<td><?php echo esc_html__( 'Date', 'citytours' ) ?></td>
								<td class="text-right"><?php echo date_i18n( 'j F Y', ct_strtotime( $date ) ); ?></td>
							</tr>
							<?php endif; ?>

							<?php if ( ! empty( $time ) ) : ?>
							<tr>
								<td><?php echo esc_html__( 'Time', 'citytours' ) ?></td>
								<td class="text-right"><?php echo esc_html( $time ); ?></td>
							</tr>
							<?php endif; ?>

							<tr>
								<td><?php echo esc_html__( 'Adults', 'citytours' ) ?></td>
								<td class="text-right"><?php echo esc_html( $adults ) ?></td>
							</tr>

								<tr>
									<td><?php echo esc_html__( 'Children', 'citytours' ) ?></td>
									<td class="text-right"><?php echo esc_html( $kids ) ?></td>
								</tr>

							<?php 
							if ( ! empty( $cart_service ) ) {
								foreach ( $cart_service as $key => $service ) { 
									?>

									<tr>
										<td><?php echo esc_html( $service['title'] ) ?></td>
										<td class="text-right"><?php echo ct_price( $service['total'] ); ?></td>
									</tr>

									<?php 
								}
							} 
							?>

							<tr class="total">
								<td><?php echo esc_html__( 'Total cost', 'citytours' ) ?></td>
								<td class="text-right"><?php $total_price = $cart->get_field( $uid, 'total_price' ); if ( ! empty( $total_price ) ) echo ct_price( $total_price ) ?></td>
							</tr>

							<?php if ( ! empty( $deposit_rate ) && $deposit_rate < 100 ) : ?>
								<tr>
									<td><?php echo sprintf( esc_html__( 'Security Deposit (%d%%)', 'citytours' ), $deposit_rate ) ?></td>
									<td class="text-right"><?php if ( ! empty( $total_price ) ) echo ct_price( $total_price * $deposit_rate / 100 ) ?></td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>

					<button type="submit" class="btn_full book-now-btn"><?php echo esc_html__( 'Book now', 'citytours' ) ?></button>

					<a class="btn_full_outline" href="<?php echo esc_url( get_permalink( $tour_id ) ) ?>">
						<i class="icon-right"></i> <?php echo esc_html__( 'Modify your search', 'citytours' ) ?>
					</a>

					<input type="hidden" name="action" value="ct_tour_submit_booking">
					<input type="hidden" name="order_id" id="order_id" value="0">
					<input type="hidden" name="uid" value="<?php echo esc_attr( $uid ) ?>">

					<?php wp_nonce_field( 'checkout' ); ?>
				</div>

				<?php do_action( 'tour_checkout_sidebar_after' ); ?>

			</aside>

		</div><!--End row -->
	</form>

	<script type="text/javascript">
		var ajaxurl = '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ) ?>';

		jQuery(document).ready( function($){
			var validation_rules = {
				first_name: { required: true},
				last_name: { required: true},
				email: { required: true, email: true},
				email2: { required: true, equalTo: 'input[name="email"]'},
				phone: { required: true},
				address1: { required: true},
				city: { required: true},
				zip: { required: true},
			};

			//validation form
			$('#booking-form').validate({
				rules: validation_rules,
				submitHandler: function (form) {
					if ( $('input[name="agree"]').length ) {
						if ( $('input[name="agree"]:checked').length == 0 ) {
							alert("<?php echo esc_html__( 'Agree to terms&conditions is required' ,'citytours' ); ?>");
							return false;
						}
					}

					var booking_data = $('#booking-form').serialize();
					$('#overlay').fadeIn();

					$.ajax({
						type: "POST",
						url: ajaxurl,
						data: booking_data,
						success: function ( response ) {
							if ( response.success == 1 ) {
								if ( response.result.payment == 'woocommerce' ) {
									<?php if ( function_exists( 'ct_woo_get_cart_page_url' ) && ct_woo_get_cart_page_url() ) { ?>
										window.location.href = '<?php echo esc_js( ct_woo_get_cart_page_url() ); ?>';
									<?php } else { ?>
										alert("<?php echo esc_js( esc_html__( 'Please set woocommerce cart page', 'citytours' ) ); ?>");
									<?php } ?>
									$('#overlay').fadeOut();
								} else {
									if ( response.result.payment == 'paypal' ) {
										$('.book-now-btn1').before('<div class="alert alert-success"><?php echo esc_js( esc_html__( 'You will be redirected to paypal.', 'citytours' ) ) ?><span class="close"></span></div>');
									}
									var confirm_url = $('#booking-form').attr('action');
									if ( confirm_url.indexOf('?') > -1 ) {
										confirm_url = confirm_url + '&';
									} else {
										confirm_url = confirm_url + '?';
									}
									confirm_url = confirm_url + 'booking_no=' + response.result.booking_no + '&pin_code=' + response.result.pin_code;
									if ( response.result.payment_info == 'paypal' ) { 
										confirm_url += '&payment_info=paypal';
									}
									$('.book-now-btn').hide();
									window.location.href = confirm_url;
								}
							} else if ( response.success == -1 ) {
								alert( response.result );
								window.location.href = '';
							} else {
								if ( response.order_id != 0 ) { 
									$('#order_id').val( response.order_id );
								}
								alert( response.result );
								$('#overlay').fadeOut();
							}
						}
					});
					
					return false;
				}
			});

			$('.form-radio-control').on('change', function(){ 
				if ( $(this).val() == 'cc' ) { 
					$('#cc-container').show();
					$('#paypal-container').hide();
				} else { 
					$('#cc-container').hide();
					$('#paypal-container').show();
				}
			});
		});
	</script>

<?php } ?>