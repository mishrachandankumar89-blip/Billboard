<?php
/**
 * Blog / guide card.
 *
 * @package Billboard_Pro
 */

$bbp_cat = get_the_category();
?>
<article <?php post_class( 'post-card reveal' ); ?>>
	<a class="post-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php bbp_thumb( get_the_ID(), 'bbp-card' ); ?></a>
	<div class="post-card__body">
		<p class="eyebrow"><?php echo $bbp_cat ? esc_html( $bbp_cat[0]->name ) : ''; ?> · <?php echo esc_html( bbp_reading_time() ); ?></p>
		<h3 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo esc_html( get_the_excerpt() ); ?></p>
		<a class="link-arrow" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read the guide', 'billboard-pro' ); ?> <?php bbp_the_icon( 'arrow', 16 ); ?><span class="screen-reader-text"> — <?php the_title(); ?></span></a>
	</div>
</article>
