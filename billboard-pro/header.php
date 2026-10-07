<?php
/**
 * Header.
 *
 * @package Billboard_Pro
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="<?php echo esc_attr( bbp_opt( 'color_dark' ) ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'billboard-pro' ); ?></a>

<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<div class="brand">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="brand__text" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<span class="brand__mark" aria-hidden="true"></span>
					<span><?php bloginfo( 'name' ); ?></span>
				</a>
			<?php endif; ?>
		</div>

		<nav class="primary-nav" id="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'billboard-pro' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'menu',
					'depth'          => 2,
					'fallback_cb'    => 'bbp_menu_fallback',
				)
			);
			?>
			<div class="primary-nav__mobile-cta">
				<a class="btn btn--primary btn--block" href="<?php echo esc_url( bbp_page_url( 'quote' ) ); ?>"><?php esc_html_e( 'Get a Quote', 'billboard-pro' ); ?></a>
				<?php if ( bbp_opt( 'phone' ) ) : ?>
					<a class="btn btn--ghost btn--block" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', bbp_opt( 'phone' ) ) ); ?>"><?php bbp_the_icon( 'phone', 18 ); ?> <?php echo esc_html( bbp_opt( 'phone' ) ); ?></a>
				<?php endif; ?>
			</div>
		</nav>

		<div class="site-header__actions">
			<?php if ( bbp_opt( 'phone' ) ) : ?>
				<a class="header-phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', bbp_opt( 'phone' ) ) ); ?>"><?php bbp_the_icon( 'phone', 16 ); ?><span><?php echo esc_html( bbp_opt( 'phone' ) ); ?></span></a>
			<?php endif; ?>
			<a class="btn btn--primary btn--sm header-cta" href="<?php echo esc_url( bbp_page_url( 'quote' ) ); ?>"><?php esc_html_e( 'Get a Quote', 'billboard-pro' ); ?></a>
			<button class="nav-toggle" type="button" aria-controls="primary-nav" aria-expanded="false">
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'billboard-pro' ); ?></span>
				<span class="nav-toggle__bars" aria-hidden="true"></span>
			</button>
		</div>
	</div>
</header>

<main id="main" class="site-main" tabindex="-1">
