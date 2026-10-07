<?php
/**
 * Template Name: Quote Request
 *
 * @package Billboard_Pro
 */

get_header();

// Prefill from ?bb_id=ID&city=Name (links from billboard / location pages).
$bbp_prefill = array();
// phpcs:disable WordPress.Security.NonceVerification
if ( ! empty( $_GET['bb_id'] ) ) {
	$bbp_bid = absint( $_GET['bb_id'] );
	if ( 'billboard' === get_post_type( $bbp_bid ) ) {
		$bbp_prefill['billboard_id'] = $bbp_bid;
		$bbp_prefill['location']     = get_the_title( $bbp_bid );
		$bbp_prefill['city']         = bbp_term( $bbp_bid, 'billboard_city' );
		$bbp_prefill['type']         = bbp_term( $bbp_bid, 'billboard_type' );
		if ( isset( $_GET['intent'] ) && 'availability' === $_GET['intent'] ) {
			$bbp_prefill['message'] = __( 'Please confirm availability for this billboard for my dates.', 'billboard-pro' );
		}
	}
}
if ( ! empty( $_GET['city'] ) ) {
	$bbp_prefill['city'] = sanitize_text_field( wp_unslash( $_GET['city'] ) );
}
// phpcs:enable

while ( have_posts() ) :
	the_post();
	bbp_page_hero( get_the_title(), has_excerpt() ? get_the_excerpt() : __( 'Share your goals and dates. We will reply with available LED screens, audience data and a tailored proposal within one business day.', 'billboard-pro' ), bbp_img( 'hero-inventory.jpg' ) );
	?>
	<section class="section section--tight">
		<div class="container form-layout">
			<div class="form-card" id="form">
				<?php if ( ! empty( $bbp_prefill['billboard_id'] ) ) : ?>
					<div class="selected-bb">
						<?php bbp_thumb( $bbp_prefill['billboard_id'], 'bbp-square' ); ?>
						<div>
							<p class="eyebrow"><?php esc_html_e( 'Selected billboard', 'billboard-pro' ); ?></p>
							<p><strong><?php echo esc_html( get_the_title( $bbp_prefill['billboard_id'] ) ); ?></strong></p>
							<p><?php echo wp_kses_post( bbp_badge( $bbp_prefill['billboard_id'] ) ); ?></p>
						</div>
					</div>
				<?php endif; ?>
				<?php bbp_render_form( 'quote', $bbp_prefill ); ?>
			</div>
			<aside class="form-aside">
				<div class="entry-content"><?php the_content(); ?></div>
				<h2 class="h4"><?php esc_html_e( 'What happens next', 'billboard-pro' ); ?></h2>
				<ol class="mini-steps">
					<li><strong><?php esc_html_e( 'Within 24 hours', 'billboard-pro' ); ?></strong><?php esc_html_e( 'A campaign planner reviews your brief and checks live availability.', 'billboard-pro' ); ?></li>
					<li><strong><?php esc_html_e( 'Your proposal', 'billboard-pro' ); ?></strong><?php esc_html_e( 'Screens, audience estimates, flighting and transparent pricing.', 'billboard-pro' ); ?></li>
					<li><strong><?php esc_html_e( 'Go live', 'billboard-pro' ); ?></strong><?php esc_html_e( 'Approve, send creative, and we handle the rest — including proof-of-play.', 'billboard-pro' ); ?></li>
				</ol>
				<div class="contact-mini">
					<?php if ( bbp_opt( 'phone' ) ) : ?>
						<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', bbp_opt( 'phone' ) ) ); ?>"><?php bbp_the_icon( 'phone', 18 ); ?> <?php echo esc_html( bbp_opt( 'phone' ) ); ?></a>
					<?php endif; ?>
					<?php if ( bbp_opt( 'email' ) ) : ?>
						<a href="mailto:<?php echo esc_attr( bbp_opt( 'email' ) ); ?>"><?php bbp_the_icon( 'mail', 18 ); ?> <?php echo esc_html( bbp_opt( 'email' ) ); ?></a>
					<?php endif; ?>
				</div>
			</aside>
		</div>
	</section>
	<?php
endwhile;
get_footer();
