<?php
/**
 * Billboard search / filter form.
 * $args['variant'] = 'hero' (homepage panel) | 'archive' (sidebar, live AJAX).
 *
 * @package Billboard_Pro
 */

$bbp_variant = isset( $args['variant'] ) ? $args['variant'] : 'archive';
$bbp_p       = bbp_filter_params( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification
$bbp_cities  = get_terms( array( 'taxonomy' => 'billboard_city', 'hide_empty' => true ) );
$bbp_types   = get_terms( array( 'taxonomy' => 'billboard_type', 'hide_empty' => true ) );
$bbp_action  = get_post_type_archive_link( 'billboard' );

// On city/type archives the term is already fixed by the URL.
if ( is_tax( 'billboard_city' ) ) {
	$bbp_p['city'] = get_queried_object()->slug;
}
if ( is_tax( 'billboard_type' ) ) {
	$bbp_p['type'] = get_queried_object()->slug;
}

$bbp_select = function ( $name, $label, $options, $current, $placeholder ) {
	$id = 'f-' . $name . '-' . wp_rand( 10, 99 );
	echo '<div class="filter-field">';
	printf( '<label for="%1$s">%2$s</label>', esc_attr( $id ), esc_html( $label ) );
	printf( '<select id="%1$s" name="%2$s"><option value="">%3$s</option>', esc_attr( $id ), esc_attr( $name ), esc_html( $placeholder ) );
	foreach ( $options as $v => $l ) {
		printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $v ), selected( $current, (string) $v, false ), esc_html( $l ) );
	}
	echo '</select></div>';
};

$bbp_city_opts = array();
if ( ! is_wp_error( $bbp_cities ) ) {
	foreach ( $bbp_cities as $t ) {
		$bbp_city_opts[ $t->slug ] = $t->name;
	}
}
$bbp_type_opts = array();
if ( ! is_wp_error( $bbp_types ) ) {
	foreach ( $bbp_types as $t ) {
		$bbp_type_opts[ $t->slug ] = $t->name;
	}
}
$bbp_states  = bbp_states();
$bbp_budgets = array();
foreach ( bbp_budget_options() as $k => $b ) {
	$bbp_budgets[ $k ] = $b[2];
}
?>
<form class="filter-form filter-form--<?php echo esc_attr( $bbp_variant ); ?>" action="<?php echo esc_url( $bbp_action ); ?>" method="get" role="search" <?php echo 'archive' === $bbp_variant ? 'data-bbp-filter' : ''; ?> aria-label="<?php esc_attr_e( 'Find a billboard', 'billboard-pro' ); ?>">
	<?php if ( 'archive' === $bbp_variant ) : ?>
		<div class="filter-field filter-field--kw">
			<label for="f-kw"><?php esc_html_e( 'Keyword', 'billboard-pro' ); ?></label>
			<div class="input-icon"><?php bbp_the_icon( 'search', 16 ); ?><input type="search" id="f-kw" name="kw" value="<?php echo esc_attr( $bbp_p['kw'] ); ?>" placeholder="<?php esc_attr_e( 'Street, landmark, ID…', 'billboard-pro' ); ?>"></div>
		</div>
	<?php endif; ?>

	<?php
	$bbp_select( 'city', __( 'City', 'billboard-pro' ), $bbp_city_opts, $bbp_p['city'], __( 'All cities', 'billboard-pro' ) );
	if ( 'archive' === $bbp_variant && $bbp_states ) {
		$bbp_select( 'state', __( 'State / Region', 'billboard-pro' ), array_combine( $bbp_states, $bbp_states ), $bbp_p['state'], __( 'All regions', 'billboard-pro' ) );
	}
	$bbp_select( 'type', __( 'Billboard type', 'billboard-pro' ), $bbp_type_opts, $bbp_p['type'], __( 'All types', 'billboard-pro' ) );
	$bbp_select( 'format', __( 'Digital / Static', 'billboard-pro' ), bbp_format_options(), $bbp_p['format'], __( 'Any format', 'billboard-pro' ) );
	$bbp_select( 'size', __( 'Size', 'billboard-pro' ), bbp_size_options(), $bbp_p['size'], __( 'Any size', 'billboard-pro' ) );
	$bbp_select( 'availability', __( 'Availability', 'billboard-pro' ), bbp_availability_options(), $bbp_p['availability'], __( 'Any status', 'billboard-pro' ) );
	$bbp_select( 'budget', __( 'Budget', 'billboard-pro' ), $bbp_budgets, $bbp_p['budget'], __( 'Any budget', 'billboard-pro' ) );
	if ( 'archive' === $bbp_variant ) {
		$bbp_select( 'traffic', __( 'Traffic level', 'billboard-pro' ), bbp_traffic_options(), $bbp_p['traffic'], __( 'Any traffic', 'billboard-pro' ) );
	}
	?>

	<div class="filter-actions">
		<button type="submit" class="btn btn--primary btn--block"><?php bbp_the_icon( 'search', 18 ); ?> <?php esc_html_e( 'Find a Billboard', 'billboard-pro' ); ?></button>
		<?php if ( 'archive' === $bbp_variant ) : ?>
			<a class="filter-reset" href="<?php echo esc_url( $bbp_action ); ?>"><?php esc_html_e( 'Reset filters', 'billboard-pro' ); ?></a>
		<?php endif; ?>
	</div>
</form>
