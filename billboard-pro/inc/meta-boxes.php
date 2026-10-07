<?php
/**
 * Native meta boxes (no ACF needed) driven by bbp_fields().
 *
 * @package Billboard_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post types that get field meta boxes.
 *
 * @return string[]
 */
function bbp_meta_post_types() {
	return array( 'billboard', 'bb_location', 'bb_case_study', 'bb_testimonial' );
}

/**
 * Register meta boxes.
 */
function bbp_add_meta_boxes() {
	foreach ( bbp_meta_post_types() as $pt ) {
		add_meta_box( 'bbp_fields', __( 'Details', 'billboard-pro' ), 'bbp_render_meta_box', $pt, 'normal', 'high' );
	}
}
add_action( 'add_meta_boxes', 'bbp_add_meta_boxes' );

/**
 * Register meta for REST (block editor compatibility & headless use).
 */
function bbp_register_meta() {
	foreach ( bbp_meta_post_types() as $pt ) {
		foreach ( bbp_fields( $pt ) as $key => $field ) {
			register_post_meta(
				$pt,
				$key,
				array(
					'type'          => 'string',
					'single'        => true,
					'show_in_rest'  => true,
					'auth_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}
}
add_action( 'init', 'bbp_register_meta', 20 );

/**
 * Render meta box.
 *
 * @param WP_Post $post Post.
 */
function bbp_render_meta_box( $post ) {
	wp_nonce_field( 'bbp_save_meta', 'bbp_meta_nonce' );
	$fields = bbp_fields( $post->post_type );
	$groups = bbp_field_groups( $post->post_type );
	?>
	<div class="bbp-mb">
		<?php if ( count( $groups ) > 1 ) : ?>
			<div class="bbp-mb__tabs" role="tablist">
				<?php $i = 0; foreach ( $groups as $gkey => $glabel ) : ?>
					<button type="button" role="tab" class="bbp-mb__tab<?php echo 0 === $i ? ' is-active' : ''; ?>" data-tab="<?php echo esc_attr( $gkey ); ?>"><?php echo esc_html( $glabel ); ?></button>
				<?php $i++; endforeach; ?>
			</div>
		<?php endif; ?>
		<?php $i = 0; foreach ( $groups as $gkey => $glabel ) : ?>
			<div class="bbp-mb__panel<?php echo 0 === $i ? ' is-active' : ''; ?>" data-panel="<?php echo esc_attr( $gkey ); ?>">
				<div class="bbp-mb__grid">
				<?php
				foreach ( $fields as $key => $field ) {
					if ( $field['group'] !== $gkey ) {
						continue;
					}
					bbp_render_field( $post->ID, $key, $field );
				}
				?>
				</div>
			</div>
		<?php $i++; endforeach; ?>
	</div>
	<?php
}

/**
 * Render a single field.
 *
 * @param int    $post_id Post ID.
 * @param string $key     Key.
 * @param array  $field   Definition.
 */
function bbp_render_field( $post_id, $key, $field ) {
	$value = get_post_meta( $post_id, $key, true );
	$id    = 'bbp-' . $key;
	$wide  = in_array( $field['type'], array( 'textarea', 'gallery' ), true ) ? ' bbp-mb__field--wide' : '';
	$ph    = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
	echo '<div class="bbp-mb__field' . esc_attr( $wide ) . '">';
	if ( 'checkbox' !== $field['type'] ) {
		printf( '<label for="%1$s">%2$s</label>', esc_attr( $id ), esc_html( $field['label'] ) );
	}

	switch ( $field['type'] ) {
		case 'textarea':
			printf( '<textarea id="%1$s" name="%2$s" rows="4" placeholder="%3$s">%4$s</textarea>', esc_attr( $id ), esc_attr( $key ), esc_attr( $ph ), esc_textarea( $value ) );
			break;

		case 'select':
			printf( '<select id="%1$s" name="%2$s"><option value="">%3$s</option>', esc_attr( $id ), esc_attr( $key ), esc_html__( '— Select —', 'billboard-pro' ) );
			foreach ( $field['options'] as $ok => $ol ) {
				printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $ok ), selected( $value, $ok, false ), esc_html( $ol ) );
			}
			echo '</select>';
			break;

		case 'post_select':
			$posts = get_posts(
				array(
					'post_type'      => $field['post_type'],
					'posts_per_page' => 200,
					'orderby'        => 'title',
					'order'          => 'ASC',
					'post_status'    => 'publish',
				)
			);
			printf( '<select id="%1$s" name="%2$s"><option value="">%3$s</option>', esc_attr( $id ), esc_attr( $key ), esc_html__( '— None —', 'billboard-pro' ) );
			foreach ( $posts as $p ) {
				printf( '<option value="%1$d"%2$s>%3$s</option>', absint( $p->ID ), selected( (int) $value, $p->ID, false ), esc_html( $p->post_title ) );
			}
			echo '</select>';
			break;

		case 'checkbox':
			printf( '<label class="bbp-mb__check"><input type="checkbox" id="%1$s" name="%2$s" value="1"%3$s> %4$s</label>', esc_attr( $id ), esc_attr( $key ), checked( $value, '1', false ), esc_html( $field['label'] ) );
			break;

		case 'gallery':
			$ids = array_filter( array_map( 'absint', explode( ',', (string) $value ) ) );
			echo '<div class="bbp-gallery" data-target="' . esc_attr( $id ) . '">';
			echo '<ul class="bbp-gallery__list">';
			foreach ( $ids as $aid ) {
				$src = wp_get_attachment_image_url( $aid, 'thumbnail' );
				if ( $src ) {
					printf( '<li data-id="%1$d"><img src="%2$s" alt=""><button type="button" class="bbp-gallery__remove" aria-label="%3$s">×</button></li>', absint( $aid ), esc_url( $src ), esc_attr__( 'Remove image', 'billboard-pro' ) );
				}
			}
			echo '</ul>';
			printf( '<input type="hidden" id="%1$s" name="%2$s" value="%3$s">', esc_attr( $id ), esc_attr( $key ), esc_attr( implode( ',', $ids ) ) );
			printf( '<button type="button" class="button bbp-gallery__add">%s</button>', esc_html__( 'Add / Edit Images', 'billboard-pro' ) );
			echo '</div>';
			break;

		default:
			$type = in_array( $field['type'], array( 'number', 'url', 'email', 'date' ), true ) ? $field['type'] : 'text';
			$step = isset( $field['step'] ) ? ' step="' . esc_attr( $field['step'] ) . '"' : ( 'number' === $type ? ' step="any"' : '' );
			printf( '<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" placeholder="%5$s"%6$s>', esc_attr( $type ), esc_attr( $id ), esc_attr( $key ), esc_attr( $value ), esc_attr( $ph ), $step ); // phpcs:ignore WordPress.Security.EscapeOutput -- $step escaped above.
	}

	if ( ! empty( $field['help'] ) ) {
		printf( '<p class="description">%s</p>', esc_html( $field['help'] ) );
	}
	echo '</div>';
}

/**
 * Save meta.
 *
 * @param int $post_id Post ID.
 */
function bbp_save_meta( $post_id ) {
	if ( ! isset( $_POST['bbp_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['bbp_meta_nonce'] ), 'bbp_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$post_type = get_post_type( $post_id );
	foreach ( bbp_fields( $post_type ) as $key => $field ) {
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized per type below.
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		switch ( $field['type'] ) {
			case 'textarea':
				$val = sanitize_textarea_field( $raw );
				break;
			case 'number':
				$val = '' === $raw ? '' : (string) floatval( $raw );
				break;
			case 'url':
				$val = esc_url_raw( $raw );
				break;
			case 'email':
				$val = sanitize_email( $raw );
				break;
			case 'checkbox':
				$val = $raw ? '1' : '';
				break;
			case 'select':
				$val = array_key_exists( $raw, $field['options'] ) ? $raw : '';
				break;
			case 'post_select':
				$val = $raw ? (string) absint( $raw ) : '';
				break;
			case 'gallery':
				$val = implode( ',', array_filter( array_map( 'absint', explode( ',', $raw ) ) ) );
				break;
			case 'date':
				$val = preg_match( '/^\d{4}-\d{2}-\d{2}$/', $raw ) ? $raw : '';
				break;
			default:
				$val = sanitize_text_field( $raw );
		}
		if ( '' === $val ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $val );
		}
	}

	if ( 'billboard' === $post_type ) {
		bbp_sync_billboard_derived( $post_id );
	}
}
add_action( 'save_post', 'bbp_save_meta' );

/**
 * Compute derived meta used by filters: size class, area, availability default.
 *
 * @param int $post_id Post ID.
 */
function bbp_sync_billboard_derived( $post_id ) {
	$w    = (float) get_post_meta( $post_id, 'bb_width', true );
	$h    = (float) get_post_meta( $post_id, 'bb_height', true );
	$unit = get_post_meta( $post_id, 'bb_unit', true );
	$area = $w * $h;
	if ( 'ft' === $unit ) {
		$area = $area * 0.092903;
	}
	update_post_meta( $post_id, '_bb_area', round( $area, 2 ) );

	if ( ! get_post_meta( $post_id, 'bb_size', true ) && $area > 0 ) {
		if ( $area < 20 ) {
			$size = 'small';
		} elseif ( $area < 50 ) {
			$size = 'medium';
		} elseif ( $area < 100 ) {
			$size = 'large';
		} else {
			$size = 'super';
		}
		update_post_meta( $post_id, '_bb_size_auto', $size );
	} else {
		delete_post_meta( $post_id, '_bb_size_auto' );
	}

	if ( ! get_post_meta( $post_id, 'bb_availability', true ) ) {
		update_post_meta( $post_id, 'bb_availability', 'available' );
	}
	// Numeric price for sorting/filtering; empty = "on request" sorts last.
	$price = get_post_meta( $post_id, 'bb_price', true );
	update_post_meta( $post_id, '_bb_price_sort', '' === $price ? 999999999 : (float) $price );
}

/**
 * Effective size class.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function bbp_size_class( $post_id ) {
	$s = get_post_meta( $post_id, 'bb_size', true );
	return $s ? $s : get_post_meta( $post_id, '_bb_size_auto', true );
}

/**
 * Admin assets for meta boxes.
 *
 * @param string $hook Hook.
 */
function bbp_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, bbp_meta_post_types(), true ) ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'bbp-admin', BBP_URI . '/assets/css/admin.css', array(), BBP_VERSION );
	wp_enqueue_script( 'bbp-admin', BBP_URI . '/assets/js/admin.js', array( 'jquery' ), BBP_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'bbp_admin_assets' );
