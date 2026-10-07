<?php
/**
 * Inquiries admin: list columns, detail view, status, CSV export.
 *
 * @package Billboard_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Status labels.
 *
 * @return array
 */
function bbp_inquiry_statuses() {
	return array(
		'new'       => __( 'New', 'billboard-pro' ),
		'contacted' => __( 'Contacted', 'billboard-pro' ),
		'proposal'  => __( 'Proposal sent', 'billboard-pro' ),
		'won'       => __( 'Won', 'billboard-pro' ),
		'lost'      => __( 'Lost', 'billboard-pro' ),
	);
}

/**
 * Columns.
 *
 * @param array $cols Columns.
 * @return array
 */
function bbp_inquiry_columns( $cols ) {
	return array(
		'cb'           => $cols['cb'],
		'title'        => __( 'Inquiry', 'billboard-pro' ),
		'bbp_email'    => __( 'Email', 'billboard-pro' ),
		'bbp_phone'    => __( 'Phone', 'billboard-pro' ),
		'bbp_city'     => __( 'City', 'billboard-pro' ),
		'bbp_budget'   => __( 'Budget', 'billboard-pro' ),
		'bbp_istatus'  => __( 'Status', 'billboard-pro' ),
		'date'         => __( 'Received', 'billboard-pro' ),
	);
}
add_filter( 'manage_bb_inquiry_posts_columns', 'bbp_inquiry_columns' );

/**
 * Column values.
 *
 * @param string $col Column.
 * @param int    $id  Post ID.
 */
function bbp_inquiry_column_values( $col, $id ) {
	$d = (array) get_post_meta( $id, '_bbp_data', true );
	switch ( $col ) {
		case 'bbp_email':
			if ( ! empty( $d['email'] ) ) {
				printf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $d['email'] ) );
			}
			break;
		case 'bbp_phone':
			echo esc_html( isset( $d['phone'] ) ? $d['phone'] : '' );
			break;
		case 'bbp_city':
			echo esc_html( isset( $d['city'] ) ? $d['city'] : '' );
			break;
		case 'bbp_budget':
			echo esc_html( isset( $d['budget'] ) ? $d['budget'] : '' );
			break;
		case 'bbp_istatus':
			$s  = get_post_meta( $id, '_bbp_status', true );
			$sl = bbp_inquiry_statuses();
			echo esc_html( isset( $sl[ $s ] ) ? $sl[ $s ] : $sl['new'] );
			break;
	}
}
add_action( 'manage_bb_inquiry_posts_custom_column', 'bbp_inquiry_column_values', 10, 2 );

/**
 * Detail meta box.
 */
function bbp_inquiry_meta_boxes() {
	add_meta_box( 'bbp_inquiry_data', __( 'Inquiry Details', 'billboard-pro' ), 'bbp_inquiry_render', 'bb_inquiry', 'normal', 'high' );
	add_meta_box( 'bbp_inquiry_status', __( 'Lead Status', 'billboard-pro' ), 'bbp_inquiry_status_render', 'bb_inquiry', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'bbp_inquiry_meta_boxes' );

/**
 * Render details table.
 *
 * @param WP_Post $post Post.
 */
function bbp_inquiry_render( $post ) {
	$d    = (array) get_post_meta( $post->ID, '_bbp_data', true );
	$form = get_post_meta( $post->ID, '_bbp_form', true );
	$defs = bbp_form_fields( $form ? $form : 'quote' );
	echo '<table class="bbp-inquiry-table widefat striped"><tbody>';
	foreach ( $d as $k => $v ) {
		$label = isset( $defs[ $k ]['label'] ) ? $defs[ $k ]['label'] : ucwords( str_replace( '_', ' ', $k ) );
		echo '<tr><th>' . esc_html( $label ) . '</th><td>' . nl2br( esc_html( $v ) ) . '</td></tr>';
	}
	echo '</tbody></table>';
}

/**
 * Render status select.
 *
 * @param WP_Post $post Post.
 */
function bbp_inquiry_status_render( $post ) {
	wp_nonce_field( 'bbp_inquiry_status', 'bbp_inquiry_nonce' );
	$s = get_post_meta( $post->ID, '_bbp_status', true );
	echo '<select name="bbp_inquiry_status" style="width:100%">';
	foreach ( bbp_inquiry_statuses() as $k => $l ) {
		printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $k ), selected( $s, $k, false ), esc_html( $l ) );
	}
	echo '</select>';
	echo '<p><label for="bbp_inquiry_notes"><strong>' . esc_html__( 'Internal notes', 'billboard-pro' ) . '</strong></label><textarea id="bbp_inquiry_notes" name="bbp_inquiry_notes" rows="5" style="width:100%">' . esc_textarea( get_post_meta( $post->ID, '_bbp_notes', true ) ) . '</textarea></p>';
}

