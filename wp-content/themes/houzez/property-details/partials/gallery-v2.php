<?php global $post, $top_area, $map_street_view, $featured_image_url, $gallery_token, $total_images, $property_gallery_popup_type; ?>

<div class="property-image-count d-block d-md-none" role="status">
    <i class="houzez-icon icon-picture-sun" aria-hidden="true"></i> <span><?php echo esc_attr($total_images); ?></span>
</div>
<div class="container h-100 d-flex flex-column justify-content-between">
    <div class="page-title-wrap page-label-wrap">
        <div class="d-flex align-items-center">   
            <?php get_template_part('property-details/partials/item-labels');?>
        </div>
    </div>
    <header class="page-title-wrap d-none d-md-block" role="banner">
        <nav class="d-flex align-items-end justify-content-between" role="navigation">
            <?php get_template_part('template-parts/page/breadcrumb');?> 
            <?php get_template_part('property-details/partials/tools');?> 
        </nav>
        <div class="property-header-wrap d-flex align-items-start justify-content-between mt-3" role="main">
            <div class="property-title-wrap d-flex flex-column">
                <div class="page-title mb-2">
                    <h1><?php the_title(); ?></h1>
                </div><!-- page-title -->
                <?php get_template_part('property-details/partials/item-address'); ?>
            </div>
            <ul class="property-price-wrap list-unstyled mb-0 mt-1 text-end" role="list">
                <?php echo houzez_listing_price_v1(); ?>
            </ul>
        </div>
    </header>
</div>

<?php if( $property_gallery_popup_type == "photoswipe" ) { ?>
    <div itemscope itemtype="http://schema.org/ImageGallery">
        <a href="#" class="property-banner-trigger position-absolute top-0 start-0 w-100 h-100" data-src="<?php echo esc_url($featured_image_url); ?>" data-houzez-fancybox data-fancybox="gallery-v2" itemprop="contentUrl"></a>
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
                    <a href="#" data-src="<?php echo esc_url($image_url); ?>" itemprop="contentUrl" data-houzez-fancybox data-fancybox="gallery-v2">
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