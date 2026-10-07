<?php
/**
 * Single post (knowledge hub guide).
 *
 * @package Billboard_Pro
 */

get_header();

while ( have_posts() ) :
	the_post();
	$bbp_cat = get_the_category();
	?>
	<article <?php post_class( 'guide' ); ?>>
		<header class="guide-hero" style="--hero-img:url('<?php echo esc_url( bbp_thumb_url( get_the_ID(), 'bbp-wide' ) ); ?>')">
			<div class="container narrow">
				<?php bbp_breadcrumbs(); ?>
				<?php if ( $bbp_cat ) : ?>
					<a class="eyebrow eyebrow--light" href="<?php echo esc_url( get_category_link( $bbp_cat[0] ) ); ?>"><?php echo esc_html( $bbp_cat[0]->name ); ?></a>
				<?php endif; ?>
				<h1 class="guide-hero__title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="guide-hero__dek"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
				<p class="guide-hero__meta">
					<span><?php echo esc_html( get_bloginfo( 'name' ) ); ?> <?php esc_html_e( 'Campaign Team', 'billboard-pro' ); ?></span>
					<span><?php esc_html_e( 'Updated', 'billboard-pro' ); ?> <time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php echo esc_html( get_the_modified_date() ); ?></time></span>
					<span><?php echo esc_html( bbp_reading_time() ); ?></span>
				</p>
			</div>
		</header>

		<div class="section section--tight">
			<div class="container guide-layout">
				<aside class="guide-toc" data-toc>
					<p class="guide-toc__title"><?php esc_html_e( 'On this page', 'billboard-pro' ); ?></p>
					<nav aria-label="<?php esc_attr_e( 'Table of contents', 'billboard-pro' ); ?>"><ol></ol></nav>
				</aside>
				<div class="guide-body entry-content">
					<?php
					the_content();
					wp_link_pages();
					?>
					<?php $bbp_tags = get_the_tags(); ?>
					<?php if ( $bbp_tags ) : ?>
						<ul class="tag-list tag-list--links">
							<?php foreach ( $bbp_tags as $bbp_t ) : ?>
								<li><a href="<?php echo esc_url( get_tag_link( $bbp_t ) ); ?>">#<?php echo esc_html( $bbp_t->name ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				<aside class="guide-aside">
					<div class="aside-cta aside-cta--dark">
						<p class="eyebrow eyebrow--light"><?php esc_html_e( 'Plan with an expert', 'billboard-pro' ); ?></p>
						<h2 class="h4"><?php esc_html_e( 'Get LED screen options and pricing for your city', 'billboard-pro' ); ?></h2>
						<a class="btn btn--primary btn--block" href="<?php echo esc_url( bbp_page_url( 'quote' ) ); ?>"><?php esc_html_e( 'Request a Quote', 'billboard-pro' ); ?></a>
						<a class="btn btn--outline-light btn--block" href="<?php echo esc_url( add_query_arg( 'format', 'digital', get_post_type_archive_link( 'billboard' ) ) ); ?>"><?php esc_html_e( 'Browse LED screens', 'billboard-pro' ); ?></a>
					</div>
				</aside>
			</div>
		</div>
	</article>

	<?php
	if ( comments_open() || get_comments_number() ) {
		echo '<div class="container narrow">';
		comments_template();
		echo '</div>';
	}

	$bbp_related = new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'category__in'        => wp_list_pluck( (array) $bbp_cat, 'term_id' ),
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		)
	);
	if ( $bbp_related->have_posts() ) :
		?>
		<section class="section section--light">
			<div class="container">
				<?php bbp_section_head( __( 'Keep reading', 'billboard-pro' ), __( 'Related guides', 'billboard-pro' ) ); ?>
				<div class="card-grid card-grid--3">
					<?php
					while ( $bbp_related->have_posts() ) {
						$bbp_related->the_post();
						get_template_part( 'template-parts/post-card' );
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
