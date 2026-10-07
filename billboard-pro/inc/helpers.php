<?php
/**
 * Helper / template functions.
 *
 * @package Billboard_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a post meta value.
 *
 * @param string   $key     Meta key.
 * @param int|null $post_id Post ID.
 * @return mixed
 */
function bbp_meta( $key, $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return get_post_meta( $post_id, $key, true );
}

/**
 * Theme option (Customizer) with default.
 *
 * @param string $key     Setting key without prefix.
 * @param mixed  $default Default.
 * @return mixed
 */
function bbp_opt( $key, $default = '' ) {
	$defaults = bbp_option_defaults();
	if ( '' === $default && isset( $defaults[ $key ] ) ) {
		$default = $defaults[ $key ];
	}
	return get_theme_mod( 'bbp_' . $key, $default );
}

/**
 * Bundled placeholder image URL.
 *
 * @param string $name File name in assets/images.
 * @return string
 */
function bbp_img( $name ) {
	return BBP_URI . '/assets/images/' . $name;
}

/**
 * Pick a bundled placeholder deterministically by post ID.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function bbp_placeholder( $post_id = 0 ) {
	$images = array( 'billboard-1.jpg', 'billboard-2.jpg', 'billboard-3.jpg', 'billboard-4.jpg', 'billboard-5.jpg', 'billboard-6.jpg' );
	return bbp_img( $images[ absint( $post_id ) % count( $images ) ] );
}

/**
 * Output featured image or a placeholder.
 *
 * @param int    $post_id Post ID.
 * @param string $size    Image size.
 * @param array  $attr    Attributes.
 */
function bbp_thumb( $post_id, $size = 'bbp-card', $attr = array() ) {
	if ( has_post_thumbnail( $post_id ) ) {
		echo get_the_post_thumbnail( $post_id, $size, array_merge( array( 'loading' => 'lazy' ), $attr ) );
		return;
	}
	$class = isset( $attr['class'] ) ? $attr['class'] : '';
	printf(
		'<img src="%1$s" alt="%2$s" loading="lazy" class="%3$s" width="1200" height="800">',
		esc_url( bbp_placeholder( $post_id ) ),
		esc_attr( get_the_title( $post_id ) ),
		esc_attr( $class )
	);
}

/**
 * Image URL for a post (featured or placeholder).
 *
 * @param int    $post_id Post ID.
 * @param string $size    Size.
 * @return string
 */
function bbp_thumb_url( $post_id, $size = 'full' ) {
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail_url( $post_id, $size );
	}
	return bbp_placeholder( $post_id );
}

/**
 * Gallery IDs from a meta key.
 *
 * @param string $key     Meta key.
 * @param int    $post_id Post ID.
 * @return int[]
 */
function bbp_gallery_ids( $key, $post_id = null ) {
	$raw = (string) bbp_meta( $key, $post_id );
	return array_filter( array_map( 'absint', explode( ',', $raw ) ) );
}

/**
 * Format a price with the currency from the Customizer.
 *
 * @param mixed $amount Amount.
 * @return string
 */
function bbp_price( $amount ) {
	if ( '' === $amount || null === $amount ) {
		return __( 'On request', 'billboard-pro' );
	}
	$symbol   = bbp_opt( 'currency' );
	$position = bbp_opt( 'currency_position' );
	$num      = number_format_i18n( (float) $amount );
	return 'after' === $position ? $num . ' ' . $symbol : $symbol . $num;
}

/**
 * Price unit label.
 *
 * @param string $unit Unit key.
 * @return string
 */
function bbp_price_unit( $unit ) {
	$f = bbp_fields( 'billboard' );
	$o = $f['bb_price_unit']['options'];
	return isset( $o[ $unit ] ) ? $o[ $unit ] : '';
}

/**
 * Compact number: 52000 -> 52K, 1200000 -> 1.2M.
 *
 * @param mixed $n Number.
 * @return string
 */
