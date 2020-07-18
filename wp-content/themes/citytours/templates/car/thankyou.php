<?php 
if ( ! defined( 'ABSPATH' ) ) { 
    exit; 
}

if ( ! isset( $_REQUEST['booking_no'] ) || ! isset( $_REQUEST['pin_code'] ) ) {
	return;
	do_action('ct_car_thankyou_wrong_data');
	exit;
}

global $wpdb, $ct_options;

$order = new CT_Hotel_Order( $_REQUEST['booking_no'], $_REQUEST['pin_code'] );
if ( ! $order_data = $order->get_order_info() ) {
	return;
	do_action('ct_car_thankyou_wrong_data');
	exit;
}

$car_data = $order->get_cars();

$deposit_rate = get_post_meta( $order_data['post_id'], '_car_security_deposit', true ); 
$deposit_rate = empty( $deposit_rate ) ? 0 : $deposit_rate;

if ( empty( $order_data['deposit_paid'] ) ) {
	// init payment variables
	if ( $deposit_rate < 100 ) {
		$ItemName = sprintf( esc_html__( 'Deposit(%d%%) for your order %d', 'citytours' ), $deposit_rate, $order_data['id'] );
	} else {
		$ItemName = sprintf( esc_html__( 'Deposit for your order %d', 'citytours' ), $order_data['id'] );
	}

	$payment_data = array();
	$payment_data['item_name'] = $ItemName;
	$payment_data['item_number'] = $order_data['id'];
	$payment_data['item_desc'] = get_the_title( $order_data['post_id'] );
	if ( ! empty( $order_data['date_from'] ) ) $payment_data['item_desc'] .= ' ' . esc_html__( 'Date', 'citytours' ) . ' ' . ct_get_phptime( $order_data['date_from'] );
	$payment_data['item_qty'] = 1;
	$payment_data['item_price'] = $order_data['deposit_price'];
	$payment_data['item_total_price'] = $payment_data['item_qty'] * $payment_data['item_price'];
	$payment_data['grand_total'] = $payment_data['item_total_price'];
	$payment_data['currency'] = strtoupper( $order_data['currency_code'] );
	$payment_data['return_url'] = ct_get_current_page_url() . '?booking_no=' . $order_data['booking_no'] . '&pin_code=' . $order_data['pin_code'] . '&payment=success';
	$payment_data['cancel_url'] = ct_get_current_page_url() . '?booking_no=' . $order_data['booking_no'] . '&pin_code=' . $order_data['pin_code'] . '&payment=failed';

	$payment_result = ct_process_payment( $payment_data );

	// after payment
	if ( $payment_result ) {
		if ( ! empty( $payment_result['success'] ) && ( $payment_result['method'] == 'paypal' ) ) {
			$other_booking_data = array();
			if ( ! empty( $order_data['other'] ) ) {
				$other_booking_data = unserialize( $order_data['other'] );
			}
			$other_booking_data['pp_transaction_id'] = $payment_result['transaction_id'];
			$order_data['deposit_paid'] = 1;
			$update_status = $wpdb->update( CT_ORDER_TABLE, array( 'deposit_paid' => $order_data['deposit_paid'], 'other' => serialize( $other_booking_data ), 'status' => 'new' ), array( 'booking_no' => $order_data['booking_no'], 'pin_code' => $order_data['pin_code'] ) );
			if ( $update_status === false ) {
				do_action( 'ct_payment_update_booking_error' );
			} elseif ( empty( $update_status ) ) {
				do_action( 'ct_payment_update_booking_no_row' );
			} else {
				do_action( 'ct_payment_update_booking_success' );
			}
		}
	}
}

if ( empty( $order_data['deposit_paid'] ) ) {
	do_action('ct_order_deposit_payment_not_paid', $order_data ); // deposit payment not paid
}

if ( empty( $order_data['mail_sent'] ) ) {
	do_action('ct_order_conf_mail_not_sent', $order_data); // mail is not sent
}

$order_rooms = $order->get_rooms();
?>