/**
 * Save status.
 *
 * @param int $post_id Post ID.
 */
function bbp_inquiry_save( $post_id ) {
	if ( ! isset( $_POST['bbp_inquiry_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['bbp_inquiry_nonce'] ), 'bbp_inquiry_status' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$s = isset( $_POST['bbp_inquiry_status'] ) ? sanitize_key( $_POST['bbp_inquiry_status'] ) : 'new';
	if ( isset( bbp_inquiry_statuses()[ $s ] ) ) {
		update_post_meta( $post_id, '_bbp_status', $s );
	}
	if ( isset( $_POST['bbp_inquiry_notes'] ) ) {
		update_post_meta( $post_id, '_bbp_notes', sanitize_textarea_field( wp_unslash( $_POST['bbp_inquiry_notes'] ) ) );
	}
}
add_action( 'save_post_bb_inquiry', 'bbp_inquiry_save' );

/**
 * Keep inquiries private when the admin clicks Update.
 *
 * @param array $data Post data.
 * @return array
 */
function bbp_inquiry_keep_private( $data ) {
	if ( 'bb_inquiry' === $data['post_type'] && 'trash' !== $data['post_status'] && 'auto-draft' !== $data['post_status'] ) {
		$data['post_status'] = 'private';
	}
	return $data;
}
add_filter( 'wp_insert_post_data', 'bbp_inquiry_keep_private' );

/**
 * New-inquiry count bubble in the admin menu.
 */
function bbp_inquiry_menu_bubble() {
	global $menu;
	$q = new WP_Query(
		array(
			'post_type'      => 'bb_inquiry',
			'post_status'    => 'private',
			'meta_key'       => '_bbp_status', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => 'new', // phpcs:ignore WordPress.DB.SlowDBQuery
			'fields'         => 'ids',
			'posts_per_page' => 1,
			'no_found_rows'  => false,
		)
	);
	$n = (int) $q->found_posts;
	if ( ! $n || empty( $menu ) ) {
		return;
	}
	foreach ( $menu as $i => $item ) {
		if ( isset( $item[2] ) && 'edit.php?post_type=bb_inquiry' === $item[2] ) {
			$menu[ $i ][0] .= ' <span class="awaiting-mod"><span class="pending-count">' . $n . '</span></span>'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		}
	}
}
add_action( 'admin_menu', 'bbp_inquiry_menu_bubble', 99 );

/**
 * Export button above the list.
 *
 * @param string $which top|bottom.
 */
function bbp_inquiry_export_button( $which ) {
	$screen = get_current_screen();
	if ( 'top' !== $which || ! $screen || 'edit-bb_inquiry' !== $screen->id ) {
		return;
	}
	$url = wp_nonce_url( admin_url( 'admin-post.php?action=bbp_export_inquiries' ), 'bbp_export' );
	echo '<div class="alignleft actions"><a class="button" href="' . esc_url( $url ) . '">' . esc_html__( 'Export CSV', 'billboard-pro' ) . '</a></div>';
}
add_action( 'manage_posts_extra_tablenav', 'bbp_inquiry_export_button' );

/**
 * CSV export.
 */
function bbp_export_inquiries() {
	if ( ! current_user_can( 'edit_others_posts' ) || ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ), 'bbp_export' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'billboard-pro' ) );
	}
	$posts = get_posts(
		array(
			'post_type'      => 'bb_inquiry',
			'post_status'    => 'private',
			'posts_per_page' => -1,
		)
	);
	$cols = array( 'date', 'form', 'status', 'full_name', 'company', 'email', 'phone', 'website', 'city', 'location', 'type', 'start_date', 'end_date', 'budget', 'quantity', 'subject', 'billboard', 'message', 'source_page' );
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=inquiries-' . gmdate( 'Y-m-d' ) . '.csv' );
	$out = fopen( 'php://output', 'w' );
	fputcsv( $out, $cols );
	foreach ( $posts as $p ) {
		$d   = (array) get_post_meta( $p->ID, '_bbp_data', true );
		$row = array();
		foreach ( $cols as $c ) {
			if ( 'date' === $c ) {
				$v = $p->post_date;
			} elseif ( 'form' === $c ) {
				$v = get_post_meta( $p->ID, '_bbp_form', true );
			} elseif ( 'status' === $c ) {
				$v = get_post_meta( $p->ID, '_bbp_status', true );
			} else {
				$v = isset( $d[ $c ] ) ? $d[ $c ] : '';
			}
			// Neutralise spreadsheet formula injection.
			if ( is_string( $v ) && preg_match( '/^[=+\-@]/', $v ) ) {
				$v = "'" . $v;
			}
			$row[] = $v;
		}
		fputcsv( $out, $row );
	}
	fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	exit;
}
add_action( 'admin_post_bbp_export_inquiries', 'bbp_export_inquiries' );
