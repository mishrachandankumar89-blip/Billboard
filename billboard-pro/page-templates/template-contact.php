<?php
/**
 * Template Name: Contact
 *
 * @package Billboard_Pro
 */

get_header();

while ( have_posts() ) :
	the_post();
	bbp_page_hero( get_the_title(), has_excerpt() ? get_the_excerpt() : __( 'Talk to a campaign planner about LED billboard screens, locations and pricing.', 'billboard-pro' ), bbp_img( 'hero-city.jpg' ) );
	$bbp_phone = bbp_opt( 'phone' );
	?>
	<section class="section section--tight">
		<div class="container">
			<div class="contact-cards">
				<?php if ( $bbp_phone ) : ?>
					<a class="contact-card reveal" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $bbp_phone ) ); ?>">
						<span class="benefit__icon"><?php bbp_the_icon( 'phone', 24 ); ?></span>
						<span class="contact-card__label"><?php esc_html_e( 'Call sales', 'billboard-pro' ); ?></span>
						<strong><?php echo esc_html( $bbp_phone ); ?></strong>
						<span class="contact-card__meta"><?php echo esc_html( bbp_opt( 'hours' ) ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( bbp_opt( 'email' ) ) : ?>
					<a class="contact-card reveal" href="mailto:<?php echo esc_attr( bbp_opt( 'email' ) ); ?>">
						<span class="benefit__icon"><?php bbp_the_icon( 'mail', 24 ); ?></span>
						<span class="contact-card__label"><?php esc_html_e( 'Email us', 'billboard-pro' ); ?></span>
						<strong><?php echo esc_html( bbp_opt( 'email' ) ); ?></strong>
						<span class="contact-card__meta"><?php esc_html_e( 'Reply within one business day', 'billboard-pro' ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( bbp_opt( 'whatsapp' ) ) : ?>
					<a class="contact-card reveal" href="https://wa.me/<?php echo esc_attr( preg_replace( '/\D/', '', bbp_opt( 'whatsapp' ) ) ); ?>" target="_blank" rel="noopener">
						<span class="benefit__icon"><?php bbp_the_icon( 'whatsapp', 24 ); ?></span>
						<span class="contact-card__label"><?php esc_html_e( 'WhatsApp', 'billboard-pro' ); ?></span>
						<strong><?php esc_html_e( 'Chat with us', 'billboard-pro' ); ?></strong>
						<span class="contact-card__meta"><?php esc_html_e( 'Fastest for quick questions', 'billboard-pro' ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( bbp_opt( 'address' ) ) : ?>
					<div class="contact-card reveal">
						<span class="benefit__icon"><?php bbp_the_icon( 'pin', 24 ); ?></span>
						<span class="contact-card__label"><?php esc_html_e( 'Head office', 'billboard-pro' ); ?></span>
						<strong><?php echo esc_html( bbp_opt( 'address' ) ); ?></strong>
						<span class="contact-card__meta"><?php echo esc_html( bbp_opt( 'hours' ) ); ?></span>
					</div>
				<?php endif; ?>
			</div>

			<div class="form-layout">
				<div class="form-card" id="form">
					<h2 class="h3"><?php esc_html_e( 'Send us a message', 'billboard-pro' ); ?></h2>
					<?php bbp_render_form( 'contact' ); ?>
				</div>
				<aside class="form-aside">
					<div class="entry-content"><?php the_content(); ?></div>
					<div class="aside-cta">
						<h2 class="h4"><?php esc_html_e( 'Ready with dates and a budget?', 'billboard-pro' ); ?></h2>
						<p><?php esc_html_e( 'Use the quote form for the fastest proposal with live availability.', 'billboard-pro' ); ?></p>
						<a class="btn btn--primary" href="<?php echo esc_url( bbp_page_url( 'quote' ) ); ?>"><?php esc_html_e( 'Request a Quote', 'billboard-pro' ); ?></a>
					</div>
					<?php
					$bbp_addr = bbp_opt( 'address' );
					if ( $bbp_addr ) :
						?>
						<div class="map-embed map-embed--office">
							<iframe title="<?php esc_attr_e( 'Office location', 'billboard-pro' ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?php echo esc_url( 'https://maps.google.com/maps?q=' . rawurlencode( $bbp_addr ) . '&output=embed' ); ?>"></iframe>
						</div>
					<?php endif; ?>
				</aside>
			</div>
		</div>
	</section>
	<?php
endwhile;
get_footer();