function bbp_compact( $n ) {
	$n = (float) $n;
	if ( $n >= 1000000 ) {
		return rtrim( rtrim( number_format( $n / 1000000, 1 ), '0' ), '.' ) . 'M';
	}
	if ( $n >= 1000 ) {
		return rtrim( rtrim( number_format( $n / 1000, 1 ), '0' ), '.' ) . 'K';
	}
	return number_format_i18n( $n );
}

/**
 * Option label lookup.
 *
 * @param string $post_type Post type.
 * @param string $key       Field key.
 * @param string $value     Stored value.
 * @return string
 */
function bbp_label( $post_type, $key, $value ) {
	$f = bbp_fields( $post_type );
	if ( isset( $f[ $key ]['options'][ $value ] ) ) {
		return $f[ $key ]['options'][ $value ];
	}
	return $value;
}

/**
 * Billboard dimensions string.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function bbp_dimensions( $post_id ) {
	$w = bbp_meta( 'bb_width', $post_id );
	$h = bbp_meta( 'bb_height', $post_id );
	if ( ! $w || ! $h ) {
		return '';
	}
	$u = bbp_meta( 'bb_unit', $post_id );
	$u = $u ? $u : 'm';
	return sprintf( '%s%s × %s%s', floatval( $w ), $u, floatval( $h ), $u );
}

/**
 * Monthly impressions (stored or daily × 30).
 *
 * @param int $post_id Post ID.
 * @return int
 */
function bbp_monthly_impressions( $post_id ) {
	$m = (int) bbp_meta( 'bb_monthly_impressions', $post_id );
	if ( ! $m ) {
		$m = (int) bbp_meta( 'bb_daily_impressions', $post_id ) * 30;
	}
	return $m;
}

/**
 * First term name of a taxonomy.
 *
 * @param int    $post_id  Post ID.
 * @param string $taxonomy Taxonomy.
 * @return string
 */
function bbp_term( $post_id, $taxonomy ) {
	$terms = get_the_terms( $post_id, $taxonomy );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return '';
	}
	return $terms[0]->name;
}

/**
 * Availability badge HTML.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function bbp_badge( $post_id ) {
	$status = bbp_meta( 'bb_availability', $post_id );
	$status = $status ? $status : 'available';
	$opts   = bbp_availability_options();
	$label  = isset( $opts[ $status ] ) ? $opts[ $status ] : $status;
	return sprintf( '<span class="badge badge--%1$s">%2$s</span>', esc_attr( $status ), esc_html( $label ) );
}

/**
 * Resolve a page URL by template (quote / contact) with Customizer override.
 *
 * @param string $which quote|contact.
 * @return string
 */
function bbp_page_url( $which ) {
	$id = absint( get_theme_mod( 'bbp_page_' . $which ) );
	if ( $id && get_post_status( $id ) === 'publish' ) {
		return get_permalink( $id );
	}
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => 'page-templates/template-' . $which . '.php', // phpcs:ignore WordPress.DB.SlowDBQuery
			'fields'         => 'ids',
		)
	);
	if ( $pages ) {
		return get_permalink( $pages[0] );
	}
	return home_url( '/' . $which . '/' );
}

/**
 * CTA URL + label for a billboard.
 *
 * @param int $post_id Post ID.
 * @return array{url:string,label:string}
 */
function bbp_billboard_cta( $post_id ) {
	$cta    = bbp_meta( 'bb_cta', $post_id );
	$custom = bbp_meta( 'bb_cta_label', $post_id );
	switch ( $cta ) {
		case 'contact':
			$url   = bbp_page_url( 'contact' );
			$label = __( 'Contact Us', 'billboard-pro' );
			break;
		case 'availability':
			$url   = add_query_arg( array( 'bb_id' => $post_id, 'intent' => 'availability' ), bbp_page_url( 'quote' ) );
			$label = __( 'Check Availability', 'billboard-pro' );
			break;
		default:
			$url   = '#inquiry';
			$label = __( 'Request a Quote', 'billboard-pro' );
	}
	return array(
		'url'   => $url,
		'label' => $custom ? $custom : $label,
	);
}

