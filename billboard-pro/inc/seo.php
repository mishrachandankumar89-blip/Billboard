<?php
/**
 * SEO baseline: meta description, Open Graph, JSON-LD, robots rules, GA4.
 * Automatically steps aside when Yoast SEO, Rank Math, SEOPress or AIOSEO is active.
 *
 * @package Billboard_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Is a dedicated SEO plugin active?
 *
 * @return bool
 */
function bbp_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'AIOSEO_VERSION' );
}

/**
 * Meta description for the current view.
 *
 * @return string
 */
function bbp_meta_description() {
	$desc = '';
	if ( is_front_page() ) {
		$desc = bbp_opt( 'hero_text' );
	} elseif ( is_singular( 'billboard' ) ) {
		$id   = get_queried_object_id();
		$city = bbp_term( $id, 'billboard_city' );
		$desc = has_excerpt( $id ) ? get_the_excerpt( $id ) : '';
		if ( ! $desc ) {
			$desc = sprintf(
				/* translators: 1: title, 2: city, 3: dimensions, 4: impressions */
				__( '%1$s in %2$s — %3$s, %4$s+ daily impressions. Check availability and request a quote.', 'billboard-pro' ),
				get_the_title( $id ),
				$city,
				bbp_dimensions( $id ),
				bbp_compact( bbp_meta( 'bb_daily_impressions', $id ) )
			);
		}
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		$desc = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 28, '…' );
	} elseif ( is_post_type_archive( 'billboard' ) ) {
		$desc = __( 'Browse LED billboard screens and static billboards by city, type, size, budget and availability. Live inventory with pricing and audience data.', 'billboard-pro' );
	} elseif ( is_post_type_archive( 'bb_location' ) ) {
		$desc = __( 'Explore every city and district where we operate LED billboard screens and outdoor advertising locations.', 'billboard-pro' );
	} elseif ( is_post_type_archive( 'bb_case_study' ) ) {
		$desc = __( 'Real outdoor and LED billboard campaigns: objectives, formats, locations and measurable results.', 'billboard-pro' );
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$desc = term_description();
		if ( ! $desc ) {
			$term = get_queried_object();
			/* translators: %s: term name */
			$desc = sprintf( __( 'LED billboard screens and outdoor advertising options: %s. Compare sizes, audience and pricing.', 'billboard-pro' ), $term->name );
		}
	} elseif ( is_home() ) {
		$desc = __( 'In-depth guides on LED billboard screens, digital out-of-home advertising, pricing, creative and campaign planning.', 'billboard-pro' );
	}
	$desc = wp_strip_all_tags( (string) $desc );
	$desc = trim( preg_replace( '/\s+/', ' ', $desc ) );
	return mb_strlen( $desc ) > 158 ? rtrim( mb_substr( $desc, 0, 155 ) ) . '…' : $desc;
}

/**
 * Output head meta.
 */
