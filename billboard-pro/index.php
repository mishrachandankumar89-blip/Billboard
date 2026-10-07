<?php
/**
 * Fallback template: blog index, archives, search.
 *
 * @package Billboard_Pro
 */

get_header();

if ( is_home() ) {
	$bbp_title = get_option( 'page_for_posts' ) ? get_the_title( get_option( 'page_for_posts' ) ) : __( 'LED Billboard Guides', 'billboard-pro' );
	$bbp_sub   = __( 'Practical, in-depth guides to LED billboard screens and outdoor advertising — written by people who plan campaigns every day.', 'billboard-pro' );
} elseif ( is_search() ) {
	/* translators: %s: search query */
	$bbp_title = sprintf( __( 'Results for “%s”', 'billboard-pro' ), get_search_query() );
	$bbp_sub   = '';
} elseif ( is_archive() ) {
	$bbp_title = wp_strip_all_tags( get_the_archive_title() );
	$bbp_sub   = wp_strip_all_tags( get_the_archive_description() );
} else {
	$bbp_title = get_bloginfo( 'name' );
	$bbp_sub   = '';
}
bbp_page_hero( $bbp_title, $bbp_sub );
?>
<section class="section section--tight">
	<div class="container">
		<?php if ( is_home() ) : ?>
			<?php
			$bbp_cats = get_categories( array( 'hide_empty' => true ) );
			if ( count( $bbp_cats ) > 1 ) :
				?>
				<nav class="chip-nav" aria-label="<?php esc_attr_e( 'Guide topics', 'billboard-pro' ); ?>">
					<?php foreach ( $bbp_cats as $bbp_c ) : ?>
						<a class="chip" href="<?php echo esc_url( get_category_link( $bbp_c ) ); ?>"><?php echo esc_html( $bbp_c->name ); ?></a>
					<?php endforeach; ?>
				</nav>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="card-grid card-grid--3">
				<?php
				while ( have_posts() ) {
					the_post();
					if ( 'billboard' === get_post_type() ) {
						get_template_part( 'template-parts/billboard-card' );
					} elseif ( 'bb_location' === get_post_type() ) {
						get_template_part( 'template-parts/location-card' );
					} elseif ( 'bb_case_study' === get_post_type() ) {
						get_template_part( 'template-parts/case-card' );
					} else {
						get_template_part( 'template-parts/post-card' );
					}
				}
				?>
			</div>
			<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
		<?php else : ?>
			<div class="empty-state">
				<h2><?php esc_html_e( 'Nothing found', 'billboard-pro' ); ?></h2>
				<p><?php esc_html_e( 'Try another search, or browse our billboard inventory.', 'billboard-pro' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/cta-band' );
get_footer();
