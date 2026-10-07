<?php
/**
 * Comments.
 *
 * @package Billboard_Pro
 */

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="h4"><?php echo esc_html( sprintf( /* translators: %d: count */ _n( '%d comment', '%d comments', get_comments_number(), 'billboard-pro' ), get_comments_number() ) ); ?></h2>
		<ol class="comment-list"><?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 40 ) ); ?></ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>
	<?php comment_form(); ?>
</div>
