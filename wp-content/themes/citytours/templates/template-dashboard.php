<?php
 /*
 Template Name: Dashboard Page Template
 */

if ( ! is_user_logged_in() ) {
	wp_redirect( home_url() );
	exit;
}

if ( ! defined( 'ABSPATH' ) ) { 
    exit; 
}

get_header();

if ( have_posts() ) {
	while ( have_posts() ) : the_post();
		$post_id = get_the_ID();
		$content_class = 'post-content';
		$slider_shortcode = get_post_meta( $post_id, '_slider_shortcode', true );
		
		if ( ! empty( $slider_shortcode ) && class_exists( 'RevSlider' ) ) {
			echo '<div class="slideshow">';
			echo do_shortcode( $slider_shortcode );
			echo '</div>';
		} else {
			$header_img_scr = ct_get_header_image_src( $post_id );
			$header_img_height = ct_get_header_image_height( $post_id );
			if ( ! empty( $header_img_scr ) ) {
				$header_content = ct_get_header_content( $post_id );
				?>

				<section class="parallax-window" data-parallax="scroll" data-image-src="<?php echo esc_url( $header_img_scr ) ?>" data-natural-width="1400" data-natural-height="<?php echo esc_attr( $header_img_height ); ?>">
					<div class="parallax-content-1">
						<div class="animated fadeInDown">
						<h1 class="page-title"><?php the_title(); ?></h1>
						<?php echo balancetags( $header_content ); ?>
						</div>
					</div>
				</section><!-- End section -->
			<?php } ?>

			<?php if ( ! is_front_page() ) : ?>
				<div id="position" <?php if ( empty( $header_img_scr ) ) echo 'class="blank-parallax"' ?>>
					<div class="container"><?php ct_breadcrumbs(); ?></div>
				</div><!-- End Position -->
			<?php endif; ?>

			<?php 
		} 

		$content_class = apply_filters( 'ct_add_custom_content_class', $content_class ); 
		?>

		<div class="container margin_60 <?php echo esc_attr( $content_class ); ?>">
			<div id="tabs" class="tabs">
				<nav>
					<ul>
						<li><a href="#section-1" class="icon-booking"><span><?php _e( 'Bookings', 'citytours' ); ?></span></a>
						</li>
						<li><a href="#section-2" class="icon-wishlist"><span><?php _e( 'Wishlist', 'citytours' ); ?></span></a>
						</li>
						<li><a href="#section-3" class="icon-settings"><span><?php _e( 'Settings', 'citytours' ); ?></span></a>
						</li>
					</ul>
				</nav>
				<div class="content">

					<section id="section-1">
						<?php ct_get_template( 'booking-history.php', '/templates/user' ); ?>
					</section>
					<!-- End section 1 -->

					<section id="section-2">
						<?php ct_get_template( 'wishlist.php', '/templates/user' ); ?>
					</section>
					<!-- End section 2 -->

					<section id="section-3">
						<?php ct_get_template( 'account.php', '/templates/user' ); ?>
					</section>
					<!-- End section 3 -->

					</div>
					<!-- End content -->
				</div>
				<!-- End tabs -->
			</div>


		<script type="text/javascript"> 
			$ = jQuery.noConflict();

			$(document).ready( function() {
	            new CBPFWTabs(document.getElementById('tabs'));

	            $('.wishlist_close_admin').on('click', function (c) {
					$(this).parent().parent().parent().fadeOut('slow', function (c) {});
				});
	        });
		</script>

		<?php 
	endwhile;
}

get_footer();