<?php
/**
 * Customizer: brand, homepage content, contact details, settings.
 * Appearance → Customize → "Billboard Pro".
 *
 * @package Billboard_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default option values.
 *
 * @return array
 */
function bbp_option_defaults() {
	static $d = null;
	if ( null !== $d ) {
		return $d;
	}
	$d = array(
		// Colors.
		'color_primary'   => '#ff5a1f',
		'color_accent'    => '#ffc24b',
		'color_dark'      => '#0a0c10',
		'color_light'     => '#ffffff',
		'color_gray'      => '#6b7280',
		'color_cta'       => '#ff5a1f',
		'google_fonts'    => true,
		// Hero.
		'hero_eyebrow'    => __( 'LED Billboard Screens & Digital Outdoor', 'billboard-pro' ),
		'hero_title'      => __( 'Make your brand impossible to miss.', 'billboard-pro' ),
		'hero_text'       => __( 'Premium LED billboard screens and digital outdoor locations that put your brand in front of the right audience — bright in full sun, scheduled to the minute, seen by millions.', 'billboard-pro' ),
		'hero_cta1'       => __( 'View Available Billboards', 'billboard-pro' ),
		'hero_cta2'       => __( 'Request a Quote', 'billboard-pro' ),
		'hero_image'      => '',
		'hero_video'      => '',
		// Stats.
		'stat1_num'       => '150+',
		'stat1_label'     => __( 'LED & static locations', 'billboard-pro' ),
		'stat2_num'       => '25+',
		'stat2_label'     => __( 'Cities covered', 'billboard-pro' ),
		'stat3_num'       => '10M+',
		'stat3_label'     => __( 'Monthly impressions', 'billboard-pro' ),
		'stat4_num'       => '500+',
		'stat4_label'     => __( 'Campaigns delivered', 'billboard-pro' ),
		// Digital section.
		'digital_title'   => __( 'LED billboard screens built for attention.', 'billboard-pro' ),
		'digital_text'    => __( 'Our LED billboards are large-format digital displays engineered for outdoor advertising. Compared with static boards or conventional lightboxes they play dynamic content, stay readable in direct sunlight and let you change creative in minutes — along highways, urban arterials, commercial districts and public spaces where reach matters most.', 'billboard-pro' ),
		'digital_image'   => '',
		// Final CTA.
		'cta_title'       => __( 'Ready to get your brand seen?', 'billboard-pro' ),
		'cta_text'        => __( 'Tell us your city, dates and budget. A campaign planner will send available LED screens and pricing within one business day.', 'billboard-pro' ),
		// Contact.
		'phone'           => '+1 (555) 010-2030',
		'email'           => 'hello@example.com',
		'whatsapp'        => '',
		'address'         => __( '350 Fifth Avenue, Suite 4800, New York, NY 10118', 'billboard-pro' ),
		'hours'           => __( 'Mon–Fri, 9:00–18:00', 'billboard-pro' ),
		'notify_email'    => '',
		// Social.
		'social_linkedin'  => '',
		'social_instagram' => '',
		'social_facebook'  => '',
		'social_x'         => '',
		'social_youtube'   => '',
		// Misc.
		'currency'          => '$',
		'currency_position' => 'before',
		'map_embed'         => true,
		'footer_about'      => __( 'We own and operate premium LED billboard screens and outdoor advertising locations, and plan campaigns that get brands seen by millions every month.', 'billboard-pro' ),
		'copyright'         => '',
		'ga4_id'            => '',
	);
	return $d;
}

