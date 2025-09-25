<?php
global $prop_featured;
$prop_featured = houzez_get_listing_data('featured');

if( $prop_featured == 1 ) {
echo '<span class="label-featured label me-1 d-inline-flex align-items-center" role="status">
        <i class="houzez-icon icon-real-estate-action-house-star me-1" style="font-size:14px; line-height:1;  "></i>
        <span style="font-size:14px; ;"> Premium </span>	
      </span>';
}