<?php
/**
 * Template Name: Full Width (no hero)
 *
 * Use with the block editor to build landing pages edge to edge.
 *
 * @package Billboard_Pro
 */

get_header();
while ( have_posts() ) :
	the_post();
	?>
	<div class="spacer-header" aria-hidden="true"></div>
	<div class="entry-content entry-content--full">
		<?php the_content(); ?>
	</div>
	<?php
endwhile;
get_footer();
