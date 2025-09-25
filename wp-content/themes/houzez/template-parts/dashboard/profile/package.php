<?php

//reda ajouter button save
$userID = get_current_user_id();
$edit_user = isset( $_GET['edit_user'] ) ? sanitize_text_field($_GET['edit_user']) : false;
$can_use_package = get_user_meta( $userID, 'houzez_is_agent_can_use_agency_package', true );
if( ! $edit_user && ( houzez_is_agency() || houzez_is_admin() ) ) { ?>
<div class="block-wrap">
    <div class="block-title-wrap">
        <h2><?php esc_html_e( 'Agents Membership Package Options', 'houzez' ); ?></h2>
    </div>
    <div class="block-content-wrap">
        <form id="houzez-package-form">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <?php wp_nonce_field( 'houzez_agency_package_nonce', 'houzez-agency-package-security' ); ?>
                    <label class="control control--checkbox" role="checkbox" aria-checked="<?php echo ($can_use_package == 'yes') ? 'true' : 'false'; ?>">
                        <input type="checkbox" id="houzez_user_package" name="houzez_user_package" value="yes" <?php checked( 'yes', $can_use_package ); ?>>
                        <span class="control__indicator" aria-hidden="true"></span>
                        <span class="control__label"><?php esc_html_e( 'Allow Agents To Use Package', 'houzez' ); ?></span>
                    </label>
                </div>
            </div>
            <button type="submit" id="save-package" class="btn btn-primary">
                <?php esc_html_e( 'Enregistrer', 'houzez' ); ?>
            </button>
        </form>
    </div>
</div>
<?php } ?>