<div class="row">
	<div class="col-md-8">

		<div class="form_title">
			<h3><strong><i class="icon-ok"></i></strong><?php echo esc_html__( 'Thank you!', 'citytours' ) ?></h3>

			<p><?php echo esc_html__( 'Your Booking Order is Now Confirmed.', 'citytours' ) ?></p>
		</div>

		<div class="step">
			<?php if ( ! empty( $ct_options['car_thankyou_text_1'] ) ) : ?>
			<p><?php echo esc_html__( $ct_options['car_thankyou_text_1'], 'citytours' ) ?></p>
			<?php endif; ?>
		</div><!--End step -->

		<div class="form_title">
			<h3><strong><i class="icon-tag-1"></i></strong><?php echo esc_html__( 'Booking summary', 'citytours' ) ?></h3>
			<p><?php echo esc_html__( 'Followings are Summary of Your Booking.', 'citytours' ) ?></p>
		</div>

		<div class="step">
			<table class="table confirm">
				<tbody>
					<tr>
						<td><strong><?php echo esc_html__( 'Name', 'citytours' ) ?></strong></td>
						<td><?php echo esc_html( $order_data['first_name'] . ' ' . $order_data['last_name'] ) ?></td>
					</tr>
					
					<?php if ( ! empty( $order_data['date_from'] ) && '0000-00-00' != $order_data['date_from'] ) : ?>
					<tr>
						<td><strong><?php echo esc_html__( 'Date', 'citytours' ) ?></strong></td>
						<td><?php echo date_i18n( 'j F Y', strtotime( $order_data['date_from'] ) ) ?></td>
					</tr>
					<?php endif; ?>
					
					<?php if ( ! empty( $car_data['car_time'] ) ) : ?>
					<tr>
						<td><strong><?php echo esc_html__( 'Time', 'citytours' ) ?></strong></td>
						<td><?php echo esc_html( $car_data['car_time'] ) ?></td>
					</tr>
					<?php endif; ?>

					<tr>
						<td><strong><?php echo esc_html__( 'Car Name', 'citytours' ) ?></strong></td>
						<td><?php echo get_the_title( $order_data['post_id'] ) ?></td>
					</tr>
					<tr>
						<td><strong><?php echo esc_html__( 'Adults', 'citytours' ) ?></strong></td>
						<td><?php echo esc_html( $order_data['total_adults'] ) ?></td>
					</tr>
					<tr>
						<td><strong><?php echo esc_html__( 'Children', 'citytours' ) ?></strong></td>
						<td><?php echo esc_html( $order_data['total_kids'] ) ?></td>
					</tr>
					<tr>
						<td><strong><?php echo esc_html__( 'From', 'citytours' ) ?></strong></td>
						<td><?php echo esc_html( $car_data['pickup_location'] ) ?></td>
					</tr>
					<tr>
						<td><strong><?php echo esc_html__( 'To', 'citytours' ) ?></strong></td>
						<td><?php echo esc_html( $car_data['dropoff_location'] ) ?></td>
					</tr>
					<?php if ( ! empty( $deposit_rate ) && $deposit_rate < 100 ) : ?>
						<tr>
							<td><strong><?php echo sprintf( esc_html__( 'Security Deposit(%d%%)', 'citytours' ), $deposit_rate ) ?></strong></td>
							<td ><?php echo ct_price( $order_data['deposit_price'], "", $order_data['currency_code'], 0 ) ?></td>
						</tr>
					<?php endif; ?>
					<tr>
						<td><strong><?php echo esc_html__( 'TOTAL COST', 'citytours' ) ?></strong></td>
						<td ><?php echo ct_price( $order_data['total_price'] ) ?></td>
					</tr>
				</tbody>
			</table>
		</div><!--End step -->

	</div><!--End col-md-8 -->

	<aside class="col-md-4">
		<div class="box_style_1">
			<h3 class="inner"><?php echo esc_html__( 'Thank you!', 'citytours' ) ?></h3>

			<?php if ( ! empty( $ct_options['car_thankyou_text_2'] ) ) : ?>
			<p><?php echo esc_html__( $ct_options['car_thankyou_text_2'], 'citytours' ) ?></p>
			<?php endif; ?>

			<hr>

			<?php if ( ! empty( $ct_options['car_invoice_page'] ) ) : ?>
				<a class="btn_full_outline" target="_blank" href="<?php echo esc_url( add_query_arg( array( 'booking_no' => $order_data['booking_no'], 'pin_code' => $order_data['pin_code'] ) ,ct_get_permalink_clang( $ct_options['car_invoice_page'] ) ) ) ?>" target="_blank"><?php echo esc_html__( 'View your invoice', 'citytours' ) ?></a>
			<?php endif; ?>
		</div>
	</aside>
	
</div><!--End row -->