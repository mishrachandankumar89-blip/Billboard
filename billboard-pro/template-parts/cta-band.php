<?php
/**
 * Final CTA band.
 *
 * @package Billboard_Pro
 */

?>
<section class="cta-band" style="--cta-img:url('<?php echo esc_url( bbp_img( 'cta-bg.jpg' ) ); ?>')">
	<div class="container cta-band__inner reveal">
		<h2 class="cta-band__title"><?php echo esc_html( bbp_opt( 'cta_title' ) ); ?></h2>
		<p class="cta-band__text"><?php echo esc_html( bbp_opt( 'cta_text' ) ); ?></p>
		<div class="btn-row">
			<a class="btn btn--primary btn--lg" href="<?php echo esc_url( bbp_page_url( 'quote' ) ); ?>"><?php esc_html_e( 'Get a Quote', 'billboard-pro' ); ?> <?php bbp_the_icon( 'arrow', 18 ); ?></a>
			<a class="btn btn--outline-light btn--lg" href="<?php echo esc_url( bbp_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Talk to Our Team', 'billboard-pro' ); ?></a>
		</div>
	</div>
</section>
