<?php
/**
 * Search form.
 *
 * @package Billboard_Pro
 */

$bbp_sid = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $bbp_sid ); ?>" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'billboard-pro' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $bbp_sid ); ?>" class="search-field" placeholder="<?php esc_attr_e( 'Search billboards, cities, guides…', 'billboard-pro' ); ?>" value="<?php echo get_search_query(); ?>" name="s">
	<button type="submit" class="btn btn--primary"><?php bbp_the_icon( 'search', 18 ); ?><span class="screen-reader-text"><?php esc_html_e( 'Search', 'billboard-pro' ); ?></span></button>
</form>