/**
 * Register Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function bbp_customize_register( $wp_customize ) {
	$d = bbp_option_defaults();

	$wp_customize->add_panel(
		'bbp_panel',
		array(
			'title'    => __( 'Billboard Pro', 'billboard-pro' ),
			'priority' => 30,
		)
	);

	$sections = array(
		'bbp_brand'   => __( 'Brand Colors & Fonts', 'billboard-pro' ),
		'bbp_hero'    => __( 'Homepage: Hero', 'billboard-pro' ),
		'bbp_stats'   => __( 'Homepage: Stats', 'billboard-pro' ),
		'bbp_digital' => __( 'Homepage: LED Screens Section', 'billboard-pro' ),
		'bbp_cta'     => __( 'Homepage: Final CTA', 'billboard-pro' ),
		'bbp_contact' => __( 'Contact Details', 'billboard-pro' ),
		'bbp_social'  => __( 'Social Links', 'billboard-pro' ),
		'bbp_general' => __( 'General Settings', 'billboard-pro' ),
	);
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section( $id, array( 'title' => $title, 'panel' => 'bbp_panel' ) );
	}

	$add = function ( $key, $section, $label, $type = 'text', $extra = array() ) use ( $wp_customize, $d ) {
		$sanitize = 'sanitize_text_field';
		if ( 'textarea' === $type ) {
			$sanitize = 'sanitize_textarea_field';
		} elseif ( 'url' === $type ) {
			$sanitize = 'esc_url_raw';
		} elseif ( 'email' === $type ) {
			$sanitize = 'sanitize_email';
		} elseif ( 'checkbox' === $type ) {
			$sanitize = 'bbp_sanitize_checkbox';
		} elseif ( 'color' === $type ) {
			$sanitize = 'sanitize_hex_color';
		} elseif ( in_array( $type, array( 'image', 'page' ), true ) ) {
			$sanitize = 'absint';
		}
		$wp_customize->add_setting(
			'bbp_' . $key,
			array(
				'default'           => isset( $d[ $key ] ) ? $d[ $key ] : '',
				'sanitize_callback' => $sanitize,
				'transport'         => 'refresh',
			)
		);
		$args = array_merge( array( 'label' => $label, 'section' => $section ), $extra );
		if ( 'color' === $type ) {
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bbp_' . $key, $args ) );
		} elseif ( 'image' === $type ) {
			$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'bbp_' . $key, array_merge( $args, array( 'mime_type' => 'image' ) ) ) );
		} elseif ( 'video' === $type ) {
			$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'bbp_' . $key, array_merge( $args, array( 'mime_type' => 'video' ) ) ) );
		} elseif ( 'page' === $type ) {
			$wp_customize->add_control( 'bbp_' . $key, array_merge( $args, array( 'type' => 'dropdown-pages' ) ) );
		} else {
			$wp_customize->add_control( 'bbp_' . $key, array_merge( $args, array( 'type' => $type ) ) );
		}
	};

	// Brand.
	$add( 'color_primary', 'bbp_brand', __( 'Primary brand color', 'billboard-pro' ), 'color' );
	$add( 'color_accent', 'bbp_brand', __( 'Secondary accent', 'billboard-pro' ), 'color' );
	$add( 'color_dark', 'bbp_brand', __( 'Dark background', 'billboard-pro' ), 'color' );
	$add( 'color_light', 'bbp_brand', __( 'Light background', 'billboard-pro' ), 'color' );
	$add( 'color_gray', 'bbp_brand', __( 'Neutral gray', 'billboard-pro' ), 'color' );
	$add( 'color_cta', 'bbp_brand', __( 'CTA button color', 'billboard-pro' ), 'color' );
	$add( 'google_fonts', 'bbp_brand', __( 'Load Inter + Manrope from Google Fonts (uncheck for system fonts only)', 'billboard-pro' ), 'checkbox' );

	// Hero.
	$add( 'hero_eyebrow', 'bbp_hero', __( 'Eyebrow', 'billboard-pro' ) );
	$add( 'hero_title', 'bbp_hero', __( 'Headline', 'billboard-pro' ) );
	$add( 'hero_text', 'bbp_hero', __( 'Supporting text', 'billboard-pro' ), 'textarea' );
	$add( 'hero_cta1', 'bbp_hero', __( 'Primary button label', 'billboard-pro' ) );
	$add( 'hero_cta2', 'bbp_hero', __( 'Secondary button label', 'billboard-pro' ) );
	$add( 'hero_image', 'bbp_hero', __( 'Background image', 'billboard-pro' ), 'image' );
	$add( 'hero_video', 'bbp_hero', __( 'Background video (MP4, optional, muted loop)', 'billboard-pro' ), 'video' );

	// Stats.
	for ( $i = 1; $i <= 4; $i++ ) {
		/* translators: %d: stat number */
		$add( "stat{$i}_num", 'bbp_stats', sprintf( __( 'Stat %d value', 'billboard-pro' ), $i ) );
		/* translators: %d: stat number */
		$add( "stat{$i}_label", 'bbp_stats', sprintf( __( 'Stat %d label', 'billboard-pro' ), $i ) );
	}

	// Digital.
	$add( 'digital_title', 'bbp_digital', __( 'Title', 'billboard-pro' ) );
	$add( 'digital_text', 'bbp_digital', __( 'Text', 'billboard-pro' ), 'textarea' );
	$add( 'digital_image', 'bbp_digital', __( 'Image', 'billboard-pro' ), 'image' );

	// CTA.
	$add( 'cta_title', 'bbp_cta', __( 'Title', 'billboard-pro' ) );
	$add( 'cta_text', 'bbp_cta', __( 'Text', 'billboard-pro' ), 'textarea' );

	// Contact.
	$add( 'phone', 'bbp_contact', __( 'Phone', 'billboard-pro' ) );
	$add( 'email', 'bbp_contact', __( 'Public email', 'billboard-pro' ), 'email' );
	$add( 'whatsapp', 'bbp_contact', __( 'WhatsApp number (digits with country code, optional)', 'billboard-pro' ) );
	$add( 'address', 'bbp_contact', __( 'Office address', 'billboard-pro' ), 'textarea' );
	$add( 'hours', 'bbp_contact', __( 'Office hours', 'billboard-pro' ) );
	$add( 'notify_email', 'bbp_contact', __( 'Send form notifications to (defaults to admin email)', 'billboard-pro' ), 'email' );

	// Social.
	foreach ( array( 'linkedin' => 'LinkedIn', 'instagram' => 'Instagram', 'facebook' => 'Facebook', 'x' => 'X / Twitter', 'youtube' => 'YouTube' ) as $k => $l ) {
		$add( 'social_' . $k, 'bbp_social', $l . ' URL', 'url' );
	}

	// General.
	$add( 'page_quote', 'bbp_general', __( 'Quote request page', 'billboard-pro' ), 'page' );
	$add( 'page_contact', 'bbp_general', __( 'Contact page', 'billboard-pro' ), 'page' );
	$add( 'currency', 'bbp_general', __( 'Currency symbol', 'billboard-pro' ) );
	$add( 'currency_position', 'bbp_general', __( 'Currency position', 'billboard-pro' ), 'select', array( 'choices' => array( 'before' => __( 'Before amount', 'billboard-pro' ), 'after' => __( 'After amount', 'billboard-pro' ) ) ) );
	$add( 'map_embed', 'bbp_general', __( 'Show OpenStreetMap embeds when coordinates exist', 'billboard-pro' ), 'checkbox' );
	$add( 'ga4_id', 'bbp_general', __( 'Google Analytics 4 Measurement ID (G-XXXX)', 'billboard-pro' ) );
	$add( 'footer_about', 'bbp_general', __( 'Footer about text', 'billboard-pro' ), 'textarea' );
	$add( 'copyright', 'bbp_general', __( 'Copyright text (optional)', 'billboard-pro' ) );
}
add_action( 'customize_register', 'bbp_customize_register' );

