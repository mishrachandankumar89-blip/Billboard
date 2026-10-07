<?php
/**
 * Locations archive.
 *
 * @package Billboard_Pro
 */

get_header();
bbp_page_hero( __( 'Advertising Locations', 'billboard-pro' ), __( 'Every city and corridor where we operate LED billboard screens and outdoor sites.', 'billboard-pro' ), bbp_img( 'hero-city.jpg' ) );
?>
<section class="section section--tight">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="loc-grid loc-grid--archive">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/location-card' );
				}
				?>
			</div>
			<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/no-results' ); ?>
		<?php endif; ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/cta-band' );
get_footer();
