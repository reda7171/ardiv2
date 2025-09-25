<?php 
global $is_multi_steps, $prop_meta_data; 
$is_featured = isset($prop_meta_data['fave_featured']) ? $prop_meta_data['fave_featured'][0] : 0;
$loggedintoview = isset($prop_meta_data['fave_loggedintoview']) ? $prop_meta_data['fave_loggedintoview'][0] : 0;
?>
<div id="settings" class="<?php echo esc_attr($is_multi_steps);?>">
	<div class="block-wrap">	
		<div class="block-title-wrap d-flex justify-content-between align-items-center">
			<h2><?php echo houzez_option('cls_settings', 'Property Settings'); ?></h2>
		</div>
		<div class="block-content-wrap">
			<div class="mb-3">
				<div class="d-flex justify-content-between">
					<label><?php echo houzez_option('cl_make_featured', 'Souhaitez-vous définir cette propriété comme étant en vedette ? Dans ce cas, 10 points seront automatiquement déduits de votre solde.'); ?></label>
					<div class="form-control-wrap d-flex">
						<label class="control control--radio me-3">
							<input type="radio" name="prop_featured" <?php checked($is_featured, 1, true); ?> value="1"><?php echo houzez_option('cl_yes', 'Yes '); ?>
							<span class="control__indicator"></span>
						</label>
						<label class="control control--radio">
							<input type="radio" name="prop_featured" <?php checked($is_featured, 0, true); ?> value="0"><?php echo houzez_option('cl_no', 'No '); ?>
							<span class="control__indicator"></span>
						</label>
					</div>
				</div>
			</div>
			

<!--
		<div class="mb-3">
				<div class="d-flex justify-content-between">
					<label><?php echo houzez_option('cl_login_view', 'The user must be logged in to view this property'); ?></label>
					<div class="form-control-wrap d-flex">
						<label class="control control--radio me-3">
							<input type="radio" name="login-required" value="1" <?php checked($loggedintoview, 1, true); ?>><?php echo houzez_option('cl_yes', 'Yes '); ?>
							<span class="control__indicator"></span>
						</label>
						<label class="control control--radio">
							<input type="radio" name="login-required" value="0" <?php checked($loggedintoview, 0, true); ?>><?php echo houzez_option('cl_no', 'No '); ?>
							<span class="control__indicator"></span>
						</label>
					</div>
				</div>
			</div>


			<div class="mb-3">
				<label class="form-label" for="property_disclaimer">
					<?php echo houzez_option('cl_disclaimer', 'Disclaimer'); ?>		
				</label>
				<textarea class="form-control" id="property_disclaimer" name="property_disclaimer" rows="6" placeholder=""><?php
				if (houzez_edit_property()) {
					houzez_field_meta('property_disclaimer');
				}
				?></textarea>
			</div>
-->
		</div><!-- block-content-wrap -->
	</div><!-- block-wrap -->
</div><!-- #settings -->