/**
 * Inline SVG icon set (stroke icons, currentColor).
 *
 * @param string $name Icon name.
 * @param int    $size Pixel size.
 * @return string
 */
function bbp_icon( $name, $size = 24 ) {
	$paths = array(
		'eye'      => '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
		'reach'    => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
		'target'   => '<path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
		'clock'    => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
		'star'     => '<path d="M12 2l3.1 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.8 21l1.2-6.8-5-4.9 6.9-1z"/>',
		'screen'   => '<rect x="2" y="3" width="20" height="14" rx="1"/><path d="M8 21h8M12 17v4"/>',
		'repeat'   => '<path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/>',
		'calendar' => '<rect x="3" y="4" width="18" height="18" rx="1"/><path d="M16 2v4M8 2v4M3 10h18"/>',
		'layers'   => '<path d="M12 2l10 5-10 5L2 7z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>',
		'sun'      => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
		'zap'      => '<path d="M13 2L3 14h9l-1 8 10-12h-9z"/>',
		'pin'      => '<path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
		'ruler'    => '<path d="M3 17L17 3l4 4L7 21z"/><path d="M7 13l2 2M10 10l2 2M13 7l2 2"/>',
		'users'    => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
		'car'      => '<path d="M5 17h14v-5l-2-5H7l-2 5z"/><circle cx="7.5" cy="17.5" r="1.5"/><circle cx="16.5" cy="17.5" r="1.5"/><path d="M5 12h14"/>',
		'tag'      => '<path d="M20.6 13.4l-7.2 7.2a2 2 0 0 1-2.8 0L2 12V2h10l8.6 8.6a2 2 0 0 1 0 2.8z"/><circle cx="7" cy="7" r="1.5"/>',
		'phone'    => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/>',
		'mail'     => '<rect x="2" y="4" width="20" height="16" rx="1"/><path d="M22 6l-10 7L2 6"/>',
		'arrow'    => '<path d="M5 12h14M13 5l7 7-7 7"/>',
		'check'    => '<path d="M20 6L9 17l-5-5"/>',
		'search'   => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>',
		'menu'     => '<path d="M3 6h18M3 12h18M3 18h18"/>',
		'close'    => '<path d="M18 6L6 18M6 6l12 12"/>',
		'chart'    => '<path d="M3 3v18h18"/><path d="M7 14l4-4 4 4 5-6"/>',
		'filter'   => '<path d="M22 3H2l8 9.5V19l4 2v-8.5z"/>',
		'quote'    => '<path d="M3 21c3 0 7-1 7-8V5H3v8h4c0 4-2 5-4 5zM14 21c3 0 7-1 7-8V5h-7v8h4c0 4-2 5-4 5z"/>',
		'building' => '<rect x="4" y="2" width="16" height="20"/><path d="M9 22v-4h6v4M8 6h.01M12 6h.01M16 6h.01M8 10h.01M12 10h.01M16 10h.01M8 14h.01M12 14h.01M16 14h.01"/>',
		'whatsapp' => '<path d="M3 21l1.7-5A9 9 0 1 1 8 19.3z"/><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1.5-1.5-2-1-1 1c-1 0-2.5-1.5-2.5-2.5l1-1-1-2z"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return sprintf(
		'<svg class="icon icon-%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( $name ),
		absint( $size ),
		$paths[ $name ]
	);
}

/**
 * Allowed SVG markup for wp_kses.
 *
 * @return array
 */
function bbp_svg_kses() {
	$common = array( 'd' => true, 'cx' => true, 'cy' => true, 'r' => true, 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true );
	return array(
		'svg'    => array( 'class' => true, 'width' => true, 'height' => true, 'viewbox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'aria-hidden' => true, 'focusable' => true ),
		'path'   => $common,
		'circle' => $common,
		'rect'   => $common,
	);
}

/**
 * Echo an icon safely.
 *
 * @param string $name Icon name.
 * @param int    $size Size.
 */
function bbp_the_icon( $name, $size = 24 ) {
	echo wp_kses( bbp_icon( $name, $size ), bbp_svg_kses() );
}

/**
 * Lines of a textarea field as an array.
 *
 * @param string $value Raw text.
 * @return string[]
 */
function bbp_lines( $value ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $value ) ) ) );
}

