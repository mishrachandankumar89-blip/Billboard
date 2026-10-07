<?php
/**
 * Central field definitions for every custom post type.
 *
 * Each field: key => array( label, type, group, options?, placeholder?, help? )
 * Types: text, textarea, number, select, url, email, date, gallery, checkbox, post_select.
 *
 * @package Billboard_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Availability statuses.
 *
 * @return array
 */
function bbp_availability_options() {
	return array(
		'available'   => __( 'Available', 'billboard-pro' ),
		'reserved'    => __( 'Reserved', 'billboard-pro' ),
		'booked'      => __( 'Booked', 'billboard-pro' ),
		'maintenance' => __( 'Maintenance', 'billboard-pro' ),
		'unavailable' => __( 'Temporarily Unavailable', 'billboard-pro' ),
	);
}

/**
 * Size classes used for filtering.
 *
 * @return array
 */
function bbp_size_options() {
	return array(
		'small'  => __( 'Small (under 20 m²)', 'billboard-pro' ),
		'medium' => __( 'Medium (20–50 m²)', 'billboard-pro' ),
		'large'  => __( 'Large (50–100 m²)', 'billboard-pro' ),
		'super'  => __( 'Spectacular (100 m²+)', 'billboard-pro' ),
	);
}

/**
 * Traffic levels used for filtering.
 *
 * @return array
 */
function bbp_traffic_options() {
	return array(
		'low'       => __( 'Low', 'billboard-pro' ),
		'medium'    => __( 'Medium', 'billboard-pro' ),
		'high'      => __( 'High', 'billboard-pro' ),
		'very-high' => __( 'Very High', 'billboard-pro' ),
	);
}

/**
 * Formats.
 *
 * @return array
 */
function bbp_format_options() {
	return array(
		'digital' => __( 'Digital', 'billboard-pro' ),
		'static'  => __( 'Static', 'billboard-pro' ),
	);
}

/**
 * Field groups per post type.
 *
 * @param string $post_type Post type.
 * @return array
 */
function bbp_field_groups( $post_type ) {
	$groups = array(
		'billboard'     => array(
			'basic'    => __( 'Basic Information', 'billboard-pro' ),
			'location' => __( 'Location', 'billboard-pro' ),
			'details'  => __( 'Billboard Details', 'billboard-pro' ),
			'audience' => __( 'Audience Information', 'billboard-pro' ),
			'pricing'  => __( 'Pricing & Booking', 'billboard-pro' ),
			'cta'      => __( 'Call to Action', 'billboard-pro' ),
		),
		'bb_location'   => array(
			'basic'    => __( 'Location Details', 'billboard-pro' ),
			'audience' => __( 'Traffic & Audience', 'billboard-pro' ),
		),
		'bb_case_study' => array(
			'basic'   => __( 'Campaign', 'billboard-pro' ),
			'results' => __( 'Results & Testimonial', 'billboard-pro' ),
		),
		'bb_testimonial' => array(
			'basic' => __( 'Testimonial Details', 'billboard-pro' ),
		),
	);
	return isset( $groups[ $post_type ] ) ? $groups[ $post_type ] : array();
}

/**
 * Fields per post type.
 *
 * @param string $post_type Post type.
 * @return array
 */
