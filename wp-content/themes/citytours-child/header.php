<?php
/* Main Header Template */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $ct_options;
?>

<!DOCTYPE html>
<!--[if IE 7 ]>    <html class="ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE   ]>    <html class="ie" <?php language_attributes(); ?>> <![endif]-->
<!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<html <?php language_attributes(); ?>>
<head>
    <!-- Meta Tags -->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="<?php echo esc_url( ct_favicon_url() ); ?>" type="image/x-icon" />

    <?php wp_head();?>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-163950422-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-163950422-1');
    </script>

    <!-- Global site tag (gtag.js) - Google Ads -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-954412331"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'AW-954412331');
    </script>
</head>

<?php $body_class = ct_get_extra_body_class(); ?>

<body <?php body_class( $body_class ); ?>>
<!--[if lte IE 8]>
    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="//browsehappy.com/">upgrade your browser</a>.</p>
<![endif]-->

    <?php if ( ! empty( $ct_options['preload'] ) ) : ?>
    <div id="preloader">
        <div class="sk-spinner sk-spinner-wave">
            <div class="sk-rect1"></div>
            <div class="sk-rect2"></div>
            <div class="sk-rect3"></div>
            <div class="sk-rect4"></div>
            <div class="sk-rect5"></div>
        </div>
    </div>
    <!-- End Preload -->
    <?php endif; ?>

    <div class="layer"></div>
    <!-- Mobile menu overlay mask -->

    <!-- Header Plain:  add the class plain to header and change logo.png to logo_sticky.png ======================= -->
    <?php $header_class = '';
    $page_template = basename( get_page_template() );
    if ( ( ! empty ( $ct_options['header_style'] ) && ( $ct_options['header_style'] == 'plain' ) ) || ( $page_template == "template-full-width-no-footer.php" ) ) { $header_class = 'plain'; } ?>

    <header class="<?php echo esc_attr( $header_class ); ?>">
        <div id="top_line">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <?php if ( ! empty( $ct_options['phone_no'] ) ) { ?><i class="icon-phone"></i><strong><?php echo esc_html( $ct_options['phone_no'] ) ?></strong><?php } ?>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <ul id="top_links">
                            <?php
                            if ( ( isset($ct_options['header_top_bar_enable_custom']) && ! empty($ct_options['header_top_bar_enable_custom']) ) || ! isset($ct_options['header_top_bar_enable_custom']) ) {
                                if( isset( $ct_options['header_top_bar_custom'] ) ) {
                                ?>

                                <li>
                                    <?php echo ( $ct_options['header_top_bar_custom'] ); ?>
                                </li>

                                <?php
                                }
                            }
                            ?>

                            <?php
                            if ( ( isset($ct_options['header_top_bar_wishlist']) && ! empty($ct_options['header_top_bar_wishlist']) ) || ! isset($ct_options['header_top_bar_wishlist']) ) :
                                $wishlist_link = ct_wishlist_page_url();
                                $class = ( $wishlist_link == '#' ) ? 'ct-modal-login' : '';

                                if ( ! empty( $wishlist_link ) ) :
                                    ?>

                                    <li>
                                        <a href="<?php echo esc_url( $wishlist_link ); ?>" id="wishlist_link" class="<?php echo esc_attr( $class ) ?>"><?php esc_html_e( 'Wishlist', 'citytours' ) ?></a>
                                    </li>

                                    <?php
                                endif;
                            endif;
                            ?>

                            <?php
                            if ( ( isset($ct_options['header_top_bar_login']) && ! empty($ct_options['header_top_bar_login']) ) || ! isset($ct_options['header_top_bar_login']) ) :
                                if ( is_user_logged_in() ) {
                                    ?>

                                    <li><a href="<?php echo esc_url( wp_logout_url( ct_get_current_page_url() ) ); ?>"><?php esc_html_e( 'Log out', 'citytours' ) ?></a></li>

                                    <?php
                                } else {
                                    ?>

                                    <li>
                                        <?php if ( ct_login_url() == '#' ) { ?>
                                            <div class="dropdown dropdown-access">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="access_link"><?php esc_html_e( 'Log in', 'citytours' ) ?></a>

                                                <div class="dropdown-menu">
                                                    <div class="text-center">
                                                        <img src="<?php echo esc_url( ct_logo_sticky_url() ) ?>" width="<?php echo esc_attr( ct_get_header_logo_width() ) ?>" height="<?php echo esc_attr( ct_get_header_logo_height() ) ?>" alt="City tours" data-retina="true" class="logo_sticky">
                                                        <div class="login-or"><hr class="hr-or"></div>
                                                    </div>

                                                    <form name="loginform" action="<?php echo esc_url( wp_login_url() )?>" method="post" class="loginform">
                                                        <div class="form-group">
                                                            <input type="text" name="log" class="form-control" placeholder="<?php esc_html_e( 'user name', 'citytours' ); ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="password" name="pwd" class="form-control" placeholder="<?php esc_html_e( 'password', 'citytours' ); ?>">
                                                        </div>

                                                        <input type="hidden" name="redirect_to" value="<?php echo esc_url( ct_redirect_url() ) ?>">

                                                        <a id="forgot_pw" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot password?', 'citytours' ); ?></a>

                                                        <input type="submit" value="<?php esc_html_e( 'Log in', 'citytours' ) ?>" class="button_drop">

                                                        <?php if ( get_option('users_can_register') ) { ?>
                                                        <a class="button_drop outline signup-btn" href="#"><?php esc_html_e( 'Sign up', 'citytours' ) ?></a>
                                                        <?php } ?>
                                                    </form>

                                                    <?php
                                                    if ( get_option('users_can_register') ) {
                                                        ?>

                                                        <form name="registerform" action="<?php echo esc_url( wp_registration_url() )?>" method="post" class="signupform">
                                                            <div class="form-group">
                                                                <input type="text" name="user_login" class="form-control" placeholder="<?php esc_html_e( 'user name', 'citytours' ); ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="email" name="user_email" class="form-control" placeholder="<?php esc_html_e( 'email address', 'citytours' ); ?>">
                                                            </div>

                                                            <input type="hidden" name="redirect_to" value="<?php echo esc_url( add_query_arg( array('checkemail' => 'confirm'), wp_login_url() ) )?>">

                                                            <input type="submit" value="<?php esc_html_e( 'Sign up', 'citytours' ) ?>" class="button_drop">

                                                            <a class="button_drop outline login-btn" href="#"><?php esc_html_e( 'Log in', 'citytours' ) ?></a>
                                                        </form>

                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div><!-- End Dropdown access -->
                                        <?php } else { ?>
                                            <a href="<?php echo esc_url( ct_login_url() ) ?>"><?php esc_html_e( 'Log in', 'citytours' ) ?></a>
                                        <?php } ?>
                                    </li>

                                    <?php
                                }
                            endif;
                            ?>
                        </ul>
                    </div>
                </div><!-- End row -->
            </div><!-- End container-->
        </div><!-- End top line-->

        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-3">
                    <div id="logo">
                        <a href="<?php echo esc_url( home_url('/') ); ?>"><img src="<?php echo esc_url( ct_logo_url() ) ?>" width="<?php echo esc_attr( ct_get_header_logo_width() ) ?>" height="<?php echo esc_attr( ct_get_header_logo_height() ) ?>" alt="City tours" data-retina="true" class="logo_normal"></a>
                        <a href="<?php echo esc_url( home_url('/') ); ?>"><img src="<?php echo esc_url( ct_logo_sticky_url() ) ?>" width="<?php echo esc_attr( ct_get_header_logo_width() ) ?>" height="<?php echo esc_attr( ct_get_header_logo_height() ) ?>" alt="City tours" data-retina="true" class="logo_sticky"></a>
                    </div>
                </div>

                <nav class="col-md-9 col-sm-9 col-xs-9">
                    <a class="cmn-toggle-switch cmn-toggle-switch__htx open_close" href="javascript:void(0);"><span><?php _e('Menu mobile', 'citytours') ?></span></a>

                    <div class="main-menu">
                        <div id="header_menu">
                            <img src="<?php echo esc_url( ct_logo_sticky_url() ) ?>" width="<?php echo esc_attr( ct_get_header_logo_width() ) ?>" height="<?php echo esc_attr( ct_get_header_logo_height() ) ?>" alt="City tours" data-retina="true">
                        </div>

                        <a href="#" class="open_close" id="close_in"><i class="icon_set_1_icon-77"></i></a>

                        <?php
                        if ( has_nav_menu( 'header-menu' ) ) {
                            wp_nav_menu( array( 'theme_location' => 'header-menu' ) );
                        } else {
                            ?>
                            <div>
                                <ul>
                                    <li class="menu-item"><a href="<?php echo esc_url( home_url('/') ); ?>"><?php esc_html_e( 'Home', 'citytours'); ?></a></li>
                                    <li class="menu-item"><a href="<?php echo esc_url( admin_url('nav-menus.php') ); ?>"><?php esc_html_e( 'Configure', 'citytours'); ?></a></li>
                                </ul>
                            </div>
                            <?php
                        }
                        ?>

                        <?php
                        if ( class_exists('WooCommerce') && $ct_options['cart_show_mini_cart'] ) {
                            ?>
                            <div class="visible-xs-block">
                                <ul>
                                    <li>
                                        <a href="<?php echo wc_get_cart_url(); ?>">
                                            <i class=" icon-basket-1"></i>
                                            <?php _e( 'Cart', 'citytours' ); ?>
                                            (<span class="cart-item-qty"><?php echo WC()->cart->cart_contents_count; ?></span>)
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <?php
                        }
                        ?>
                    </div><!-- End main-menu -->

                    <ul id="top_tools">
                        <?php
                        if ( ( isset($ct_options['header_search_bar']) && ! empty($ct_options['header_search_bar']) ) || ! isset($ct_options['header_search_bar']) ) :
                            ?>
                        <li>
                            <div class="dropdown dropdown-search">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-search"></i></a>
                                <div class="dropdown-menu">
                                    <?php get_search_form(); ?>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>

                        <?php
                        if ( class_exists('WooCommerce') && $ct_options['cart_show_mini_cart'] ) {
                            ?>

                            <li>
                                <div class="dropdown dropdown-cart">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class=" icon-basket-1"></i><?php _e( 'Cart', 'citytours' ) ?>
                                        (<span class="cart-item-qty"><?php echo WC()->cart->cart_contents_count; ?></span>)
                                    </a>
                                    <ul class="dropdown-menu" id="cart_items">
                                        <?php woocommerce_mini_cart( array( 'type' => 'header-mini-cart' ) ); ?>
                                    </ul>
                                </div>
                            </li>

                            <?php
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div><!-- container -->
    </header><!-- End Header -->
