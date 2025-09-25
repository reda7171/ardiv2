<?php
global $post;

$visible_images = houzez_option('block_gallery_visible', 9);
$images_in_row = houzez_option('block_gallery_columns', 3);;

if( empty($visible_images) ) {
    $visible_images = 9;
}
$property_gallery_popup_type = houzez_get_popup_gallery_type();

// Get the dynamically assigned image size for this layout
$image_size = houzez_get_image_size_for('property_detail_block_gallery');

$builtin_gallery_class = ' houzez-trigger-popup-slider-js';
$dataModal = 'href="#" data-bs-toggle="modal" data-bs-target="#property-lightbox"';
$images_ids = get_post_meta($post->ID, 'fave_property_images', false);

$i = 0;

if( !empty($images_ids) && count($images_ids)) {

    $images_ids = array_unique($images_ids);
    $total_images = count($images_ids);
    $remaining_images = $total_images - $visible_images;
    $gallery_token = wp_generate_password(5, false, false);
    ?>
<div class="property-gallery-grid property-section-wrap" id="property-gallery-grid">     
    <div class="property-gallery-grid-wrap row row-cols-3 row-cols-md-<?php echo $images_in_row; ?> g-0">
        <?php
        foreach( $images_ids as $image_id ) { $i++; 
            $image_data = wp_get_attachment_image_src( $image_id, $image_size );

            // Skip this iteration if image_data is false
            if(!$image_data) {
                continue;
            }

            $image_url = $image_data[0];
            $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
            $image_meta = wp_get_attachment_metadata($image_id);

            // Generate srcset and sizes for responsive images
            $srcset = wp_get_attachment_image_srcset($image_id, $image_size, $image_meta);
            $sizes = wp_get_attachment_image_sizes($image_id, $image_size, $image_meta);

            if( $property_gallery_popup_type == 'photoswipe' ) {
                $full_image = wp_get_attachment_image_src( $image_id, 'full' );
                $full_image_url = $full_image[0] ?? '';
                $dataModal = 'href="#" data-src="'.esc_url($full_image_url).'" data-houzez-fancybox data-fancybox="block-gallery"';
                $builtin_gallery_class = '';
            }
        ?>
        <div class="col">
            <a <?php echo $dataModal; ?> data-slider-no="<?php echo esc_attr($i); ?>" class="gallery-grid-item<?php echo $builtin_gallery_class; ?><?php if($i == $visible_images && $remaining_images > 0 ){ echo ' more-images'; } elseif($i > $visible_images) {echo ' gallery-hidden'; } ?>">
            <?php if( $i == $visible_images && $remaining_images > 0 ){ echo '<span>'.$remaining_images.'+</span>'; } ?>
                <img class="img-fluid" 
                     src="<?php echo esc_url($image_url); ?>" 
                     alt="<?php echo esc_attr($image_alt); ?>"
                     <?php if($srcset): ?>srcset="<?php echo esc_attr($srcset); ?>"<?php endif; ?>
                     <?php if($sizes): ?>sizes="<?php echo esc_attr($sizes); ?>"<?php endif; ?>>
            </a>
        </div>
        <?php } ?>
    </div>
</div><!-- property-gallery-grid -->
<?php } ?>