function bbp_fields( $post_type ) {
	switch ( $post_type ) {
		case 'billboard':
			$fields = array(
				// Basic.
				'bb_code'             => array( 'label' => __( 'Billboard ID', 'billboard-pro' ), 'type' => 'text', 'group' => 'basic', 'placeholder' => 'NYC-001' ),
				'bb_availability'     => array( 'label' => __( 'Availability Status', 'billboard-pro' ), 'type' => 'select', 'group' => 'basic', 'options' => bbp_availability_options() ),
				'bb_available_from'   => array( 'label' => __( 'Next Available Date', 'billboard-pro' ), 'type' => 'date', 'group' => 'basic' ),
				'bb_featured'         => array( 'label' => __( 'Feature on homepage', 'billboard-pro' ), 'type' => 'checkbox', 'group' => 'basic' ),
				'bb_gallery'          => array( 'label' => __( 'Gallery', 'billboard-pro' ), 'type' => 'gallery', 'group' => 'basic', 'help' => __( 'Short description uses the post Excerpt. Main photo uses the Featured Image.', 'billboard-pro' ) ),
				// Location.
				'bb_address'          => array( 'label' => __( 'Street Address', 'billboard-pro' ), 'type' => 'text', 'group' => 'location' ),
				'bb_state'            => array( 'label' => __( 'State / Region', 'billboard-pro' ), 'type' => 'text', 'group' => 'location' ),
				'bb_country'          => array( 'label' => __( 'Country', 'billboard-pro' ), 'type' => 'text', 'group' => 'location' ),
				'bb_zip'              => array( 'label' => __( 'ZIP / Postal Code', 'billboard-pro' ), 'type' => 'text', 'group' => 'location' ),
				'bb_lat'              => array( 'label' => __( 'Latitude', 'billboard-pro' ), 'type' => 'text', 'group' => 'location', 'placeholder' => '40.7580' ),
				'bb_lng'              => array( 'label' => __( 'Longitude', 'billboard-pro' ), 'type' => 'text', 'group' => 'location', 'placeholder' => '-73.9855' ),
				'bb_landmark'         => array( 'label' => __( 'Nearby Landmarks', 'billboard-pro' ), 'type' => 'textarea', 'group' => 'location', 'help' => __( 'One per line.', 'billboard-pro' ) ),
				'bb_location_id'      => array( 'label' => __( 'Parent Location', 'billboard-pro' ), 'type' => 'post_select', 'group' => 'location', 'post_type' => 'bb_location' ),
				// Details.
				'bb_format'           => array( 'label' => __( 'Digital / Static', 'billboard-pro' ), 'type' => 'select', 'group' => 'details', 'options' => bbp_format_options() ),
				'bb_width'            => array( 'label' => __( 'Width', 'billboard-pro' ), 'type' => 'number', 'group' => 'details', 'step' => '0.1' ),
				'bb_height'           => array( 'label' => __( 'Height', 'billboard-pro' ), 'type' => 'number', 'group' => 'details', 'step' => '0.1' ),
				'bb_unit'             => array( 'label' => __( 'Dimension Unit', 'billboard-pro' ), 'type' => 'select', 'group' => 'details', 'options' => array( 'm' => 'm', 'ft' => 'ft' ) ),
				'bb_size'             => array( 'label' => __( 'Size Class', 'billboard-pro' ), 'type' => 'select', 'group' => 'details', 'options' => bbp_size_options(), 'help' => __( 'Leave empty to calculate automatically from width × height.', 'billboard-pro' ) ),
				'bb_orientation'      => array( 'label' => __( 'Orientation', 'billboard-pro' ), 'type' => 'select', 'group' => 'details', 'options' => array( 'landscape' => __( 'Landscape', 'billboard-pro' ), 'portrait' => __( 'Portrait', 'billboard-pro' ), 'square' => __( 'Square', 'billboard-pro' ) ) ),
				'bb_resolution'       => array( 'label' => __( 'Resolution (digital)', 'billboard-pro' ), 'type' => 'text', 'group' => 'details', 'placeholder' => '1920 × 1080 px' ),
				'bb_illumination'     => array( 'label' => __( 'Illumination', 'billboard-pro' ), 'type' => 'text', 'group' => 'details', 'placeholder' => 'LED backlit / Front-lit / None' ),
				'bb_installation'     => array( 'label' => __( 'Installation Type', 'billboard-pro' ), 'type' => 'text', 'group' => 'details', 'placeholder' => 'Monopole / Rooftop / Wall-mounted' ),
				'bb_viewing'          => array( 'label' => __( 'Viewing Direction', 'billboard-pro' ), 'type' => 'text', 'group' => 'details', 'placeholder' => 'Northbound traffic' ),
				'bb_slot_length'      => array( 'label' => __( 'Ad Slot Length (digital)', 'billboard-pro' ), 'type' => 'text', 'group' => 'details', 'placeholder' => '10 seconds, 6 advertisers per loop' ),
				// Audience.
				'bb_daily_impressions'   => array( 'label' => __( 'Estimated Daily Impressions', 'billboard-pro' ), 'type' => 'number', 'group' => 'audience' ),
				'bb_monthly_impressions' => array( 'label' => __( 'Estimated Monthly Impressions', 'billboard-pro' ), 'type' => 'number', 'group' => 'audience', 'help' => __( 'Leave empty to use daily × 30.', 'billboard-pro' ) ),
				'bb_traffic_count'    => array( 'label' => __( 'Daily Traffic Count', 'billboard-pro' ), 'type' => 'number', 'group' => 'audience' ),
				'bb_traffic_level'    => array( 'label' => __( 'Traffic Level', 'billboard-pro' ), 'type' => 'select', 'group' => 'audience', 'options' => bbp_traffic_options() ),
				'bb_demographics'     => array( 'label' => __( 'Audience Demographics', 'billboard-pro' ), 'type' => 'textarea', 'group' => 'audience' ),
				'bb_visibility'       => array( 'label' => __( 'Visibility Distance', 'billboard-pro' ), 'type' => 'text', 'group' => 'audience', 'placeholder' => '300 m' ),
				'bb_view_time'        => array( 'label' => __( 'Average Viewing Time', 'billboard-pro' ), 'type' => 'text', 'group' => 'audience', 'placeholder' => '8 seconds' ),
				// Pricing.
				'bb_price'            => array( 'label' => __( 'Starting Price', 'billboard-pro' ), 'type' => 'number', 'group' => 'pricing' ),
				'bb_price_unit'       => array( 'label' => __( 'Price Unit', 'billboard-pro' ), 'type' => 'select', 'group' => 'pricing', 'options' => array( 'week' => __( 'per week', 'billboard-pro' ), 'month' => __( 'per month', 'billboard-pro' ), '4weeks' => __( 'per 4 weeks', 'billboard-pro' ), 'day' => __( 'per day', 'billboard-pro' ), 'spot' => __( 'per spot', 'billboard-pro' ) ) ),
				'bb_campaign_duration' => array( 'label' => __( 'Typical Campaign Duration', 'billboard-pro' ), 'type' => 'text', 'group' => 'pricing', 'placeholder' => '4–12 weeks' ),
				'bb_min_booking'      => array( 'label' => __( 'Minimum Booking Duration', 'billboard-pro' ), 'type' => 'text', 'group' => 'pricing', 'placeholder' => '2 weeks' ),
				// CTA.
				'bb_cta'              => array( 'label' => __( 'Primary CTA', 'billboard-pro' ), 'type' => 'select', 'group' => 'cta', 'options' => array( 'quote' => __( 'Request Quote', 'billboard-pro' ), 'availability' => __( 'Check Availability', 'billboard-pro' ), 'contact' => __( 'Contact Us', 'billboard-pro' ) ) ),
				'bb_cta_label'        => array( 'label' => __( 'Custom CTA Label (optional)', 'billboard-pro' ), 'type' => 'text', 'group' => 'cta' ),
			);
			break;

		case 'bb_location':
			$fields = array(
				'loc_city'      => array( 'label' => __( 'City', 'billboard-pro' ), 'type' => 'text', 'group' => 'basic', 'help' => __( 'Billboards in the matching City term are shown on this page automatically.', 'billboard-pro' ) ),
				'loc_state'     => array( 'label' => __( 'State / Region', 'billboard-pro' ), 'type' => 'text', 'group' => 'basic' ),
				'loc_address'   => array( 'label' => __( 'Address', 'billboard-pro' ), 'type' => 'text', 'group' => 'basic' ),
				'loc_lat'       => array( 'label' => __( 'Latitude', 'billboard-pro' ), 'type' => 'text', 'group' => 'basic' ),
				'loc_lng'       => array( 'label' => __( 'Longitude', 'billboard-pro' ), 'type' => 'text', 'group' => 'basic' ),
				'loc_gallery'   => array( 'label' => __( 'Gallery', 'billboard-pro' ), 'type' => 'gallery', 'group' => 'basic' ),
				'loc_traffic'   => array( 'label' => __( 'Traffic Information', 'billboard-pro' ), 'type' => 'textarea', 'group' => 'audience' ),
				'loc_audience'  => array( 'label' => __( 'Audience Information', 'billboard-pro' ), 'type' => 'textarea', 'group' => 'audience' ),
				'loc_landmarks' => array( 'label' => __( 'Nearby Landmarks', 'billboard-pro' ), 'type' => 'textarea', 'group' => 'audience', 'help' => __( 'One per line.', 'billboard-pro' ) ),
			);
			break;

		case 'bb_case_study':
			$fields = array(
				'cs_client'      => array( 'label' => __( 'Client', 'billboard-pro' ), 'type' => 'text', 'group' => 'basic' ),
				'cs_industry'    => array( 'label' => __( 'Industry', 'billboard-pro' ), 'type' => 'text', 'group' => 'basic' ),
				'cs_campaign'    => array( 'label' => __( 'Campaign Name', 'billboard-pro' ), 'type' => 'text', 'group' => 'basic' ),
				'cs_location'    => array( 'label' => __( 'Location', 'billboard-pro' ), 'type' => 'text', 'group' => 'basic' ),
				'cs_duration'    => array( 'label' => __( 'Campaign Duration', 'billboard-pro' ), 'type' => 'text', 'group' => 'basic' ),
				'cs_format'      => array( 'label' => __( 'Billboard Type / Format', 'billboard-pro' ), 'type' => 'text', 'group' => 'basic' ),
				'cs_objective'   => array( 'label' => __( 'Campaign Objective', 'billboard-pro' ), 'type' => 'textarea', 'group' => 'basic' ),
				'cs_gallery'     => array( 'label' => __( 'Gallery', 'billboard-pro' ), 'type' => 'gallery', 'group' => 'basic' ),
				'cs_impressions' => array( 'label' => __( 'Total Impressions', 'billboard-pro' ), 'type' => 'text', 'group' => 'results', 'placeholder' => '4.2M' ),
				'cs_results'     => array( 'label' => __( 'Results', 'billboard-pro' ), 'type' => 'textarea', 'group' => 'results', 'help' => __( 'One result per line. Use "Value | Label" for a highlighted metric, e.g. "+38% | Store visits".', 'billboard-pro' ) ),
				'cs_testimonial' => array( 'label' => __( 'Client Testimonial', 'billboard-pro' ), 'type' => 'textarea', 'group' => 'results' ),
				'cs_testimonial_author' => array( 'label' => __( 'Testimonial Author', 'billboard-pro' ), 'type' => 'text', 'group' => 'results', 'placeholder' => 'Jane Doe, Marketing Director' ),
			);
			break;

		case 'bb_testimonial':
			$fields = array(
				't_company'  => array( 'label' => __( 'Company', 'billboard-pro' ), 'type' => 'text', 'group' => 'basic' ),
				't_position' => array( 'label' => __( 'Position', 'billboard-pro' ), 'type' => 'text', 'group' => 'basic', 'help' => __( 'Title = person name. Content = testimonial. Featured image = photo.', 'billboard-pro' ) ),
			);
			break;

		default:
			$fields = array();
	}

	return apply_filters( 'bbp_fields', $fields, $post_type );
}
