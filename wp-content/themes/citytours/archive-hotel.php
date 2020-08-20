<?php
/* Hotel List Page Template */
if ( ! defined( 'ABSPATH' ) ) { 
    exit; 
}

get_header();

global $ct_options, $post_list, $current_view;

$order_array = array( 'ASC', 'DESC' );
$order_by_array = array(
    '' => '',
    'price' => 'price',
    'rating' => 'rating'
);
$order_defaults = array(
    'price' => 'ASC',
    'rating' => 'DESC'
);

$s = isset($_REQUEST['s']) ? sanitize_text_field( $_REQUEST['s'] ) : '';
$date_from = isset( $_REQUEST['date_from'] ) ? ct_sanitize_date( $_REQUEST['date_from'] ) : '';
$date_to = isset( $_REQUEST['date_to'] ) ? ct_sanitize_date( $_REQUEST['date_to'] ) : '';
if ( ct_strtotime( $date_from ) >= ct_strtotime( $date_to ) ) {
    $date_from = '';
    $date_to = '';
}

$rooms = ( isset( $_REQUEST['rooms'] ) && is_numeric( $_REQUEST['rooms'] ) ) ? sanitize_text_field( $_REQUEST['rooms'] ) : 1;
$adults = ( isset( $_REQUEST['adults'] ) && is_numeric( $_REQUEST['adults'] ) ) ? sanitize_text_field( $_REQUEST['adults'] ) : 1;
$kids = ( isset( $_REQUEST['kids'] ) && is_numeric( $_REQUEST['kids'] ) ) ? sanitize_text_field( $_REQUEST['kids'] ) : 0;

$order_by = ( isset( $_REQUEST['order_by'] ) && array_key_exists( $_REQUEST['order_by'], $order_by_array ) ) ? sanitize_text_field( $_REQUEST['order_by'] ) : 'price';
$order = ( isset( $_REQUEST['order'] ) && in_array( $_REQUEST['order'], $order_array ) ) ? sanitize_text_field( $_REQUEST['order'] ) : 'ASC';
$districts = ( isset( $_REQUEST['districts'] ) ) ? ( is_array( $_REQUEST['districts'] ) ? $_REQUEST['districts'] : array( $_REQUEST['districts'] ) ):array();
$price_filter = ( isset( $_REQUEST['price_filter'] ) && is_array( $_REQUEST['price_filter'] ) ) ? $_REQUEST['price_filter'] : array();
$star_filter = ( isset( $_REQUEST['star_filter'] ) && is_array( $_REQUEST['star_filter'] ) ) ? $_REQUEST['star_filter'] : array();
$rating_filter = ( isset( $_REQUEST['rating_filter'] ) && is_array( $_REQUEST['rating_filter'] ) ) ? $_REQUEST['rating_filter'] : array();
$facility_filter = ( isset( $_REQUEST['facilities'] ) && is_array( $_REQUEST['facilities'] ) ) ? $_REQUEST['facilities'] : array();

if ( isset( $_REQUEST['view'] ) && ! empty( $_REQUEST['view'] ) ) { 
    $current_view = sanitize_text_field( $_REQUEST['view'] );
} else if ( isset( $ct_options['hotel_list_default_view'] ) && ! empty( $ct_options['hotel_list_default_view'] ) ) { 
    $current_view = $ct_options['hotel_list_default_view'];
} else { 
    $current_view = 'list';
}

$page = ( isset( $_REQUEST['page'] ) && ( is_numeric( $_REQUEST['page'] ) ) && ( $_REQUEST['page'] >= 1 ) ) ? sanitize_text_field( $_REQUEST['page'] ):1;
$per_page = ( isset( $ct_options['hotel_posts'] ) && is_numeric($ct_options['hotel_posts']) )?$ct_options['hotel_posts']:6;
$search_result = ct_hotel_get_search_result( array( 's'=>$s, 'date_from'=>$date_from, 'date_to'=>$date_to, 'rooms'=>$rooms, 'adults'=>$adults, 'kids'=>$kids, 'star_filter' => $star_filter, 'district'=>$districts, 'price_filter'=>$price_filter, 'rating_filter'=>$rating_filter, 'facility_filter'=>$facility_filter, 'order_by'=>$order_by_array[$order_by], 'order'=>$order, 'last_no'=>( $page - 1 ) * $per_page, 'per_page'=>$per_page ) );

