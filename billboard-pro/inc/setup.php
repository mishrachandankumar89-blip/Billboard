<?php
/**
 * Theme setup, assets and widgets.
 *
 * @package Billboard_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme supports.
 */
function bbp_setup() {
	load_theme_textdomain( 'billboard-pro', BBP_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 260,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_editor_style( 'assets/css/editor.css' );

	add_image_size( 'bbp-card', 800, 560, true );
	add_image_size( 'bbp-wide', 1600, 900, true );
	add_image_size( 'bbp-square', 300, 300, true );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'billboard-pro' ),
			'footer'  => __( 'Footer Menu', 'billboard-pro' ),
			'legal'   => __( 'Footer Legal Menu', 'billboard-pro' ),
		)
	);
}
add_action( 'after_setup_theme', 'bbp_setup' );

/**
 * Content width.
 */
function bbp_content_width() {
	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'bbp_content_width', 0 );

/**
 * Enqueue front-end assets.
 */
function bbp_assets() {
	$font = bbp_opt( 'google_fonts' );
	if ( $font ) {
		wp_enqueue_style( 'bbp-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap', array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}
	wp_enqueue_style( 'bbp-style', get_stylesheet_uri(), array(), BBP_VERSION );
	wp_add_inline_style( 'bbp-style', bbp_customizer_css() );

	wp_enqueue_script( 'bbp-main', BBP_URI . '/assets/js/main.js', array(), BBP_VERSION, true );
	wp_localize_script(
		'bbp-main',
		'BBP',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'bbp_filter' ),
			'i18n'    => array(
				'loading'  => __( 'Loading billboards…', 'billboard-pro' ),
				'error'    => __( 'Something went wrong. Please try again.', 'billboard-pro' ),
				'required' => __( 'This field is required.', 'billboard-pro' ),
				'email'    => __( 'Please enter a valid email address.', 'billboard-pro' ),
				'dates'    => __( 'End date must be after the start date.', 'billboard-pro' ),
				'sending'  => __( 'Sending…', 'billboard-pro' ),
			),
		)
	);

	if ( is_singular( 'post' ) && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bbp_assets' );

/**
 * Preconnect for Google Fonts.
 *
 * @param array  $urls          URLs.
 * @param string $relation_type Relation.
 * @return array
 */
function bbp_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && bbp_opt( 'google_fonts' ) ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'bbp_resource_hints', 10, 2 );

/**
 * Widget areas.
 */
function bbp_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Blog Sidebar', 'billboard-pro' ),
			'id'            => 'sidebar-1',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
	register_sidebar(
		array(
			'name'          => __( 'Footer Column', 'billboard-pro' ),
			'id'            => 'footer-1',
			'description'   => __( 'Optional extra footer column.', 'billboard-pro' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="footer-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'bbp_widgets_init' );

/**
 * Primary menu fallback (before a menu is assigned).
 */
function bbp_menu_fallback() {
	$links = array(
		get_post_type_archive_link( 'billboard' )     => __( 'Billboards', 'billboard-pro' ),
		add_query_arg( 'format', 'digital', get_post_type_archive_link( 'billboard' ) ) => __( 'Digital Screens', 'billboard-pro' ),
		get_post_type_archive_link( 'bb_location' )   => __( 'Locations', 'billboard-pro' ),
		get_post_type_archive_link( 'bb_case_study' ) => __( 'Case Studies', 'billboard-pro' ),
		bbp_page_url( 'contact' )                     => __( 'Contact', 'billboard-pro' ),
	);
	echo '<ul class="menu">';
	foreach ( $links as $url => $label ) {
		printf( '<li class="menu-item"><a href="%1$s">%2$s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Body classes.
 *
 * @param array $classes Classes.
 * @return array
 */
function bbp_body_classes( $classes ) {
	// Every template opens with a dark hero, so the header overlays it.
	$classes[] = 'has-transparent-header';
	return $classes;
}
add_filter( 'body_class', 'bbp_body_classes' );

/**
 * Excerpt length.
 *
 * @return int
 */
function bbp_excerpt_length() {
	return 24;
}
add_filter( 'excerpt_length', 'bbp_excerpt_length' );

/**
 * Excerpt more.
 *
 * @return string
 */
function bbp_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'bbp_excerpt_more' );

/**
 * Skip link focus fix not needed in modern browsers; add async to main script.
 *
 * @param string $tag    Tag.
 * @param string $handle Handle.
 * @return string
 */
function bbp_defer_scripts( $tag, $handle ) {
	if ( 'bbp-main' === $handle ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'bbp_defer_scripts', 10, 2 );

/**
 * Flush rewrite rules on theme switch so CPT permalinks work immediately.
 */
function bbp_after_switch() {
	bbp_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'bbp_after_switch' );
