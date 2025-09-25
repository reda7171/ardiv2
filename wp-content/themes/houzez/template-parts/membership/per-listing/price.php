<?php
$currency_symbol = houzez_option( 'currency_symbol' );
$where_currency = houzez_option( 'currency_position' );
$price_listing_submission = houzez_option('price_listing_submission');
$price_featured_submission = houzez_option('price_featured_listing_submission');
$price_per_submission = floatval($price_listing_submission);
$price_featured_submission = floatval($price_featured_submission);

$upgrade_id = isset( $_GET['upgrade_id'] ) ? $_GET['upgrade_id'] : '';
$prop_id = isset( $_GET['prop-id'] ) ? $_GET['prop-id'] : '';

if ( $where_currency == 'before' ) {
    $price_listing_submission_cn = $currency_symbol.''.$price_listing_submission;
    $price_featured_submission_cn = $currency_symbol.''.$price_featured_submission;
} else {
    $price_listing_submission_cn = $price_listing_submission.''.$currency_symbol;
    $price_featured_submission_cn = $price_featured_submission.''.$currency_symbol;
}
?>
<div class="membership-package-order-detail-wrap block-wrap">
    <div class="block-title-wrap border-none mb-0 pb-3">
        <h2><?php esc_html_e( 'Pay Listing', 'houzez' ); ?></h2>
    </div>
    <div class="block-content-wrap">
        <div class="membership-package-order-detail">

            <ul class="list-unstyled mebership-list-info d-flex flex-column">
                <?php if( !empty( $upgrade_id ) ) {

                    $prop_featured = get_post_meta($upgrade_id, 'fave_featured', true);
                    if ($prop_featured != 1) { ?>
                        <li class="border-bottom py-3 d-flex align-items-center justify-content-between">
                            <span>
                                <i class="houzez-icon icon-check-circle-1 me-2 primary-text"></i> 
                                <?php esc_html_e('Featured Fee', 'houzez'); ?>
                            </span>
                            <strong><?php echo esc_attr($price_featured_submission_cn); ?></strong> 
                            <span id="submission_featured_price" class="hidden"><?php echo $price_featured_submission; ?></span>
                        </li>
                    <?php } ?>

                    <li class="pt-3 d-flex align-items-center justify-content-between total-price">
                        <strong><?php esc_html_e('Total Price', 'houzez' ); ?></strong>
                        <strong><?php echo esc_attr( $price_featured_submission_cn ); ?></strong>
                    </li>

            <?php } else { ?>

                <li class="border-bottom py-3 d-flex align-items-center justify-content-between">
                    <span>
                        <i class="houzez-icon icon-check-circle-1 me-2 primary-text"></i> 
                        <?php esc_html_e('Submission Fee', 'houzez' ); ?>
                    </span>
                    <strong><?php echo esc_attr($price_listing_submission_cn); ?></strong>
                    <span id="submission_price" class="hidden"><?php echo $price_per_submission; ?></span>
                </li>

                <?php if( !empty( $prop_id ) ) {
                    $prop_featured = get_post_meta($prop_id, 'fave_featured', true);
                    if ($prop_featured != 1) {
                        ?>
                        <li class="border-bottom py-3 d-flex align-items-center justify-content-between">
                            <span>
                                <i class="houzez-icon icon-check-circle-1 me-2 primary-text"></i>
                                <?php esc_html_e('Featured Fee', 'houzez'); ?> 
                            </span>
                            <strong><?php echo esc_attr($price_featured_submission_cn); ?></strong>
                            <span id="submission_featured_price" class="hidden"><?php echo $price_featured_submission; ?></span>
                        </li>
                        <li class="border-bottom py-3 d-flex align-items-center justify-content-between">
                            <span>
                                <?php esc_html_e('Make Featured', 'houzez'); ?>
                            </span>
                            <strong>
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="prop_featured" name="prop_featured" id="prop_featured" value="1">
                                    <span class="control__indicator"></span>
                                </label>
                            </strong>
                        </li>
                    <?php }
                }?>

                <li class="pt-3 d-flex align-items-center justify-content-between total-price">
                    <strong><?php esc_html_e('Total Price', 'houzez' ); ?></strong>
                    <strong id="submission_total_price"><?php echo esc_attr( $price_listing_submission_cn ); ?></strong>
                </li>

            <?php } // else ?>
                
            </ul>
        </div><!-- membership-package-order-detail -->    
    </div>
</div><!-- membership-package-order-detail-wrap -->