/**
 * Checkbox sanitizer.
 *
 * @param mixed $v Value.
 * @return bool
 */
function bbp_sanitize_checkbox( $v ) {
	return (bool) $v;
}

/**
 * CSS variables from Customizer colors.
 *
 * @return string
 */
function bbp_customizer_css() {
	$map = array(
		'--c-primary' => 'color_primary',
		'--c-accent'  => 'color_accent',
		'--c-dark'    => 'color_dark',
		'--c-light'   => 'color_light',
		'--c-gray'    => 'color_gray',
		'--c-cta'     => 'color_cta',
	);
	$css = ':root{';
	foreach ( $map as $var => $key ) {
		$val = sanitize_hex_color( bbp_opt( $key ) );
		if ( $val ) {
			$css .= $var . ':' . $val . ';';
		}
	}
	$css .= '}';
	if ( ! bbp_opt( 'google_fonts' ) ) {
		$css .= ':root{--f-body:system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;--f-head:var(--f-body)}';
	}
	return $css;
}

/**
 * Image URL from an attachment-ID theme mod, else fallback.
 *
 * @param string $key      Option key.
 * @param string $fallback Fallback bundled image file.
 * @param string $size     Size.
 * @return string
 */
function bbp_opt_image( $key, $fallback, $size = 'full' ) {
	$id = absint( bbp_opt( $key ) );
	if ( $id ) {
		$url = wp_get_attachment_image_url( $id, $size );
		if ( $url ) {
			return $url;
		}
	}
	return bbp_img( $fallback );
}
