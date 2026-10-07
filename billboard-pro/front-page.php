<?php
/**
 * Homepage.
 *
 * @package Billboard_Pro
 */

get_header();

$bbp_archive = get_post_type_archive_link( 'billboard' );
$bbp_video   = absint( bbp_opt( 'hero_video' ) ) ? wp_get_attachment_url( absint( bbp_opt( 'hero_video' ) ) ) : '';
$bbp_hero    = bbp_opt_image( 'hero_image', 'hero.jpg' );
?>

<!-- HERO -->
<section class="hero" style="--hero-img:url('<?php echo esc_url( $bbp_hero ); ?>')">
	<?php if ( $bbp_video ) : ?>
		<video class="hero__video" autoplay muted loop playsinline poster="<?php echo esc_url( $bbp_hero ); ?>"><source src="<?php echo esc_url( $bbp_video ); ?>" type="video/mp4"></video>
	<?php endif; ?>
	<div class="hero__overlay" aria-hidden="true"></div>
	<div class="container hero__inner">
		<p class="eyebrow eyebrow--light hero__eyebrow"><span class="live-dot" aria-hidden="true"></span><?php echo esc_html( bbp_opt( 'hero_eyebrow' ) ); ?></p>
		<h1 class="hero__title"><?php echo esc_html( bbp_opt( 'hero_title' ) ); ?></h1>
		<p class="hero__text"><?php echo esc_html( bbp_opt( 'hero_text' ) ); ?></p>
		<div class="btn-row">
			<a class="btn btn--primary btn--lg" href="<?php echo esc_url( $bbp_archive ); ?>"><?php echo esc_html( bbp_opt( 'hero_cta1' ) ); ?> <?php bbp_the_icon( 'arrow', 18 ); ?></a>
			<a class="btn btn--outline-light btn--lg" href="<?php echo esc_url( bbp_page_url( 'quote' ) ); ?>"><?php echo esc_html( bbp_opt( 'hero_cta2' ) ); ?></a>
		</div>
		<ul class="hero__points">
			<li><?php bbp_the_icon( 'check', 16 ); ?><?php esc_html_e( 'Daylight-readable LED', 'billboard-pro' ); ?></li>
			<li><?php bbp_the_icon( 'check', 16 ); ?><?php esc_html_e( 'Book from 1 week', 'billboard-pro' ); ?></li>
			<li><?php bbp_the_icon( 'check', 16 ); ?><?php esc_html_e( 'Proof-of-play reporting', 'billboard-pro' ); ?></li>
		</ul>
	</div>
</section>

<!-- STATS -->
<section class="stats" aria-label="<?php esc_attr_e( 'Company in numbers', 'billboard-pro' ); ?>">
	<div class="container stats__grid">
		<?php for ( $bbp_i = 1; $bbp_i <= 4; $bbp_i++ ) : ?>
			<div class="stat reveal">
				<span class="stat__num" data-count="<?php echo esc_attr( bbp_opt( "stat{$bbp_i}_num" ) ); ?>"><?php echo esc_html( bbp_opt( "stat{$bbp_i}_num" ) ); ?></span>
				<span class="stat__label"><?php echo esc_html( bbp_opt( "stat{$bbp_i}_label" ) ); ?></span>
			</div>
		<?php endfor; ?>
	</div>
</section>

