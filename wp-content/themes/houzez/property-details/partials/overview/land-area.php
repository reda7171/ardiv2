<?php
$propID = get_the_ID();
$land_area = houzez_get_listing_data('property_land');
$land_area_prefix = houzez_get_listing_data('property_land_postfix');

if( !empty( $land_area ) ) {
	// Get the version from the parameter or use default
	$version = isset($args['overview']) ? $args['overview'] : '';
	
	$output_land = '';
	if( !empty( $land_area_prefix ) ) {
		$output_land = esc_attr($land_area).' '.esc_attr($land_area_prefix);
	} else {
		$output_land = esc_attr($land_area);
	}
	
	// Use the helper function to generate the HTML
	echo houzez_get_overview_item('land-area', $output_land, houzez_option('spl_land', esc_html__('Land Area', 'houzez')), $version);
}