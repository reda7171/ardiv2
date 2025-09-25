<?php 
global $post, $hide_author_date; 
$agent_info = houzez_get_property_agent($post->ID);
$prop_id = houzez_get_listing_data('property_id');
$agents_ids = houzez_get_listing_data('agents', false);

$agent_agency_id = houzez_get_agent_agency_id($agents_ids);



$email = (string) $agent_info["agent_email"];


$user = get_user_by('email', $email);

		    if(empty($user)) {
		        return '';
		    }

$user_id = intval($user->ID);

$package_id  = get_user_active_package_id($user_id);


// Tableau de configuration des badges
$badges = [
    1240 => ['class' => 'btn-secondary', 'label' => 'Professionnel Gold'],
    1239 => ['class' => 'btn-primary',   'label' => 'Professionnel'],
    1241 => ['class' => 'btn-primary',   'label' => 'Magasin'],
];

// Vérifier et afficher le badge
if (isset($badges[$package_id]) ) {
    $badge = $badges[$package_id];
} else {
    $badge = ['class' => 'btn-danger', 'label' => 'Magasin en Test'];
}




$show_author_date = isset($hide_author_date) ? $hide_author_date : houzez_option('disable_agent', 1);

if( $show_author_date && !empty( $agent_info )) { ?>
<div class="item-author d-flex align-items-center gap-1">
	<div class="item-author-image me-2" role="img">
		<img class="rounded-circle" src="<?php echo $agent_info['picture']; ?>" width="32" height="32" alt="">
	</div>
	<a href="<?php echo $agent_info['link']; ?>" role="link">
		<?php echo $agent_info["agent_name"]; ?>
	</a>
	
	
	<span class="badge <?php echo esc_attr($badge['class']); ?> agent-verified-badge">
		<i class="houzez-icon icon-check-circle-1 me-1"></i>
		<?php echo esc_html__($badge['label'], 'houzez'); ?>
	</span>
	
</div><!-- item-author -->
<?php } 




?>