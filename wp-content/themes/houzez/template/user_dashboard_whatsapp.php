<?php
/**
 * Template Name: User Dashboard Whatsapp
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 11/01/16
 * Time: 4:35 PM
 */
if ( !is_user_logged_in() ) {
    wp_redirect(  home_url() );
}

global $wpdb, $houzez_local, $houzez_search_data;

$userID = get_current_user_id();

$is_verified = get_post_meta(  wp_get_current_user(), 'fave_agency_verified', true );

// Récupérer le package
$package_id = get_user_active_package_id($userID);

//SUPPORT
$whatsapp = get_post_meta($package_id, 'fave_support_whatsapp', true);
$support_email = get_post_meta($package_id, 'fave_support_email', true);
$support_ticket = get_post_meta($package_id, 'fave_support_ticket', true);
$support_telephone = get_post_meta($package_id, 'fave_support_telephone', true);
$support_appel_centre = get_post_meta($package_id, 'fave_support_appel_centre', true);




get_header('dashboard'); ?>

<!-- Load the dashboard sidebar -->
<?php get_template_part('template-parts/dashboard/sidebar'); ?>
<style>

</style>
<div class="dashboard-right">
    <!-- Dashboard Topbar --> 
    <?php get_template_part('template-parts/dashboard/topbar'); ?>

    <div class="dashboard-content">

		
					
					<!-- Bloc Contact WhatsApp -->
			<?php if ( $whatsapp === 'yes' ) { ?>
			<div class="contact-card">
				<div class="contact-header flex items-center gap-2">
					
					<h2><i class="houzez-icon icon-messaging-whatsapp" style="font-size:20px;color:#25D366;"></i><?php echo '  '.houzez_option('dsh_whatsapp', 'Contactez-nous sur WhatsApp'); ?></h2>
				</div>
				<div class="contact-body">
					<button 
						onclick="window.open('https://wa.me/212666038129?text=Bonjour%20Equipe%20Ardi.ma%0AJ%E2%80%99ai%20besoin%20d%E2%80%99assistance', '_blank');" 
						class="btn btn-phone">
						<i class="houzez-icon icon-whatsapp"></i> Assistance WhatsApp
					</button>
				</div>
			</div>
			<?php } ?>

			<!-- Bloc Contact Téléphone -->
			<?php if ( $support_telephone === 'yes' ) { ?> 
			<div class="contact-card">
				<div class="contact-header flex items-center gap-2">
					<h2><i class="houzez-icon icon-phone" style="font-size:20px;color:#007BFF;"></i><?php echo '  '.houzez_option('dsh_telephone', 'Contactez-nous par Téléphone'); ?></h2>
				</div>
				<div class="contact-body">
					<button 
						onclick="window.open('tel:+212666038129');" 
						class="btn btn-phone">
						<i class="houzez-icon icon-phone"></i> Votre Assistance : Ahmed Sabiri (+212) 666 038 129
					</button>
				</div>
			</div>
			<?php } ?>

		
			<!-- Bloc Contact Email -->
			<?php if ( $support_email === 'yes' ) { ?> 
			<div class="contact-card">
				<div class="contact-header flex items-center gap-2">
					<h2><i class="houzez-icon icon-envelope" style="font-size:20px;color:#FF9800;"></i><?php echo '  '.houzez_option('dsh_email', 'Contactez-nous par Email'); ?></h2>
				</div>
				<div class="contact-body">
					<button 
						onclick="mailto:ahmedsabiri@ardi.ma?subject=Assistance%20Client&body=Bonjour%20Equipe%20Ardi.ma,"
						class="btn btn-phone">
						<i class="houzez-icon icon-mail"></i> Assistance Email : ahmedsabiri@ardi.ma
					</button>
				</div>
			</div>
			<?php } ?>

		
			<!-- Bloc Contact Ticket -->
			<?php if ( $support_ticket === 'yes' ) { ?> 
			<div class="contact-card">
				<div class="contact-header flex items-center gap-2">
					
					<h2><i class="houzez-icon icon-list-to-do" style="font-size:20px;color:#9C27B0;"></i><?php echo '  '.houzez_option('dsh_ticket', 'Contactez-nous par Ticket'); ?></h2>
				</div>
				<div class="contact-body">
					<button class="btn btn-phone">
						<i class="houzez-icon icon-list-to-do"></i> Numéro Ticket : #1452
					</button>
				</div>
			</div>
			<?php } ?>

			<!-- Bloc Contact Centre d'appel -->
			<?php if ( $support_appel_centre === 'yes' ) { ?> 
			<div class="contact-card">
				<div class="contact-header flex items-center gap-2">
					
					<h2><i class="houzez-icon icon-headphones" style="font-size:20px;color:#4CAF50;"></i><?php echo '  '.houzez_option('dsh_support_appel_centre', 'Contactez notre Centre d’appel'); ?></h2>
				</div>
				<div class="contact-body">
					<button class="btn btn-mail">
						<i class="houzez-icon icon-headphones"></i> Appeler le centre : +212 (5 22 22 44 55)
					</button>
				</div>
			</div>
			<?php } ?>

	
		
		

	</div>

</div>
<?php the_content('dashboard'); ?>

<?php get_footer('dashboard'); ?>