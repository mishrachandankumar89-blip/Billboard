<?php
/**
 * Quote + contact forms: validation, spam protection, storage, email.
 * No third-party form plugin required.
 *
 * Integration hooks:
 *  - filter `bbp_form_fields`        modify field definitions.
 *  - filter `bbp_is_spam`            plug in Akismet / reCAPTCHA / Turnstile.
 *  - action `bbp_inquiry_submitted`  push to a CRM, Slack, webhook, etc.
 *
 * @package Billboard_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Field definitions for each form.
 *
 * @param string $form quote|contact.
 * @return array
 */
function bbp_form_fields( $form ) {
	$types = array( '' => __( 'Any type', 'billboard-pro' ) );
	$terms = get_terms( array( 'taxonomy' => 'billboard_type', 'hide_empty' => false ) );
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $t ) {
			$types[ $t->name ] = $t->name;
		}
	}
	if ( count( $types ) < 2 ) {
		$types += array(
			'LED Digital Billboard' => __( 'LED Digital Billboard', 'billboard-pro' ),
			'Static Billboard'      => __( 'Static Billboard', 'billboard-pro' ),
			'Mall Screen'           => __( 'Mall Screen', 'billboard-pro' ),
			'Transit Screen'        => __( 'Transit Screen', 'billboard-pro' ),
		);
	}

	if ( 'contact' === $form ) {
		$fields = array(
			'full_name' => array( 'label' => __( 'Full Name', 'billboard-pro' ), 'type' => 'text', 'required' => true, 'autocomplete' => 'name' ),
			'company'   => array( 'label' => __( 'Company', 'billboard-pro' ), 'type' => 'text', 'autocomplete' => 'organization' ),
			'email'     => array( 'label' => __( 'Email', 'billboard-pro' ), 'type' => 'email', 'required' => true, 'autocomplete' => 'email' ),
			'phone'     => array( 'label' => __( 'Phone', 'billboard-pro' ), 'type' => 'tel', 'autocomplete' => 'tel' ),
			'subject'   => array(
				'label'   => __( 'How can we help?', 'billboard-pro' ),
				'type'    => 'select',
				'options' => array(
					__( 'Advertise on an LED billboard', 'billboard-pro' ) => __( 'Advertise on an LED billboard', 'billboard-pro' ),
					__( 'Campaign planning assistance', 'billboard-pro' )  => __( 'Campaign planning assistance', 'billboard-pro' ),
					__( 'Partner / landowner enquiry', 'billboard-pro' )   => __( 'Partner / landowner enquiry', 'billboard-pro' ),
					__( 'Press & media', 'billboard-pro' )                 => __( 'Press & media', 'billboard-pro' ),
					__( 'Something else', 'billboard-pro' )                => __( 'Something else', 'billboard-pro' ),
				),
			),
			'message'   => array( 'label' => __( 'Message', 'billboard-pro' ), 'type' => 'textarea', 'required' => true, 'wide' => true ),
		);
	} else {
		$cities = array( '' => __( 'Select a city', 'billboard-pro' ) );
		$cterms = get_terms( array( 'taxonomy' => 'billboard_city', 'hide_empty' => false ) );
		if ( ! is_wp_error( $cterms ) ) {
			foreach ( $cterms as $t ) {
				$cities[ $t->name ] = $t->name;
			}
		}
		$fields = array(
			'full_name'   => array( 'label' => __( 'Full Name', 'billboard-pro' ), 'type' => 'text', 'required' => true, 'autocomplete' => 'name' ),
			'company'     => array( 'label' => __( 'Company', 'billboard-pro' ), 'type' => 'text', 'required' => true, 'autocomplete' => 'organization' ),
			'email'       => array( 'label' => __( 'Email', 'billboard-pro' ), 'type' => 'email', 'required' => true, 'autocomplete' => 'email' ),
			'phone'       => array( 'label' => __( 'Phone', 'billboard-pro' ), 'type' => 'tel', 'required' => true, 'autocomplete' => 'tel' ),
			'website'     => array( 'label' => __( 'Website', 'billboard-pro' ), 'type' => 'url', 'autocomplete' => 'url', 'placeholder' => 'https://' ),
			'city'        => count( $cities ) > 1
				? array( 'label' => __( 'Preferred City', 'billboard-pro' ), 'type' => 'select', 'options' => $cities )
				: array( 'label' => __( 'Preferred City', 'billboard-pro' ), 'type' => 'text' ),
			'location'    => array( 'label' => __( 'Preferred Location / Billboard', 'billboard-pro' ), 'type' => 'text' ),
			'type'        => array( 'label' => __( 'Billboard Type', 'billboard-pro' ), 'type' => 'select', 'options' => $types ),
			'start_date'  => array( 'label' => __( 'Campaign Start Date', 'billboard-pro' ), 'type' => 'date' ),
			'end_date'    => array( 'label' => __( 'Campaign End Date', 'billboard-pro' ), 'type' => 'date' ),
			'budget'      => array(
				'label'   => __( 'Estimated Budget', 'billboard-pro' ),
				'type'    => 'select',
				'options' => array(
					''               => __( 'Select a range', 'billboard-pro' ),
					'Under 5,000'    => __( 'Under 5,000', 'billboard-pro' ),
					'5,000–15,000'   => '5,000–15,000',
					'15,000–50,000'  => '15,000–50,000',
					'50,000–150,000' => '50,000–150,000',
					'150,000+'       => '150,000+',
				),
			),
			'quantity'    => array( 'label' => __( 'Number of Billboards', 'billboard-pro' ), 'type' => 'number', 'min' => 1, 'max' => 500 ),
			'message'     => array( 'label' => __( 'Message', 'billboard-pro' ), 'type' => 'textarea', 'wide' => true, 'placeholder' => __( 'Tell us about your brand, audience and campaign goals.', 'billboard-pro' ) ),
		);
	}
	return apply_filters( 'bbp_form_fields', $fields, $form );
}

