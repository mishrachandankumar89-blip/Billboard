<?php
/**
 * Location card.
 *
 * @package Billboard_Pro
 */

$bbp_id    = get_the_ID();
$bbp_count = bbp_location_billboard_count( $bbp_id );
?>
<article class="loc-card reveal">
	<a href="<?php the_permalink(); ?>" class="loc-card__link">
		<?php bbp_thumb( $bbp_id, 'bbp-card', array( 'class' => 'loc-card__img' ) ); ?>
		<span class="loc-card__overlay">
			<span class="loc-card__state"><?php echo esc_html( bbp_meta( 'loc_state', $bbp_id ) ); ?></span>
			<span class="loc-card__name"><?php the_title(); ?></span>
			<span class="loc-card__count">
				<?php
				/* translators: %d: number of billboards */
				echo esc_html( sprintf( _n( '%d advertising location', '%d advertising locations', $bbp_count, 'billboard-pro' ), $bbp_count ) );
				?>
				<?php bbp_the_icon( 'arrow', 16 ); ?>
			</span>
		</span>
	</a>
</article>
