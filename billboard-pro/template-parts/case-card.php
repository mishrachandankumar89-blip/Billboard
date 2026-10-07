<?php
/**
 * Case study card.
 *
 * @package Billboard_Pro
 */

$bbp_id = get_the_ID();
?>
<article class="case-card reveal">
	<a class="case-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php bbp_thumb( $bbp_id, 'bbp-card' ); ?>
	</a>
	<div class="case-card__body">
		<p class="eyebrow"><?php echo esc_html( bbp_meta( 'cs_client', $bbp_id ) ); ?><?php echo bbp_meta( 'cs_industry', $bbp_id ) ? ' · ' . esc_html( bbp_meta( 'cs_industry', $bbp_id ) ) : ''; ?></p>
		<h3 class="case-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<dl class="case-card__meta">
			<?php if ( bbp_meta( 'cs_location', $bbp_id ) ) : ?>
				<div><dt><?php esc_html_e( 'Location', 'billboard-pro' ); ?></dt><dd><?php echo esc_html( bbp_meta( 'cs_location', $bbp_id ) ); ?></dd></div>
			<?php endif; ?>
			<?php if ( bbp_meta( 'cs_duration', $bbp_id ) ) : ?>
				<div><dt><?php esc_html_e( 'Duration', 'billboard-pro' ); ?></dt><dd><?php echo esc_html( bbp_meta( 'cs_duration', $bbp_id ) ); ?></dd></div>
			<?php endif; ?>
			<?php if ( bbp_meta( 'cs_format', $bbp_id ) ) : ?>
				<div><dt><?php esc_html_e( 'Format', 'billboard-pro' ); ?></dt><dd><?php echo esc_html( bbp_meta( 'cs_format', $bbp_id ) ); ?></dd></div>
			<?php endif; ?>
		</dl>
		<?php
		$bbp_res = bbp_lines( bbp_meta( 'cs_results', $bbp_id ) );
		if ( $bbp_res ) :
			$bbp_first = array_map( 'trim', explode( '|', $bbp_res[0] ) );
			?>
			<p class="case-card__result"><strong><?php echo esc_html( $bbp_first[0] ); ?></strong> <?php echo esc_html( isset( $bbp_first[1] ) ? $bbp_first[1] : '' ); ?></p>
		<?php endif; ?>
	</div>
</article>
