<?php
 /*
 Template Name: Full Width Template
 */

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

				<section class="parallax-window" data-parallax="scroll" data-image-src="<?php echo esc_url( $header_img_scr ) ?>" data-natural-width="1400" data-natural-height="<?php echo esc_attr( $header_img_height ); ?>" style="<?php echo ( ! empty( $header_img_height ) ) ? 'min-height:' . esc_attr( $header_img_height ) . 'px' : ''; ?>">
					<div class="parallax-content-1" style="<?php echo ( ! empty( $header_img_height ) ) ? 'height:' . esc_attr( $header_img_height ) . 'px' : ''; ?>">
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
		<?php } 
		$content_class = apply_filters( 'ct_add_custom_content_class', $content_class ); 
		?>

		<div class="<?php echo esc_attr( $content_class ); ?>">
			<div class="post nopadding clearfix">
				<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'full', array('class' => 'img-responsive') ); } ?>
				<?php the_content(); ?>
				<?php wp_link_pages('before=<div class="page-links">&after=</div>'); ?>
				<?php if ( comments_open() || get_comments_number() ) {
					comments_template();
				} ?>
			</div><!-- end post -->
		</div>

		<?php 
	endwhile;
}

get_footer();