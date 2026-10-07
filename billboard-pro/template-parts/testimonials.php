<?php
/**
 * Testimonials slider (scroll-snap, no library).
 *
 * @package Billboard_Pro
 */

$bbp_t = new WP_Query(
	array(
		'post_type'      => 'bb_testimonial',
		'posts_per_page' => 9,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		'no_found_rows'  => true,
	)
);
if ( ! $bbp_t->have_posts() ) {
	return;
}
?>
<section class="section section--light testimonials">
	<div class="container">
		<div class="section-head-row">
			<?php bbp_section_head( __( 'Testimonials', 'billboard-pro' ), __( 'Brands that chose to be seen', 'billboard-pro' ) ); ?>
			<div class="slider-nav">
				<button type="button" class="slider-btn" data-slide="prev" aria-label="<?php esc_attr_e( 'Previous testimonial', 'billboard-pro' ); ?>"><?php bbp_the_icon( 'arrow', 18 ); ?></button>
				<button type="button" class="slider-btn" data-slide="next" aria-label="<?php esc_attr_e( 'Next testimonial', 'billboard-pro' ); ?>"><?php bbp_the_icon( 'arrow', 18 ); ?></button>
			</div>
		</div>
		<div class="t-track" data-slider tabindex="0" aria-label="<?php esc_attr_e( 'Testimonials', 'billboard-pro' ); ?>">
			<?php
			while ( $bbp_t->have_posts() ) :
				$bbp_t->the_post();
				$bbp_initials = implode( '', array_map( function ( $w ) { return mb_substr( $w, 0, 1 ); }, array_slice( explode( ' ', get_the_title() ), 0, 2 ) ) );
				?>
				<figure class="t-card">
					<?php bbp_the_icon( 'quote', 28 ); ?>
					<blockquote><?php echo wp_kses_post( wpautop( get_the_content() ) ); ?></blockquote>
					<figcaption>
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'bbp-square', array( 'class' => 't-card__photo', 'loading' => 'lazy' ) ); ?>
						<?php else : ?>
							<span class="t-card__photo t-card__photo--initials" aria-hidden="true"><?php echo esc_html( $bbp_initials ); ?></span>
						<?php endif; ?>
						<span>
							<strong><?php the_title(); ?></strong>
							<span><?php echo esc_html( trim( bbp_meta( 't_position' ) . ', ' . bbp_meta( 't_company' ), ', ' ) ); ?></span>
						</span>
					</figcaption>
				</figure>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
