<?php
/* Tour Cart Page Template */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// validation
$required_params = array( 'tour_id' );
foreach ( $required_params as $param ) {
	if ( ! isset( $_REQUEST[ $param ] ) ) {
		return;
		do_action( 'ct_tour_booking_wrong_data' ); // ct_redirect_home() - if data is not valid return to home
		exit;
	}
}

// init variables
$tour_id      = $_REQUEST['tour_id'];
$is_repeated  = get_post_meta( $tour_id, '_tour_repeated', true );
$tour_date    = get_post_meta( $tour_id, '_tour_date', true );
$charge_child = get_post_meta( $tour_id, '_tour_charge_child', true );
$price_type = get_post_meta( $tour_id, '_tour_price_type', true );
$deposit_rate = get_post_meta( $tour_id, '_tour_security_deposit', true );
$deposit_rate = empty( $deposit_rate ) ? 0 : $deposit_rate;
$add_services = ct_get_add_services_by_postid( $tour_id );
$time = '';
$date = '';
if ( ! empty( $is_repeated ) ) {
	if ( empty( $_REQUEST['date'] ) ) {
		do_action( 'ct_tour_booking_wrong_data' ); // ct_redirect_home() - if data is not valid return to home
		exit;
	}
	$date = $_REQUEST['date'];
	$time = ( isset( $_REQUEST['time'] ) ) ? $_REQUEST['time'] : '';
} else if ( ! empty( $tour_date ) ) {
	$date = $tour_date;
	$time = '';
}

$adults      = ( isset( $_REQUEST['adults'] ) ) ? $_REQUEST['adults'] : 1;
$kids        = ( isset( $_REQUEST['kids'] ) ) ? $_REQUEST['kids'] : 0;

$uid = $tour_id . $date;
if ( $cart_data = CT_Hotel_Cart::get( $uid ) ) {
	// init booking info if cart is not empty

	$total_price = ct_tour_calc_tour_price( $tour_id, $date, $adults, $kids );

	$cart_data['total_price'] -= $cart_data['tour']['total'];

	$cart_data['tour']['adults'] = $adults;
	$cart_data['tour']['kids'] = $kids;
	$cart_data['tour']['total'] = $total_price;
	$cart_data['total_adults'] = $adults;
	$cart_data['total_kids'] = $kids;
	$cart_data['time'] = $time;

	$cart_data['total_price'] = $cart_data['total_price'] + $total_price;

	CT_Hotel_Cart::set( $uid, $cart_data );
} else {
	// init cart if it is empty

	$total_price = ct_tour_calc_tour_price( $tour_id, $date, $adults, $kids );
	$cart_data   = array(
		'tour'          => array(
			'adults'    => $adults,
			'kids'      => $kids,
			'total'     => $total_price,
		),
		'tour_id'       => $tour_id,
		'date'          => $date,
		'time'			=> $time,
		'total_adults'  => $adults,
		'total_kids'    => $kids,
		'total_price'   => $total_price,
	);

	CT_Hotel_Cart::set( $uid, $cart_data );
}

$cart = new CT_Hotel_Cart();
$cart_service = $cart->get_field( $uid, 'add_service' );
$ct_tour_checkout_page_url = apply_filters( 'ct_get_woocommerce_cart_url', ct_get_tour_checkout_page() );

