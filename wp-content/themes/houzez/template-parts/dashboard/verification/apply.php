<?php
global $verification_data, $apply_verification_link, $cancel_verification_link, $document_types;

if( !isset( $_GET['apply'] ) ): ?>
<p class="mb-3"><?php esc_html_e('Your account is not verified. Apply for verification to show a badge on your profile and build trust with potential clients.', 'houzez'); ?></p>
<p class="mb-3"><?php esc_html_e('A verified badge helps users identify you as a trusted member of the community.', 'houzez'); ?></p>
<div class="mt-3">
    <a href="<?php echo esc_url($apply_verification_link); ?>" class="btn btn-primary">
        <?php esc_html_e('Apply for Verification', 'houzez'); ?>
    </a>
</div>

<?php else: ?>

<p class="text-muted mb-3"><?php esc_html_e('Please fill in the form below and upload a valid identification document to verify your identity.', 'houzez'); ?></p>

<form id="houzez-verification-form" class="verification-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="orm-group mb-3">
                <label for="full_name" class="form-label"><?php esc_html_e('Full Legal Name', 'houzez'); ?> <span class="required">*</span></label>
                <input type="text" id="full_name" name="full_name" class="form-control form-control-lg" 
                    value="<?php echo isset($verification_data['full_name']) ? esc_attr($verification_data['full_name']) : ''; ?>"
                    placeholder="<?php esc_attr_e('Enter your full legal name as it appears on your ID', 'houzez'); ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="document_type" class="form-label"><?php esc_html_e('Document Type', 'houzez'); ?> <span class="required">*</span></label>
                <select id="document_type" name="document_type" class="selectpicker form-control bs-select-hidden">
                    <option value=""><?php esc_html_e('Select Document Type', 'houzez'); ?></option>
                    <?php foreach ($document_types as $value => $document): ?>
                        <option value="<?php echo esc_attr($value); ?>" 
                            data-requires-back="<?php echo esc_attr($document['requires_back'] ? 'true' : 'false'); ?>"
                            <?php selected(isset($verification_data['document_type']) ? $verification_data['document_type'] : '', $value); ?>>
                            <?php echo esc_html($document['label']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group mb-3">
                <label class="form-label"><?php esc_html_e('Upload Front Side', 'houzez'); ?> <span class="required">*</span></label>
                <input type="file" id="verification_document" name="verification_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                <p class="file-help-text"><?php esc_html_e('Upload the front side of your document. Allowed file types: JPG, PNG, PDF. Maximum file size: 10MB', 'houzez'); ?></p>
            </div>
        </div>
    </div>
    
    <div class="row document-back-upload" style="display: none;">
        <div class="col-12">
            <div class="form-group mb-3">
                <label class="form-label"><?php esc_html_e('Upload Back Side', 'houzez'); ?> <span class="required">*</span></label>
                <input type="file" id="verification_document_back" name="verification_document_back" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                <p class="file-help-text"><?php esc_html_e('Upload the back side of your document. Allowed file types: JPG, PNG, PDF. Maximum file size: 10MB', 'houzez'); ?></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group mb-4 mt-4">
            <div class="verification-agreement bg-light p-4 rounded border shadow-sm">
                <h4 class="mb-3"><?php esc_html_e('Terms of Verification', 'houzez'); ?></h4>
                <div class="terms-content">
                    <p class="fw-bold mb-2"><?php esc_html_e('By submitting your verification request, you acknowledge and agree to the following terms:', 'houzez'); ?></p>
                    <ul class="mb-3 ps-4">
                        <li class="mb-2 text-muted"><?php esc_html_e('The personal information and documentation provided are accurate, authentic, and belong to you.', 'houzez'); ?></li>
                        <li class="mb-2 text-muted"><?php esc_html_e('All documents submitted are current, valid, and not expired.', 'houzez'); ?></li>
                        <li class="mb-2 text-muted"><?php esc_html_e('You authorize us to process and store this information for the purpose of verifying your identity.', 'houzez'); ?></li>
                        <li class="mb-2 text-muted"><?php esc_html_e('We may contact you for additional information if needed to complete the verification process.', 'houzez'); ?></li>
                        <li class="mb-2 text-muted"><?php esc_html_e('Providing false information or documentation may result in immediate account suspension and possible legal consequences.', 'houzez'); ?></li>
                        <li class="mb-2 text-muted"><?php esc_html_e('Your verification status may be displayed publicly on your profile to other users of the platform.', 'houzez'); ?></li>
                    </ul>
                    <p class="mb-1 text-muted small"><?php esc_html_e('Your personal data will be protected in accordance with our Privacy Policy and applicable data protection laws.', 'houzez'); ?></p>
                    <p class="mb-0 text-muted small"><?php esc_html_e('Verification is subject to review and can be revoked if violations of our terms are discovered.', 'houzez'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div id="verification-messages"></div>
    
    <div class="form-group d-flex justify-content-between">
        <button type="submit" id="submit-verification-btn" class="btn btn-primary btn-lg">
        <?php esc_html_e('Submit Verification Request', 'houzez'); ?>
        </button>
        
        <a href="<?php echo esc_url($cancel_verification_link); ?>" class="btn btn-light btn-lg">
            <?php esc_html_e('Cancel', 'houzez'); ?>
        </a>
    </div>
</form>
<?php endif; ?>