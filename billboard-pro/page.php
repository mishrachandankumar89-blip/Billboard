<?php
/**
 * Default page.
 *
 * @package Billboard_Pro
 */

get_header();
while ( have_posts() ) :
	the_post();
	bbp_page_hero( get_the_title(), has_excerpt() ? get_the_excerpt() : '', has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'bbp-wide' ) : '' );
	?>
	<section class="section section--tight">
		<div class="container narrow entry-content">
			<?php
			the_content();
			wp_link_pages();
			?>
		</div>
	</section>
	<?php
endwhile;
get_footer();
