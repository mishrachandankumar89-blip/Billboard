<?php
/**
 * Single billboard.
 *
 * @package Billboard_Pro
 */

get_header();

while ( have_posts() ) :
	the_post();
	$bbp_id      = get_the_ID();
	$bbp_city    = bbp_term( $bbp_id, 'billboard_city' );
	$bbp_cterms  = get_the_terms( $bbp_id, 'billboard_city' );
	$bbp_tterms  = get_the_terms( $bbp_id, 'billboard_type' );
	$bbp_type    = bbp_term( $bbp_id, 'billboard_type' );
	$bbp_format  = bbp_meta( 'bb_format' );
	$bbp_digital = 'digital' === $bbp_format;
	$bbp_price   = bbp_meta( 'bb_price' );
	$bbp_cta     = bbp_billboard_cta( $bbp_id );
	$bbp_address = trim( implode( ', ', array_filter( array( bbp_meta( 'bb_address' ), $bbp_city, bbp_meta( 'bb_state' ), bbp_meta( 'bb_zip' ), bbp_meta( 'bb_country' ) ) ) ) );
	$bbp_gallery = bbp_gallery_ids( 'bb_gallery' );
	$bbp_loc_id  = absint( bbp_meta( 'bb_location_id' ) );
	?>

	<section class="bb-hero" style="--hero-img:url('<?php echo esc_url( bbp_thumb_url( $bbp_id, 'bbp-wide' ) ); ?>')">
		<div class="bb-hero__overlay" aria-hidden="true"></div>
		<div class="container bb-hero__inner">
			<?php bbp_breadcrumbs(); ?>
			<div class="bb-hero__badges">
				<?php echo wp_kses_post( bbp_badge( $bbp_id ) ); ?>
				<?php if ( $bbp_digital ) : ?>
					<span class="badge badge--digital"><?php esc_html_e( 'LED Digital', 'billboard-pro' ); ?></span>
				<?php endif; ?>
				<?php if ( bbp_meta( 'bb_code' ) ) : ?>
					<span class="badge badge--ghost"><?php echo esc_html( bbp_meta( 'bb_code' ) ); ?></span>
				<?php endif; ?>
			</div>
			<h1 class="bb-hero__title"><?php the_title(); ?></h1>
			<?php if ( $bbp_address ) : ?>
				<p class="bb-hero__loc"><?php bbp_the_icon( 'pin', 18 ); ?> <?php echo esc_html( $bbp_address ); ?></p>
			<?php endif; ?>
			<ul class="bb-hero__facts">
				<?php if ( $bbp_type ) : ?>
					<li><span><?php esc_html_e( 'Type', 'billboard-pro' ); ?></span><strong><?php echo esc_html( $bbp_type ); ?></strong></li>
				<?php endif; ?>
				<?php if ( bbp_dimensions( $bbp_id ) ) : ?>
					<li><span><?php esc_html_e( 'Size', 'billboard-pro' ); ?></span><strong><?php echo esc_html( bbp_dimensions( $bbp_id ) ); ?></strong></li>
				<?php endif; ?>
				<?php if ( bbp_meta( 'bb_daily_impressions' ) ) : ?>
					<li><span><?php esc_html_e( 'Daily impressions', 'billboard-pro' ); ?></span><strong><?php echo esc_html( bbp_compact( bbp_meta( 'bb_daily_impressions' ) ) ); ?>+</strong></li>
				<?php endif; ?>
				<li><span><?php esc_html_e( 'From', 'billboard-pro' ); ?></span><strong><?php echo esc_html( bbp_price( $bbp_price ) ); ?> <small><?php echo '' !== $bbp_price ? esc_html( bbp_price_unit( bbp_meta( 'bb_price_unit' ) ) ) : ''; ?></small></strong></li>
			</ul>
			<div class="btn-row">
				<a class="btn btn--primary btn--lg" href="<?php echo esc_url( $bbp_cta['url'] ); ?>"><?php echo esc_html( $bbp_cta['label'] ); ?> <?php bbp_the_icon( 'arrow', 18 ); ?></a>
				<a class="btn btn--outline-light btn--lg" href="#specs"><?php esc_html_e( 'View specifications', 'billboard-pro' ); ?></a>
			</div>
		</div>
	</section>

	<div class="bb-sticky-cta" aria-hidden="true">
		<div class="container bb-sticky-cta__inner">
			<span><strong><?php the_title(); ?></strong> · <?php echo esc_html( bbp_price( $bbp_price ) ); ?> <?php echo '' !== $bbp_price ? esc_html( bbp_price_unit( bbp_meta( 'bb_price_unit' ) ) ) : ''; ?></span>
			<a class="btn btn--primary btn--sm" href="#inquiry" tabindex="-1"><?php esc_html_e( 'Request a Quote', 'billboard-pro' ); ?></a>
		</div>
	</div>

	<section class="section section--tight">
		<div class="container bb-layout">
			<div class="bb-main">

				<?php if ( $bbp_gallery || has_post_thumbnail() ) : ?>
					<div class="bb-gallery" data-lightbox>
						<?php
						$bbp_all = $bbp_gallery;
						if ( has_post_thumbnail() ) {
							array_unshift( $bbp_all, get_post_thumbnail_id() );
						}
						$bbp_all = array_values( array_unique( $bbp_all ) );
						foreach ( array_slice( $bbp_all, 0, 5 ) as $bbp_i => $bbp_aid ) :
							$bbp_full = wp_get_attachment_image_url( $bbp_aid, 'full' );
							?>
							<a class="bb-gallery__item<?php echo 0 === $bbp_i ? ' bb-gallery__item--main' : ''; ?>" href="<?php echo esc_url( $bbp_full ); ?>">
								<?php echo wp_get_attachment_image( $bbp_aid, 0 === $bbp_i ? 'bbp-wide' : 'bbp-card', false, array( 'loading' => 0 === $bbp_i ? 'eager' : 'lazy' ) ); ?>
								<?php if ( 4 === $bbp_i && count( $bbp_all ) > 5 ) : ?>
									<span class="bb-gallery__more">+<?php echo esc_html( count( $bbp_all ) - 5 ); ?></span>
								<?php endif; ?>
							</a>
						<?php endforeach; ?>
						<?php foreach ( array_slice( $bbp_all, 5 ) as $bbp_aid ) : ?>
							<a class="bb-gallery__item" hidden href="<?php echo esc_url( wp_get_attachment_image_url( $bbp_aid, 'full' ) ); ?>"></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="bb-block entry-content">
					<h2><?php esc_html_e( 'About this location', 'billboard-pro' ); ?></h2>
					<?php
					if ( get_the_content() ) {
						the_content();
					} else {
						echo '<p>' . esc_html( get_the_excerpt() ) . '</p>';
					}
					?>
				</div>

				<div class="bb-block" id="specs">
					<h2><?php esc_html_e( 'Billboard specifications', 'billboard-pro' ); ?></h2>
					<dl class="spec-table">
						<?php
						$bbp_unit  = bbp_meta( 'bb_unit' ) ? bbp_meta( 'bb_unit' ) : 'm';
						$bbp_specs = array(
							__( 'Billboard ID', 'billboard-pro' )      => bbp_meta( 'bb_code' ),
							__( 'Billboard type', 'billboard-pro' )    => $bbp_type,
							__( 'Format', 'billboard-pro' )            => bbp_label( 'billboard', 'bb_format', $bbp_format ),
							__( 'Dimensions', 'billboard-pro' )        => bbp_dimensions( $bbp_id ),
							__( 'Width', 'billboard-pro' )             => bbp_meta( 'bb_width' ) ? floatval( bbp_meta( 'bb_width' ) ) . ' ' . $bbp_unit : '',
							__( 'Height', 'billboard-pro' )            => bbp_meta( 'bb_height' ) ? floatval( bbp_meta( 'bb_height' ) ) . ' ' . $bbp_unit : '',
							__( 'Size class', 'billboard-pro' )        => bbp_size_class( $bbp_id ) ? bbp_size_options()[ bbp_size_class( $bbp_id ) ] : '',
							__( 'Orientation', 'billboard-pro' )       => bbp_label( 'billboard', 'bb_orientation', bbp_meta( 'bb_orientation' ) ),
							__( 'Resolution', 'billboard-pro' )        => bbp_meta( 'bb_resolution' ),
							__( 'Ad slot', 'billboard-pro' )           => bbp_meta( 'bb_slot_length' ),
							__( 'Illumination', 'billboard-pro' )      => bbp_meta( 'bb_illumination' ),
							__( 'Installation', 'billboard-pro' )      => bbp_meta( 'bb_installation' ),
							__( 'Viewing direction', 'billboard-pro' ) => bbp_meta( 'bb_viewing' ),
						);
						foreach ( $bbp_specs as $bbp_l => $bbp_v ) {
							if ( '' === (string) $bbp_v ) {
								continue;
							}
							printf( '<div><dt>%1$s</dt><dd>%2$s</dd></div>', esc_html( $bbp_l ), esc_html( $bbp_v ) );
						}
						?>
					</dl>
				</div>

				<div class="bb-block">
					<h2><?php esc_html_e( 'Audience & traffic', 'billboard-pro' ); ?></h2>
					<div class="metric-grid">
						<?php
						$bbp_metrics = array(
							array( 'eye', bbp_meta( 'bb_daily_impressions' ) ? bbp_compact( bbp_meta( 'bb_daily_impressions' ) ) . '+' : '', __( 'Daily impressions', 'billboard-pro' ) ),
							array( 'chart', bbp_monthly_impressions( $bbp_id ) ? bbp_compact( bbp_monthly_impressions( $bbp_id ) ) . '+' : '', __( 'Monthly impressions', 'billboard-pro' ) ),
							array( 'car', bbp_meta( 'bb_traffic_count' ) ? bbp_compact( bbp_meta( 'bb_traffic_count' ) ) : '', __( 'Daily traffic count', 'billboard-pro' ) ),
							array( 'zap', bbp_label( 'billboard', 'bb_traffic_level', bbp_meta( 'bb_traffic_level' ) ), __( 'Traffic level', 'billboard-pro' ) ),
							array( 'ruler', bbp_meta( 'bb_visibility' ), __( 'Visibility distance', 'billboard-pro' ) ),
							array( 'clock', bbp_meta( 'bb_view_time' ), __( 'Avg. viewing time', 'billboard-pro' ) ),
						);
						foreach ( $bbp_metrics as $bbp_m ) :
							if ( ! $bbp_m[1] ) {
								continue;
							}
							?>
							<div class="metric">
								<span class="metric__icon"><?php bbp_the_icon( $bbp_m[0], 22 ); ?></span>
								<strong class="metric__value"><?php echo esc_html( $bbp_m[1] ); ?></strong>
								<span class="metric__label"><?php echo esc_html( $bbp_m[2] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
					<?php if ( bbp_meta( 'bb_demographics' ) ) : ?>
						<h3><?php esc_html_e( 'Audience demographics', 'billboard-pro' ); ?></h3>
						<p><?php echo nl2br( esc_html( bbp_meta( 'bb_demographics' ) ) ); ?></p>
					<?php endif; ?>
					<p class="small-note"><?php esc_html_e( 'Impressions are modelled estimates based on traffic counts, visibility and dwell time. Full audience reports are available on request.', 'billboard-pro' ); ?></p>
				</div>

				<?php if ( $bbp_digital ) : ?>
					<div class="bb-block bb-block--dark">
						<h2><?php esc_html_e( 'Why advertise on this LED screen', 'billboard-pro' ); ?></h2>
						<ul class="check-list">
							<li><?php esc_html_e( 'High-brightness LED — fully readable in direct sunlight, auto-dimmed at night.', 'billboard-pro' ); ?></li>
							<li><?php esc_html_e( 'Book by week or daypart and run several creatives in rotation.', 'billboard-pro' ); ?></li>
							<li><?php esc_html_e( 'Remote content updates — change your message without print or installation costs.', 'billboard-pro' ); ?></li>
							<li><?php esc_html_e( 'Proof-of-play logs and campaign photography included.', 'billboard-pro' ); ?></li>
						</ul>
						<?php
						$bbp_link = get_page_by_path( 'led-billboard-content-specifications', OBJECT, 'post' );
						if ( $bbp_link ) :
							?>
							<p><a class="link-arrow link-arrow--light" href="<?php echo esc_url( get_permalink( $bbp_link ) ); ?>"><?php esc_html_e( 'Read our LED creative specification guide', 'billboard-pro' ); ?> <?php bbp_the_icon( 'arrow', 16 ); ?></a></p>
						<?php endif; ?>
					</div>
				<?php else : ?>
					<div class="bb-block bb-block--dark">
						<h2><?php esc_html_e( 'Why advertise on this billboard', 'billboard-pro' ); ?></h2>
						<ul class="check-list">
							<li><?php esc_html_e( 'Exclusive, 24/7 presence — your brand is the only message on the face.', 'billboard-pro' ); ?></li>
							<li><?php esc_html_e( 'Landmark scale that builds recall with daily commuters.', 'billboard-pro' ); ?></li>
							<li><?php esc_html_e( 'Printing and installation managed end-to-end.', 'billboard-pro' ); ?></li>
						</ul>
					</div>
				<?php endif; ?>

				<div class="bb-block">
					<h2><?php esc_html_e( 'Location', 'billboard-pro' ); ?></h2>
					<?php bbp_map( bbp_meta( 'bb_lat' ), bbp_meta( 'bb_lng' ), get_the_title() ); ?>
					<div class="loc-info">
						<?php if ( $bbp_address ) : ?>
							<p><?php bbp_the_icon( 'pin', 18 ); ?> <?php echo esc_html( $bbp_address ); ?></p>
						<?php endif; ?>
						<?php $bbp_marks = bbp_lines( bbp_meta( 'bb_landmark' ) ); ?>
						<?php if ( $bbp_marks ) : ?>
							<h3><?php esc_html_e( 'Nearby landmarks', 'billboard-pro' ); ?></h3>
							<ul class="tag-list">
								<?php foreach ( $bbp_marks as $bbp_mk ) : ?>
									<li><?php echo esc_html( $bbp_mk ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<p class="term-links">
							<?php if ( $bbp_loc_id ) : ?>
								<a href="<?php echo esc_url( get_permalink( $bbp_loc_id ) ); ?>"><?php /* translators: %s: location */ printf( esc_html__( 'Explore the %s location guide', 'billboard-pro' ), esc_html( get_the_title( $bbp_loc_id ) ) ); ?></a>
							<?php endif; ?>
							<?php if ( $bbp_cterms && ! is_wp_error( $bbp_cterms ) ) : ?>
								<a href="<?php echo esc_url( get_term_link( $bbp_cterms[0] ) ); ?>"><?php /* translators: %s: city */ printf( esc_html__( 'All billboards in %s', 'billboard-pro' ), esc_html( $bbp_cterms[0]->name ) ); ?></a>
							<?php endif; ?>
							<?php if ( $bbp_tterms && ! is_wp_error( $bbp_tterms ) ) : ?>
								<a href="<?php echo esc_url( get_term_link( $bbp_tterms[0] ) ); ?>"><?php /* translators: %s: type */ printf( esc_html__( 'More %s sites', 'billboard-pro' ), esc_html( $bbp_tterms[0]->name ) ); ?></a>
							<?php endif; ?>
						</p>
					</div>
				</div>

				<div class="bb-block" id="inquiry">
					<h2><?php esc_html_e( 'Request a quote for this billboard', 'billboard-pro' ); ?></h2>
					<p><?php esc_html_e( 'Tell us your dates and budget — we will confirm availability and send a proposal within one business day.', 'billboard-pro' ); ?></p>
					<div id="form">
						<?php
						bbp_render_form(
							'quote',
							array(
								'billboard_id' => $bbp_id,
								'city'         => $bbp_city,
								'location'     => get_the_title() . ( bbp_meta( 'bb_code' ) ? ' (' . bbp_meta( 'bb_code' ) . ')' : '' ),
								'type'         => $bbp_type,
							)
						);
						?>
					</div>
				</div>
			</div>

			<aside class="bb-aside">
				<div class="price-card">
					<?php echo wp_kses_post( bbp_badge( $bbp_id ) ); ?>
					<?php if ( bbp_meta( 'bb_available_from' ) && 'available' !== bbp_meta( 'bb_availability' ) ) : ?>
						<p class="price-card__next"><?php /* translators: %s: date */ printf( esc_html__( 'Next available: %s', 'billboard-pro' ), esc_html( date_i18n( get_option( 'date_format' ), strtotime( bbp_meta( 'bb_available_from' ) ) ) ) ); ?></p>
					<?php endif; ?>
					<p class="price-card__label"><?php esc_html_e( 'Starting from', 'billboard-pro' ); ?></p>
					<p class="price-card__price"><?php echo esc_html( bbp_price( $bbp_price ) ); ?> <small><?php echo '' !== $bbp_price ? esc_html( bbp_price_unit( bbp_meta( 'bb_price_unit' ) ) ) : ''; ?></small></p>
					<dl class="price-card__list">
						<?php if ( bbp_meta( 'bb_min_booking' ) ) : ?>
							<div><dt><?php esc_html_e( 'Minimum booking', 'billboard-pro' ); ?></dt><dd><?php echo esc_html( bbp_meta( 'bb_min_booking' ) ); ?></dd></div>
						<?php endif; ?>
						<?php if ( bbp_meta( 'bb_campaign_duration' ) ) : ?>
							<div><dt><?php esc_html_e( 'Typical campaign', 'billboard-pro' ); ?></dt><dd><?php echo esc_html( bbp_meta( 'bb_campaign_duration' ) ); ?></dd></div>
						<?php endif; ?>
						<?php if ( bbp_monthly_impressions( $bbp_id ) ) : ?>
							<div><dt><?php esc_html_e( 'Monthly impressions', 'billboard-pro' ); ?></dt><dd><?php echo esc_html( bbp_compact( bbp_monthly_impressions( $bbp_id ) ) ); ?>+</dd></div>
						<?php endif; ?>
					</dl>
					<a class="btn btn--primary btn--block btn--lg" href="<?php echo esc_url( $bbp_cta['url'] ); ?>"><?php echo esc_html( $bbp_cta['label'] ); ?></a>
					<a class="btn btn--ghost btn--block" href="<?php echo esc_url( add_query_arg( array( 'bb_id' => $bbp_id, 'intent' => 'availability' ), bbp_page_url( 'quote' ) ) ); ?>"><?php esc_html_e( 'Check availability', 'billboard-pro' ); ?></a>
					<?php if ( bbp_opt( 'phone' ) ) : ?>
						<a class="price-card__phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', bbp_opt( 'phone' ) ) ); ?>"><?php bbp_the_icon( 'phone', 16 ); ?> <?php echo esc_html( bbp_opt( 'phone' ) ); ?></a>
					<?php endif; ?>
				</div>
			</aside>
		</div>
	</section>

	<?php
	// Related: same city first, then same type.
	$bbp_tax = array( 'relation' => 'OR' );
	if ( $bbp_cterms && ! is_wp_error( $bbp_cterms ) ) {
		$bbp_tax[] = array( 'taxonomy' => 'billboard_city', 'field' => 'term_id', 'terms' => wp_list_pluck( $bbp_cterms, 'term_id' ) );
	}
	if ( $bbp_tterms && ! is_wp_error( $bbp_tterms ) ) {
		$bbp_tax[] = array( 'taxonomy' => 'billboard_type', 'field' => 'term_id', 'terms' => wp_list_pluck( $bbp_tterms, 'term_id' ) );
	}
	$bbp_related = new WP_Query(
		array(
			'post_type'      => 'billboard',
			'posts_per_page' => 3,
			'post__not_in'   => array( $bbp_id ),
			'no_found_rows'  => true,
			'tax_query'      => count( $bbp_tax ) > 1 ? $bbp_tax : array(), // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'rand',
		)
	);
	if ( $bbp_related->have_posts() ) :
		?>
		<section class="section section--light">
			<div class="container">
				<?php bbp_section_head( __( 'Related billboards', 'billboard-pro' ), __( 'Build a network around this site', 'billboard-pro' ) ); ?>
				<div class="card-grid card-grid--3">
					<?php
					while ( $bbp_related->have_posts() ) {
						$bbp_related->the_post();
						get_template_part( 'template-parts/billboard-card' );
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