/**
 * Section heading markup.
 *
 * @param string $eyebrow Small label.
 * @param string $title   Title.
 * @param string $intro   Intro text.
 * @param string $align   left|center.
 */
function bbp_section_head( $eyebrow, $title, $intro = '', $align = 'left' ) {
	?>
	<header class="section-head section-head--<?php echo esc_attr( $align ); ?>">
		<?php if ( $eyebrow ) : ?>
			<p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<?php endif; ?>
		<h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
		<?php if ( $intro ) : ?>
			<p class="section-intro"><?php echo esc_html( $intro ); ?></p>
		<?php endif; ?>
	</header>
	<?php
}

/**
 * Breadcrumbs.
 */
function bbp_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}
	$items   = array();
	$items[] = array( home_url( '/' ), __( 'Home', 'billboard-pro' ) );

	if ( is_singular() ) {
		$pt = get_post_type();
		if ( 'post' === $pt && get_option( 'page_for_posts' ) ) {
			$items[] = array( get_permalink( get_option( 'page_for_posts' ) ), get_the_title( get_option( 'page_for_posts' ) ) );
		} elseif ( 'page' !== $pt && 'post' !== $pt ) {
			$obj = get_post_type_object( $pt );
			$items[] = array( get_post_type_archive_link( $pt ), $obj->labels->name );
		}
		$items[] = array( '', get_the_title() );
	} elseif ( is_post_type_archive() ) {
		$items[] = array( '', post_type_archive_title( '', false ) );
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$items[] = array( '', single_term_title( '', false ) );
	} elseif ( is_search() ) {
		$items[] = array( '', __( 'Search', 'billboard-pro' ) );
	} elseif ( is_home() ) {
		$items[] = array( '', __( 'Insights', 'billboard-pro' ) );
	}

	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'billboard-pro' ) . '"><ol>';
	foreach ( $items as $item ) {
		if ( $item[0] ) {
			printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $item[0] ), esc_html( $item[1] ) );
		} else {
			printf( '<li aria-current="page">%s</li>', esc_html( $item[1] ) );
		}
	}
	echo '</ol></nav>';
}

/**
 * Page hero for inner pages.
 *
 * @param string $title    Title.
 * @param string $subtitle Subtitle.
 * @param string $image    Image URL.
 */
