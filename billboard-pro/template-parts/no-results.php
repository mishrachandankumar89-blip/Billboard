<?php
/**
 * Empty state.
 *
 * @package Billboard_Pro
 */

?>
<div class="empty-state">
	<?php bbp_the_icon( 'search', 40 ); ?>
	<h3><?php esc_html_e( 'No billboards match those filters', 'billboard-pro' ); ?></h3>
	<p><?php esc_html_e( 'Try widening your search — or tell us what you need and we will find screens that fit, including inventory that is not listed yet.', 'billboard-pro' ); ?></p>
	<p class="empty-state__actions">
		<a class="btn btn--dark" href="<?php echo esc_url( get_post_type_archive_link( 'billboard' ) ); ?>"><?php esc_html_e( 'See all billboards', 'billboard-pro' ); ?></a>
		<a class="btn btn--primary" href="<?php echo esc_url( bbp_page_url( 'quote' ) ); ?>"><?php esc_html_e( 'Ask a campaign planner', 'billboard-pro' ); ?></a>
	</p>
</div>
