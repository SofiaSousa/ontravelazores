<?php
/* Page Template for Single Tour */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
if ( have_posts() ) {
    while ( have_posts() ) : the_post();

        //init variables
        $post_id    = get_the_ID();

        $is_repeated        =  get_post_meta( $post_id, '_tour_repeated', true );
        $tour_start_date    =  get_post_meta( $post_id, '_tour_start_date', true );
        $tour_end_date      =  get_post_meta( $post_id, '_tour_end_date', true );
		$tour_time = get_post_meta( $post_id, '_tour_time', true );
		if ( ! empty( $tour_time ) ) {
			$tour_time = array_map( 'trim', explode( ',', $tour_time ) );
		}

		$tour_available_days = ct_get_tour_available_days( $post_id );
		$tour_prices = ct_tour_get_price_per_day( $post_id );
		$tour_prices_html = array();
		foreach ( $tour_prices as $key => $value ) {
			$tour_prices_html[$key] = ct_price( $value );
		}

		$price_type		= get_post_meta( $post_id, '_tour_price_type', true );
        $person_price       = get_post_meta( $post_id, '_tour_price', true );
        if ( empty( $person_price ) ) $person_price = 0;

        $charge_child   = get_post_meta( $post_id, '_tour_charge_child', true );
        $child_price    = get_post_meta( $post_id, '_tour_price_child', true );

        $slider         = get_post_meta( $post_id, '_tour_slider', true );
        $schedule_info  = get_post_meta( $post_id, '_tour_schedule_info', true );

        $review = get_post_meta( $post_id, '_review', true );
        $review = ( ! empty( $review ) )?round( $review, 1 ):0;

        $is_fixed_sidebar   = get_post_meta( $post_id, '_tour_fixed_sidebar', true );

        $tour_setting       = get_post_meta( $post_id, '_tour_booking_type', true );
        $tour_setting       = empty( $tour_setting )? 'default' : $tour_setting;
        $inquiry_form       = get_post_meta( $post_id, '_tour_inquiry_form', true );
        $external_link      = get_post_meta( $post_id, '_tour_external_link', true );
        $external_link      = empty( $external_link )? '#' : $external_link;

        $address    = get_post_meta( $post_id, '_tour_address', true );
        $tour_pos   = get_post_meta( $post_id, '_tour_loc', true );
        if ( ! empty( $tour_pos ) ) $tour_pos = explode( ',', $tour_pos );

        if ( ! empty( $ct_options['tour_map_maker_img'] ) && ! empty( $ct_options['tour_map_maker_img']['url'] ) ) {
            $tour_marker_img_url = $ct_options['tour_map_maker_img']['url'];
        } else {
            $tour_marker_img_url = CT_IMAGE_URL . "/pins/tour.png";
        }

        if ( ! empty( $ct_options['hotel_map_maker_img'] ) && ! empty( $ct_options['hotel_map_maker_img']['url'] ) ) {
            $hotel_marker_img_url = $ct_options['hotel_map_maker_img']['url'];
        } else {
            $hotel_marker_img_url = CT_IMAGE_URL . "/pins/hotel.png";
        }

        $t_types = wp_get_post_terms( $post_id, 'tour_type' );
        if ( ! $t_types || is_wp_error( $t_types ) ) {
            $tour_type_img_url =  $tour_marker_img_url;
        } else {
            $img = get_tax_meta( $t_types[0]->term_id, 'ct_tax_marker_img', true );
            if ( isset( $img ) && is_array( $img ) ) {
                $tour_type_img_url = $img['src'];
            } else {
                $tour_type_img_url =  $tour_marker_img_url;
            }
        }

        $related_ht = get_post_meta( $post_id, '_tour_related' );

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
									<?php echo ( ! empty( $price_type ) && $price_type == 'per_group' ) ? esc_html__( 'from/per group', 'citytours' ) : esc_html__( 'from/per person', 'citytours' ); ?> <?php echo ct_price( $person_price, "special" ) ?>
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
                <div class="col-md-8" id="single_tour_desc">

                    <div id="single_tour_feat">
                        <ul>
                            <?php
                                $tour_types = wp_get_post_terms( $post_id, 'tour_type' );
                                $tour_facilities = wp_get_post_terms( $post_id, 'tour_facility' );

                                if ( ! $tour_types || is_wp_error( $tour_types ) ) $tour_types = array();
                                if ( ! $tour_facilities || is_wp_error( $tour_facilities ) ) $tour_facilities = array();

                                $tour_terms = array_merge( $tour_types, $tour_facilities );
                                foreach ( $tour_terms as $tour_term ) :
                                    $term_id = $tour_term->term_id;
                                    $icon_class = get_tax_meta($term_id, 'ct_tax_icon_class', true);
                                    echo '<li>';
                                    if ( ! empty( $icon_class ) ) echo '<i class="' . esc_attr( $icon_class ) . '"></i>';
                                    echo esc_html( $tour_term->name );
                                    echo '</li>';
                                endforeach; ?>
                        </ul>
                    </div>

                    <p class="visible-sm visible-xs"><a class="btn_map" data-toggle="collapse" href="#collapseMap" aria-expanded="false" aria-controls="collapseMap"><?php echo __( 'View on map', 'citytours' ) ?></a></p>

                    <?php if ( ! empty( $slider ) ) : ?>
                        <?php echo do_shortcode( $slider ); ?>
                        <hr>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-3">
							<h3><?php echo esc_html__( 'Description', 'citytours' ); ?></h3>
                        </div>

                        <div class="col-md-9">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <hr>

                    <?php if ( ! empty( $schedule_info ) ) : ?>
                    <div class="row">
                        <div class="col-md-3">
							<h3><?php echo esc_html__( 'Schedule', 'citytours' ); ?></h3>
                        </div>

                        <div class="col-md-9">
                            <?php echo do_shortcode( $schedule_info ); ?>
                        </div>
                    </div>

                    <hr>
                    <?php endif; ?>

                    <?php
                    global $ct_options;

                    if ( ! empty( $ct_options['tour_review'] ) ) :
                        $review_fields = ! empty( $ct_options['tour_review_fields'] ) ? explode( ",", $ct_options['tour_review_fields'] ) : array( "Position", "Comfort", "Price", "Quality" );
                        $review = get_post_meta( ct_tour_org_id( $post_id ), '_review', true );
                        $review = round( ( ! empty( $review ) ) ? (float) $review : 0, 1 );
                        $review_detail = get_post_meta( ct_tour_org_id( $post_id ), '_review_detail', true );

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
											<li><?php echo esc_html__( $review_fields[ $i ], 'citytours' ); ?>
                                                <div class="rating"><?php echo ct_rating_smiles( $review_detail[ $i ] ) ?></div>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul>
                                            <?php for ( $i = $i; $i < count( $review_fields ); $i++ ) { ?>
											<li><?php echo esc_html__( $review_fields[ $i ], 'citytours' ); ?>
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

                </div><!--End  single_tour_desc-->

                <aside class="col-md-4" <?php if ($is_fixed_sidebar) echo 'id="sidebar"'; ?>>

                    <p class="hidden-sm hidden-xs">
                        <a class="btn_map" data-toggle="collapse" href="#collapseMap" aria-expanded="false" aria-controls="collapseMap"><?php echo __( 'View on map', 'citytours' ) ?></a>
                    </p>

                    <?php if ( $is_fixed_sidebar ) : ?>
                    <div class="theiaStickySidebar">
                    <?php endif; ?>

                    <?php if ( 'empty' != $tour_setting ) : ?>

                        <div class="box_style_1 expose">
                            <h3 class="inner">- <?php echo esc_html__( 'Booking', 'citytours' ) ?> -</h3>

                            <?php if ( 'external' == $tour_setting ) : ?>
                                <a href="<?php echo esc_url( $external_link ) ?>" class="btn_full"><?php echo esc_html__( 'Check now', 'citytours' ) ?></a>
                            <?php elseif ( 'default' == $tour_setting ) : ?>

                                <?php if ( ct_get_tour_cart_page() ) : ?>

                                <form method="get" id="booking-form" action="<?php echo esc_url( ct_get_tour_cart_page() ); ?>">

                                    <input type="hidden" name="tour_id" value="<?php echo esc_attr( $post_id ) ?>">

                                    <?php
                                    $lang_code = ct_get_lang_code_for_page( $ct_options['tour_cart_page'] );
                                    if ( ! empty( $ct_options['tour_cart_page'] ) && ! empty( $lang_code ) ) {
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

										<?php if ( ! empty( $tour_time ) && is_array( $tour_time ) ) : ?>
										<div class="col-md-6 col-sm-6">
											<div class="form-group">
												<label><i class="icon-clock"></i> <?php echo esc_html__( 'Time', 'citytours' ) ?></label>
												<select class="form-control" name="time">
													<?php foreach ( $tour_time as $t ) : ?>
														<option value="<?php echo esc_attr( $t ); ?>"><?php echo esc_html( $t ); ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<?php endif; ?>

                                    </div>
                                    <?php endif; ?>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo esc_html__( 'Adults (+11 Years)', 'citytours' ) ?></label>
                                                <div class="numbers-row" data-min="1">
                                                    <input type="text" value="1" id="adults" class="qty2 form-control" name="adults">

                                                    <div class="inc button_inc">+</div>
                                                    <div class="dec button_inc">-</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo esc_html__( 'Children (3 - 10 Years)', 'citytours' ) ?></label>
                                                <div class="numbers-row" data-min="0">
													<input type="text" value="0" id="children" class="qty2 form-control" name="kids" <?php if ( ! empty( $price_type ) && $price_type != 'per_group' && empty( $charge_child ) ) echo 'disabled' ?> >

                                                    <div class="inc button_inc">+</div>
                                                    <div class="dec button_inc">-</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo esc_html__( 'Infants (0 - 2 Years)', 'citytours' ) ?></label>
                                                <div class="numbers-row" data-min="0">
                                                    <input type="text" value="0" id="infants" class="qty2 form-control" name="infants">

                                                    <div class="inc button_inc">+</div>
                                                    <div class="dec button_inc">-</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <br>

                                    <table class="table table_summary">
                                        <tbody>
                                            <tr>
                                                <td>
                                                        <?php echo esc_html__( 'Adults (+11 Years)', 'citytours' ) ?>
                                                </td>
                                                <td class="text-right adults-number">
                                                    1
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php echo esc_html__( 'Children (3 - 10 Years)', 'citytours' ) ?>
                                                </td>
                                                <td class="text-right children-number">
                                                    0
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <?php echo esc_html__( 'Infants (0 - 2 Years)', 'citytours' ) ?>
                                                </td>
                                                <td class="text-right infants-number">
                                                    0
                                                </td>
                                            </tr>

											<?php if ( empty( $price_type ) || $price_type == 'per_person' ) : ?>
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
											<?php endif; ?>

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

                                    <?php echo wp_kses_post( sprintf( __( 'Please set tour booking page on <a href="%s">Theme Options</a>/Tour Main Settings', 'citytours' ), esc_url( admin_url( 'admin.php?page=theme_options' ) ) ) ); ?>
                                <?php endif; ?>

                            <?php elseif ( 'inquiry' == $tour_setting ) :
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

                                    <a class="btn_full_outline btn-add-wishlist" href="#" data-label-add="<?php esc_html_e( 'Add to wishlist', 'citytours' ); ?>" data-label-remove="<?php esc_html_e( 'Remove from wishlist', 'citytours' ); ?>" data-post-id="<?php echo esc_attr( $post_id ) ?>"<?php echo ( in_array( ct_tour_org_id( $post_id ), $wishlist) ) ? ' style="display:none;"' : '' ?>><i class=" icon-heart"></i> <?php echo esc_html__( 'Add to wishlist', 'citytours' ) ?></a>
                                    <a class="btn_full_outline btn-remove-wishlist" href="#" data-label-add="<?php esc_html_e( 'Add to wishlist', 'citytours' ); ?>" data-label-remove="<?php esc_html_e( 'Remove from wishlist', 'citytours' ); ?>" data-post-id="<?php echo esc_attr( $post_id ) ?>"<?php echo ( ! in_array( ct_tour_org_id( $post_id ), $wishlist) ) ? ' style="display:none;"' : '' ?>><i class=" icon-heart"></i> <?php esc_html_e( 'Remove from wishlist', 'citytours' ); ?></a>

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

                    <?php if ( is_active_sidebar( 'sidebar-tour' ) ) : ?>
                        <?php dynamic_sidebar( 'sidebar-tour' ); ?>
                    <?php endif; ?>

                    <?php if ( $is_fixed_sidebar ) : ?>
                    </div>
                    <?php endif; ?>

                </aside>

            </div><!--End row -->

        </div><!--End container -->

        <?php
        if ( ! empty( $ct_options['tour_review'] ) ) :
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
            $tour_start_date_milli_sec = 0;
            if ( ! empty( $tour_start_date ) ) {
                $tour_start_date_milli_sec = strtotime( $tour_start_date) * 1000;
            }

            $tour_end_date_milli_sec = 9999*365*24*60*60*1000;
            if ( ! empty( $tour_end_date ) ) {
                $tour_end_date_milli_sec = strtotime( $tour_end_date) * 1000;
            }

            $next_days_block_optional = rwmb_meta( 'tours_number_days_to_block' );
            $next_days_block_optional = ($next_days_block_optional == '' ? '0' : $next_days_block_optional);
        ?>

        <script type="text/javascript">
            $ = jQuery.noConflict();

            var price_per_person = 0,
                price_per_child = 0,
				exchange_rate = 1,
				price_type = 1; // per_group is 0

			<?php if ( ! empty( $price_type ) && $price_type == 'per_group' ) : ?>
				price_type = 0;
			<?php endif; ?>
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
                var available_days = <?php echo json_encode($tour_available_days );?>;
				var tour_prices = <?php echo json_encode( $tour_prices ); ?>;
				var tour_prices_html = <?php echo json_encode( $tour_prices_html ); ?>;
                var today = new Date();
                var tour_start_date = new Date( <?php echo esc_js( $tour_start_date_milli_sec ); ?> );
                var tour_end_date = new Date( <?php echo esc_js( $tour_end_date_milli_sec ); ?> );
                var available_first_date = tour_end_date;
                var lang = '<?php echo get_locale() ?>';

                lang = lang.replace( '_', '-' );

                today.setHours(0, 0, 0, 0);
                tour_start_date.setHours(0, 0, 0, 0);
                tour_end_date.setHours(0, 0, 0, 0);

                <?php
                $arr_dates_blocked = array();
                $tmp_datepicker_string = '';

                $post_id = get_the_ID();

                $start_dates = rwmb_meta( 'tour_blocked_start_dates' );
                $end_dates   = rwmb_meta( 'tour_blocked_end_dates' );

                if ( $start_dates && is_array( $start_dates ) && $end_dates && is_array( $end_dates ) ) {
                    foreach ( $start_dates as $k => $start_date ) {
                        if ( $end_dates[$k] ) {
                            $end_date = $end_dates[$k];

                            // return: Y-m-d
                            $temp_start_date = date( 'Y-m-d', strtotime( $start_date ) );
                            $temp_end_date   = date( 'Y-m-d', strtotime( $end_date ) );

                            if ( $temp_start_date < $temp_end_date ) {
                                $begin = new DateTime( $start_date );
                                $end   = new DateTime( $end_date );

                                $interval = DateInterval::createFromDateString( '1 day' );
                                $period   = new DatePeriod( $begin, $interval, $end );

                                foreach ( $period as $dt ) {
                                    $date = $dt->format( 'Y-m-d' );
                                    array_push( $arr_dates_blocked, $date );
                                }
                            }

                            array_push( $arr_dates_blocked, $end_date );
                        }
                    }
                }

                $g_start_dates = rwmb_meta( 'tours_blocked_start_dates', ['object_type' => 'setting'], 'tours_settings' );
                $g_end_dates   = rwmb_meta( 'tours_blocked_end_dates', ['object_type' => 'setting'], 'tours_settings' );

                if ( $g_start_dates && is_array( $g_start_dates ) && $g_end_dates && is_array( $g_end_dates ) ) {
                    foreach ( $g_start_dates as $k => $start_date ) {
                        if ( $g_end_dates[$k] ) {
                            $end_date = $g_end_dates[$k];

                            // return: Y-m-d
                            $temp_start_date = date( 'Y-m-d', strtotime( $start_date ) );
                            $temp_end_date   = date( 'Y-m-d', strtotime( $end_date ) );

                            if ( $temp_start_date < $temp_end_date ) {

                                $begin = new DateTime( $start_date );
                                  $end   = new DateTime( $end_date );

                                $interval = DateInterval::createFromDateString( '1 day' );
                                $period   = new DatePeriod( $begin, $interval, $end );

                                foreach ( $period as $dt ) {
                                    $date = $dt->format( 'Y-m-d' );
                                    array_push( $arr_dates_blocked, $date );
                                }
                            }

                            array_push( $arr_dates_blocked, $end_date );
                        }
                    }
                }

                for ( $i=0; $i < count( $arr_dates_blocked ) ; $i++ ) {
                    $temp_date = $arr_dates_blocked[$i];
                    $new_date = str_replace( '-', '/', $temp_date );
                    $new_date = date( 'd/m/Y', strtotime( $new_date ) );

                    if ( $tmp_datepicker_string == '' ) {
                        $tmp_datepicker_string .= '"'.$new_date.'"';
                    } else {
                        $tmp_datepicker_string .= ',"'.$new_date.'"';
                    }

                    if ( $i < ( count( $arr_dates_blocked ) - 1 ) ) {
                        $tmp_datepicker_string .= ',';
                    }
                }
                ?>

                var disableDates = [<?php  echo $tmp_datepicker_string; ?>];

                if ( today > tour_start_date ) {
					tour_start_date.setTime( today.getTime() );
                }

                <?php
                    $next_days_block_optional = rwmb_meta( 'tours_number_days_to_block' );
                    $next_days_block_optional = ($next_days_block_optional == '' ? '0' : $next_days_block_optional);
                ?>
                var ttt = tour_start_date.getDate();
                tour_start_date.setDate( ttt + <?php echo $next_days_block_optional; ?> );


                var ddd = tour_start_date.getDate();
				for(var i=0;i<7;i++) {
					tour_start_date.setDate( ddd + i );
					if ( $.inArray( tour_start_date.getDay(), available_days ) >= 0 ) {
                        available_first_date = tour_start_date;
						break;
					}
				}

				function update_tour_price() {
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

					var d = day.getDay();
					$('span.day-price').html(tour_prices_html[d]);

					price_per_person = tour_prices[d];
					}
					if ( price_type ) {
					price = +( (adults * price_per_person + children * price_per_child) * exchange_rate ).toFixed(2);
					} else {
						price = +( price_per_person * exchange_rate ).toFixed(2);
					}
					if ( Number.isNaN( price ) ) {
						price = 0;
					}
					$('.child-amount').toggleClass( 'hide', children < 1 );
					total_price = $('.total-cost').text().replace(/[\d\.\,]+/g, price);
					$('.total-cost').text( total_price );
                }


                function DisableDays(date) {
                    var day = date.getDay();
                    let formatted_date = ('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth()+1)).slice(-2) + '/' + date.getFullYear();

                    if ( disableDates.length >= 1 ) {
                        if ( $.inArray( formatted_date, disableDates ) >= 0 ) {
                            return false;
                        }
                    }

                    if ( available_days.length >= 1 ) {
                        if ( $.inArray( day, available_days ) == -1 ) {
                            return false;
                        }
                    }

                    if ( available_first_date >= date && date >= tour_start_date ) {
                        available_first_date = date;
                    }

                    return true;
                }

                if ( $('input.date-pick').length ) {
                    if ( lang.substring( 0, 2 ) != 'fa' ) {
                        $('input.date-pick').datepicker({
                            startDate: tour_start_date,
                        <?php if ( $tour_end_date_milli_sec > 0 ) : ?>
                            endDate: tour_end_date,
                        <?php endif; ?>
                            beforeShowDay: DisableDays,
                            language: lang
                        });

                        let formatted_date_available_first_date = ('0' + available_first_date.getDate()).slice(-2) + '/' + ('0' + (available_first_date.getMonth()+1)).slice(-2) + '/' + available_first_date.getFullYear();
                        if ( $.inArray( formatted_date_available_first_date, disableDates ) == -1 ) {
                            $('input[name="date"]').datepicker( 'setDate', available_first_date );
                        }

                    } else {

                        $('input.date-pick').persianDatepicker({
                            observer: true,
                            format: date_format.toUpperCase(),
                        });
                    }
                }

                $('input#adults').on('change', function(){
                    $('.adults-number').html( $(this).val() );
                    update_tour_price();
                });
                $('input#children').on('change', function(){
                    $('.children-number').html( $(this).val() );
                    update_tour_price();
                });

                $('input#infants').on('change', function(){
                    $('.infants-number').html( $(this).val() );
                });



				$('input.date-pick').on('change', function(e){
					e.preventDefault();
					update_tour_price();
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

                $('#collapseMap').on('shown.bs.collapse', function(e){
                    var markersData = {
                        <?php
                        foreach ( $related_ht as $each_ht ) {
                            if ( get_post_type( $each_ht ) == 'hotel' ) {
                                $each_pos = get_post_meta( $each_ht, '_hotel_loc', true );
                                $img_url = $hotel_marker_img_url;
                            } else {
                                $each_pos = get_post_meta( $each_ht, '_tour_loc', true );
                                $t_types = wp_get_post_terms( $each_ht, 'tour_type' );
                                if ( ! $t_types || is_wp_error( $t_types ) ) {
                                    $img_url =  $tour_marker_img_url;
                                } else {
                                    $img = get_tax_meta( $t_types[0]->term_id, 'ct_tax_marker_img', true );
                                    if ( isset( $img ) && is_array( $img ) ) {
                                        $post_type = $img['src'];
                                    } else {
                                        $img_url =  $tour_marker_img_url;
                                    }
                                }
                            }

                            if ( ! empty( $each_pos ) ) {
                                $each_pos = explode( ',', $each_pos );
                                $description = str_replace( "'", "\'", wp_trim_words( strip_shortcodes(get_post_field("post_content", $each_ht)), 20, '...' ) );
                             ?>
                                '<?php echo esc_js( $each_ht ); ?>' :  [{
                                    name: '<?php echo get_the_title( $each_ht ) ?>',
                                    type: '<?php echo esc_js( $img_url ); ?>',
                                    location_latitude: <?php echo esc_js( $each_pos[0] ); ?>,
                                    location_longitude: <?php echo esc_js( $each_pos[1] ); ?>,
                                    map_image_url: '<?php echo ct_get_header_image_src( $each_ht, "ct-map-thumb" ); ?>',
                                    name_point: '<?php echo esc_js( get_the_title( $each_ht ) ); ?>',
                                    description_point: '<?php echo esc_js( $description ); ?>',
                                    url_point: '<?php echo get_permalink( $each_ht ); ?>'
                                }],
                            <?php
                            }
                        }
                        if ( ! empty( $tour_pos ) ) {
                            $description = str_replace( "'", "\'", wp_trim_words( strip_shortcodes(get_post_field("post_content", $post_id)), 20, '...' ) );
                        ?>
                            'Center': [
                            {
                                name: '<?php echo esc_js( get_the_title() ); ?>',
                                type: '<?php echo esc_js( $tour_type_img_url ); ?>',
                                location_latitude: <?php echo esc_js( $tour_pos[0] ); ?>,
                                location_longitude: <?php echo esc_js( $tour_pos[1] ); ?>,
                                map_image_url: '<?php echo ct_get_header_image_src( $post_id, "ct-map-thumb" ) ?>',
                                name_point: '<?php echo esc_js( get_the_title() ); ?>',
                                description_point: '<?php echo esc_js( $description ); ?>',
                                url_point: '<?php echo get_permalink( $post_id ) ?>'
                            },
                            ]
                        <?php
                        } ?>
                    };
                    <?php
                    if ( empty($tour_pos) ) {
                        foreach ( $related_ht as $each_ht ) {
                            if ( get_post_type( $each_ht ) == 'hotel' ) {
                                $each_pos = get_post_meta( $each_ht, '_hotel_loc', true );
                            } else {
                                $each_pos = get_post_meta( $each_ht, '_tour_loc', true );
                            }

                            if ( ! empty( $each_pos ) ) {
                                $tour_pos = explode( ',', $each_pos );
                                break;
                            }
                        }
                    }

                    if ( !empty( $tour_pos ) ) {
                    ?>
                    var lati = <?php echo esc_js( $tour_pos[0] ); ?>;
                    var long = <?php echo esc_js( $tour_pos[1] ); ?>;
                    // var _center = [48.865633, 2.321236];
                    var _center = [lati, long];
                    renderMap( _center, markersData, 14, google.maps.MapTypeId.ROADMAP, false );
                    <?php } ?>
                });

            });
        </script>

    <?php endwhile;
}

get_footer();