/**
 * Render a form.
 *
 * @param string $form    quote|contact.
 * @param array  $prefill Prefilled values.
 */
function bbp_render_form( $form, $prefill = array() ) {
	$fields = bbp_form_fields( $form );
	$status = isset( $_GET['bbp_status'], $_GET['bbp_form'] ) && $form === $_GET['bbp_form'] ? sanitize_key( $_GET['bbp_status'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$label  = 'contact' === $form ? __( 'Send Message', 'billboard-pro' ) : __( 'Request My Quote', 'billboard-pro' );
	$uid    = 'bbp-' . $form . '-' . wp_rand( 100, 999 );
	?>
	<form class="bbp-form" id="<?php echo esc_attr( $uid ); ?>" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" novalidate data-bbp-form>
		<div class="form-alert form-alert--success" role="status" <?php echo 'success' === $status ? '' : 'hidden'; ?>>
			<?php bbp_the_icon( 'check', 20 ); ?>
			<span><?php echo 'contact' === $form ? esc_html__( 'Thank you — your message has been sent. We will reply within one business day.', 'billboard-pro' ) : esc_html__( 'Thank you — your quote request is in. A campaign planner will send availability and pricing within one business day.', 'billboard-pro' ); ?></span>
		</div>
		<div class="form-alert form-alert--error" role="alert" <?php echo 'error' === $status ? '' : 'hidden'; ?>>
			<span><?php esc_html_e( 'We could not send your request. Please check the highlighted fields and try again, or email us directly.', 'billboard-pro' ); ?></span>
		</div>

		<input type="hidden" name="action" value="bbp_submit">
		<input type="hidden" name="bbp_form" value="<?php echo esc_attr( $form ); ?>">
		<input type="hidden" name="bbp_ts" value="<?php echo esc_attr( bbp_sign_time() ); ?>">
		<input type="hidden" name="bbp_ref" value="<?php echo esc_url( get_permalink() ); ?>">
		<?php if ( ! empty( $prefill['billboard_id'] ) ) : ?>
			<input type="hidden" name="billboard_id" value="<?php echo absint( $prefill['billboard_id'] ); ?>">
		<?php endif; ?>
		<?php wp_nonce_field( 'bbp_submit_' . $form, 'bbp_nonce' ); ?>
		<div class="hp-field" aria-hidden="true">
			<label for="<?php echo esc_attr( $uid ); ?>-hp"><?php esc_html_e( 'Leave this field empty', 'billboard-pro' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $uid ); ?>-hp" name="bbp_hp" tabindex="-1" autocomplete="off">
		</div>

		<div class="form-grid">
			<?php
			foreach ( $fields as $name => $f ) :
				$id    = $uid . '-' . $name;
				$req   = ! empty( $f['required'] );
				$val   = isset( $prefill[ $name ] ) ? $prefill[ $name ] : '';
				$class = 'field' . ( ! empty( $f['wide'] ) ? ' field--wide' : '' );
				?>
				<div class="<?php echo esc_attr( $class ); ?>">
					<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $f['label'] ); ?><?php echo $req ? ' <span class="req" aria-hidden="true">*</span>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
					<?php
					$common = sprintf(
						'id="%1$s" name="%2$s"%3$s%4$s%5$s aria-describedby="%1$s-err"',
						esc_attr( $id ),
						esc_attr( $name ),
						$req ? ' required aria-required="true"' : '',
						! empty( $f['autocomplete'] ) ? ' autocomplete="' . esc_attr( $f['autocomplete'] ) . '"' : '',
						! empty( $f['placeholder'] ) ? ' placeholder="' . esc_attr( $f['placeholder'] ) . '"' : ''
					);
					if ( 'textarea' === $f['type'] ) {
						printf( '<textarea %1$s rows="5" maxlength="3000">%2$s</textarea>', $common, esc_textarea( $val ) ); // phpcs:ignore WordPress.Security.EscapeOutput
					} elseif ( 'select' === $f['type'] ) {
						printf( '<select %s>', $common ); // phpcs:ignore WordPress.Security.EscapeOutput
						foreach ( $f['options'] as $ov => $ol ) {
							printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $ov ), selected( $val, $ov, false ), esc_html( $ol ) );
						}
						echo '</select>';
					} else {
						$extra = '';
						if ( isset( $f['min'] ) ) {
							$extra .= ' min="' . absint( $f['min'] ) . '"';
						}
						if ( isset( $f['max'] ) ) {
							$extra .= ' max="' . absint( $f['max'] ) . '"';
						}
						if ( 'date' === $f['type'] ) {
							$extra .= ' min="' . esc_attr( wp_date( 'Y-m-d' ) ) . '"';
						}
						printf( '<input type="%1$s" %2$s value="%3$s" maxlength="200"%4$s>', esc_attr( $f['type'] ), $common, esc_attr( $val ), $extra ); // phpcs:ignore WordPress.Security.EscapeOutput
					}
					?>
					<p class="field-error" id="<?php echo esc_attr( $id ); ?>-err" aria-live="polite"></p>
				</div>
			<?php endforeach; ?>
			<div class="field field--wide field--consent">
				<label class="check">
					<input type="checkbox" name="consent" value="1" required aria-required="true" aria-describedby="<?php echo esc_attr( $uid ); ?>-consent-err">
					<span><?php esc_html_e( 'I agree to be contacted about my enquiry. We never share your details.', 'billboard-pro' ); ?> <span class="req" aria-hidden="true">*</span></span>
				</label>
				<p class="field-error" id="<?php echo esc_attr( $uid ); ?>-consent-err" aria-live="polite"></p>
			</div>
		</div>
		<button type="submit" class="btn btn--primary btn--lg btn--block">
			<span class="btn__label"><?php echo esc_html( $label ); ?></span>
			<?php bbp_the_icon( 'arrow', 18 ); ?>
		</button>
		<p class="form-note"><?php esc_html_e( 'Typical response time: under 24 hours on business days.', 'billboard-pro' ); ?></p>
	</form>
	<?php
}

