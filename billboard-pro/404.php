<?php
/**
 * 404.
 *
 * @package Billboard_Pro
 */

get_header();
?>
<section class="error-404" style="--hero-img:url('<?php echo esc_url( bbp_img( 'hero-city.jpg' ) ); ?>')">
	<div class="container error-404__inner">
		<p class="error-404__code" aria-hidden="true">404</p>
		<h1><?php esc_html_e( 'This screen is dark.', 'billboard-pro' ); ?></h1>
		<p><?php esc_html_e( 'The page you were looking for has moved or no longer exists. Let’s get you back in front of an audience.', 'billboard-pro' ); ?></p>
		<?php get_search_form(); ?>
		<div class="btn-row">
			<a class="btn btn--primary" href="<?php echo esc_url( get_post_type_archive_link( 'billboard' ) ); ?>"><?php esc_html_e( 'Browse billboards', 'billboard-pro' ); ?></a>
			<a class="btn btn--outline-light" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'billboard-pro' ); ?></a>
		</div>
	</div>
</section>
<?php
get_footer();