// main function
if ( ! $ct_tour_checkout_page_url ) {
	?>

	<h5 class="alert alert-warning"><?php echo esc_html__( 'Please set checkout page in theme options panel.', 'citytours' ) ?></h5>

	<?php
} else {

	// function
	$is_available = ct_tour_check_availability( $tour_id, $date, $time, $adults, $kids );
	if ( true === $is_available ) : ?>

	<form id="tour-cart" action="<?php echo esc_url( add_query_arg( array('uid'=> $uid), $ct_tour_checkout_page_url ) ); ?>">

		<div class="row">

			<div class="col-md-8">

				<?php do_action( 'tour_cart_main_before' ); ?>

				<table class="table table-striped cart-list tour add_bottom_30">
					<thead>
						<tr>
							<th><?php echo esc_html__( 'Item', 'citytours' ) ?></th>
							<th><?php echo esc_html__( 'Adults', 'citytours' ) ?></th>
							<th><?php echo esc_html__( 'Children', 'citytours' ) ?></th>
							<th><?php echo esc_html__( 'Total', 'citytours' ) ?></th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td data-title="<?php esc_attr_e( 'Item', 'citytours' ) ?>">
								<div class="thumb_cart">
									<a href="<?php echo esc_url( get_permalink( $tour_id ) ) ?>" data-toggle="modal" data-target="#tour-<?php echo esc_attr( $tour_id ) ?>"><?php echo get_the_post_thumbnail( $tour_id, 'thumbnail' ); ?></a>
								</div>
								 <span class="item_cart"><a href="#" data-toggle="modal" data-target="#tour-<?php echo esc_attr( $tour_id ) ?>"><?php echo esc_html( get_the_title( $tour_id ) ); ?></a></span>
							</td>
							<td data-title="<?php esc_attr_e( 'Adults', 'citytours' ) ?>">
								<div class="numbers-row" data-min="1">
									<input type="text" class="qty2 form-control tour-adults" name="adults" value="<?php echo esc_attr( $adults ) ?>">

									<div class="inc button_inc">+</div>
									<div class="dec button_inc">-</div>
								</div>
							</td>
							<td data-title="<?php esc_attr_e( 'Children', 'citytours' ) ?>">
								<div class="numbers-row" data-min="0">
									<input type="text" class="qty2 form-control tour-kids" name="kids" value="<?php echo esc_attr( $kids ) ?>" <?php if ( ! empty( $price_type ) && $price_type != 'per_group' && empty( $charge_child ) ) echo 'disabled'; ?>>

									<div class="inc button_inc">+</div>
									<div class="dec button_inc">-</div>
								</div>
							</td>
							<td data-title="<?php esc_attr_e( 'Total', 'citytours' ) ?>">
								<strong><?php if ( ! empty( $total_price ) ) echo ct_price( $total_price ) ?></strong>
							</td>
						</tr>
					</tbody>
				</table>

				<?php if ( ! empty( $add_services ) ) : ?>
					<table class="table table-striped options_cart">
						<thead>
							<tr>
								<th colspan="4"><?php echo esc_html__( 'Add options / Services', 'citytours' ) ?></th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ( $add_services as $service ) : ?>
								<tr>
									<td>
										<i class="<?php echo esc_attr( $service->icon_class ); ?>"></i>
									</td>
									<td>
										<?php echo esc_attr( $service->title ); ?>
										<strong>+<?php echo ct_price( $service->price ); ?></strong>
									</td>
									<td>
										<?php
										$field_name = 'add_service_' . esc_attr( $service->id );

										if ( ! empty( $cart_service ) && ! empty( $cart_service[ $service->id ] ) ) {
											$temp_value = isset( $cart_service[$service->id]['qty'] ) ? $cart_service[$service->id]['qty'] : 1;
										} else {
											$temp_value = isset( $_REQUEST[ $field_name ] ) ? $_REQUEST[ $field_name ] : 1;
										}
										?>

										<div class="numbers-row post-right <?php if ( empty( $cart_service ) || empty( $cart_service[ $service->id ] ) ) echo 'hide-row';  ?>" data-min="1">
											<input type="text" class="qty2 form-control <?php echo esc_attr( $field_name ); ?>" name="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( $temp_value ); ?>">

											<div class="inc button_inc">+</div>
											<div class="dec button_inc">-</div>
										</div>
									</td>
									<td>
										<label class="switch-light switch-ios pull-right">
										<input type="checkbox" name="add_service[<?php echo esc_attr( $service->id ); ?>]" value="1"<?php if ( ! empty( $cart_service ) && ! empty( $cart_service[ $service->id ] ) ) echo ' checked="checked"' ?>>
										<span>
										<span><?php echo esc_html__( 'No', 'citytours' ) ?></span>
										<span><?php echo esc_html__( 'Yes', 'citytours' ) ?></span>
										</span>
										<a></a>
										</label>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				<?php endif; ?>

				<?php do_action( 'tour_cart_main_after' ); ?>

			</div><!-- End col-md-8 -->

			<aside class="col-md-4">

				<?php do_action( 'tour_cart_sidebar_before' ); ?>

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
							<?php if ( ! empty( $cart_service ) ) {
								foreach ( $cart_service as $key => $service ) { ?>
									<tr>
										<td><?php echo esc_html( $service['title'] ) ?></td>
										<td class="text-right"><?php echo ct_price( $service['total'] ); ?></td>
									</tr>
							<?php }} ?>
							<tr class="total">
								<td><?php echo esc_html__( 'Total cost', 'citytours' ) ?></td>
								<td class="text-right"><?php $total_price = $cart->get_field( $uid, 'total_price' ); if ( ! empty( $total_price ) ) echo ct_price( $total_price ) ?></td>
							</tr>
							<tr>
								<td><?php echo sprintf( esc_html__( 'Security Deposit (%d%%)', 'citytours' ), $deposit_rate ) ?></td>
								<td class="text-right"><?php if ( ! empty( $total_price ) ) echo ct_price( $total_price * $deposit_rate / 100 ) ?></td>
							</tr>
						</tbody>
					</table>

					<a class="btn_full book-now-btn" href="#"><?php echo esc_html__( 'Book now', 'citytours' ) ?></a>
					<a class="btn_full update-cart-btn" href="#"><?php echo esc_html__( 'Update Cart', 'citytours' ) ?></a>
					<a class="btn_full_outline" href="<?php echo esc_url( get_permalink( $tour_id ) ) ?>"><i class="icon-right"></i> <?php echo esc_html__( 'Modify your search', 'citytours' ) ?></a>

					<input type="hidden" name="action" value="ct_tour_book">
					<input type="hidden" name="tour_id" value="<?php echo esc_attr( $tour_id ) ?>">
					<input type="hidden" name="date" value="<?php echo esc_attr( $date ) ?>">
					<input type="hidden" name="time" value="<?php echo esc_attr( $time ) ?>">
					<?php wp_nonce_field( 'tour_update_cart' ); ?>
				</div>

				<?php do_action( 'tour_cart_sidebar_after' ); ?>

			</aside><!-- End aside -->

		</div><!--End row -->

	</form>

	<script>
		var ajaxurl = '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ) ?>';
		var is_woocommerce_enabled = '<?php if ( ct_is_woocommerce_integration_enabled() ) echo "true"; else echo "false" ?>';

		jQuery(document).ready( function($){
			$('#tour-cart input').change(function(){
				$('.update-cart-btn').css('display', 'inline-block');
				$('.book-now-btn').hide();
			});

			$('.update-cart-btn').click(function(e){
				e.preventDefault();

				$('input[name="action"]').val('ct_tour_update_cart');
				$('#overlay').fadeIn();

				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: $('#tour-cart').serialize(),
					success: function(response){
						if (response.success == 1) {
							//location.reload();
							var url = new URL(location.href);
							var query_string = url.search;
							var search_params = new URLSearchParams(query_string);
							search_params.delete('time');
							search_params.delete('adults');
							search_params.delete('kids');
							search_params.append('time', $('input[name="time"]').val());
							search_params.append('adults', $('input[name="adults"]').val());
							search_params.append('kids', $('input[name="kids"]').val());
							url.search = search_params.toString();
							document.location.href = url.toString();
						} else {
							alert(response.message);
							$('#overlay').fadeOut();
						}
					}
				});

				return false;
			});

			$('.options_cart input[type="checkbox"]').change(function(){
				var qty_display = $(this).parent().parent().parent().find('.numbers-row');

				if ( qty_display.hasClass("hide-row") ) {
					qty_display.removeClass("hide-row");
				} else {
					qty_display.addClass("hide-row");
				}
			});

			$('.book-now-btn').click(function(e){
				e.preventDefault();

				if ( is_woocommerce_enabled == "true" ) {
					$('#overlay').fadeIn();
					$('input[name="action"]').val('ct_add_tour_to_woo_cart');

					$.ajax({
						url: ajaxurl,
						type: "POST",
						data: $('#tour-cart').serialize(),
						success: function(response){
							$('#overlay').fadeOut();
							if (response.success == 1) {
								document.location.href=$("#tour-cart").attr('action');
							} else {
								alert(response.message);
							}
						}
					});
				} else {
					document.location.href=$("#tour-cart").attr('action');
				}
			})
		} );
	</script>

	<?php else : ?>
		<h5 class="alert alert-warning"><?php echo esc_html( $is_available ); ?></h5>
	<?php endif;
}
