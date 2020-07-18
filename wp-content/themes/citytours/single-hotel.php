<?php
/* Page Template for Single Hotel */
if ( ! defined( 'ABSPATH' ) ) { 
    exit; 
}

get_header();

if ( have_posts() ) {
    while ( have_posts() ) : the_post();
        $post_id        = get_the_ID();
        $address        = get_post_meta( $post_id, '_hotel_address', true );
        $person_price   = get_post_meta( $post_id, '_hotel_price', true );
        if ( empty( $person_price ) ) $person_price = 0;

        $slider         = get_post_meta( $post_id, '_hotel_slider', true );
        $star_rating    = get_post_meta( $post_id, '_hotel_star', true );
        $minimum_stay   = get_post_meta( $post_id, '_hotel_minimum_stay', true );

        $hotel_pos      = get_post_meta( $post_id, '_hotel_loc', true );
        $related_ht     = get_post_meta( $post_id, '_hotel_related' );

        $hotel_setting  = get_post_meta( $post_id, '_hotel_booking_type', true );
        $hotel_setting  = empty( $hotel_setting )? 'default' : $hotel_setting;
        $inquiry_form   = get_post_meta( $post_id, '_hotel_inquiry_form', true );
        $external_link  = get_post_meta( $post_id, '_hotel_external_link', true );
        $external_link  = empty( $external_link )? '#' : $external_link;

        $is_fixed_sidebar   = get_post_meta( $post_id, '_hotel_fixed_sidebar', true );

        $date_from = ( isset( $_GET['date_from'] ) ) ? ct_get_phptime( $_GET['date_from'] ) : '';
        $date_to = ( isset( $_GET['date_to'] ) ) ? ct_get_phptime( $_GET['date_to'] ) : '';
        
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

        if ( ! empty( $hotel_pos ) ) { 
            $hotel_pos = explode( ',', $hotel_pos );
        }

        $args = array(
            'post_type' => 'room_type',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_room_hotel_id',
                    'value' => array( $post_id )
                )
            ),
            'post_status' => 'publish',
            'suppress_filters' => 0,
        );
        $room_types = get_posts( $args );

        $header_img_scr = ct_get_header_image_src( $post_id );
        if ( ! empty( $header_img_scr ) ) { 
            $header_img_height = ct_get_header_image_height( $post_id );
            ?>

            <section class="parallax-window" data-parallax="scroll" data-image-src="<?php echo esc_url( $header_img_scr ) ?>" data-natural-width="1400" data-natural-height="470" style="min-height: <?php echo esc_attr( $header_img_height ) . 'px'; ?>">
                <div class="parallax-content-2">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8 col-sm-8">
                                <span class="rating">
                                <?php ct_rating_smiles( $star_rating, 'icon-star-empty', 'icon-star voted' ); ?>
                                </span>
                                <h1><?php the_title() ?></h1>
                                <span><?php echo esc_html( $address, 'citytours' ); ?></span>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div id="price_single_main">
                                    <?php echo esc_html__( 'from/per night', 'citytours' ) ?> <?php echo ct_price( $person_price, "special" ) ?>
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
                <div class="col-md-8" id="single_hotel_desc">

                    <div id="single_tour_feat">
                        <ul>
                            <?php
                            $hotel_facilities = wp_get_post_terms( $post_id, 'hotel_facility' );

                            if ( ! $hotel_facilities || is_wp_error( $hotel_facilities ) ) $hotel_facilities = array();

                            foreach ( $hotel_facilities as $hotel_term ) :
                                $term_id = $hotel_term->term_id;
                                $icon_class = get_tax_meta($term_id, 'ct_tax_icon_class', true);
                                echo '<li>';
                                if ( ! empty( $icon_class ) ) echo '<i class="' . esc_attr( $icon_class ) . '"></i>';
                                echo esc_html( $hotel_term->name );
                                echo '</li>';
                            endforeach; 
                            ?>
                        </ul>
                    </div>

                    <p class="visible-sm visible-xs"><a class="btn_map" data-toggle="collapse" href="#collapseMap" aria-expanded="false" aria-controls="collapseMap"><?php echo __( 'View on map', 'citytours' ) ?></a></p>

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

                    <div class="row rooms-list">
                        <div class="col-md-3">
                            <h3><?php echo esc_html__( 'Rooms Types', 'citytours' ) ?></h3>
                        </div>
                        <div class="col-md-9">
                            <?php if ( ! empty( $room_types ) ) : ?>
                                <?php 
                                $is_first = true;
                                foreach( $room_types as $post ) : setup_postdata( $post ); ?>
                                    <?php if ( $is_first ) { $is_first = false; } else { echo '<hr>'; } ?>
                                    <h4><?php the_title() ?></h4>
                                    <?php the_content() ?>
                                    <ul class="list_icons">

                                        <?php 
                                        $room_type_id = get_the_ID();
                                        $hotel_facilities = wp_get_post_terms( $room_type_id, 'hotel_facility' );

                                        if ( ! $hotel_facilities || is_wp_error( $hotel_facilities ) ) $hotel_facilities = array();

                                        foreach ( $hotel_facilities as $hotel_term ) :
                                            $term_id = $hotel_term->term_id;
                                            $icon_class = get_tax_meta($term_id, 'ct_tax_icon_class', true);
                                            echo '<li>';
                                            if ( ! empty( $icon_class ) ) echo '<i class="' . esc_attr( $icon_class ) . '"></i>';
                                            echo esc_html( $hotel_term->name );
                                            echo '</li>';
                                        endforeach; ?>

                                    </ul>
                                    <?php $gallery_imgs = get_post_meta( $room_type_id, '_gallery_imgs' );?>
                                    <?php if ( ! empty( $gallery_imgs ) ) : ?>
                                        <div class="owl-carousel owl-theme magnific-gallery">
                                            <?php foreach ( $gallery_imgs as $gallery_img ) {
                                                echo '<div class="item"><a href="' . esc_url( wp_get_attachment_url( $gallery_img ) ) . '">' . wp_get_attachment_image( $gallery_img, 'ct-room-gallery' ) . '</a></div>';
                                            } ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php wp_reset_postdata(); ?>
                                <?php endforeach ?>
                            <?php endif; ?>

                        </div><!-- End col-md-9  -->
                    </div><!-- End row  -->

                    <hr>

                    <?php
                    global $ct_options;

                    if ( ! empty( $ct_options['hotel_review'] ) ) :
                        $review_fields = ! empty( $ct_options['hotel_review_fields'] ) ? explode( ",", $ct_options['hotel_review_fields'] ) : array( __("Position", "citytours"), __("Comfort","citytours"), __("Price","citytours"), __("Quality","citytours"));
                        $review = get_post_meta( ct_hotel_org_id( $post_id ), '_review', true );
                        // $review = round( ( ! empty( $review ) ) ? (float) $review : 0, 1 );
                        $doubled_review = number_format( round( $review * 2, 1 ), 1 );
                        $review_content = '';

                        if ( $doubled_review >= 9 ) {
                            $review_content = esc_html__( 'Superb', 'citytours' );
                        } elseif ( $doubled_review >= 8 ) {
                            $review_content = esc_html__( 'Very good', 'citytours' );
                        } elseif ( $doubled_review >= 7 ) {
                            $review_content = esc_html__( 'Good', 'citytours' );
                        } elseif ( $doubled_review >= 6 ) {
                            $review_content = esc_html__( 'Pleasant', 'citytours' );
                        } else {
                            $review_content = esc_html__( 'Review Rating', 'citytours' );
                        }

                        $review_detail = get_post_meta( ct_hotel_org_id( $post_id ), '_review_detail', true );
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
                                <div id="score_detail">
                                    <span><?php echo esc_html( $doubled_review ) ?></span><?php echo esc_html( $review_content ) ?> <small><?php echo sprintf( esc_html__( '(Based on %d reviews)' , 'citytours' ), ct_get_review_count( $post_id ) ) ?></small>
                                </div>

                                <div class="row" id="rating_summary">
                                    <div class="col-md-6">
                                        <ul>
                                            <?php for ( $i = 0; $i < ( count( $review_fields ) / 2 ); $i++ ) { ?>
                                            <li><?php echo esc_html__( $review_fields[$i] ); ?>
                                                <div class="rating"><?php echo ct_rating_smiles( $review_detail[ $i ] ) ?></div>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>

                                    <div class="col-md-6">
                                        <ul>
                                            <?php for ( $i = $i; $i < count( $review_fields ); $i++ ) { ?>
                                            <li><?php echo esc_html__( $review_fields[$i] ); ?>
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
                                        
                                        <a href="#" class="btn_1 more-review" data-post_id="<?php echo esc_attr( $post_id ) ?>"><?php echo esc_html__( 'Load More Reviews', 'citytours' ) ?></a>

                                        <?php 
                                    } 
                                    ?>
                                </div>
                            </div>
                        </div>

                    <?php  endif; ?>

                </div><!--End  single_hotel_desc-->

                <aside class="col-md-4" <?php if ($is_fixed_sidebar) echo 'id="sidebar"'; ?>>

                    <p class="hidden-sm hidden-xs">
                        <a class="btn_map" data-toggle="collapse" href="#collapseMap" aria-expanded="false" aria-controls="collapseMap"><?php echo __( 'View on map', 'citytours' ) ?></a>
                    </p>

                    <?php if ( $is_fixed_sidebar ) : ?>
                    <div class="theiaStickySidebar">
                    <?php endif; ?>

                    <div class="box_style_1 expose">
                        <h3 class="inner"><?php echo esc_html__( 'Check Availability', 'citytours' ) ?></h3>

                        <?php if ( 'external' == $hotel_setting ) : ?>
                            <a href="<?php echo esc_url( $external_link ) ?>" class="btn_full"><?php echo esc_html__( 'Check now', 'citytours' ) ?></a>
                        <?php elseif ( 'default' == $hotel_setting ) : ?>
                            <?php if ( ct_get_hotel_cart_page() ) : ?>

                                <form method="get" id="booking-form" action="<?php echo esc_url( ct_get_hotel_cart_page() ); ?>">
                                    <input type="hidden" name="hotel_id" value="<?php echo esc_attr( $post_id ) ?>">

                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label><i class="icon-calendar-7"></i> <?php echo esc_html__( 'Check in', 'citytours' ) ?></label>
                                                <input class="date-pick form-control" placeholder="<?php echo ct_get_date_format('html'); ?>" data-date-format="<?php echo ct_get_date_format('html'); ?>" type="text" name="date_from" value="<?php echo esc_attr( $date_from ); ?>" autocomplete="off" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-6">
                                             <div class="form-group">
                                                <label><i class="icon-calendar-7"></i> <?php echo esc_html__( 'Check out', 'citytours' ) ?></label>
                                                <input class="date-pick form-control" placeholder="<?php echo ct_get_date_format('html'); ?>" data-date-format="<?php echo ct_get_date_format('html'); ?>" type="text" name="date_to" value="<?php echo esc_attr( $date_to ); ?>" autocomplete="off" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <br>

                                    <button type="submit" class="btn_full book-now"><?php echo esc_html__( 'Check now', 'citytours' ) ?></button>
                                </form>

                            <?php else : ?>

                                <?php echo wp_kses_post( sprintf( __( 'Please set hotel booking page on <a href="%s">Theme Options</a>/Hotel Main Settings', 'citytours' ), esc_url( admin_url( 'admin.php?page=theme_options' ) ) ) ); ?>

                            <?php endif; ?>

                        <?php elseif ( 'inquiry' == $hotel_setting ) :
                                echo do_shortcode( $inquiry_form );
                        endif; ?>

                        <?php 
                        if ( ! empty( $ct_options['wishlist'] ) ) :
                            if ( is_user_logged_in() ) {
                                $user_id = get_current_user_id();
                                $wishlist = get_user_meta( $user_id, 'wishlist', true );

                                if ( empty( $wishlist ) ) {
                                    $wishlist = array(); 
                                }
                                ?>

                                <a class="btn_full_outline btn-add-wishlist" href="#" data-post-id="<?php echo esc_attr( $post_id ) ?>"<?php echo ( in_array( ct_hotel_org_id( $post_id ), $wishlist) ) ? ' style="display:none;"' : '' ?>><i class=" icon-heart"></i> <?php echo esc_html__( 'Add to wishlist', 'citytours' ) ?></a>
                                <a class="btn_full_outline btn-remove-wishlist" href="#" data-post-id="<?php echo esc_attr( $post_id ) ?>"<?php echo ( ! in_array( ct_hotel_org_id( $post_id ), $wishlist) ) ? ' style="display:none;"' : '' ?>><i class=" icon-heart"></i> <?php esc_html_e( 'Remove from wishlist', 'citytours' ); ?></a>

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

                        endif; ?>

                    </div><!--/box_style_1 -->

                    <?php if ( is_active_sidebar( 'sidebar-hotel' ) ) : ?>
                        <?php dynamic_sidebar( 'sidebar-hotel' ); ?>
                    <?php endif; ?>
                    <?php if ( $is_fixed_sidebar ) : ?>
                    </div>
                    <?php endif; ?>
                </aside>
            </div><!--End row -->
        </div><!--End container -->

        <?php if ( ! empty( $ct_options['hotel_review'] ) ) : ?>

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
                            <!-- End row -->

                            <hr>

                            <div class="row">
                                <?php for ( $i = 0; $i < ( count( $review_fields ) ); $i++ ) { ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo esc_html( $review_fields[$i] ); ?></label>
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
                            <!-- End row -->

                            <div class="form-group">
                                <textarea name="review_text" id="review_text" class="form-control" style="height:100px" placeholder="<?php esc_html_e( 'Write your review', 'citytours' ); ?>"></textarea>
                            </div>

                            <input type="submit" value="<?php esc_attr_e( 'Submit', 'citytours' ); ?>" class="btn_1" id="submit-review">
                        </form>

                        <div id="message-review" class="alert alert-warning"></div>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>

        <script type="text/javascript">
            $ = jQuery.noConflict();

            var lang = '<?php echo get_locale() ?>';
            lang = lang.replace( '_', '-' );

            $(document).ready(function(){
                $('input.date-pick').datepicker({
                    startDate: "today",
                    language: lang
                });

                $('input[name="date_from"]').on('change', function(){
                    var day;
                    date_format = $(this).data('date-format');
                    if ( date_format == 'dd/mm/yyyy' ) {
                        var day_str = $(this).val().split('/');
                        day = new Date( day_str[1]+'/'+day_str[0]+'/'+day_str[2] );
                    } else {
                        day = new Date( $(this).val() );
                    }
                    
                    var d = new Date(day);
                    d.setDate( day.getDate()+1 );
                    $('input[name="date_to').datepicker( "setDate", d );
                });

                $('#booking-form').submit( function(){
                    var minimum_stay = 0,
                        date_from,
                        date_to,
                        one_day;

                    <?php 
                    if ( ! empty( $minimum_stay ) ) { 
                        echo 'minimum_stay=' . $minimum_stay .';'; 
                    } 
                    ?>

                    date_from = $('input[name="date_from"]').datepicker('getDate').getTime();
                    date_to = $('input[name="date_to"]').datepicker('getDate').getTime();
                    one_day = 1000*60*60*24;

                    if ( date_from + one_day * minimum_stay > date_to ) {
                        alert( "<?php echo esc_js( sprintf( __( 'Minimum stay for this hotel is %d nights. Have another look at your dates and try again.', 'citytours' ), $minimum_stay ) ) ?>" );

                        return false;
                    }
                });

                $('#sidebar').theiaStickySidebar({
                    additionalMarginTop: 80
                });

                /* Show Map on "View on Map" button click */
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
                                        $img_url = $img['src'];
                                    } else {
                                        $img_url = $tour_marker_img_url;
                                    }
                                }
                            }

                            if ( ! empty( $each_pos ) ) { 
                                $each_pos = explode( ',', $each_pos );
                                $description = str_replace( "'", "\'", wp_trim_words( strip_shortcodes(get_post_field("post_content", $each_ht)), 20, '...' ) );
                             ?>
                                '<?php echo esc_js( $each_ht ); ?>' :  [{
                                    name: '<?php echo esc_js( get_the_title( $each_ht ) ); ?>',
                                    type: '<?php echo esc_js( $img_url ); ?>',
                                    location_latitude: <?php echo esc_js( $each_pos[0] ); ?>,
                                    location_longitude: <?php echo esc_js( $each_pos[1] ); ?>,
                                    map_image_url: '<?php echo ct_get_header_image_src( $each_ht, "ct-map-thumb" ) ?>',
                                    name_point: '<?php echo esc_js( get_the_title( $each_ht ) ); ?>',
                                    description_point: '<?php echo esc_js( $description ); ?>',
                                    url_point: '<?php echo get_permalink( $each_ht ) ?>'
                                }],
                            <?php
                            }
                        } 
                        if ( ! empty( $hotel_pos ) ) { 
                            $description = str_replace( "'", "\'", wp_trim_words( strip_shortcodes(get_post_field("post_content", $post_id)), 20, '...' ) );
                        ?>
                            'Center': [
                            {
                                name: '<?php echo esc_js( get_the_title() ); ?>',
                                type: '<?php echo esc_js( $hotel_marker_img_url ); ?>',
                                location_latitude: <?php echo esc_js( $hotel_pos[0] ); ?>,
                                location_longitude: <?php echo esc_js( $hotel_pos[1] ); ?>,
                                map_image_url: '<?php echo ct_get_header_image_src( $post_id, "ct-map-thumb" ) ?>',
                                name_point: '<?php esc_js( get_the_title() ); ?>',
                                description_point: '<?php echo esc_js( $description ); ?>',
                                url_point: '<?php echo get_permalink( $post_id ); ?>'
                            },
                            ]
                        <?php 
                        } ?>
                    };
                    <?php 
                    if ( empty($hotel_pos) ) { 
                        foreach ( $related_ht as $each_ht ) {
                            if ( get_post_type( $each_ht ) == 'hotel' ) {
                                $each_pos = get_post_meta( $each_ht, '_hotel_loc', true );
                            } else { 
                                $each_pos = get_post_meta( $each_ht, '_tour_loc', true );
                            }

                            if ( ! empty( $each_pos ) ) { 
                                $hotel_pos = explode( ',', $each_pos );
                                break;
                            }
                        }
                    }

                    if ( !empty( $hotel_pos ) ) {
                     ?>
                    var lati = <?php echo esc_js( $hotel_pos[0] ); ?>;
                    var long = <?php echo esc_js( $hotel_pos[1] ); ?>;

                    var _center = [lati, long];
                    renderMap( _center, markersData, 14, google.maps.MapTypeId.ROADMAP, false );
                    <?php } ?>
                });
            });
        </script>

    <?php endwhile;
}

get_footer();