<!-- SEARCH -->
<section class="section search-section" id="find">
	<div class="container">
		<div class="search-panel reveal">
			<div class="search-panel__head">
				<p class="eyebrow"><?php esc_html_e( 'Live inventory', 'billboard-pro' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( 'Find the right screen in seconds', 'billboard-pro' ); ?></h2>
				<p class="section-intro"><?php esc_html_e( 'Filter LED and static billboards by city, format, size, availability and budget.', 'billboard-pro' ); ?></p>
			</div>
			<?php get_template_part( 'template-parts/filter-form', null, array( 'variant' => 'hero' ) ); ?>
		</div>
	</div>
</section>

<!-- FEATURED -->
<?php
$bbp_featured = new WP_Query(
	array(
		'post_type'      => 'billboard',
		'posts_per_page' => 6,
		'no_found_rows'  => true,
		'meta_key'       => 'bb_featured', // phpcs:ignore WordPress.DB.SlowDBQuery
		'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
	)
);
if ( ! $bbp_featured->have_posts() ) {
	$bbp_featured = new WP_Query( array( 'post_type' => 'billboard', 'posts_per_page' => 6, 'no_found_rows' => true ) );
}
if ( $bbp_featured->have_posts() ) :
	?>
	<section class="section section--light">
		<div class="container">
			<div class="section-head-row">
				<?php bbp_section_head( __( 'Featured locations', 'billboard-pro' ), __( 'Premium screens available now', 'billboard-pro' ), __( 'Hand-picked LED billboards and landmark sites with the highest reach in each market.', 'billboard-pro' ) ); ?>
				<a class="link-arrow" href="<?php echo esc_url( $bbp_archive ); ?>"><?php esc_html_e( 'View all billboards', 'billboard-pro' ); ?> <?php bbp_the_icon( 'arrow', 16 ); ?></a>
			</div>
			<div class="card-grid">
				<?php
				while ( $bbp_featured->have_posts() ) {
					$bbp_featured->the_post();
					get_template_part( 'template-parts/billboard-card' );
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- LED ADVANTAGES -->
<section class="section section--dark digital" id="led-screens">
	<div class="container digital__grid">
		<div class="digital__media reveal">
			<img src="<?php echo esc_url( bbp_opt_image( 'digital_image', 'led-closeup.jpg' ) ); ?>" alt="<?php esc_attr_e( 'Close-up of an outdoor LED billboard screen at dusk', 'billboard-pro' ); ?>" loading="lazy" width="1200" height="1400">
			<div class="digital__chip">
				<span class="live-dot" aria-hidden="true"></span>
				<span><strong><?php esc_html_e( 'Now playing', 'billboard-pro' ); ?></strong> <?php esc_html_e( '10-second spot · loop of 6', 'billboard-pro' ); ?></span>
			</div>
		</div>
		<div class="digital__content">
			<p class="eyebrow eyebrow--light"><?php esc_html_e( 'LED billboard screens', 'billboard-pro' ); ?></p>
			<h2 class="section-title"><?php echo esc_html( bbp_opt( 'digital_title' ) ); ?></h2>
			<p class="section-intro"><?php echo esc_html( bbp_opt( 'digital_text' ) ); ?></p>
			<ul class="feature-list">
				<?php
				$bbp_led = array(
					array( 'sun', __( 'Exceptional daylight performance', 'billboard-pro' ), __( 'High-brightness LED modules stay vivid and legible in direct sunlight, then dim automatically at night.', 'billboard-pro' ) ),
					array( 'calendar', __( 'Flexible advertising scheduling', 'billboard-pro' ), __( 'Book by day, week or daypart. Run morning-commute and evening creatives on the same screen.', 'billboard-pro' ) ),
					array( 'eye', __( 'Consistent image quality at any angle', 'billboard-pro' ), __( 'Wide horizontal viewing angles keep colour and contrast true for traffic approaching from both sides.', 'billboard-pro' ) ),
					array( 'building', __( 'Durable outdoor construction', 'billboard-pro' ), __( 'Weather-sealed cabinets built for rain, heat and dust, monitored remotely around the clock.', 'billboard-pro' ) ),
					array( 'layers', __( 'Customizable screen formats', 'billboard-pro' ), __( 'Landscape spectaculars, portrait towers and mall screens — creative specs supplied for every site.', 'billboard-pro' ) ),
					array( 'repeat', __( 'Multiple creatives, dynamic campaigns', 'billboard-pro' ), __( 'Rotate several messages, count down to launches or switch creative by weather and time.', 'billboard-pro' ) ),
				);
				foreach ( $bbp_led as $bbp_f ) :
					?>
					<li class="reveal">
						<span class="feature-list__icon"><?php bbp_the_icon( $bbp_f[0], 22 ); ?></span>
						<span><strong><?php echo esc_html( $bbp_f[1] ); ?></strong><?php echo esc_html( $bbp_f[2] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="btn-row">
				<a class="btn btn--primary btn--lg" href="<?php echo esc_url( add_query_arg( 'format', 'digital', $bbp_archive ) ); ?>"><?php esc_html_e( 'Explore Digital Screens', 'billboard-pro' ); ?> <?php bbp_the_icon( 'arrow', 18 ); ?></a>
				<?php $bbp_guide = get_page_by_path( 'what-is-an-led-billboard', OBJECT, 'post' ); ?>
				<?php if ( $bbp_guide ) : ?>
					<a class="btn btn--outline-light btn--lg" href="<?php echo esc_url( get_permalink( $bbp_guide ) ); ?>"><?php esc_html_e( 'How LED billboards work', 'billboard-pro' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<!-- WHY OUTDOOR -->
<section class="section">
	<div class="container">
		<?php bbp_section_head( __( 'Why outdoor advertising', 'billboard-pro' ), __( 'The one medium your audience can’t skip, block or scroll past', 'billboard-pro' ), __( 'Outdoor reaches people in the real world, at scale, every day — and LED makes it as flexible as digital media.', 'billboard-pro' ), 'center' ); ?>
		<div class="benefit-grid">
			<?php
			$bbp_benefits = array(
				array( 'eye', __( 'High visibility', 'billboard-pro' ), __( 'Large-format placements on the routes your customers already travel.', 'billboard-pro' ) ),
				array( 'reach', __( 'Massive reach', 'billboard-pro' ), __( 'Tens of thousands of impressions per screen, per day.', 'billboard-pro' ) ),
				array( 'target', __( 'Local targeting', 'billboard-pro' ), __( 'Choose cities, districts and corridors close to your stores.', 'billboard-pro' ) ),
				array( 'clock', __( '24/7 exposure', 'billboard-pro' ), __( 'Your message works day and night — no ad blockers, no skip button.', 'billboard-pro' ) ),
				array( 'star', __( 'Brand awareness', 'billboard-pro' ), __( 'Scale and permanence build trust and recall that small screens can’t.', 'billboard-pro' ) ),
				array( 'zap', __( 'Digital flexibility', 'billboard-pro' ), __( 'Launch, update or pause LED creative remotely, in minutes.', 'billboard-pro' ) ),
				array( 'repeat', __( 'High-frequency impressions', 'billboard-pro' ), __( 'Commuters see you repeatedly — the frequency that drives recall.', 'billboard-pro' ) ),
				array( 'chart', __( 'Measurable delivery', 'billboard-pro' ), __( 'Proof-of-play logs and audience estimates for every campaign.', 'billboard-pro' ) ),
			);
			foreach ( $bbp_benefits as $bbp_b ) :
				?>
				<div class="benefit reveal">
					<span class="benefit__icon"><?php bbp_the_icon( $bbp_b[0], 26 ); ?></span>
					<h3><?php echo esc_html( $bbp_b[1] ); ?></h3>
					<p><?php echo esc_html( $bbp_b[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- LOCATIONS -->
<?php
$bbp_locs = new WP_Query( array( 'post_type' => 'bb_location', 'posts_per_page' => 6, 'no_found_rows' => true, 'orderby' => 'menu_order title', 'order' => 'ASC' ) );
if ( $bbp_locs->have_posts() ) :
	?>
	<section class="section section--light locations-section">
		<div class="container">
			<div class="section-head-row">
				<?php bbp_section_head( __( 'Locations', 'billboard-pro' ), __( 'Where your brand can go live', 'billboard-pro' ), __( 'Prime screens in the cities and corridors that matter. Pick a market to see every available site.', 'billboard-pro' ) ); ?>
				<a class="link-arrow" href="<?php echo esc_url( get_post_type_archive_link( 'bb_location' ) ); ?>"><?php esc_html_e( 'All locations', 'billboard-pro' ); ?> <?php bbp_the_icon( 'arrow', 16 ); ?></a>
			</div>
			<div class="loc-layout">
				<div class="loc-map reveal" data-map-ready aria-label="<?php esc_attr_e( 'Coverage map', 'billboard-pro' ); ?>">
					<div class="loc-map__grid" aria-hidden="true"></div>
					<?php
					// Plot pins by normalised lat/lng — no map API needed. Swap for a real map via the bbp_map_html filter.
					$bbp_pins = array();
					while ( $bbp_locs->have_posts() ) {
						$bbp_locs->the_post();
						$bbp_lat = bbp_meta( 'loc_lat' );
						$bbp_lng = bbp_meta( 'loc_lng' );
						if ( is_numeric( $bbp_lat ) && is_numeric( $bbp_lng ) ) {
							$bbp_pins[] = array( (float) $bbp_lat, (float) $bbp_lng, get_the_title(), get_permalink() );
						}
					}
					if ( $bbp_pins ) {
						$bbp_lats = wp_list_pluck( $bbp_pins, 0 );
						$bbp_lngs = wp_list_pluck( $bbp_pins, 1 );
						$bbp_la0  = min( $bbp_lats );
						$bbp_la1  = max( $bbp_lats );
						$bbp_ln0  = min( $bbp_lngs );
						$bbp_ln1  = max( $bbp_lngs );
						foreach ( $bbp_pins as $bbp_pin ) {
							$bbp_x = $bbp_ln1 > $bbp_ln0 ? 10 + 80 * ( $bbp_pin[1] - $bbp_ln0 ) / ( $bbp_ln1 - $bbp_ln0 ) : 50;
							$bbp_y = $bbp_la1 > $bbp_la0 ? 10 + 80 * ( 1 - ( $bbp_pin[0] - $bbp_la0 ) / ( $bbp_la1 - $bbp_la0 ) ) : 50;
							printf(
								'<a class="map-pin%5$s" href="%1$s" style="left:%2$.1f%%;top:%3$.1f%%"><span class="map-pin__dot"></span><span class="map-pin__label">%4$s</span></a>',
								esc_url( $bbp_pin[3] ),
								(float) $bbp_x,
								(float) $bbp_y,
								esc_html( $bbp_pin[2] ),
								$bbp_x > 62 ? ' map-pin--left' : ''
							);
						}
					}
					?>
				</div>
				<div class="loc-grid">
					<?php
					$bbp_locs->rewind_posts();
					while ( $bbp_locs->have_posts() ) {
						$bbp_locs->the_post();
						get_template_part( 'template-parts/location-card' );
					}
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- HOW IT WORKS -->
<section class="section how">
	<div class="container">
		<?php bbp_section_head( __( 'How it works', 'billboard-pro' ), __( 'From brief to billboard in four steps', 'billboard-pro' ), '', 'center' ); ?>
		<ol class="steps">
			<?php
			$bbp_steps = array(
				array( __( 'Choose your location', 'billboard-pro' ), __( 'Browse cities and corridors, or tell us your audience and we will shortlist sites.', 'billboard-pro' ) ),
				array( __( 'Select your billboard', 'billboard-pro' ), __( 'Compare LED and static formats, sizes, impressions and pricing side by side.', 'billboard-pro' ) ),
				array( __( 'Submit your campaign', 'billboard-pro' ), __( 'Send dates and creative. We check specs and handle approvals and scheduling.', 'billboard-pro' ) ),
				array( __( 'Get your brand live', 'billboard-pro' ), __( 'Your campaign goes live on schedule — with photos and proof-of-play reporting.', 'billboard-pro' ) ),
			);
			foreach ( $bbp_steps as $bbp_n => $bbp_s ) :
				?>
				<li class="step reveal">
					<span class="step__num"><?php echo esc_html( sprintf( '%02d', $bbp_n + 1 ) ); ?></span>
					<h3><?php echo esc_html( $bbp_s[0] ); ?></h3>
					<p><?php echo esc_html( $bbp_s[1] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<!-- CASE STUDIES -->
<?php
$bbp_cases = new WP_Query( array( 'post_type' => 'bb_case_study', 'posts_per_page' => 3, 'no_found_rows' => true ) );
if ( $bbp_cases->have_posts() ) :
	?>
	<section class="section section--light">
		<div class="container">
			<div class="section-head-row">
				<?php bbp_section_head( __( 'Campaign showcase', 'billboard-pro' ), __( 'Campaigns that moved the needle', 'billboard-pro' ) ); ?>
				<a class="link-arrow" href="<?php echo esc_url( get_post_type_archive_link( 'bb_case_study' ) ); ?>"><?php esc_html_e( 'All case studies', 'billboard-pro' ); ?> <?php bbp_the_icon( 'arrow', 16 ); ?></a>
			</div>
			<div class="card-grid card-grid--3">
				<?php
				while ( $bbp_cases->have_posts() ) {
					$bbp_cases->the_post();
					get_template_part( 'template-parts/case-card' );
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_template_part( 'template-parts/testimonials' ); ?>

<!-- GUIDES -->
<?php
$bbp_posts = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 3, 'no_found_rows' => true, 'ignore_sticky_posts' => true ) );
if ( $bbp_posts->have_posts() ) :
	?>
	<section class="section">
		<div class="container">
			<div class="section-head-row">
				<?php bbp_section_head( __( 'Knowledge hub', 'billboard-pro' ), __( 'Understand LED billboards before you buy', 'billboard-pro' ) ); ?>
				<?php if ( get_option( 'page_for_posts' ) ) : ?>
					<a class="link-arrow" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php esc_html_e( 'All guides', 'billboard-pro' ); ?> <?php bbp_the_icon( 'arrow', 16 ); ?></a>
				<?php endif; ?>
			</div>
			<div class="card-grid card-grid--3">
				<?php
				while ( $bbp_posts->have_posts() ) {
					$bbp_posts->the_post();
					get_template_part( 'template-parts/post-card' );
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
// Optional content from the static front page (block editor).
if ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ) ) {
	$bbp_front = get_post( get_option( 'page_on_front' ) );
	if ( $bbp_front && trim( $bbp_front->post_content ) ) {
		echo '<section class="section"><div class="container entry-content">' . apply_filters( 'the_content', $bbp_front->post_content ) . '</div></section>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

get_template_part( 'template-parts/cta-band' );
get_footer();
