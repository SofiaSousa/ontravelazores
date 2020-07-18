<?php
/* Page Template for Single Car */
if ( ! defined( 'ABSPATH' ) ) { 
	exit; 
}

get_header();

if ( have_posts() ) {
	while ( have_posts() ) : the_post();

		//init variables
		$post_id    = get_the_ID();

		$is_repeated        =  get_post_meta( $post_id, '_car_repeated', true );
		$car_start_date    =  get_post_meta( $post_id, '_car_start_date', true );
		$car_end_date      =  get_post_meta( $post_id, '_car_end_date', true );
		$car_available_days = ct_get_car_available_days( $post_id );
		$car_prices = ct_car_get_price_per_day( $post_id );
		$car_prices_html = array();
		foreach ( $car_prices as $key => $value ) {
			$car_prices_html[$key] = ct_price( $value );
		}
		
		$person_price       = get_post_meta( $post_id, '_car_price', true );
		if ( empty( $person_price ) ) $person_price = 0;

		$charge_child   = get_post_meta( $post_id, '_car_charge_child', true );
		$child_price    = get_post_meta( $post_id, '_car_price_child', true );
		$address    = get_post_meta( $post_id, '_car_address', true );
		$pickup_locations  = get_post_meta( $post_id, '_car_pickup_location', true );
		$dropoff_locations  = get_post_meta( $post_id, '_car_dropoff_location', true );

		$slider         = get_post_meta( $post_id, '_car_slider', true );
		
		$review = get_post_meta( $post_id, '_review', true );
		$review = ( ! empty( $review ) )?round( $review, 1 ):0;

		$is_fixed_sidebar   = get_post_meta( $post_id, '_car_fixed_sidebar', true );

		$car_setting       = get_post_meta( $post_id, '_car_booking_type', true );
		$car_setting       = empty( $car_setting )? 'default' : $car_setting;
		$inquiry_form       = get_post_meta( $post_id, '_car_inquiry_form', true );
		$external_link      = get_post_meta( $post_id, '_car_external_link', true );
		$external_link      = empty( $external_link )? '#' : $external_link;

		$header_img_scr = ct_get_header_image_src( $post_id );
		if ( ! empty( $header_img_scr ) ) {
			$header_img_height = ct_get_header_image_height( $post_id );
			?>

			<section class="parallax-window" data-parallax="scroll" data-image-src="<?php echo esc_url( $header_img_scr ) ?>" data-natural-width="1400" data-natural-height="470" style="min-height: <?php echo esc_attr( $header_img_height ) . 'px' ?>">
				<div class="parallax-content-2">
					<div class="container">
						<div class="row">
							<div class="col-md-8 col-sm-8">
								<h1><?php the_title() ?></h1>
								<span><?php echo esc_html( $address, 'citytours' ); ?></span>
								<span class="rating"><?php ct_rating_smiles( $review )?><small>(<?php echo esc_html( ct_get_review_count( $post_id ) ) ?>)</small></span>
							</div>
							<div class="col-md-4 col-sm-4">
								<div id="price_single_main">
									<?php echo esc_html__( 'from/per person', 'citytours' ) ?> <?php echo ct_price( $person_price, "special" ) ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section><!-- End section -->
			<div id="position">

		<?php } else { ?>
			<div id="position" class="blank-parallax">
		<?php } ?>

			<div class="container"><?php ct_breadcrumbs(); ?></div>
		</div><!-- End Position -->

		<div class="collapse" id="collapseMap">
			<div id="map" class="map"></div>
		</div>

		<div class="container margin_60">

			<div class="row">
				<div class="col-md-8" id="single_car_desc">

					<div id="single_tour_feat">
						<ul>
							<?php
								$car_facilities = wp_get_post_terms( $post_id, 'car_facility' );

								if ( ! $car_facilities || is_wp_error( $car_facilities ) ) $car_facilities = array();

								$car_terms = $car_facilities;
								foreach ( $car_terms as $car_term ) :
									$term_id = $car_term->term_id;
									$icon_class = get_tax_meta($term_id, 'ct_tax_icon_class', true);
									echo '<li>';
									if ( ! empty( $icon_class ) ) echo '<i class="' . esc_attr( $icon_class ) . '"></i>';
									echo esc_html( $car_term->name );
									echo '</li>';
								endforeach; ?>
						</ul>
					</div>

					<?php if ( ! empty( $slider ) ) : ?>
						<?php echo do_shortcode( $slider ); ?>
						<hr>
					<?php endif; ?>

					<div class="row">
						<div class="col-md-3">
							<h3><?php echo esc_html__( 'Description', 'citytours') ?></h3>
						</div>

						<div class="col-md-9">
							<?php the_content(); ?>
						</div>
					</div>

					<hr>

					<?php
					global $ct_options;

					if ( ! empty( $ct_options['car_review'] ) ) :
						$review_fields = ! empty( $ct_options['car_review_fields'] ) ? explode( ",", $ct_options['car_review_fields'] ) : array( "Position", "Comfort", "Price", "Quality" );
						$review = get_post_meta( ct_car_org_id( $post_id ), '_review', true );
						$review = round( ( ! empty( $review ) ) ? (float) $review : 0, 1 );
						$review_detail = get_post_meta( ct_car_org_id( $post_id ), '_review_detail', true );
						
						if ( ! empty( $review_detail ) ) {
							$review_detail = is_array( $review_detail ) ? $review_detail : unserialize( $review_detail );
						} else {
							$review_detail = array_fill( 0, count( $review_fields ), 0 );
						}
						?>

						<div class="row">
							<div class="col-md-3">
								<h3><?php echo esc_html__( 'Reviews', 'citytours') ?></h3>
								
								<a href="#" class="btn_1 add_bottom_15" data-toggle="modal" data-target="#myReview"><?php echo esc_html__( 'Leave a review', 'citytours') ?></a>
							</div>

							<div class="col-md-9">
								<div id="general_rating"><?php echo sprintf( esc_html__( '%d Reviews', 'citytours' ), ct_get_review_count( $post_id ) ) ?>
									<div class="rating"><?php echo ct_rating_smiles( $review ) ?></div>
								</div>

								<div class="row" id="rating_summary">
									<div class="col-md-6">
										<ul>
											<?php for ( $i = 0; $i < ( count( $review_fields ) / 2 ); $i++ ) { ?>
											<li><?php echo esc_html( $review_fields[ $i ], 'citytours' ); ?>
												<div class="rating"><?php echo ct_rating_smiles( $review_detail[ $i ] ) ?></div>
											</li>
											<?php } ?>
										</ul>
									</div>
									<div class="col-md-6">
										<ul>
											<?php for ( $i = $i; $i < count( $review_fields ); $i++ ) { ?>
											<li><?php echo esc_html( $review_fields[ $i ], 'citytours' ); ?>
												<div class="rating"><?php echo ct_rating_smiles( $review_detail[ $i ] ) ?></div>
											</li>
											<?php } ?>
										</ul>
									</div>
								</div><!-- End row -->

								<hr>

								<div class="guest-reviews">
									<?php
									$per_page = 10;
									$review_html = ct_get_review_html($post_id, 0, $per_page);

									echo ( $review_html['html'] );
								
									if ( $review_html['count'] >= $per_page ) { 
										?>
										
										<a href="#" class="btn more-review" data-post_id="<?php echo esc_attr( $post_id ) ?>"><?php echo esc_html__( 'Load More Reviews', 'citytours' ) ?></a>

										<?php 
									} 
									?>
								</div>
							</div>
						</div>

						<?php 
					endif; 
					?>

				</div><!--End  single_car_desc-->

				<aside class="col-md-4" <?php if ($is_fixed_sidebar) echo 'id="sidebar"'; ?>>

					<?php if ( $is_fixed_sidebar ) : ?>
					<div class="theiaStickySidebar">
					<?php endif; ?>

					<?php if ( 'empty' != $car_setting ) : ?>
						
						<div class="box_style_1 expose">
							<h3 class="inner">- <?php echo esc_html__( 'Booking', 'citytours' ) ?> -</h3>

							<?php if ( 'external' == $car_setting ) : ?>
								<a href="<?php echo esc_url( $external_link ) ?>" class="btn_full"><?php echo esc_html__( 'Check now', 'citytours' ) ?></a>
							<?php elseif ( 'default' == $car_setting ) : ?>

								<?php if ( ct_get_car_cart_page() ) : ?>

								<form method="get" id="booking-form" action="<?php echo esc_url( ct_get_car_cart_page() ); ?>">

									<input type="hidden" name="car_id" value="<?php echo esc_attr( $post_id ) ?>">

									<?php 
									$lang_code = ct_get_lang_code_for_page( $ct_options['car_cart_page'] );
									if ( ! empty( $ct_options['car_cart_page'] ) && ! empty( $lang_code ) ) { 
										?>
										<input type="hidden" name="lang" value="<?php echo esc_attr( $lang_code ); ?>">
										<?php 
									} 
									?>

									<?php if ( ! empty( $is_repeated ) ) : ?>
									<div class="row">
										<div class="col-md-6 col-sm-6">
											<div class="form-group">
												<label><i class="icon-calendar-7"></i> <?php echo esc_html__( 'Select a date', 'citytours' ) ?></label>
												<input class="date-pick form-control" data-date-format="<?php echo ct_get_date_format('html'); ?>" type="text" name="date" autocomplete="off" readonly>
											</div>
										</div>

										<div class="col-md-6 col-sm-6">
											<div class="form-group">
												<label><i class=" icon-clock"></i> <?php echo esc_html__( 'Time', 'citytours' ) ?></label>
												<input class="form-control booking_time" type="text" name="time">
											</div>
										</div>

									</div>									
									<?php endif; ?>

									<div class="row">
										<div class="col-md-6 col-sm-6">
											<div class="form-group">
												<label><?php echo esc_html__( 'Adults', 'citytours' ) ?></label>
												<div class="numbers-row" data-min="1">
													<input type="text" value="1" id="adults" class="qty2 form-control" name="adults">

													<div class="inc button_inc">+</div>
													<div class="dec button_inc">-</div>
												</div>
											</div>
										</div>

										<div class="col-md-6 col-sm-6">
											<div class="form-group">
												<label><?php echo esc_html__( 'Children', 'citytours' ) ?></label>
												<div class="numbers-row" data-min="0">
													<input type="text" value="0" id="children" class="qty2 form-control" name="kids" <?php if (empty( $charge_child )) echo 'disabled' ?> >
													
													<div class="inc button_inc">+</div>
													<div class="dec button_inc">-</div>
												</div>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label><?php echo esc_html__( 'Pick up address', 'citytours' ) ?></label>
										<select id="address" class="form-control" name="pickup_location">
											<?php if ( ! empty( $pickup_locations ) ) : ?>
												<?php foreach( $pickup_locations as $pickup_location ) : ?>
													<option value="<?php echo esc_attr( $pickup_location ); ?>"><?php echo esc_html( $pickup_location ); ?></option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</div>
									<div class="form-group">
										<label><?php echo esc_html__( 'Drop off address', 'citytours' ) ?></label>
										<select id="address_2" class="form-control" name="dropoff_location">
											<?php if ( ! empty( $dropoff_locations ) ) : ?>
												<?php foreach( $dropoff_locations as $dropoff_location ) : ?>
													<option value="<?php echo esc_attr( $dropoff_location ); ?>"><?php echo esc_html( $dropoff_location ); ?></option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</div>

									<br>

									<table class="table table_summary">
										<tbody>
											<tr>
												<td>
													<?php echo esc_html__( 'Adults', 'citytours' ) ?>
												</td>
												<td class="text-right adults-number">
													1
												</td>
											</tr>
											<tr>
												<td>
													<?php echo esc_html__( 'Children', 'citytours' ) ?>
												</td>
												<td class="text-right children-number">
													0
												</td>
											</tr>                                            
											<tr>
												<td>
													<?php echo esc_html__( 'Total amount', 'citytours' ) ?>
												</td>
												<td class="text-right">
													<span class="adults-number">1</span>x <span class="day-price"><?php echo ct_price( $person_price ) ?></span>
													<?php if ( ! empty( $child_price ) ) : ?>
														<span class="child-amount hide"> + <span class="children-number">0</span>x <?php echo ct_price( $child_price ) ?></span>
													<?php endif; ?>
												</td>
											</tr>
											<tr class="total">
												<td>
													<?php echo esc_html__( 'Total cost', 'citytours' ) ?>
												</td>
												<td class="text-right total-cost">
													<?php echo ct_price( $person_price ) ?>
												</td>
											</tr>
										</tbody>
									</table>

									<button type="submit" class="btn_full book-now"><?php echo esc_html__( 'Book now', 'citytours' ) ?></button>

								</form>

								<?php else : ?>

									<?php echo wp_kses_post( sprintf( __( 'Please set car booking page on <a href="%s">Theme Options</a>/Car Main Settings', 'citytours' ), esc_url( admin_url( 'admin.php?page=theme_options' ) ) ) ); ?>
								<?php endif; ?>

							<?php elseif ( 'inquiry' == $car_setting ) :
								echo do_shortcode( $inquiry_form );
							endif; ?>

							<hr>

							<?php 
							if ( ! empty( $ct_options['wishlist'] ) ) :
								if ( is_user_logged_in() ) {
									$user_id = get_current_user_id();
									$wishlist = get_user_meta( $user_id, 'wishlist', true );
									if ( empty( $wishlist ) ) 
										$wishlist = array(); 
									?>

									<a class="btn_full_outline btn-add-wishlist" href="#" data-label-add="<?php esc_html_e( 'Add to wishlist', 'citytours' ); ?>" data-label-remove="<?php esc_html_e( 'Remove from wishlist', 'citytours' ); ?>" data-post-id="<?php echo esc_attr( $post_id ) ?>"<?php echo ( in_array( ct_car_org_id( $post_id ), $wishlist) ) ? ' style="display:none;"' : '' ?>><i class=" icon-heart"></i> <?php echo esc_html__( 'Add to wishlist', 'citytours' ) ?></a>
									<a class="btn_full_outline btn-remove-wishlist" href="#" data-label-add="<?php esc_html_e( 'Add to wishlist', 'citytours' ); ?>" data-label-remove="<?php esc_html_e( 'Remove from wishlist', 'citytours' ); ?>" data-post-id="<?php echo esc_attr( $post_id ) ?>"<?php echo ( ! in_array( ct_car_org_id( $post_id ), $wishlist) ) ? ' style="display:none;"' : '' ?>><i class=" icon-heart"></i> <?php esc_html_e( 'Remove from wishlist', 'citytours' ); ?></a>

								<?php 
								} else { 
								?>

									<div><?php esc_html_e( 'To save your wishlist please login.', 'citytours' ); ?></div>

									<?php if ( empty( $ct_options['login_page'] ) ) { ?>
										<a href="#" class="btn_full_outline"><?php esc_html_e( 'login', 'citytours' ); ?></a>
									<?php } else { ?>
										<a href="<?php echo esc_url( ct_get_permalink_clang( $ct_options['login_page'] ) ); ?>" class="btn_full_outline"><?php esc_html_e( 'login', 'citytours' ); ?></a>
									<?php } ?>

								<?php 
								} 
							endif; 
							?>

						</div><!--/box_style_1 -->

					<?php endif; ?>

					<?php if ( is_active_sidebar( 'sidebar-car' ) ) : ?>
						<?php dynamic_sidebar( 'sidebar-car' ); ?>
					<?php endif; ?>

					<?php if ( $is_fixed_sidebar ) : ?>
					</div>
					<?php endif; ?>

				</aside>

			</div><!--End row -->

		</div><!--End container -->

		<?php 
		if ( ! empty( $ct_options['car_review'] ) ) : 
			?>

			<div class="modal fade" id="myReview" tabindex="-1" role="dialog" aria-labelledby="myReviewLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>

							<h4 class="modal-title" id="myReviewLabel"><?php echo esc_html__( 'Write your review', 'citytours' ) ?></h4>
						</div>

						<div class="modal-body">
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ) ?>" name="review" id="review-form">
								<?php wp_nonce_field( 'post-' . $post_id, '_wpnonce', false ); ?>
								<input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ); ?>">
								<input type="hidden" name="action" value="submit_review">

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<input name="booking_no" id="booking_no" type="text" placeholder="<?php echo esc_html__( 'Booking No', 'citytours' ) ?>" class="form-control">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<input name="pin_code" id="pin_code" type="text" placeholder="<?php echo esc_html__( 'Pin Code', 'citytours' ) ?>" class="form-control">
										</div>
									</div>
								</div>

								<hr>

								<div class="row">
									<?php for ( $i = 0; $i < ( count( $review_fields ) ); $i++ ) { ?>
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo esc_html( $review_fields[ $i ], 'citytours' ); ?></label>
												<select class="form-control" name="review_rating_detail[<?php echo esc_attr( $i ) ?>]">
													<option value="0"><?php esc_html_e( 'Please review', 'citytours' ); ?></option>
													<option value="1"><?php esc_html_e( 'Low', 'citytours' ); ?></option>
													<option value="2"><?php esc_html_e( 'Sufficient', 'citytours' ); ?></option>
													<option value="3"><?php esc_html_e( 'Good', 'citytours' ); ?></option>
													<option value="4"><?php esc_html_e( 'Excellent', 'citytours' ); ?></option>
													<option value="5"><?php esc_html_e( 'Super', 'citytours' ); ?></option>
												</select>
											</div>
										</div>
									<?php } ?>
								</div>

								<div class="form-group">
									<textarea name="review_text" id="review_text" class="form-control" style="height:100px" placeholder="<?php esc_html_e( 'Write your review', 'citytours' ); ?>"></textarea>
								</div>

								<input type="submit" value="<?php esc_attr_e( 'Submit', 'citytours' ); ?>" class="btn_1" id="submit-review">
							</form>

							<div id="message-review" class="alert alert-warning">
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php 
		endif; 
		?>

		<?php
			$car_start_date_milli_sec = 0;
			if ( ! empty( $car_start_date ) ) {
				$car_start_date_milli_sec = strtotime( $car_start_date) * 1000;
			}

			$car_end_date_milli_sec = 9999*365*24*60*60*1000;
			if ( ! empty( $car_end_date ) ) {
				$car_end_date_milli_sec = strtotime( $car_end_date) * 1000;
			}
		?>

		<script type="text/javascript">
			$ = jQuery.noConflict();

			var price_per_person = 0,
				price_per_child = 0,
				exchange_rate = 1;

			<?php if ( ! empty( $person_price ) ) : ?>
				price_per_person = <?php echo esc_js( $person_price ); ?>;
			<?php endif; ?>
			<?php if ( ! empty( $child_price ) ) : ?>
				price_per_child = <?php echo esc_js( $child_price ); ?>;
			<?php endif; ?>
			<?php if ( ! empty( $_SESSION['exchange_rate'] ) ) : ?>
				exchange_rate = <?php echo esc_js( $_SESSION['exchange_rate'] ); ?>;
			<?php endif; ?>
			var date_format = $('input.date-pick').data('date-format'); 

			$(document).ready(function(){
				var available_days = <?php echo json_encode( $car_available_days );?>;
				var car_prices = <?php echo json_encode( $car_prices ); ?>;
				var car_prices_html = <?php echo json_encode( $car_prices_html ); ?>;
				var today = new Date();
				var car_start_date = new Date( <?php echo esc_js( $car_start_date_milli_sec ); ?> );
				var car_end_date = new Date( <?php echo esc_js( $car_end_date_milli_sec ); ?> );
				var available_first_date = car_end_date;
				var lang = '<?php echo get_locale() ?>';

				lang = lang.replace( '_', '-' );

				today.setHours(0, 0, 0, 0);
				car_start_date.setHours(0, 0, 0, 0);
				car_end_date.setHours(0, 0, 0, 0);

				if ( today > car_start_date ) {
					car_start_date = today;
				}

				for(var i=0;i<7;i++) {
					car_start_date.setDate( car_start_date.getDate() + i );
					if ( $.inArray( car_start_date.getDay(), available_days ) >= 0 ) {
						break;
					}
				}

				function update_car_price() {
					var adults = $('input#adults').val();
					var children = price = total_price = 0;
					var day;

					if ( $('input#children').length ) {
						children = $('input#children').val();
					}

					if ($('input.date-pick').length > 0) {
						date_format = $('input.date-pick').data('date-format');
						if ( date_format == 'dd/mm/yyyy' ) {
							var day_str = $('input.date-pick').val().split('/');
							day = new Date( day_str[1]+'/'+day_str[0]+'/'+day_str[2] );
						} else {
							day = new Date( $('input.date-pick').val() );
						}
						console.log(day);
						var d = day.getDay();

						$('span.day-price').html(car_prices_html[d]);
						
						price_per_person = car_prices[d];
					}
					price = +( (adults * price_per_person + children * price_per_child) * exchange_rate ).toFixed(2);
					$('.child-amount').toggleClass( 'hide', children < 1 );
					total_price = $('.total-cost').text().replace(/[\d\.\,]+/g, price);
					$('.total-cost').text( total_price );
				}

				function DisableDays(date) {

					var day = date.getDay();

					if ( available_days.length == 0 ) {
						if ( available_first_date >= date && date >= car_start_date) {
							available_first_date = date;
						}
						return true;
					}

					if ( $.inArray( day, available_days ) >= 0 ) {
						if ( available_first_date >= date && date >= car_start_date) {
							available_first_date = date;
						}
						return true;
					} else {
						return false;
					}
				}

				if ( $('input.date-pick').length ) {
					if ( lang.substring( 0, 2 ) != 'fa' ) { 
						$('input.date-pick').datepicker({
							startDate: car_start_date,
						<?php if ( $car_end_date_milli_sec > 0 ) : ?>
							endDate: car_end_date,
						<?php endif; ?>
							beforeShowDay: DisableDays,
							language: lang
						});
						$('input[name="date"]').datepicker( 'setDate', available_first_date );
					} else { 
						var date_format = $('input.date-pick').data('date-format'); 
						$('input.date-pick').persianDatepicker({
							observer: true,
							format: date_format.toUpperCase(),
						});
					}
				}

				$('input#adults').on('change', function(){
					$('.adults-number').html( $(this).val() );
					update_car_price();
				});
				$('input#children').on('change', function(){
					$('.children-number').html( $(this).val() );
					update_car_price();
				});

				$('input.date-pick').on('change', function(e){
					e.preventDefault();
					update_car_price();
				});

				$('input.date').trigger('change');
			
				var validation_rules = {};
				if ( $('input.date-pick').length ) {
					validation_rules.date = { required: true};
				}
				//validation form
				$('#booking-form').validate({
					rules: validation_rules
				});

				$('#sidebar').theiaStickySidebar({
					additionalMarginTop: 80
				});

			});
		</script>

	<?php endwhile;
}

get_footer();