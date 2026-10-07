<?php
/**
 * Custom post types and taxonomies.
 *
 * Note: content types live in the theme as requested. If you later switch
 * themes, move this file into a small plugin to keep the data accessible.
 *
 * @package Billboard_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register CPTs + taxonomies.
 */
function bbp_register_post_types() {
	$cpt = function ( $singular, $plural, $extra = array() ) {
		return array_merge(
			array(
				'labels'       => array(
					'name'               => $plural,
					'singular_name'      => $singular,
					/* translators: %s: singular post type name */
					'add_new_item'       => sprintf( __( 'Add New %s', 'billboard-pro' ), $singular ),
					/* translators: %s: singular post type name */
					'edit_item'          => sprintf( __( 'Edit %s', 'billboard-pro' ), $singular ),
					/* translators: %s: singular post type name */
					'new_item'           => sprintf( __( 'New %s', 'billboard-pro' ), $singular ),
					/* translators: %s: singular post type name */
					'view_item'          => sprintf( __( 'View %s', 'billboard-pro' ), $singular ),
					/* translators: %s: plural post type name */
					'search_items'       => sprintf( __( 'Search %s', 'billboard-pro' ), $plural ),
					/* translators: %s: plural post type name */
					'not_found'          => sprintf( __( 'No %s found', 'billboard-pro' ), strtolower( $plural ) ),
					/* translators: %s: plural post type name */
					'all_items'          => sprintf( __( 'All %s', 'billboard-pro' ), $plural ),
				),
				'public'       => true,
				'has_archive'  => true,
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			),
			$extra
		);
	};

	register_post_type(
		'billboard',
		$cpt(
			__( 'Billboard', 'billboard-pro' ),
			__( 'Billboards', 'billboard-pro' ),
			array(
				'menu_icon'     => 'dashicons-format-image',
				'menu_position' => 5,
				'rewrite'       => array( 'slug' => 'billboards', 'with_front' => false ),
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'bb_location',
		$cpt(
			__( 'Location', 'billboard-pro' ),
			__( 'Locations', 'billboard-pro' ),
			array(
				'menu_icon'     => 'dashicons-location',
				'menu_position' => 6,
				'rewrite'       => array( 'slug' => 'locations', 'with_front' => false ),
			)
		)
	);

	register_post_type(
		'bb_case_study',
		$cpt(
			__( 'Case Study', 'billboard-pro' ),
			__( 'Case Studies', 'billboard-pro' ),
			array(
				'menu_icon'     => 'dashicons-awards',
				'menu_position' => 7,
				'rewrite'       => array( 'slug' => 'case-studies', 'with_front' => false ),
			)
		)
	);

	register_post_type(
		'bb_testimonial',
		$cpt(
			__( 'Testimonial', 'billboard-pro' ),
			__( 'Testimonials', 'billboard-pro' ),
			array(
				'public'              => false,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'menu_icon'           => 'dashicons-format-quote',
				'menu_position'       => 8,
				'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'bb_inquiry',
		array(
			'labels'              => array(
				'name'          => __( 'Inquiries', 'billboard-pro' ),
				'singular_name' => __( 'Inquiry', 'billboard-pro' ),
				'all_items'     => __( 'All Inquiries', 'billboard-pro' ),
				'edit_item'     => __( 'Inquiry', 'billboard-pro' ),
				'not_found'     => __( 'No inquiries yet.', 'billboard-pro' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'exclude_from_search' => true,
			'show_in_rest'        => false,
			'menu_icon'           => 'dashicons-email-alt',
			'menu_position'       => 4,
			'supports'            => array( 'title' ),
			'capability_type'     => 'post',
			'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'        => true,
		)
	);

	register_taxonomy(
		'billboard_city',
		array( 'billboard' ),
		array(
			'labels'            => array(
				'name'          => __( 'Cities', 'billboard-pro' ),
				'singular_name' => __( 'City', 'billboard-pro' ),
				'add_new_item'  => __( 'Add New City', 'billboard-pro' ),
				'edit_item'     => __( 'Edit City', 'billboard-pro' ),
				'all_items'     => __( 'All Cities', 'billboard-pro' ),
			),
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'billboards-in', 'with_front' => false ),
		)
	);

	register_taxonomy(
		'billboard_type',
		array( 'billboard' ),
		array(
			'labels'            => array(
				'name'          => __( 'Billboard Types', 'billboard-pro' ),
				'singular_name' => __( 'Billboard Type', 'billboard-pro' ),
				'add_new_item'  => __( 'Add New Type', 'billboard-pro' ),
				'edit_item'     => __( 'Edit Type', 'billboard-pro' ),
				'all_items'     => __( 'All Types', 'billboard-pro' ),
			),
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'billboard-type', 'with_front' => false ),
		)
	);
}
add_action( 'init', 'bbp_register_post_types' );

/**
 * Admin list columns for billboards.
 *
 * @param array $cols Columns.
 * @return array
 */
function bbp_billboard_columns( $cols ) {
	$new = array();
	foreach ( $cols as $k => $v ) {
		if ( 'title' === $k ) {
			$new['bbp_thumb'] = '';
		}
		$new[ $k ] = $v;
		if ( 'title' === $k ) {
			$new['bbp_code']   = __( 'ID', 'billboard-pro' );
			$new['bbp_format'] = __( 'Format', 'billboard-pro' );
			$new['bbp_status'] = __( 'Availability', 'billboard-pro' );
			$new['bbp_price']  = __( 'From', 'billboard-pro' );
		}
	}
	return $new;
}
add_filter( 'manage_billboard_posts_columns', 'bbp_billboard_columns' );

/**
 * Admin column values.
 *
 * @param string $col     Column.
 * @param int    $post_id Post ID.
 */
function bbp_billboard_column_values( $col, $post_id ) {
	switch ( $col ) {
		case 'bbp_thumb':
			echo '<img src="' . esc_url( bbp_thumb_url( $post_id, 'thumbnail' ) ) . '" alt="" style="width:60px;height:40px;object-fit:cover;border-radius:2px">';
			break;
		case 'bbp_code':
			echo esc_html( bbp_meta( 'bb_code', $post_id ) );
			break;
		case 'bbp_format':
			echo esc_html( bbp_label( 'billboard', 'bb_format', bbp_meta( 'bb_format', $post_id ) ) );
			break;
		case 'bbp_status':
			echo wp_kses_post( bbp_badge( $post_id ) );
			break;
		case 'bbp_price':
			$p = bbp_meta( 'bb_price', $post_id );
			echo esc_html( '' === $p ? '—' : bbp_price( $p ) );
			break;
	}
}
add_action( 'manage_billboard_posts_custom_column', 'bbp_billboard_column_values', 10, 2 );

/**
 * Badge colors in admin.
 */
function bbp_admin_badge_css() {
	echo '<style>.badge{display:inline-block;padding:2px 8px;border-radius:2px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em;background:#e5e7eb;color:#111}.badge--available{background:#d1fae5;color:#065f46}.badge--reserved{background:#fef3c7;color:#92400e}.badge--booked{background:#fee2e2;color:#991b1b}.badge--maintenance,.badge--unavailable{background:#e5e7eb;color:#374151}.column-bbp_thumb{width:70px}</style>';
}
add_action( 'admin_head-edit.php', 'bbp_admin_badge_css' );
