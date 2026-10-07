<?php
/**
 * Footer.
 *
 * @package Billboard_Pro
 */

$bbp_cities = get_terms(
	array(
		'taxonomy'   => 'billboard_city',
		'hide_empty' => true,
		'orderby'    => 'count',
		'order'      => 'DESC',
		'number'     => 8,
		'parent'     => 0,
	)
);
$bbp_types  = get_terms(
	array(
		'taxonomy'   => 'billboard_type',
		'hide_empty' => true,
		'number'     => 8,
	)
);
?>
</main>

<footer class="site-footer">
	<div class="container">
		<div class="footer-top">
			<div class="footer-brand">
				<a class="brand__text brand__text--footer" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<span class="brand__mark" aria-hidden="true"></span>
					<span><?php bloginfo( 'name' ); ?></span>
				</a>
				<p><?php echo esc_html( bbp_opt( 'footer_about' ) ); ?></p>
				<ul class="footer-contact">
					<?php if ( bbp_opt( 'phone' ) ) : ?>
						<li><?php bbp_the_icon( 'phone', 16 ); ?><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', bbp_opt( 'phone' ) ) ); ?>"><?php echo esc_html( bbp_opt( 'phone' ) ); ?></a></li>
					<?php endif; ?>
					<?php if ( bbp_opt( 'email' ) ) : ?>
						<li><?php bbp_the_icon( 'mail', 16 ); ?><a href="mailto:<?php echo esc_attr( bbp_opt( 'email' ) ); ?>"><?php echo esc_html( bbp_opt( 'email' ) ); ?></a></li>
					<?php endif; ?>
					<?php if ( bbp_opt( 'address' ) ) : ?>
						<li><?php bbp_the_icon( 'pin', 16 ); ?><span><?php echo esc_html( bbp_opt( 'address' ) ); ?></span></li>
					<?php endif; ?>
				</ul>
			</div>

			<div class="footer-col">
				<h3 class="footer-title"><?php esc_html_e( 'Inventory', 'billboard-pro' ); ?></h3>
				<ul>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'billboard' ) ); ?>"><?php esc_html_e( 'All billboards', 'billboard-pro' ); ?></a></li>
					<li><a href="<?php echo esc_url( add_query_arg( 'format', 'digital', get_post_type_archive_link( 'billboard' ) ) ); ?>"><?php esc_html_e( 'LED digital screens', 'billboard-pro' ); ?></a></li>
					<li><a href="<?php echo esc_url( add_query_arg( 'availability', 'available', get_post_type_archive_link( 'billboard' ) ) ); ?>"><?php esc_html_e( 'Available now', 'billboard-pro' ); ?></a></li>
					<?php if ( ! is_wp_error( $bbp_types ) ) : foreach ( array_slice( $bbp_types, 0, 5 ) as $bbp_t ) : ?>
						<li><a href="<?php echo esc_url( get_term_link( $bbp_t ) ); ?>"><?php echo esc_html( $bbp_t->name ); ?></a></li>
					<?php endforeach; endif; ?>
				</ul>
			</div>

			<div class="footer-col">
				<h3 class="footer-title"><?php esc_html_e( 'Top cities', 'billboard-pro' ); ?></h3>
				<ul>
					<?php if ( ! is_wp_error( $bbp_cities ) && $bbp_cities ) : foreach ( $bbp_cities as $bbp_c ) : ?>
						<li><a href="<?php echo esc_url( get_term_link( $bbp_c ) ); ?>"><?php /* translators: %s: city */ printf( esc_html__( 'Billboards in %s', 'billboard-pro' ), esc_html( $bbp_c->name ) ); ?></a></li>
					<?php endforeach; else : ?>
						<li><a href="<?php echo esc_url( get_post_type_archive_link( 'bb_location' ) ); ?>"><?php esc_html_e( 'All locations', 'billboard-pro' ); ?></a></li>
					<?php endif; ?>
				</ul>
			</div>

			<div class="footer-col">
				<h3 class="footer-title"><?php esc_html_e( 'Company', 'billboard-pro' ); ?></h3>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'depth' => 1 ) );
				} else {
					echo '<ul>';
					printf( '<li><a href="%s">%s</a></li>', esc_url( get_post_type_archive_link( 'bb_location' ) ), esc_html__( 'Locations', 'billboard-pro' ) );
					printf( '<li><a href="%s">%s</a></li>', esc_url( get_post_type_archive_link( 'bb_case_study' ) ), esc_html__( 'Case studies', 'billboard-pro' ) );
					if ( get_option( 'page_for_posts' ) ) {
						printf( '<li><a href="%s">%s</a></li>', esc_url( get_permalink( get_option( 'page_for_posts' ) ) ), esc_html__( 'LED billboard guides', 'billboard-pro' ) );
					}
					printf( '<li><a href="%s">%s</a></li>', esc_url( bbp_page_url( 'quote' ) ), esc_html__( 'Request a quote', 'billboard-pro' ) );
					printf( '<li><a href="%s">%s</a></li>', esc_url( bbp_page_url( 'contact' ) ), esc_html__( 'Contact', 'billboard-pro' ) );
					echo '</ul>';
				}
				?>
			</div>

			<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<div class="footer-col"><?php dynamic_sidebar( 'footer-1' ); ?></div>
			<?php endif; ?>
		</div>

		<div class="footer-bottom">
			<p>
				<?php
				$bbp_copy = bbp_opt( 'copyright' );
				echo $bbp_copy ? esc_html( $bbp_copy ) : '&copy; ' . esc_html( wp_date( 'Y' ) . ' ' . get_bloginfo( 'name' ) ) . '. ' . esc_html__( 'All rights reserved.', 'billboard-pro' );
				?>
			</p>
			<?php
			if ( has_nav_menu( 'legal' ) ) {
				wp_nav_menu( array( 'theme_location' => 'legal', 'container' => 'nav', 'container_class' => 'legal-nav', 'depth' => 1 ) );
			}
			?>
			<ul class="social">
				<?php
				foreach ( array( 'linkedin' => 'LinkedIn', 'instagram' => 'Instagram', 'facebook' => 'Facebook', 'x' => 'X', 'youtube' => 'YouTube' ) as $bbp_k => $bbp_l ) :
					$bbp_u = bbp_opt( 'social_' . $bbp_k );
					if ( ! $bbp_u ) {
						continue;
					}
					?>
					<li><a href="<?php echo esc_url( $bbp_u ); ?>" rel="noopener" target="_blank"><?php echo esc_html( $bbp_l ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</footer>

<?php if ( bbp_opt( 'whatsapp' ) ) : ?>
	<a class="whatsapp-fab" href="https://wa.me/<?php echo esc_attr( preg_replace( '/\D/', '', bbp_opt( 'whatsapp' ) ) ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Chat on WhatsApp', 'billboard-pro' ); ?>"><?php bbp_the_icon( 'whatsapp', 26 ); ?></a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