function bbp_page_hero( $title, $subtitle = '', $image = '' ) {
	$image = $image ? $image : bbp_img( 'hero-inner.jpg' );
	?>
	<section class="page-hero" style="--hero-img:url('<?php echo esc_url( $image ); ?>')">
		<div class="container">
			<?php bbp_breadcrumbs(); ?>
			<h1 class="page-hero__title"><?php echo esc_html( $title ); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p class="page-hero__sub"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Map block: embeds an OpenStreetMap iframe when coordinates exist
 * (no API key needed) or a styled placeholder otherwise. Filterable for
 * Google Maps / Mapbox integrations via `bbp_map_html`.
 *
 * @param string $lat   Latitude.
 * @param string $lng   Longitude.
 * @param string $label Label.
 */
function bbp_map( $lat, $lng, $label = '' ) {
	$html = '';
	if ( is_numeric( $lat ) && is_numeric( $lng ) && bbp_opt( 'map_embed' ) ) {
		$lat  = (float) $lat;
		$lng  = (float) $lng;
		$bbox = ( $lng - 0.01 ) . ',' . ( $lat - 0.006 ) . ',' . ( $lng + 0.01 ) . ',' . ( $lat + 0.006 );
		$src  = 'https://www.openstreetmap.org/export/embed.html?bbox=' . rawurlencode( $bbox ) . '&layer=mapnik&marker=' . rawurlencode( $lat . ',' . $lng );
		$html = sprintf(
			'<div class="map-embed"><iframe title="%1$s" src="%2$s" loading="lazy" referrerpolicy="no-referrer"></iframe></div>',
			esc_attr( $label ? $label : __( 'Map', 'billboard-pro' ) ),
			esc_url( $src )
		);
	} else {
		$html = sprintf(
			'<div class="map-placeholder" data-lat="%1$s" data-lng="%2$s">%3$s<span>%4$s</span></div>',
			esc_attr( $lat ),
			esc_attr( $lng ),
			bbp_icon( 'pin', 32 ),
			esc_html( $label ? $label : __( 'Map location', 'billboard-pro' ) )
		);
	}
	echo apply_filters( 'bbp_map_html', $html, $lat, $lng, $label ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
}

/**
 * Query args for billboards belonging to a Location post: those linked via
 * "Parent Location", plus those in the City term matching the location's city.
 *
 * @param int $location_id Location post ID.
 * @param int $limit       Posts per page.
 * @return array
 */
function bbp_location_billboard_args( $location_id, $limit = 12 ) {
	$ids  = get_posts(
		array(
			'post_type'      => 'billboard',
			'posts_per_page' => 500,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_key'       => 'bb_location_id', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => $location_id, // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	$city = bbp_meta( 'loc_city', $location_id );
	$city = $city ? $city : get_the_title( $location_id );
	$term = get_term_by( 'name', $city, 'billboard_city' );
	if ( $term ) {
		$ids = array_merge(
			$ids,
			get_posts(
				array(
					'post_type'      => 'billboard',
					'posts_per_page' => 500,
					'fields'         => 'ids',
					'no_found_rows'  => true,
					'tax_query'      => array( array( 'taxonomy' => 'billboard_city', 'field' => 'term_id', 'terms' => $term->term_id, 'include_children' => true ) ), // phpcs:ignore WordPress.DB.SlowDBQuery
				)
			)
		);
	}
	$ids = array_values( array_unique( $ids ) );
	return array(
		'post_type'      => 'billboard',
		'post__in'       => $ids ? $ids : array( 0 ),
		'posts_per_page' => $limit,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	);
}

/**
 * Number of billboards for a location (cached per request).
 *
 * @param int $location_id Location ID.
 * @return int
 */
function bbp_location_billboard_count( $location_id ) {
	$args = bbp_location_billboard_args( $location_id );
	return 1 === count( $args['post__in'] ) && 0 === $args['post__in'][0] ? 0 : count( $args['post__in'] );
}

/**
 * Reading time.
 *
 * @param int|null $post_id Post ID.
 * @return string
 */
function bbp_reading_time( $post_id = null ) {
	$words = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ? $post_id : get_the_ID() ) ) );
	$min   = max( 1, (int) ceil( $words / 230 ) );
	/* translators: %d: minutes */
	return sprintf( _n( '%d min read', '%d min read', $min, 'billboard-pro' ), $min );
}

/**
 * Add ids to H2/H3 in posts so the table of contents and deep links work.
 *
 * @param string $content Content.
 * @return string
 */
function bbp_heading_ids( $content ) {
	if ( ! is_singular( array( 'post', 'page', 'bb_location', 'billboard', 'bb_case_study' ) ) || ! in_the_loop() ) {
		return $content;
	}
	$used = array();
	return preg_replace_callback(
		'#<h([23])([^>]*)>(.*?)</h\1>#is',
		function ( $m ) use ( &$used ) {
			if ( false !== stripos( $m[2], 'id=' ) ) {
				return $m[0];
			}
			$slug = sanitize_title( wp_strip_all_tags( $m[3] ) );
			$base = $slug;
			$i    = 2;
			while ( isset( $used[ $slug ] ) ) {
				$slug = $base . '-' . $i++;
			}
			$used[ $slug ] = true;
			return sprintf( '<h%1$s%2$s id="%3$s">%4$s</h%1$s>', $m[1], $m[2], esc_attr( $slug ), $m[3] );
		},
		$content
	);
}
add_filter( 'the_content', 'bbp_heading_ids', 20 );
