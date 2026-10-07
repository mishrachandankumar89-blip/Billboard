<?php
/**
 * Single location.
 *
 * @package Billboard_Pro
 */

get_header();

while ( have_posts() ) :
	the_post();
	$bbp_id   = get_the_ID();
	$bbp_args = bbp_location_billboard_args( $bbp_id, 12 );
	$bbp_q    = new WP_Query( $bbp_args );
	$bbp_n    = $bbp_q->found_posts;
	$bbp_led  = 0;
	foreach ( $bbp_args['post__in'] as $bbp_bid ) {
		if ( $bbp_bid && 'digital' === get_post_meta( $bbp_bid, 'bb_format', true ) ) {
			$bbp_led++;
		}
	}
	bbp_page_hero( get_the_title(), trim( bbp_meta( 'loc_state' ) . ' · ' . sprintf( /* translators: %d: count */ _n( '%d advertising location', '%d advertising locations', $bbp_n, 'billboard-pro' ), $bbp_n ), ' ·' ), bbp_thumb_url( $bbp_id, 'bbp-wide' ) );
	?>

	<section class="section section--tight">
		<div class="container loc-detail">
			<div class="loc-detail__main entry-content">
				<?php the_content(); ?>
			</div>
			<aside class="loc-detail__aside">
				<div class="fact-card">
					<h2 class="fact-card__title"><?php esc_html_e( 'Market snapshot', 'billboard-pro' ); ?></h2>
					<dl>
						<div><dt><?php esc_html_e( 'Billboards', 'billboard-pro' ); ?></dt><dd><?php echo esc_html( number_format_i18n( $bbp_n ) ); ?></dd></div>
						<div><dt><?php esc_html_e( 'LED screens', 'billboard-pro' ); ?></dt><dd><?php echo esc_html( number_format_i18n( $bbp_led ) ); ?></dd></div>
						<?php if ( bbp_meta( 'loc_state' ) ) : ?>
							<div><dt><?php esc_html_e( 'State / Region', 'billboard-pro' ); ?></dt><dd><?php echo esc_html( bbp_meta( 'loc_state' ) ); ?></dd></div>
						<?php endif; ?>
						<?php if ( bbp_meta( 'loc_address' ) ) : ?>
							<div><dt><?php esc_html_e( 'Area', 'billboard-pro' ); ?></dt><dd><?php echo esc_html( bbp_meta( 'loc_address' ) ); ?></dd></div>
						<?php endif; ?>
					</dl>
					<a class="btn btn--primary btn--block" href="<?php echo esc_url( add_query_arg( 'city', rawurlencode( bbp_meta( 'loc_city' ) ? bbp_meta( 'loc_city' ) : get_the_title() ), bbp_page_url( 'quote' ) ) ); ?>"><?php esc_html_e( 'Plan a campaign here', 'billboard-pro' ); ?></a>
				</div>
			</aside>
		</div>
	</section>

	<?php if ( bbp_meta( 'loc_traffic' ) || bbp_meta( 'loc_audience' ) || bbp_meta( 'loc_landmarks' ) ) : ?>
		<section class="section section--dark section--tight">
			<div class="container info-cols">
				<?php if ( bbp_meta( 'loc_traffic' ) ) : ?>
					<div><span class="benefit__icon"><?php bbp_the_icon( 'car', 26 ); ?></span><h2 class="h4"><?php esc_html_e( 'Traffic', 'billboard-pro' ); ?></h2><p><?php echo nl2br( esc_html( bbp_meta( 'loc_traffic' ) ) ); ?></p></div>
				<?php endif; ?>
				<?php if ( bbp_meta( 'loc_audience' ) ) : ?>
					<div><span class="benefit__icon"><?php bbp_the_icon( 'users', 26 ); ?></span><h2 class="h4"><?php esc_html_e( 'Audience', 'billboard-pro' ); ?></h2><p><?php echo nl2br( esc_html( bbp_meta( 'loc_audience' ) ) ); ?></p></div>
				<?php endif; ?>
				<?php if ( bbp_lines( bbp_meta( 'loc_landmarks' ) ) ) : ?>
					<div><span class="benefit__icon"><?php bbp_the_icon( 'building', 26 ); ?></span><h2 class="h4"><?php esc_html_e( 'Nearby landmarks', 'billboard-pro' ); ?></h2>
						<ul class="tag-list tag-list--light"><?php foreach ( bbp_lines( bbp_meta( 'loc_landmarks' ) ) as $bbp_l ) : ?><li><?php echo esc_html( $bbp_l ); ?></li><?php endforeach; ?></ul>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<section class="section section--light">
		<div class="container">
			<div class="section-head-row">
				<?php /* translators: %s: location */ bbp_section_head( __( 'Available billboards', 'billboard-pro' ), sprintf( __( 'Billboards in %s', 'billboard-pro' ), get_the_title() ) ); ?>
				<?php
				$bbp_term = get_term_by( 'name', bbp_meta( 'loc_city' ) ? bbp_meta( 'loc_city' ) : get_the_title(), 'billboard_city' );
				if ( $bbp_term ) :
					?>
					<a class="link-arrow" href="<?php echo esc_url( get_term_link( $bbp_term ) ); ?>"><?php esc_html_e( 'Filter all sites in this city', 'billboard-pro' ); ?> <?php bbp_the_icon( 'arrow', 16 ); ?></a>
				<?php endif; ?>
			</div>
			<?php if ( $bbp_q->have_posts() ) : ?>
				<div class="card-grid card-grid--3">
					<?php
					while ( $bbp_q->have_posts() ) {
						$bbp_q->the_post();
						get_template_part( 'template-parts/billboard-card' );
					}
					wp_reset_postdata();
					?>
				</div>
			<?php else : ?>
				<?php get_template_part( 'template-parts/no-results' ); ?>
			<?php endif; ?>
		</div>
	</section>

	<?php
	$bbp_gal = bbp_gallery_ids( 'loc_gallery', $bbp_id );
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

	<section class="section section--tight">
		<div class="container">
			<h2 class="h4"><?php esc_html_e( 'Map', 'billboard-pro' ); ?></h2>
			<?php bbp_map( bbp_meta( 'loc_lat', $bbp_id ), bbp_meta( 'loc_lng', $bbp_id ), get_the_title( $bbp_id ) ); ?>
			<?php
			$bbp_others = get_posts( array( 'post_type' => 'bb_location', 'posts_per_page' => 12, 'post__not_in' => array( $bbp_id ), 'orderby' => 'title', 'order' => 'ASC' ) );
			if ( $bbp_others ) :
				?>
				<h2 class="h4 mt-lg"><?php esc_html_e( 'Other markets', 'billboard-pro' ); ?></h2>
				<ul class="tag-list tag-list--links">
					<?php foreach ( $bbp_others as $bbp_o ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( $bbp_o ) ); ?>"><?php echo esc_html( $bbp_o->post_title ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</section>

	<?php
endwhile;
get_template_part( 'template-parts/cta-band' );
get_footer();