function bbp_head_meta() {
	if ( bbp_seo_plugin_active() ) {
		return;
	}
	$desc  = bbp_meta_description();
	$title = wp_get_document_title();
	$url   = is_singular() ? get_permalink() : ( is_front_page() ? home_url( '/' ) : '' );
	$img   = is_singular() ? bbp_thumb_url( get_queried_object_id(), 'large' ) : bbp_opt_image( 'hero_image', 'hero.jpg', 'large' );

	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	echo '<meta property="og:type" content="' . ( is_singular( 'post' ) ? 'article' : 'website' ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $desc ) {
		echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	if ( $url ) {
		echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	}
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	echo '<meta property="og:image" content="' . esc_url( $img ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

	// Filtered / paginated inventory views: keep crawl budget on canonical pages.
	// phpcs:ignore WordPress.Security.NonceVerification
	$filtered = ( is_post_type_archive( 'billboard' ) || is_tax( array( 'billboard_city', 'billboard_type' ) ) ) && array_intersect( array_keys( $_GET ), array( 'city', 'state', 'type', 'format', 'size', 'availability', 'traffic', 'budget', 'sort', 'kw' ) );
	if ( $filtered || is_search() ) {
		echo '<meta name="robots" content="noindex,follow">' . "\n";
	}
}
add_action( 'wp_head', 'bbp_head_meta', 2 );

/**
 * JSON-LD structured data.
 */
function bbp_schema() {
	if ( bbp_seo_plugin_active() ) {
		return;
	}
	$graph = array();
	$org   = array(
		'@type'       => 'Organization',
		'@id'         => home_url( '/#organization' ),
		'name'        => get_bloginfo( 'name' ),
		'url'         => home_url( '/' ),
		'description' => bbp_opt( 'footer_about' ),
	);
	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$org['logo'] = wp_get_attachment_image_url( $logo_id, 'full' );
	}
	$same = array_values( array_filter( array( bbp_opt( 'social_linkedin' ), bbp_opt( 'social_instagram' ), bbp_opt( 'social_facebook' ), bbp_opt( 'social_x' ), bbp_opt( 'social_youtube' ) ) ) );
	if ( $same ) {
		$org['sameAs'] = $same;
	}
	if ( bbp_opt( 'phone' ) ) {
		$org['contactPoint'] = array(
			'@type'       => 'ContactPoint',
			'telephone'   => bbp_opt( 'phone' ),
			'email'       => bbp_opt( 'email' ),
			'contactType' => 'sales',
		);
	}
	$graph[] = $org;

	if ( is_front_page() ) {
		$graph[] = array(
			'@type'     => 'WebSite',
			'@id'       => home_url( '/#website' ),
			'url'       => home_url( '/' ),
			'name'      => get_bloginfo( 'name' ),
			'publisher' => array( '@id' => home_url( '/#organization' ) ),
		);
		$graph[] = array(
			'@type'           => 'LocalBusiness',
			'@id'             => home_url( '/#business' ),
			'name'            => get_bloginfo( 'name' ),
			'url'             => home_url( '/' ),
			'telephone'       => bbp_opt( 'phone' ),
			'email'           => bbp_opt( 'email' ),
			'address'         => bbp_opt( 'address' ),
			'image'           => bbp_opt_image( 'hero_image', 'hero.jpg' ),
			'parentOrganization' => array( '@id' => home_url( '/#organization' ) ),
		);
	}

	if ( is_singular( 'billboard' ) ) {
		$id    = get_queried_object_id();
		$price = bbp_meta( 'bb_price', $id );
		$item  = array(
			'@type'       => 'Product',
			'name'        => get_the_title( $id ),
			'description' => bbp_meta_description(),
			'image'       => bbp_thumb_url( $id, 'large' ),
			'sku'         => bbp_meta( 'bb_code', $id ),
			'category'    => __( 'Outdoor advertising space', 'billboard-pro' ),
			'brand'       => array( '@id' => home_url( '/#organization' ) ),
		);
		if ( '' !== $price ) {
			$avail          = bbp_meta( 'bb_availability', $id );
			$item['offers'] = array(
				'@type'         => 'Offer',
				'price'         => (float) $price,
				'priceCurrency' => apply_filters( 'bbp_currency_code', 'USD' ),
				'availability'  => in_array( $avail, array( '', 'available' ), true ) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
				'url'           => get_permalink( $id ),
				'seller'        => array( '@id' => home_url( '/#organization' ) ),
			);
		}
		$lat = bbp_meta( 'bb_lat', $id );
		$lng = bbp_meta( 'bb_lng', $id );
		if ( is_numeric( $lat ) && is_numeric( $lng ) ) {
			$graph[] = array(
				'@type' => 'Place',
				'name'  => get_the_title( $id ),
				'address' => trim( bbp_meta( 'bb_address', $id ) . ', ' . bbp_term( $id, 'billboard_city' ), ', ' ),
				'geo'   => array( '@type' => 'GeoCoordinates', 'latitude' => (float) $lat, 'longitude' => (float) $lng ),
			);
		}
		$graph[] = $item;
	}

	if ( is_singular( 'post' ) ) {
		$p       = get_queried_object();
		$graph[] = array(
			'@type'         => 'Article',
			'headline'      => get_the_title( $p ),
			'datePublished' => get_the_date( 'c', $p ),
			'dateModified'  => get_the_modified_date( 'c', $p ),
			'author'        => array( '@type' => 'Organization', 'name' => get_bloginfo( 'name' ) ),
			'publisher'     => array( '@id' => home_url( '/#organization' ) ),
			'image'         => bbp_thumb_url( $p->ID, 'large' ),
			'mainEntityOfPage' => get_permalink( $p ),
		);
	}

	if ( is_singular( array( 'post', 'page', 'bb_location' ) ) ) {
		$faq = bbp_extract_faq( get_queried_object()->post_content );
		if ( $faq ) {
			$graph[] = array( '@type' => 'FAQPage', 'mainEntity' => $faq );
		}
	}

	// Breadcrumbs.
	if ( ! is_front_page() && ( is_singular() || is_archive() ) ) {
		$crumbs = array( array( home_url( '/' ), __( 'Home', 'billboard-pro' ) ) );
		if ( is_singular() ) {
			$pt = get_post_type();
			if ( 'post' === $pt && get_option( 'page_for_posts' ) ) {
				$crumbs[] = array( get_permalink( get_option( 'page_for_posts' ) ), get_the_title( get_option( 'page_for_posts' ) ) );
			} elseif ( ! in_array( $pt, array( 'page', 'post' ), true ) ) {
				$crumbs[] = array( get_post_type_archive_link( $pt ), get_post_type_object( $pt )->labels->name );
			}
			$crumbs[] = array( get_permalink(), get_the_title() );
		} elseif ( is_post_type_archive() ) {
			$crumbs[] = array( get_post_type_archive_link( get_query_var( 'post_type' ) ), post_type_archive_title( '', false ) );
		} elseif ( is_tax() || is_category() ) {
			$t        = get_queried_object();
			$crumbs[] = array( get_term_link( $t ), $t->name );
		}
		$list = array();
		foreach ( $crumbs as $i => $c ) {
			$list[] = array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => wp_strip_all_tags( $c[1] ), 'item' => $c[0] );
		}
		$graph[] = array( '@type' => 'BreadcrumbList', 'itemListElement' => $list );
	}

	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'bbp_schema', 20 );

