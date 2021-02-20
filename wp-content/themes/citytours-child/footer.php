<?php
/* Footer Template */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $ct_options;

$page_template = basename( get_page_template() );

if ( $page_template != "template-full-width-no-footer.php" ) :
?>

<footer>
    <div class="container">

        <div class="row"><!-- Start row -->

            <div class="col-md-4 col-sm-3">
                <?php if ( is_active_sidebar( 'sidebar-footer-1' ) ) : ?>
                    <?php dynamic_sidebar( 'sidebar-footer-1' );?>
                <?php endif; ?>
            </div>

            <div class="col-md-3 col-sm-3">
                <?php if ( is_active_sidebar( 'sidebar-footer-2' ) ) : ?>
                    <?php dynamic_sidebar( 'sidebar-footer-2' );?>
                <?php endif; ?>
            </div>

            <div class="col-md-3 col-sm-3">
                <?php if ( is_active_sidebar( 'sidebar-footer-3' ) ) : ?>
                    <?php dynamic_sidebar( 'sidebar-footer-3' );?>
                <?php endif; ?>
            </div>

            <div class="col-md-2 col-sm-3">
                <?php if ( is_active_sidebar( 'sidebar-footer-4' ) ) : ?>
                    <?php dynamic_sidebar( 'sidebar-footer-4' );?>
                <?php endif; ?>
            </div>
        </div><!-- End row -->

        <!-- Start row - Logos -->
        <div class="row" id="footer-sidebar-widget-logos">

            <div class="col-md-4">
                <?php
                    if ( is_active_sidebar( 'footer-widget-logo-01' ) ) :
                        dynamic_sidebar( 'footer-widget-logo-01' );
                    endif;
                ?>
            </div>

            <div class="col-md-3">
                <?php
                    if ( is_active_sidebar( 'footer-widget-logo-02' ) ) :
                        dynamic_sidebar( 'footer-widget-logo-02' );
                    endif;
                ?>
            </div>

            <div class="col-md-3">
                <?php
                    if ( is_active_sidebar( 'footer-widget-logo-03' ) ) :
                        dynamic_sidebar( 'footer-widget-logo-03' );
                    endif;
                ?>
            </div>

            <div class="col-md-2 not-apply">
                <?php
                    if ( is_active_sidebar( 'footer-widget-logo-04' ) ) :
                        dynamic_sidebar( 'footer-widget-logo-04' );
                    endif;
                ?>
            </div>

        </div>
        <!-- End row - Logos -->

        <?php
        if ( is_active_sidebar( 'footer-sponsors-widget' ) ) {
            ?>
            <!-- Start row - Sponsors Logos -->
            <div class="row">
                <div class="col-md-12">
                    <?php dynamic_sidebar( 'footer-sponsors-widget' ); ?>
                </div>
            </div>
            <!-- End row - Sponsors Logos -->
            <?php
        }
        ?>

        <!-- Start row -->
        <div class="row">
            <div class="col-md-12">
                <div id="social_footer">
                    <ul>
                        <?php
                        $social_links = array( 'facebook', 'twitter', 'google', 'instagram', 'pinterest', 'vimeo', 'youtube-play', 'linkedin' );

                        foreach( $social_links as $social_link ) :
                            if ( ! empty( $ct_options[ $social_link ] ) ) :
                                ?>

                                <li><a href="<?php echo esc_url( $ct_options[ $social_link ] ) ?>"><i class="icon-<?php echo esc_attr( $social_link ) ?>"></i></a></li>

                                <?php
                            endif;
                        endforeach;

                        $social_links = array( 'tripadvisor', 'mixcloud' );

                        foreach( $social_links as $social_link ) :
                            if ( ! empty( $ct_options[ $social_link ] ) ) :
                                ?>

                                <li><a href="<?php echo esc_url( $ct_options[ $social_link ] ) ?>"><i class="fa fa-<?php echo esc_attr( $social_link ) ?>"></i></a></li>

                                <?php
                            endif;
                        endforeach;
                        ?>
                    </ul>

                    <?php if ( ! empty( $ct_options['copyright'] ) ) { ?>

                        <p>&copy; <?php echo esc_html( $ct_options['copyright'] ); ?></p>

                    <?php } ?>
                </div>
            </div>
        </div><!-- End row -->

    </div><!-- End container -->
</footer><!-- End footer -->

<?php endif; ?>

<div id="toTop"></div><!-- Back to top button -->
<div id="overlay"><i class="icon-spin3 animate-spin"></i></div>
<script type="text/javascript">
    jQuery(function() {
        <?php
            global $post;
            if ( ! empty( $post ) ) {
            $post_id = $post->ID;
            } else {
                $post_id = 0;
            }

            $popup_infos = get_post_meta( $post_id, 'popup_infos', true );
            if ( ! empty( $popup_infos ) ) {
                foreach ( $popup_infos as $key=>$popup_info ) {
                ?>
                    setTimeout(function() {
                        jQuery.notify({
                            // options
                            icon: '<?php echo ( ! empty( $popup_info['popup_icon'] ) ) ? $popup_info['popup_icon'] : ''; ?>',
                            title: "<?php echo ( ! empty( $popup_info['popup_title'] ) ) ? addslashes( $popup_info['popup_title'] ) : ''; ?>",
                            message: "<?php echo ( ! empty( $popup_info['popup_content'] ) ) ? addslashes( $popup_info['popup_content'] ) : ''; ?>"
                        },{
                            // settings
                            <?php echo ( !empty( $popup_info['popup_icon_type'] ) && $popup_info['popup_icon_type'] == "image" ) ? "icon_type: 'image'," : ""; ?>
                            type: 'info',
                            delay: 5000,
                            timer: 3000,
                            z_index: 9999,
                            showProgressbar: false,
                            placement: {
                                from: "bottom",
                                align: "right"
                            },
                            animate: {
                                enter: 'animated bounceInUp',
                                exit: 'animated bounceOutDown'
                            },
                        });
                         }, <?php echo ( ! empty( $popup_info['popup_delay_time'] ) ) ? intval( $popup_info['popup_delay_time'] ) : '1000'; ?> );
                <?php
                }
            }
        ?>
    });
</script>
<?php wp_footer(); ?>
</body>
</html>