$post_list = $search_result['ids'];
$count = $search_result['count']; // total_count

$map_zoom = empty( $ct_options['hotel_list_zoom'] )? 14 : $ct_options['hotel_list_zoom'];

if ( ! empty( $ct_options['hotel_map_maker_img'] ) && ! empty( $ct_options['hotel_map_maker_img']['url'] ) ) {
    $hotel_marker_img_url = $ct_options['hotel_map_maker_img']['url'];
} else {
    $hotel_marker_img_url = CT_IMAGE_URL . "/pins/hotel.png";
}

$header_img_scr = ct_get_header_image_src('hotel');
if ( ! empty( $header_img_scr ) ) {
    $header_content = ct_get_header_content('hotel');
    $header_img_height = ct_get_header_image_height('hotel');
    ?>

    <section class="parallax-window" data-parallax="scroll" data-image-src="<?php echo esc_url( $header_img_scr ) ?>" data-natural-width="1400" data-natural-height="470" style="min-height: <?php echo esc_attr( $header_img_height ) . 'px'; ?>">
        <div class="parallax-content-1" style="height: <?php echo esc_attr( $header_img_height ) . 'px'; ?>">
            <div class="animated fadeInDown">
                <?php echo balancetags( $header_content ); ?>
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
</div><!-- End Map -->

<div class="container margin_60">
    <div class="row">
        <aside class="col-lg-3 col-md-3">
            <p>
                <a class="btn_map" data-toggle="collapse" href="#collapseMap" aria-expanded="false" aria-controls="collapseMap"><?php echo __( 'View on map', 'citytours' ) ?></a>
            </p>

            <div id="search_results"><?php echo sprintf( esc_html__( '%d Results found', 'citytours' ), $count ) ?></div>

            <div id="modify_search">
                <a data-toggle="collapse" href="#collapseModify_search" aria-expanded="false" aria-controls="collapseModify_search" id="modify_col_bt">
                    <i class="icon_set_1_icon-78"></i><?php _e('Modify Search', 'citytours') ?> <i class="icon-plus-1 pull-right"></i>
                </a>

                <div class="collapse" id="collapseModify_search">
                    <div class="modify_search_wp">
                        <form role="search" method="get" id="search-hotel-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <input type="hidden" name="post_type" value="hotel">

                            <div class="form-group">
                                <label><?php echo esc_html( 'Hotel name', 'citytours' ) ?></label>
                                <input type="text" class="form-control" id="hotel_name" name="s" placeholder="<?php esc_attr_e( 'Optionally type hotel name', 'citytours' ) ?>" value="<?php echo esc_attr( $s ) ?>">
                            </div>
                            <div class="form-group">
                                <label><i class="icon-calendar-7"></i> <?php esc_html_e( 'Check in', 'citytours' ) ?></label>
                                <input class="date-pick form-control" data-date-format="<?php echo ct_get_date_format('html'); ?>" type="text" name="date_from" value="<?php echo esc_attr( $date_from ) ?>" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label><i class="icon-calendar-7"></i> <?php esc_html_e( 'Check out', 'citytours' ) ?></label>
                                <input class="date-pick form-control" data-date-format="<?php echo ct_get_date_format('html'); ?>" type="text" name="date_to" value="<?php echo esc_attr( $date_to ) ?>" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label><?php esc_html_e( 'Rooms', 'citytours' ); ?></label>
                                <div class="numbers-row">
                                    <input type="text" id="rooms" class="qty2 form-control" name="rooms" value="<?php echo esc_attr( $rooms ) ?>">

                                    <div class="inc button_inc">+</div>
                                    <div class="dec button_inc">-</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?php esc_html_e( 'Adults', 'citytours' ); ?></label>
                                <div class="numbers-row">
                                    <input type="text" class="qty2 form-control" name="adults" value="<?php echo esc_attr( $adults ) ?>">

                                    <div class="inc button_inc">+</div>
                                    <div class="dec button_inc">-</div>
                                </div>
                            </div>
                            <div class="form-group add_bottom_30">
                                <label><?php esc_html_e( 'Children', 'citytours' ); ?></label>
                                <div class="numbers-row">
                                    <input type="text" class="qty2 form-control" name="kids" value="<?php echo esc_attr( $kids ) ?>">
                                    
                                    <div class="inc button_inc">+</div>
                                    <div class="dec button_inc">-</div>
                                </div>
                            </div>

                            <button class="btn_1 green"><?php esc_html_e( 'Search again', 'citytours' ); ?></button>
                        </form>
                    </div><!--End modify_search_wp -->
                </div><!--End collapse -->
            </div>

            <div id="filters_col">
                <a data-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters" id="filters_col_bt">
                    <i class="icon_set_1_icon-65"></i><?php echo esc_html__( 'Filters', 'citytours' ) ?> <i class="icon-plus-1 pull-right"></i>
                </a>

                <div class="collapse" id="collapseFilters">
                    <?php 
                    if ( ! empty( $ct_options['hotel_price_filter'] ) ) :
                        $price_steps = empty( $ct_options['hotel_price_filter_steps'] ) ? '50,80,100' : $ct_options['hotel_price_filter_steps'];
                        $step_arr = explode( ',', $price_steps );
                        array_unshift( $step_arr, 0 );
                        ?>

                        <div class="filter_type">
                            <h6><?php echo esc_html__( 'Price', 'citytours' ) ?></h6>

                            <ul class="list-filter price-filter" data-base-url="<?php echo esc_url( remove_query_arg( array( 'price_filter', 'page' ) ) ); ?>" data-arg="price_filter">
                                <?php for( $i = 0; $i < count( $step_arr ); $i++ ) {
                                    $checked = ( in_array( $i, $price_filter ) ) ? ' checked="checked"' : '';

                                    if ( $i < count( $step_arr ) - 1 ) { ?>
                                        <li><label><input type="checkbox" name="price_filter[]" value="<?php echo esc_attr( $i ) ?>"<?php echo ( $checked ) ?>><?php echo ct_price( $step_arr[ $i ] ) ?> - <?php echo ct_price( $step_arr[ $i + 1 ] ) ?></label></li>
                                    <?php } else { ?>
                                        <li><label><input type="checkbox" name="price_filter[]" value="<?php echo esc_attr( $i ) ?>"<?php echo ( $checked ) ?>><?php echo ct_price( $step_arr[ $i ] ) ?> +</label></li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>

                        <?php 
                    endif;

                    if ( ! empty( $ct_options['hotel_star_filter'] ) ) :
                        $counts_by_star = ct_hotel_get_search_result_count( array( 'by' => 'star', 'price_filter'=>$price_filter, 'rating_filter'=>$rating_filter, 'facility_filter'=>$facility_filter, 'district' => $districts ) ); 
                        ?>

                        <div class="filter_type">
                            <h6><?php echo esc_html__( 'Star Category', 'citytours' ) ?></h6>

                            <ul class="list-filter star-filter" data-base-url="<?php echo esc_url( remove_query_arg( array( 'star_filter', 'page' ) ) ); ?>" data-arg="star_filter">
                                <li><label><input type="checkbox" name="star_filter[]" value="-1"><?php echo esc_html__( 'All Hotels', 'citytours' ) . ' (' . esc_html( array_sum( $counts_by_star ) ) . ')' ?></label></li>

                                <?php for ( $i = 5; $i > 0; $i-- ) {
                                    $checked = ( in_array( $i, $star_filter ) ) ? ' checked="checked"' : ''; ?>

                                    <li><label><input type="checkbox" name="star_filter[]" value="<?php echo esc_attr( $i ) ?>"<?php echo ( $checked )?>><span class="rating">
                                        <?php ct_rating_smiles( $i, 'icon_set_1_icon-81' ); ?></span><?php echo '(' . esc_html( ( empty( $counts_by_star[ $i ] ) ? 0 : $counts_by_star[ $i ] ) ) . ')' ?></label></li>
                                <?php } ?>
                            </ul>
                        </div>

                        <?php 
                    endif;

                    if ( ! empty( $ct_options['hotel_rating_filter'] ) ) :
                        ?>

                        <div class="filter_type">
                            <h6><?php echo esc_html__( 'Review Score', 'citytours' ) ?></h6>

                            <ul class="list-filter rating-filter" data-base-url="<?php echo esc_url( remove_query_arg( array( 'rating_filter', 'page' ) ) ); ?>" data-arg="rating_filter">
                                <?php for ( $i = 5; $i > 0; $i-- ) {
                                    $checked = ( in_array( $i, $rating_filter ) ) ? ' checked="checked"' : ''; ?>

                                    <li>
                                        <label><input type="checkbox" name="rating_filter[]" value="<?php echo esc_attr( $i ) ?>"<?php echo ( $checked )?>><span class="rating">
                                        <?php ct_rating_smiles( $i ); ?></span></label>
                                    </li>

                                <?php } ?>
                            </ul>
                        </div>

                        <?php 
                    endif;

                    if ( ! empty( $ct_options['hotel_facility_filter'] ) ) :
                        ?>

                        <div class="filter_type">
                            <h6><?php echo esc_html__( 'Facility', 'citytours' ) ?></h6>

                            <ul class="list-filter facility-filter" data-base-url="<?php echo esc_url( remove_query_arg( array( 'facilities', 'page' ) ) ); ?>" data-arg="facilities">

                                <?php 
                                $all_facilities = get_terms( 'hotel_facility', array('hide_empty' => 0) );

                                if ( ! empty( $all_facilities ) ) :
                                    foreach ( $all_facilities as $facility ) {
                                        $term_id = $facility->term_id;
                                        $checked = ( in_array( $term_id, $facility_filter ) ) ? ' checked="checked"' : '';

                                        echo '<li><label><input type="checkbox" name="facility_filter[]" value="' . esc_attr( $term_id ) . '"' . $checked . '>' . esc_html( $facility->name ) . '</label></li>';
                                    }
                                endif;
                                ?>

                            </ul>
                        </div>

                        <?php 
                    endif;

                    if ( ! empty( $ct_options['hotel_district_filter'] ) ) :
                        ?>

                        <div class="filter_type">
                            <h6><?php echo esc_html__( 'District', 'citytours' ) ?></h6>

                            <ul class="list-filter district-filter" data-base-url="<?php echo esc_url( remove_query_arg( array( 'districts', 'page' ) ) ); ?>" data-arg="districts">

                                <?php
                                $all_districts = get_terms( 'district', array('hide_empty' => 0) );

                                if ( ! empty( $all_districts ) ) :
                                    foreach ( $all_districts as $district ) {
                                        $term_id = $district->term_id;
                                        $checked = ( in_array( $term_id, $districts ) ) ? ' checked="checked"' : '';

                                        echo '<li><label><input type="checkbox" name="district_filter[]" value="' . esc_attr( $term_id ) . '"' . $checked . '>' . esc_html( $district->name ) . '</label></li>';
                                    }
                                endif;
                                ?>

                            </ul>
                        </div>

                        <?php 
                    endif;
                    ?>

                </div><!--End collapse -->
            </div><!--End filters col-->
        </aside><!--End aside -->

        <div class="col-lg-9 col-md-8">
            <div id="tools">
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="styled-select-filters">
                            <select name="sort_price" id="sort_price" data-base-url="<?php echo esc_url( remove_query_arg( array( 'order', 'order_by', 'page' ) ) ); ?>">
                                <option value="" <?php if ( $order_by != 'price' ) echo 'selected' ?>><?php echo esc_html__( 'Sort by price', 'citytours' ) ?></option>
                                <option value="lower" <?php if ( $order_by == 'price' && $order == 'ASC' ) echo 'selected' ?>><?php echo esc_html__( 'Lowest price', 'citytours' ) ?></option>
                                <option value="higher" <?php if ( $order_by == 'price' && $order == 'DESC' ) echo 'selected' ?>><?php echo esc_html__( 'Highest price', 'citytours' ) ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="styled-select-filters">
                            <select name="sort_rating" id="sort_rating" data-base-url="<?php echo esc_url( remove_query_arg( array( 'order', 'order_by', 'page' ) ) ); ?>">
                                <option value="" <?php if ( $order_by != 'rating' ) echo 'selected' ?>><?php echo esc_html__( 'Sort by rating', 'citytours' ) ?></option>
                                <option value="lower" <?php if ( $order_by == 'rating' && $order == 'ASC' ) echo 'selected' ?>><?php echo esc_html__( 'Lowest rating', 'citytours' ) ?></option>
                                <option value="higher" <?php if ( $order_by == 'rating' && $order == 'DESC' ) echo 'selected' ?>><?php echo esc_html__( 'Highest rating', 'citytours' ) ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 hidden-xs text-right">
                        <a href="<?php echo esc_url( add_query_arg( array( 'view' => 'grid' ) ) ) ?>" class="bt_filters" title="<?php esc_html_e( 'Grid View', 'citytours' ) ?>"><i class="icon-th"></i></a>
                        <a href="<?php echo esc_url( add_query_arg( array( 'view' => 'list' ) ) ) ?>" class="bt_filters" title="<?php esc_html_e( 'List View', 'citytours' ) ?>"><i class="icon-list"></i></a>
                    </div>
                </div>
            </div><!--End tools -->

            <div class="hotel-list <?php if ( $current_view == 'grid' ) echo 'row add-clearfix' ?>">
                <?php ct_get_template( 'hotel-list.php', '/templates/hotel/'); ?>
            </div><!-- hotel-list -->

            <hr>

            <div class="text-center">
                <?php
                unset( $_GET['page'] );

                $pagenum_link = strtok( filter_input( INPUT_SERVER, 'REQUEST_URI' ), '?' ) . '%_%';
                $total = ceil( $count / $per_page );
                $args = array(
                    'base' => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
                    'total' => $total,
                    'format' => '?page=%#%',
                    'current' => $page,
                    'show_all' => false,
                    'prev_next' => true,
                    'prev_text' => esc_html__('Previous', 'citytours'),
                    'next_text' => esc_html__('Next', 'citytours'),
                    'end_size' => 1,
                    'mid_size' => 2,
                    'type' => 'list',
                    'add_args' => $_GET,
                );

                echo paginate_links( $args );
                ?>
            </div><!-- end pagination-->
                
        </div><!-- End col lg 9 -->
    </div><!-- End row -->
</div><!-- End container -->

<script type="text/javascript">
    jQuery(document).ready(function(){
        var lang = '<?php echo get_locale() ?>';
        lang = lang.replace( '_', '-' );

        jQuery('input').iCheck({
           checkboxClass: 'icheckbox_square-grey',
           radioClass: 'iradio_square-grey'
        });

        if ( jQuery('input.date-pick').length ) {
            jQuery('input.date-pick').datepicker({
                startDate: "today",
                language: lang
            });
        }

        jQuery('#collapseMap').on('shown.bs.collapse', function(e){
            var zoom = <?php echo esc_js( $map_zoom ); ?>;
            var markersData = {
                <?php foreach ( $post_list as $hotel_obj ) { 
                    $hotel_pos = get_post_meta( $hotel_obj['hotel_id'], '_hotel_loc', true );

                    if ( ! empty( $hotel_pos ) ) { 
                        $hotel_pos = explode( ',', $hotel_pos );
                        $description = str_replace( "'", "\'", wp_trim_words( strip_shortcodes(get_post_field("post_content", $hotel_obj['hotel_id'])), 20, '...' ) );
                     ?>
                        '<?php echo esc_js( $hotel_obj['hotel_id'] ); ?>' :  [{
                            name: '<?php echo esc_js( get_the_title( $hotel_obj['hotel_id'] ) ); ?>',
                            type: '<?php echo esc_js( $hotel_marker_img_url ); ?>',
                            location_latitude: <?php echo esc_js( $hotel_pos[0] ); ?>,
                            location_longitude: <?php echo esc_js( $hotel_pos[1] ); ?>,
                            map_image_url: '<?php echo ct_get_header_image_src( $hotel_obj['hotel_id'], "ct-map-thumb" ) ?>',
                            name_point: '<?php echo esc_js( get_the_title( $hotel_obj['hotel_id'] ) ); ?>',
                            description_point: '<?php echo esc_js( $description ); ?>',
                            url_point: '<?php echo get_permalink( $hotel_obj['hotel_id'] ); ?>'
                        }],
                    <?php
                    }
                } ?>
            };
            <?php 
            $hotel_pos = array();
            if ( ! empty( $post_list ) ) { 
                foreach ( $post_list as $hotel_obj ) {
                    $hotel_pos = get_post_meta( $hotel_obj['hotel_id'], '_hotel_loc', true );

                    if ( ! empty( $hotel_pos ) ) { 
                        $hotel_pos = explode( ',', $hotel_pos );
                        break;
                    }
                }
            }
            
            if ( ! empty( $hotel_pos ) ) {
            ?>
            var lati = <?php echo esc_js( $hotel_pos[0] ); ?>;
            var long = <?php echo esc_js( $hotel_pos[1] ); ?>;
            // var _center = [48.865633, 2.321236];
            var _center = [lati, long];
            renderMap( _center, markersData, zoom, google.maps.MapTypeId.ROADMAP, false );
            <?php } ?>
        });
    });
</script>

<?php
get_footer();