/**
 * Extract FAQ pairs from content: an H2 "Frequently asked questions" followed
 * by H3 questions and paragraph answers.
 *
 * @param string $content Content.
 * @return array
 */
function bbp_extract_faq( $content ) {
	$pos = stripos( $content, 'Frequently asked questions' );
	if ( false === $pos ) {
		return array();
	}
	$part = substr( $content, $pos );
	preg_match_all( '#<h3[^>]*>(.*?)</h3>\s*(?:<!--[^>]*-->\s*)?<p[^>]*>(.*?)</p>#is', $part, $m, PREG_SET_ORDER );
	$out = array();
	foreach ( $m as $row ) {
		$out[] = array(
			'@type'          => 'Question',
			'name'           => wp_strip_all_tags( $row[1] ),
			'acceptedAnswer' => array( '@type' => 'Answer', 'text' => wp_strip_all_tags( $row[2] ) ),
		);
	}
	return $out;
}

/**
 * Exclude non-public types from the core sitemap.
 *
 * @param array $types Post types.
 * @return array
 */
function bbp_sitemap_types( $types ) {
	unset( $types['bb_testimonial'], $types['bb_inquiry'] );
	return $types;
}
add_filter( 'wp_sitemaps_post_types', 'bbp_sitemap_types' );

/**
 * robots.txt additions (virtual robots only).
 *
 * @param string $output Output.
 * @param bool   $public Is public.
 * @return string
 */
function bbp_robots_txt( $output, $public ) {
	if ( ! $public ) {
		return $output;
	}
	$output .= "Disallow: /*?*kw=\n";
	$output .= "Disallow: /*?*sort=\n";
	$output .= "\nUser-agent: GPTBot\nAllow: /\n\nUser-agent: OAI-SearchBot\nAllow: /\n\nUser-agent: PerplexityBot\nAllow: /\n\nUser-agent: ClaudeBot\nAllow: /\n\nUser-agent: Google-Extended\nAllow: /\n";
	if ( false === strpos( $output, 'Sitemap:' ) ) {
		$output .= "\nSitemap: " . esc_url( home_url( '/wp-sitemap.xml' ) ) . "\n";
	}
	return $output;
}
add_filter( 'robots_txt', 'bbp_robots_txt', 20, 2 );

/**
 * Serve /llms.txt — a plain summary of the site for AI answer engines.
 */
function bbp_llms_txt() {
	$path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';
	if ( '/llms.txt' !== $path ) {
		return;
	}
	header( 'Content-Type: text/plain; charset=utf-8' );
	$lines   = array();
	$lines[] = '# ' . get_bloginfo( 'name' );
	$lines[] = '';
	$lines[] = '> ' . bbp_opt( 'footer_about' );
	$lines[] = '';
	$lines[] = '## Key pages';
	$lines[] = '- [Billboard inventory](' . get_post_type_archive_link( 'billboard' ) . ')';
	$lines[] = '- [Locations](' . get_post_type_archive_link( 'bb_location' ) . ')';
	$lines[] = '- [Case studies](' . get_post_type_archive_link( 'bb_case_study' ) . ')';
	$lines[] = '- [Request a quote](' . bbp_page_url( 'quote' ) . ')';
	$lines[] = '- [Contact](' . bbp_page_url( 'contact' ) . ')';
	$guides  = get_posts( array( 'posts_per_page' => 50, 'post_status' => 'publish' ) );
	if ( $guides ) {
		$lines[] = '';
		$lines[] = '## Guides';
		foreach ( $guides as $g ) {
			$lines[] = '- [' . $g->post_title . '](' . get_permalink( $g ) . ')';
		}
	}
	$lines[] = '';
	$lines[] = '## Contact';
	$lines[] = 'Phone: ' . bbp_opt( 'phone' );
	$lines[] = 'Email: ' . bbp_opt( 'email' );
	$lines[] = 'Address: ' . bbp_opt( 'address' );
	echo wp_strip_all_tags( implode( "\n", $lines ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- text/plain.
	exit;
}
add_action( 'template_redirect', 'bbp_llms_txt', 0 );

/**
 * GA4.
 */
function bbp_ga4() {
	$id = bbp_opt( 'ga4_id' );
	if ( ! $id || ! preg_match( '/^G-[A-Z0-9]+$/', $id ) || is_user_logged_in() ) {
		return;
	}
	?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $id ); ?>"></script>
	<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?php echo esc_js( $id ); ?>');</script>
	<?php
}
add_action( 'wp_head', 'bbp_ga4', 30 );
