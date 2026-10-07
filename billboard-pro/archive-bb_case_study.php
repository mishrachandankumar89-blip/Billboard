<?php
/**
 * Case studies archive.
 *
 * @package Billboard_Pro
 */

get_header();
bbp_page_hero( __( 'Case Studies', 'billboard-pro' ), __( 'Real LED billboard and outdoor campaigns — the objective, the plan and the results.', 'billboard-pro' ), bbp_img( 'hero-campaign.jpg' ) );
?>
<section class="section section--tight">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="card-grid card-grid--3">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/case-card' );
				}
				?>
			</div>
			<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Case studies are coming soon.', 'billboard-pro' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/testimonials' );
get_template_part( 'template-parts/cta-band' );
get_footer();
