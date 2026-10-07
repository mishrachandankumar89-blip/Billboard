<?php
/**
 * Billboard card.
 *
 * @package Billboard_Pro
 */

$bbp_id     = get_the_ID();
$bbp_city   = bbp_term( $bbp_id, 'billboard_city' );
$bbp_type   = bbp_term( $bbp_id, 'billboard_type' );
$bbp_format = bbp_meta( 'bb_format', $bbp_id );
$bbp_dim    = bbp_dimensions( $bbp_id );
$bbp_daily  = bbp_meta( 'bb_daily_impressions', $bbp_id );
$bbp_price  = bbp_meta( 'bb_price', $bbp_id );
$bbp_addr   = bbp_meta( 'bb_address', $bbp_id );
?>
<article class="bb-card reveal">
	<a class="bb-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php bbp_thumb( $bbp_id, 'bbp-card' ); ?>
		<span class="bb-card__badges">
			<?php echo wp_kses_post( bbp_badge( $bbp_id ) ); ?>
			<?php if ( 'digital' === $bbp_format ) : ?>
				<span class="badge badge--digital"><?php esc_html_e( 'LED', 'billboard-pro' ); ?></span>
			<?php endif; ?>
		</span>
	</a>
	<div class="bb-card__body">
		<p class="bb-card__loc"><?php bbp_the_icon( 'pin', 14 ); ?> <?php echo esc_html( trim( $bbp_addr ? $bbp_addr . ', ' . $bbp_city : $bbp_city, ', ' ) ); ?></p>
		<h3 class="bb-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<ul class="bb-card__specs">
			<?php if ( $bbp_type ) : ?>
				<li><span><?php esc_html_e( 'Type', 'billboard-pro' ); ?></span><?php echo esc_html( $bbp_type ); ?></li>
			<?php endif; ?>
			<?php if ( $bbp_dim ) : ?>
				<li><span><?php esc_html_e( 'Size', 'billboard-pro' ); ?></span><?php echo esc_html( $bbp_dim ); ?></li>
			<?php endif; ?>
			<?php if ( $bbp_daily ) : ?>
				<li><span><?php esc_html_e( 'Daily reach', 'billboard-pro' ); ?></span><?php echo esc_html( bbp_compact( $bbp_daily ) ); ?>+</li>
			<?php endif; ?>
		</ul>
		<div class="bb-card__foot">
			<p class="bb-card__price">
				<span><?php esc_html_e( 'From', 'billboard-pro' ); ?></span>
				<strong><?php echo esc_html( bbp_price( $bbp_price ) ); ?></strong>
				<?php if ( '' !== $bbp_price ) : ?>
					<small><?php echo esc_html( bbp_price_unit( bbp_meta( 'bb_price_unit', $bbp_id ) ) ); ?></small>
				<?php endif; ?>
			</p>
			<a class="btn btn--dark btn--sm" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Details', 'billboard-pro' ); ?><span class="screen-reader-text"> — <?php the_title(); ?></span></a>
		</div>
	</div>
</article>
