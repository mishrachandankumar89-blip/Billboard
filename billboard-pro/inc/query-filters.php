<?php
/**
 * Real billboard filtering via WP_Query (works with and without JS).
 *
 * @package Billboard_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Budget buckets (min, max) on the starting price.
 *
 * @return array
 */
function bbp_budget_options() {
	$c = bbp_opt( 'currency' );
	return array(
		'0-2500'      => array( 0, 2500, sprintf( /* translators: %s amount */ __( 'Under %s', 'billboard-pro' ), $c . '2,500' ) ),
		'2500-7500'   => array( 2500, 7500, $c . '2,500 – ' . $c . '7,500' ),
		'7500-20000'  => array( 7500, 20000, $c . '7,500 – ' . $c . '20,000' ),
		'20000-'      => array( 20000, 999999998, sprintf( /* translators: %s amount */ __( '%s+', 'billboard-pro' ), $c . '20,000' ) ),
	);
}

/**
 * Sorting options.
 *
 * @return array
 */
function bbp_sort_options() {
	return array(
		''           => __( 'Recommended', 'billboard-pro' ),
		'price-asc'  => __( 'Price: low to high', 'billboard-pro' ),
		'price-desc' => __( 'Price: high to low', 'billboard-pro' ),
		'reach'      => __( 'Highest impressions', 'billboard-pro' ),
		'newest'     => __( 'Newest', 'billboard-pro' ),
	);
}

/**
 * Read + sanitize filter params from an array (usually $_GET / $_POST).
 *
 * @param array $src Source.
 * @return array
 */
function bbp_filter_params( $src ) {
	$keys = array( 'city', 'state', 'type', 'format', 'size', 'availability', 'traffic', 'budget', 'sort', 'kw' );
	$out  = array();
	foreach ( $keys as $k ) {
		$out[ $k ] = isset( $src[ $k ] ) ? sanitize_text_field( wp_unslash( $src[ $k ] ) ) : '';
	}
	$out['kw'] = substr( $out['kw'], 0, 80 );
	return $out;
}

/**
 * Translate params into WP_Query args.
 *
 * @param array $p Params.
 * @return array
 */
function bbp_filter_query_args( $p ) {
	$tax  = array();
	$meta = array();

	if ( $p['city'] ) {
		$tax[] = array( 'taxonomy' => 'billboard_city', 'field' => 'slug', 'terms' => sanitize_title( $p['city'] ) );
	}
	if ( $p['type'] ) {
		$tax[] = array( 'taxonomy' => 'billboard_type', 'field' => 'slug', 'terms' => sanitize_title( $p['type'] ) );
	}
	if ( $p['state'] ) {
		$meta[] = array( 'key' => 'bb_state', 'value' => $p['state'] );
	}
	if ( $p['format'] && isset( bbp_format_options()[ $p['format'] ] ) ) {
		$meta[] = array( 'key' => 'bb_format', 'value' => $p['format'] );
	}
	if ( $p['availability'] && isset( bbp_availability_options()[ $p['availability'] ] ) ) {
		$meta[] = array( 'key' => 'bb_availability', 'value' => $p['availability'] );
	}
	if ( $p['traffic'] && isset( bbp_traffic_options()[ $p['traffic'] ] ) ) {
		$meta[] = array( 'key' => 'bb_traffic_level', 'value' => $p['traffic'] );
	}
	if ( $p['size'] && isset( bbp_size_options()[ $p['size'] ] ) ) {
		$meta[] = array(
			'relation' => 'OR',
			array( 'key' => 'bb_size', 'value' => $p['size'] ),
			array( 'key' => '_bb_size_auto', 'value' => $p['size'] ),
		);
	}
	$budgets = bbp_budget_options();
	if ( $p['budget'] && isset( $budgets[ $p['budget'] ] ) ) {
		$meta[] = array(
			'key'     => '_bb_price_sort',
			'value'   => array( $budgets[ $p['budget'] ][0], $budgets[ $p['budget'] ][1] ),
			'type'    => 'NUMERIC',
			'compare' => 'BETWEEN',
		);
	}

	$args = array();
	if ( $tax ) {
		$args['tax_query'] = array_merge( array( 'relation' => 'AND' ), $tax ); // phpcs:ignore WordPress.DB.SlowDBQuery
	}
	if ( $meta ) {
		$args['meta_query'] = array_merge( array( 'relation' => 'AND' ), $meta ); // phpcs:ignore WordPress.DB.SlowDBQuery
	}
	if ( $p['kw'] ) {
		$args['s'] = $p['kw'];
	}

	switch ( $p['sort'] ) {
		case 'price-asc':
		case 'price-desc':
			$args['meta_key'] = '_bb_price_sort'; // phpcs:ignore WordPress.DB.SlowDBQuery
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'price-asc' === $p['sort'] ? 'ASC' : 'DESC';
			break;
		case 'reach':
			$args['meta_key'] = 'bb_daily_impressions'; // phpcs:ignore WordPress.DB.SlowDBQuery
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
			break;
		case 'newest':
			$args['orderby'] = 'date';
			$args['order']   = 'DESC';
			break;
		default:
			$args['orderby'] = array( 'menu_order' => 'ASC', 'date' => 'DESC' );
	}
	return $args;
}

