<?php
$dashboard_logo = houzez_option( 'dashboard_logo', false, 'url' ); 



$user_id     = get_current_user_id();

// Récupérer le package
$package_id  = get_user_active_package_id($user_id);

// Tableau de configuration des badges
$badges = [
    1240 => ['class' => 'btn-secondary', 'label' => 'Professionnel Gold'],
    1239 => ['class' => 'gold-badge',   'label' => 'Professionnel'],
    1241 => ['class' => 'btn-primary',   'label' => 'Magasin'],
];

// Vérifier et afficher le badge
if (isset($badges[$package_id]) ) {
    $badge = $badges[$package_id];
} else {
    $badge = ['class' => 'btn-danger', 'label' => 'Magasin en Test'];
}





?>



<div class="dashboard-sidebar"> 
	<div class="sidebar-logo position-relative">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
			<img src="<?php echo esc_url($dashboard_logo); ?>" alt="logo">
		</a>
		<span class="badge <?php echo esc_attr($badge['class']); ?> agent-verified-badge">
			<i class="houzez-icon icon-check-circle-1 me-1"></i>
			<?php echo esc_html__($badge['label'], 'houzez'); ?>
		</span>
		<a href="javascript:void(0)" class="crose-btn d-xl-none d-flex">
			<i class="houzez-icon icon-close"></i>
		</a>
	</div>

	<?php get_template_part('template-parts/dashboard/dashboard-menu'); ?>
</div>
