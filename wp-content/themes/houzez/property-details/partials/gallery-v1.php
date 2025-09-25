<?php 
global $post, $top_area, $map_street_view, $gallery_token, $total_images, $property_gallery_popup_type; 

// Get the dynamically assigned image size for this layout
$image_size = houzez_get_image_size_for('property_detail_v1');
$featured_image = houzez_get_image_url('full');
$featured_image_url = $featured_image[0] ?? '';
?>

<div class="property-image-count" role="status">
    <i class="houzez-icon icon-picture-sun" aria-hidden="true"></i> <span><?php echo esc_attr($total_images); ?></span>
</div>

<?php 
if(houzez_option('agent_form_above_image') ) {
    get_template_part('property-details/agent-form'); 
}?>

<?php if( $property_gallery_popup_type == "photoswipe" ) { ?>
    <div itemscope itemtype="http://schema.org/ImageGallery">
        <a href="#" class="property-banner-trigger position-absolute top-0 start-0 w-100 h-100" data-src="<?php echo esc_url($featured_image_url); ?>" data-houzez-fancybox data-fancybox="gallery-v1" itemprop="contentUrl"></a>
        <?php
        $images_ids = get_post_meta($post->ID, 'fave_property_images', false);
        $featured_image_id = get_post_thumbnail_id($post->ID);
        if (($key = array_search($featured_image_id, $images_ids)) !== false) {
            unset($images_ids[$key]);
        }
        if(!empty($images_ids)) {
            foreach( $images_ids as $image_id ) {
                $image_data = wp_get_attachment_image_src($image_id, 'full');

                // Skip this iteration if image_data is false
                if(!$image_data) {
                    continue;
                }

                $image_url = $image_data[0] ?? '';    
                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                ?>
                <div itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" class="gallery-hidden">
                    <a href="#" data-src="<?php echo esc_url($image_url); ?>" itemprop="contentUrl" data-houzez-fancybox data-fancybox="gallery-v1">
                        <img class="img-fluid" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" itemprop="thumbnail">
                    </a>
                </div>
                <?php
            }
        }
        ?>
    </div>
<?php } else { ?>
    <a class="property-banner-trigger position-absolute top-0 start-0 w-100 h-100" data-bs-toggle="modal" data-bs-target="#property-lightbox" href="#" role="button"></a>
<?php } ?>
<img class="property-featured-image w-100 h-100 left-0 top-0" src="<?php echo esc_url($featured_image_url); ?>" alt="" role="img">