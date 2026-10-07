<?php
/**
 * Billboard Pro functions and definitions.
 *
 * @package Billboard_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BBP_VERSION', '1.0.0' );
define( 'BBP_DIR', get_template_directory() );
define( 'BBP_URI', get_template_directory_uri() );

$bbp_includes = array(
	'inc/helpers.php',
	'inc/setup.php',
	'inc/post-types.php',
	'inc/meta-fields.php',
	'inc/meta-boxes.php',
	'inc/customizer.php',
	'inc/query-filters.php',
	'inc/forms.php',
	'inc/inquiries-admin.php',
	'inc/seo.php',
	'inc/demo-import.php',
);

foreach ( $bbp_includes as $bbp_file ) {
	require_once BBP_DIR . '/' . $bbp_file;
}
