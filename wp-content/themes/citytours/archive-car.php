<?php
/* Car List Page Template */
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

$order_by = ( isset( $_REQUEST['order_by'] ) && array_key_exists( $_REQUEST['order_by'], $order_by_array ) ) ? sanitize_text_field( $_REQUEST['order_by'] ) : 'price';
$order = ( isset( $_REQUEST['order'] ) && in_array( $_REQUEST['order'], $order_array ) ) ? sanitize_text_field( $_REQUEST['order'] ) : 'ASC';
$car_type = ( isset( $_REQUEST['car_types'] ) ) ? ( is_array( $_REQUEST['car_types'] ) ? $_REQUEST['car_types'] : array( $_REQUEST['car_types'] ) ):array();
$price_filter = ( isset( $_REQUEST['price_filter'] ) && is_array( $_REQUEST['price_filter'] ) ) ? $_REQUEST['price_filter'] : array();
$rating_filter = ( isset( $_REQUEST['rating_filter'] ) && is_array( $_REQUEST['rating_filter'] ) ) ? $_REQUEST['rating_filter'] : array();
$facility_filter = ( isset( $_REQUEST['facilities'] ) && is_array( $_REQUEST['facilities'] ) ) ? $_REQUEST['facilities'] : array();

if ( isset( $_REQUEST['view'] ) && ! empty( $_REQUEST['view'] ) ) { 
    $current_view = sanitize_text_field( $_REQUEST['view'] );
} else if ( isset( $ct_options['car_list_default_view'] ) && ! empty( $ct_options['car_list_default_view'] ) ) { 
    $current_view = $ct_options['car_list_default_view'];
} else { 
    $current_view = 'list';
}

$page = ( isset( $_REQUEST['page'] ) && ( is_numeric( $_REQUEST['page'] ) ) && ( $_REQUEST['page'] >= 1 ) ) ? sanitize_text_field( $_REQUEST['page'] ):1;
$per_page = ( isset( $ct_options['car_posts'] ) && is_numeric($ct_options['car_posts']) )?$ct_options['car_posts']:6;
$search_result = ct_car_get_search_result( array( 's'=>$s, 'car_type'=>$car_type, 'price_filter'=>$price_filter, 'rating_filter'=>$rating_filter, 'facility_filter'=>$facility_filter, 'order_by'=>$order_by_array[$order_by], 'order'=>$order, 'last_no'=>( $page - 1 ) * $per_page, 'per_page'=>$per_page ) );
$post_list = $search_result['ids'];
$count = $search_result['count']; // total_count