/**
 * Apply filters to the main billboard archive / taxonomy queries.
 *
 * @param WP_Query $q Query.
 */
function bbp_pre_get_posts( $q ) {
	if ( is_admin() || ! $q->is_main_query() ) {
		return;
	}
	if ( $q->is_post_type_archive( 'billboard' ) || $q->is_tax( array( 'billboard_city', 'billboard_type' ) ) ) {
		$p    = bbp_filter_params( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification -- public read-only filters.
		$args = bbp_filter_query_args( $p );
		// WP_Query merges taxonomy query vars (on term archives) with tax_query.
		foreach ( $args as $k => $v ) {
			$q->set( $k, $v );
		}
		$q->set( 'posts_per_page', 12 );
		if ( $p['kw'] ) {
			$q->set( 'post_type', 'billboard' );
		}
	}
	if ( $q->is_post_type_archive( array( 'bb_location', 'bb_case_study' ) ) ) {
		$q->set( 'posts_per_page', 12 );
	}
}
add_action( 'pre_get_posts', 'bbp_pre_get_posts' );

/**
 * AJAX: return filtered cards.
 */
function bbp_ajax_filter() {
	check_ajax_referer( 'bbp_filter', 'nonce' );
	$p     = bbp_filter_params( $_POST );
	$paged = isset( $_POST['paged'] ) ? max( 1, absint( $_POST['paged'] ) ) : 1;
	$args  = array_merge(
		array(
			'post_type'      => 'billboard',
			'post_status'    => 'publish',
			'posts_per_page' => 12,
			'paged'          => $paged,
		),
		bbp_filter_query_args( $p )
	);
	$q = new WP_Query( $args );

	ob_start();
	if ( $q->have_posts() ) {
		while ( $q->have_posts() ) {
			$q->the_post();
			get_template_part( 'template-parts/billboard-card' );
		}
	} else {
		get_template_part( 'template-parts/no-results' );
	}
	$html = ob_get_clean();
	wp_reset_postdata();

	wp_send_json_success(
		array(
			'html'       => $html,
			'found'      => (int) $q->found_posts,
			'pagination' => bbp_ajax_pagination( $q, $paged ),
		)
	);
}
add_action( 'wp_ajax_bbp_filter', 'bbp_ajax_filter' );
add_action( 'wp_ajax_nopriv_bbp_filter', 'bbp_ajax_filter' );

/**
 * Simple pagination markup for AJAX results.
 *
 * @param WP_Query $q     Query.
 * @param int      $paged Current page.
 * @return string
 */
function bbp_ajax_pagination( $q, $paged ) {
	if ( $q->max_num_pages < 2 ) {
		return '';
	}
	$out = '<nav class="pagination" aria-label="' . esc_attr__( 'Billboard results pages', 'billboard-pro' ) . '"><div class="nav-links">';
	for ( $i = 1; $i <= $q->max_num_pages; $i++ ) {
		if ( $q->max_num_pages > 9 && abs( $i - $paged ) > 2 && 1 !== $i && (int) $q->max_num_pages !== $i ) {
			if ( abs( $i - $paged ) === 3 ) {
				$out .= '<span class="page-numbers dots">…</span>';
			}
			continue;
		}
		if ( $i === $paged ) {
			$out .= '<span aria-current="page" class="page-numbers current">' . $i . '</span>';
		} else {
			$out .= '<a class="page-numbers" href="#" data-page="' . $i . '">' . $i . '</a>';
		}
	}
	return $out . '</div></nav>';
}

/**
 * Distinct state values (cached) for the filter dropdown.
 *
 * @return string[]
 */
function bbp_states() {
	$states = get_transient( 'bbp_states' );
	if ( false === $states ) {
		global $wpdb;
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$states = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id WHERE pm.meta_key = %s AND pm.meta_value <> '' AND p.post_type = 'billboard' AND p.post_status = 'publish' ORDER BY pm.meta_value ASC",
				'bb_state'
			)
		);
		set_transient( 'bbp_states', $states, DAY_IN_SECONDS );
	}
	return (array) $states;
}

/**
 * Clear caches on billboard save.
 *
 * @param int $post_id Post ID.
 */
function bbp_clear_caches( $post_id ) {
	if ( 'billboard' === get_post_type( $post_id ) ) {
		delete_transient( 'bbp_states' );
	}
}
add_action( 'save_post', 'bbp_clear_caches', 30 );
