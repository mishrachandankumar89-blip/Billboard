<?php
/**
 * Billboard inventory archive with live filters.
 * Also used for City and Type taxonomy archives.
 *
 * @package Billboard_Pro
 */

get_header();

global $wp_query;
$bbp_title = __( 'Billboard Inventory', 'billboard-pro' );
$bbp_sub   = __( 'LED billboard screens and static sites with live availability, audience data and pricing.', 'billboard-pro' );
$bbp_intro = '';
if ( is_tax() ) {
	$bbp_term  = get_queried_object();
	/* translators: %s: term name */
	$bbp_title = is_tax( 'billboard_city' ) ? sprintf( __( 'Billboards in %s', 'billboard-pro' ), $bbp_term->name ) : $bbp_term->name;
	$bbp_intro = term_description( $bbp_term );
	if ( is_tax( 'billboard_city' ) ) {
		/* translators: %s: city */
		$bbp_sub = sprintf( __( 'LED screens and outdoor advertising locations across %s — filter by format, size, budget and availability.', 'billboard-pro' ), $bbp_term->name );
	}
}
bbp_page_hero( $bbp_title, $bbp_sub, bbp_img( 'hero-inventory.jpg' ) );
?>

<section class="section section--tight inventory">
	<div class="container inventory__layout">
		<aside class="inventory__filters" id="filters">
			<button class="filters-toggle btn btn--dark btn--block" type="button" aria-expanded="false" aria-controls="filters-panel"><?php bbp_the_icon( 'filter', 18 ); ?> <?php esc_html_e( 'Filter billboards', 'billboard-pro' ); ?></button>
			<div class="filters-panel" id="filters-panel">
				<?php get_template_part( 'template-parts/filter-form', null, array( 'variant' => 'archive' ) ); ?>
			</div>
		</aside>

		<div class="inventory__results">
			<div class="results-bar">
				<p class="results-count" aria-live="polite">
					<?php
					/* translators: %s: number of billboards */
					printf( esc_html( _n( '%s billboard found', '%s billboards found', (int) $wp_query->found_posts, 'billboard-pro' ) ), '<strong data-count-target>' . esc_html( number_format_i18n( $wp_query->found_posts ) ) . '</strong>' );
					?>
				</p>
				<label class="results-sort">
					<span><?php esc_html_e( 'Sort', 'billboard-pro' ); ?></span>
					<select name="sort" form="" data-bbp-sort>
						<?php
						$bbp_sort = isset( $_GET['sort'] ) ? sanitize_key( $_GET['sort'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
						foreach ( bbp_sort_options() as $bbp_k => $bbp_l ) {
							printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $bbp_k ), selected( $bbp_sort, $bbp_k, false ), esc_html( $bbp_l ) );
						}
						?>
					</select>
				</label>
			</div>

			<div class="card-grid card-grid--results" data-bbp-results>
				<?php
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
						get_template_part( 'template-parts/billboard-card' );
					}
				} else {
					get_template_part( 'template-parts/no-results' );
				}
				?>
			</div>
			<div data-bbp-pagination>
				<?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => __( 'Previous', 'billboard-pro' ), 'next_text' => __( 'Next', 'billboard-pro' ) ) ); ?>
			</div>

			<?php if ( $bbp_intro ) : ?>
				<div class="term-intro entry-content"><?php echo wp_kses_post( $bbp_intro ); ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta-band' );
get_footer();
