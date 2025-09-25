<?php
$propID = get_the_ID();
$area_size = houzez_get_listing_data('property_size');
$area_size_prefix = houzez_get_listing_data('property_size_prefix');

if( !empty( $area_size ) ) {
	// Get the version from the parameter or use default
	$version = isset($args['overview']) ? $args['overview'] : '';
	
	$output_size = '';
	if( !empty( $area_size_prefix ) ) {
		$output_size = esc_attr($area_size).' '.esc_attr($area_size_prefix);
	} else {
		$output_size = esc_attr($area_size);
	}
	
	// Use the helper function to generate the HTML
	echo houzez_get_overview_item('area-size', $output_size, houzez_option('spl_area_size', esc_html__('Area Size', 'houzez')), $version);
}