/**
 * Signed timestamp for a time-trap (bots submit instantly).
 *
 * @return string
 */
function bbp_sign_time() {
	$t = time();
	return $t . '.' . substr( wp_hash( 'bbp_ts' . $t ), 0, 12 );
}

/**
 * Validate a signed timestamp.
 *
 * @param string $value Value.
 * @return bool
 */
function bbp_check_time( $value ) {
	$parts = explode( '.', (string) $value );
	if ( 2 !== count( $parts ) ) {
		return false;
	}
	$t = absint( $parts[0] );
	if ( ! hash_equals( substr( wp_hash( 'bbp_ts' . $t ), 0, 12 ), $parts[1] ) ) {
		return false;
	}
	$age = time() - $t;
	return $age >= 3 && $age < DAY_IN_SECONDS * 2;
}

/**
 * Handle submission (admin-post + AJAX).
 */
function bbp_handle_submit() {
	$is_ajax = wp_doing_ajax();
	// phpcs:disable WordPress.Security.NonceVerification -- verified below.
	$form = isset( $_POST['bbp_form'] ) && 'contact' === $_POST['bbp_form'] ? 'contact' : 'quote';
	$ref  = isset( $_POST['bbp_ref'] ) ? esc_url_raw( wp_unslash( $_POST['bbp_ref'] ) ) : home_url( '/' );
	$ref  = wp_validate_redirect( $ref, home_url( '/' ) );
	// phpcs:enable

	$fail = function ( $errors = array(), $code = 400 ) use ( $is_ajax, $form, $ref ) {
		if ( $is_ajax ) {
			wp_send_json_error( array( 'errors' => $errors ), $code );
		}
		wp_safe_redirect( add_query_arg( array( 'bbp_status' => 'error', 'bbp_form' => $form ), $ref ) . '#form' );
		exit;
	};

	if ( ! isset( $_POST['bbp_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['bbp_nonce'] ), 'bbp_submit_' . $form ) ) {
		$fail( array( '_form' => __( 'Your session expired. Please reload the page and try again.', 'billboard-pro' ) ), 403 );
	}

	// Spam: honeypot + time trap + rate limit + extensible filter.
	$hp   = isset( $_POST['bbp_hp'] ) ? sanitize_text_field( wp_unslash( $_POST['bbp_hp'] ) ) : '';
	$ts   = isset( $_POST['bbp_ts'] ) ? sanitize_text_field( wp_unslash( $_POST['bbp_ts'] ) ) : '';
	$ip   = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$rkey = 'bbp_rl_' . md5( $ip );
	$hits = (int) get_transient( $rkey );
	$spam = '' !== $hp || ! bbp_check_time( $ts ) || $hits >= 6;
	if ( apply_filters( 'bbp_is_spam', $spam, $_POST, $form ) ) {
		$fail( array( '_form' => __( 'Your submission could not be verified. Please wait a few seconds and try again.', 'billboard-pro' ) ), 429 );
	}

	$fields = bbp_form_fields( $form );
	$data   = array();
	$errors = array();
	foreach ( $fields as $name => $f ) {
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized below.
		$raw = isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : '';
		switch ( $f['type'] ) {
			case 'textarea':
				$val = sanitize_textarea_field( $raw );
				$val = mb_substr( $val, 0, 3000 );
				break;
			case 'email':
				$val = sanitize_email( $raw );
				if ( '' !== trim( (string) $raw ) && ! is_email( $val ) ) {
					$errors[ $name ] = __( 'Please enter a valid email address.', 'billboard-pro' );
				}
				break;
			case 'url':
				$val = esc_url_raw( $raw );
				break;
			case 'number':
				$val = '' === $raw ? '' : (string) absint( $raw );
				break;
			case 'date':
				$val = preg_match( '/^\d{4}-\d{2}-\d{2}$/', (string) $raw ) ? $raw : '';
				break;
			case 'select':
				$val = sanitize_text_field( $raw );
				if ( '' !== $val && ! array_key_exists( $val, $f['options'] ) ) {
					$val = '';
				}
				break;
			default:
				$val = mb_substr( sanitize_text_field( $raw ), 0, 200 );
		}
		if ( ! empty( $f['required'] ) && '' === trim( (string) $val ) && ! isset( $errors[ $name ] ) ) {
			$errors[ $name ] = __( 'This field is required.', 'billboard-pro' );
		}
		$data[ $name ] = $val;
	}
	if ( ! empty( $data['start_date'] ) && ! empty( $data['end_date'] ) && $data['end_date'] < $data['start_date'] ) {
		$errors['end_date'] = __( 'End date must be after the start date.', 'billboard-pro' );
	}
	if ( empty( $_POST['consent'] ) ) {
		$errors['consent'] = __( 'Please confirm we may contact you.', 'billboard-pro' );
	}
	if ( $errors ) {
		$fail( $errors, 422 );
	}

	$billboard_id = isset( $_POST['billboard_id'] ) ? absint( $_POST['billboard_id'] ) : 0;
	if ( $billboard_id && 'billboard' === get_post_type( $billboard_id ) ) {
		$data['billboard'] = get_the_title( $billboard_id ) . ' (#' . $billboard_id . ')';
	}
	$data['source_page'] = $ref;

	set_transient( $rkey, $hits + 1, 10 * MINUTE_IN_SECONDS );

	$type_label = 'contact' === $form ? __( 'Contact', 'billboard-pro' ) : __( 'Quote', 'billboard-pro' );
	$inquiry_id = wp_insert_post(
		array(
			'post_type'   => 'bb_inquiry',
			'post_status' => 'private',
			/* translators: 1: form type, 2: name, 3: company */
			'post_title'  => sprintf( '%1$s — %2$s%3$s', $type_label, $data['full_name'], ! empty( $data['company'] ) ? ' (' . $data['company'] . ')' : '' ),
		),
		true
	);
	if ( is_wp_error( $inquiry_id ) ) {
		$fail( array( '_form' => __( 'Server error. Please email us directly.', 'billboard-pro' ) ), 500 );
	}
	update_post_meta( $inquiry_id, '_bbp_form', $form );
	update_post_meta( $inquiry_id, '_bbp_data', $data );
	update_post_meta( $inquiry_id, '_bbp_status', 'new' );
	if ( $billboard_id ) {
		update_post_meta( $inquiry_id, '_bbp_billboard_id', $billboard_id );
	}

	bbp_send_notification( $inquiry_id, $form, $data, $fields );
	do_action( 'bbp_inquiry_submitted', $inquiry_id, $data, $form );

	if ( $is_ajax ) {
		wp_send_json_success( array( 'id' => $inquiry_id ) );
	}
	wp_safe_redirect( add_query_arg( array( 'bbp_status' => 'success', 'bbp_form' => $form ), $ref ) . '#form' );
	exit;
}
add_action( 'admin_post_bbp_submit', 'bbp_handle_submit' );
add_action( 'admin_post_nopriv_bbp_submit', 'bbp_handle_submit' );
add_action( 'wp_ajax_bbp_submit', 'bbp_handle_submit' );
add_action( 'wp_ajax_nopriv_bbp_submit', 'bbp_handle_submit' );

/**
 * Email the team + auto-reply to the sender.
 *
 * @param int    $id     Inquiry ID.
 * @param string $form   Form.
 * @param array  $data   Data.
 * @param array  $fields Field defs.
 */
function bbp_send_notification( $id, $form, $data, $fields ) {
	$to   = bbp_opt( 'notify_email' );
	$to   = is_email( $to ) ? $to : get_option( 'admin_email' );
	$site = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	/* translators: 1: site name, 2: form type, 3: name */
	$subject = sprintf( __( '[%1$s] New %2$s request from %3$s', 'billboard-pro' ), $site, $form, $data['full_name'] );
	$lines   = array();
	foreach ( $data as $k => $v ) {
		if ( '' === $v ) {
			continue;
		}
		$label   = isset( $fields[ $k ]['label'] ) ? $fields[ $k ]['label'] : ucwords( str_replace( '_', ' ', $k ) );
		$lines[] = $label . ': ' . $v;
	}
	$lines[] = '';
	$lines[] = __( 'View in dashboard:', 'billboard-pro' ) . ' ' . admin_url( 'post.php?post=' . $id . '&action=edit' );
	$headers = array();
	if ( ! empty( $data['email'] ) && is_email( $data['email'] ) ) {
		$headers[] = 'Reply-To: ' . str_replace( array( "\r", "\n" ), '', $data['full_name'] ) . ' <' . $data['email'] . '>';
	}
	wp_mail( $to, $subject, implode( "\n", $lines ), $headers );

	if ( ! empty( $data['email'] ) && is_email( $data['email'] ) && apply_filters( 'bbp_send_autoreply', true, $form ) ) {
		/* translators: %s: site name */
		$reply_subject = sprintf( __( 'We received your request — %s', 'billboard-pro' ), $site );
		/* translators: 1: name, 2: site name */
		$reply_body = sprintf( __( "Hi %1\$s,\n\nThanks for contacting %2\$s. A campaign planner is reviewing your request and will get back to you within one business day with available screens and pricing.\n\nBest regards,\n%2\$s", 'billboard-pro' ), $data['full_name'], $site );
		wp_mail( $data['email'], $reply_subject, $reply_body );
	}
}
