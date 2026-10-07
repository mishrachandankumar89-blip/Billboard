<?php
/**
 * Single case study.
 *
 * @package Billboard_Pro
 */

get_header();

while ( have_posts() ) :
	the_post();
	$bbp_id      = get_the_ID();
	$bbp_results = bbp_lines( bbp_meta( 'cs_results' ) );
	$bbp_metrics = array();
	$bbp_points  = array();
	foreach ( $bbp_results as $bbp_r ) {
		if ( false !== strpos( $bbp_r, '|' ) ) {
			$bbp_metrics[] = array_map( 'trim', explode( '|', $bbp_r, 2 ) );
		} else {
			$bbp_points[] = $bbp_r;
		}
	}
	bbp_page_hero( get_the_title(), bbp_meta( 'cs_client' ) . ( bbp_meta( 'cs_industry' ) ? ' · ' . bbp_meta( 'cs_industry' ) : '' ), bbp_thumb_url( $bbp_id, 'bbp-wide' ) );
	?>

	<section class="case-facts">
		<div class="container">
			<dl class="case-facts__grid">
				<?php
				$bbp_facts = array(
					__( 'Client', 'billboard-pro' )      => bbp_meta( 'cs_client' ),
					__( 'Campaign', 'billboard-pro' )    => bbp_meta( 'cs_campaign' ),
					__( 'Location', 'billboard-pro' )    => bbp_meta( 'cs_location' ),
					__( 'Duration', 'billboard-pro' )    => bbp_meta( 'cs_duration' ),
					__( 'Format', 'billboard-pro' )      => bbp_meta( 'cs_format' ),
					__( 'Impressions', 'billboard-pro' ) => bbp_meta( 'cs_impressions' ),
				);
				foreach ( $bbp_facts as $bbp_l => $bbp_v ) {
					if ( $bbp_v ) {
						printf( '<div><dt>%1$s</dt><dd>%2$s</dd></div>', esc_html( $bbp_l ), esc_html( $bbp_v ) );
					}
				}
				?>
			</dl>
		</div>
	</section>

	<section class="section section--tight">
		<div class="container narrow">
			<?php if ( bbp_meta( 'cs_objective' ) ) : ?>
				<div class="objective">
					<p class="eyebrow"><?php esc_html_e( 'The objective', 'billboard-pro' ); ?></p>
					<p class="lead"><?php echo esc_html( bbp_meta( 'cs_objective' ) ); ?></p>
				</div>
			<?php endif; ?>
			<div class="entry-content"><?php the_content(); ?></div>
		</div>
	</section>

	<?php if ( $bbp_metrics || $bbp_points ) : ?>
		<section class="section section--dark section--tight">
			<div class="container">
				<p class="eyebrow eyebrow--light"><?php esc_html_e( 'Results', 'billboard-pro' ); ?></p>
				<?php if ( $bbp_metrics ) : ?>
					<div class="result-grid">
						<?php foreach ( $bbp_metrics as $bbp_m ) : ?>
							<div class="result reveal"><strong><?php echo esc_html( $bbp_m[0] ); ?></strong><span><?php echo esc_html( $bbp_m[1] ); ?></span></div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( $bbp_points ) : ?>
					<ul class="check-list check-list--light">
						<?php foreach ( $bbp_points as $bbp_pt ) : ?>
							<li><?php echo esc_html( $bbp_pt ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$bbp_gal = bbp_gallery_ids( 'cs_gallery', $bbp_id );
	if ( $bbp_gal ) :
		?>
		<section class="section section--tight">
			<div class="container">
				<div class="photo-grid" data-lightbox>
					<?php foreach ( $bbp_gal as $bbp_aid ) : ?>
						<a href="<?php echo esc_url( wp_get_attachment_image_url( $bbp_aid, 'full' ) ); ?>"><?php echo wp_get_attachment_image( $bbp_aid, 'bbp-card' ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( bbp_meta( 'cs_testimonial' ) ) : ?>
		<section class="section section--tight">
			<div class="container narrow">
				<figure class="big-quote">
					<?php bbp_the_icon( 'quote', 36 ); ?>
					<blockquote><p><?php echo esc_html( bbp_meta( 'cs_testimonial' ) ); ?></p></blockquote>
					<?php if ( bbp_meta( 'cs_testimonial_author' ) ) : ?>
						<figcaption><?php echo esc_html( bbp_meta( 'cs_testimonial_author' ) ); ?></figcaption>
					<?php endif; ?>
				</figure>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$bbp_more = new WP_Query( array( 'post_type' => 'bb_case_study', 'posts_per_page' => 3, 'post__not_in' => array( $bbp_id ), 'no_found_rows' => true ) );
	if ( $bbp_more->have_posts() ) :
		?>
		<section class="section section--light">
			<div class="container">
				<?php bbp_section_head( __( 'More campaigns', 'billboard-pro' ), __( 'See what else outdoor can do', 'billboard-pro' ) ); ?>
				<div class="card-grid card-grid--3">
					<?php
					while ( $bbp_more->have_posts() ) {
						$bbp_more->the_post();
						get_template_part( 'template-parts/case-card' );
					}
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	endif;
endwhile;

get_template_part( 'template-parts/cta-band' );
get_footer();