$header_img_scr = ct_get_header_image_src( 'car' );
if ( ! empty( $header_img_scr ) ) {
    $header_content = ct_get_header_content( 'car' );
    $header_img_height = ct_get_header_image_height('car');
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

<div class="container margin_60">
    <div class="row">
        <aside class="col-lg-3 col-md-3">
            <div id="search_results"><?php echo sprintf( esc_html__( '%d Results found', 'citytours' ), $count ) ?></div>
            
            <div id="modify_search">
                <a data-toggle="collapse" href="#collapseModify_search" aria-expanded="false" aria-controls="collapseModify_search" id="modify_col_bt"><i class="icon_set_1_icon-78"></i><?php echo esc_html( 'Modify Search', 'citytours' ) ?> <i class="icon-plus-1 pull-right"></i></a>

                <div class="collapse" id="collapseModify_search">
                    <div class="modify_search_wp">
                        <form role="search" method="get" id="search-car-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <input type="hidden" name="post_type" value="car">

                            <div class="form-group">
                                <label><?php echo esc_html( 'Search terms', 'citytours' ) ?></label>
                                <input type="text" class="form-control" id="search_terms" name="s" placeholder="<?php echo esc_html( 'Type your pick up location or destination', 'citytours' ) ?>" value="<?php echo esc_attr( $s ) ?>">
                            </div>

                            <div class="form-group">
                                <label><?php echo esc_html( 'Car Transfer Type', 'citytours' ) ?></label>

                                <?php
                                $all_car_types = get_terms( 'car_type', array('hide_empty' => 0) );
                                if ( ! empty( $all_car_types ) ) : 
                                    ?>

                                    <select class="form-control" name="car_types">
                                        <option value="" selected><?php esc_html_e( 'All cars', 'citytours' ) ?></option>
                                        <?php 
                                        foreach ( $all_car_types as $each_car_type ) {
                                            $term_id = $each_car_type->term_id;
                                            $icon_class = get_tax_meta( $term_id, 'ct_tax_icon_class' );
                                            $selected = in_array( $term_id, $car_type ) ? 'selected' : '';
                                            ?>

                                            <option value="<?php echo esc_attr( $term_id ) ?>" <?php echo ( $selected ) ?>><?php echo esc_html( $each_car_type->name ) ?></option>

                                            <?php 
                                        } 
                                        ?>
                                    </select>

                                    <?php 
                                endif; 
                                ?>
                            </div>

                            <button class="btn_1 green"><?php esc_html_e( 'Search again', 'citytours' ) ?></button>
                        </form>
                    </div>
                </div><!--End collapse -->
            </div>

            <div class="box_style_cat">
                <ul id="cat_nav">
                    <?php
                    $selected = empty( $car_type )?' class="active"':'';
                    $counts_by_car_type = ct_car_get_search_result_count( array( 'by' => 'car_type', 's'=>$s, 'price_filter'=>$price_filter, 'rating_filter'=>$rating_filter, 'facility_filter'=>$facility_filter ) );

                    if ( is_rtl() ) {
                        echo '<li class="all-types"><a href="' . esc_url( remove_query_arg( array( 'car_types', 'page' ) ) ) . '"' . $selected . '><i class="icon_set_1_icon-51"></i><small>(' . esc_html( array_sum( $counts_by_car_type ) ) . ')</small>' . esc_html__( 'All cars', 'citytours' ) . '</a></li>';
                    } else { 
                        echo '<li class="all-types"><a href="' . esc_url( remove_query_arg( array( 'car_types', 'page' ) ) ) . '"' . $selected . '><i class="icon_set_1_icon-51"></i>' . esc_html__( 'All cars', 'citytours' ) . '<small>(' . esc_html( array_sum( $counts_by_car_type ) ) . ')</small></a></li>';
                    }

                    $all_car_types = get_terms( 'car_type', array('hide_empty' => 0) );

                    if ( ! empty( $all_car_types ) ) :
                        foreach ( $all_car_types as $each_car_type ) {
                            $term_id = $each_car_type->term_id;
                            $selected = ( ( is_array( $car_type ) && in_array( $term_id, $car_type ) ) )?' class="active"':'';
                            $icon_class = get_tax_meta( $term_id, 'ct_tax_icon_class' );

                            echo '<li data-term-id="' . esc_attr( $term_id ) . '"><a href="' . esc_url( add_query_arg( array( 'car_types'=>$term_id, 'page'=>0 ) ) ) . '"' . $selected . '>';

                            if ( ! empty( $icon_class ) ) {
                                echo '<i class="' . esc_attr( $icon_class ) . '"></i>';
                            }

                            if ( is_rtl() ) {
                                echo '<small>(' . esc_html( ( empty( $counts_by_car_type[ $term_id ] ) ? 0 : $counts_by_car_type[ $term_id ] ) ) . ')</small>' . esc_html( $each_car_type->name ) . '</a></li>';
                            } else { 
                                echo esc_html( $each_car_type->name ) . '<small>(' . esc_html( ( empty( $counts_by_car_type[ $term_id ] ) ? 0 : $counts_by_car_type[ $term_id ] ) ) . ')</small></a></li>';
                            }
                        }
                    endif;
                    ?>
                </ul>
            </div>

            <div id="filters_col">
                <a data-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters" id="filters_col_bt"><i class="icon_set_1_icon-65"></i><?php echo esc_html__( 'Filters', 'citytours' ) ?> <i class="icon-plus-1 pull-right"></i></a>

                <div class="collapse" id="collapseFilters">

                    <?php 
                    if ( ! empty( $ct_options['car_price_filter'] ) ) :
                        $price_steps = empty( $ct_options['car_price_filter_steps'] ) ? '50,80,100' : $ct_options['car_price_filter_steps'];
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

                    if ( ! empty( $ct_options['car_rating_filter'] ) ) :
                        ?>
                        <div class="filter_type">
                            <h6><?php echo esc_html__( 'Rating', 'citytours' ) ?></h6>

                            <ul class="list-filter rating-filter" data-base-url="<?php echo esc_url( remove_query_arg( array( 'rating_filter', 'page' ) ) ); ?>" data-arg="rating_filter">
                                <?php for ( $i = 5; $i > 0; $i-- ) {
                                    $checked = ( in_array( $i, $rating_filter ) ) ? ' checked="checked"' : ''; ?>
                                    <li>
                                        <label><input type="checkbox" name="rating_filter[]" value="<?php echo esc_attr( $i ) ?>"<?php echo ( $checked )?>><span class="rating"><?php ct_rating_smiles( $i ); ?></span></label>
                                    </li>

                                <?php } ?>
                            </ul>
                        </div>
                        <?php 
                    endif;

                    if ( ! empty( $ct_options['car_facility_filter'] ) ) :
                        ?>
                        <div class="filter_type">
                            <h6><?php echo esc_html__( 'Facility', 'citytours' ) ?></h6>

                            <ul class="list-filter facility-filter" data-base-url="<?php echo esc_url( remove_query_arg( array( 'facilities', 'page' ) ) ); ?>" data-arg="facilities">
                                <?php 
                                $all_facilities = get_terms( 'car_facility', array('hide_empty' => 0) );

                                if ( ! empty( $all_facilities ) ) :
                                    foreach ( $all_facilities as $facility ) {
                                        $term_id = $facility->term_id;
                                        $checked = ( in_array( $term_id, $facility_filter ) ) ? ' checked="checked"' : '';
                                        echo '<li><label><input type="checkbox" name="facility_filter[]" value="' . esc_attr( $term_id ) . '"' . $checked . '>' . esc_html( $facility->name ) . '</label></li>';
                                    }
                                endif;?>
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

            <div class="car-list <?php if ( $current_view == 'grid' ) echo 'row add-clearfix' ?>">
                <?php ct_get_template( 'car-list.php', '/templates/car/'); ?>
            </div><!-- End row -->

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

        jQuery('#cat_nav').mobileMenu();

        if ( jQuery('input.date-pick').length ) {
            if ( lang.substring( 0, 2 ) != 'fa' ) { 
                jQuery('input.date-pick').datepicker({
                    startDate: "today",
                    language: lang
                });
            } else { 
                var date_format = $('input.date-pick').data('date-format'); 
                $('input.date-pick').persianDatepicker({
                    observer: true,
                    format: date_format.toUpperCase(),
                });
            }
        }

    });
</script>

<?php 
get_footer();