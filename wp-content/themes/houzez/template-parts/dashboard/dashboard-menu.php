<?php
global $houzez_local;

$userID = get_current_user_id();
$dashboard_link = houzez_get_template_link_2('template/user_dashboard.php');
$dash_profile_link = houzez_get_template_link_2('template/user_dashboard_profile.php');
$dashboard_insight = houzez_get_template_link_2('template/user_dashboard_insight.php');
$dashboard_properties = houzez_get_template_link_2('template/user_dashboard_properties.php');
$dashboard_add_listing = houzez_get_template_link_2('template/user_dashboard_submit.php');
$dashboard_favorites = houzez_get_template_link_2('template/user_dashboard_favorites.php');
$dashboard_search = houzez_get_template_link_2('template/user_dashboard_saved_search.php');
$dashboard_invoices = houzez_get_template_link_2('template/user_dashboard_invoices.php');
$dashboard_msgs = houzez_get_template_link_2('template/user_dashboard_messages.php');
$dashboard_membership = houzez_get_template_link_2('template/user_dashboard_membership.php');
$dashboard_gdpr = houzez_get_template_link_2('template/user_dashboard_gdpr.php');
$dashboard_wha = houzez_get_template_link_2('template/user_dashboard_whatsapp.php');

$dashboard_seen_msgs = add_query_arg( 'view', 'inbox', $dashboard_msgs );
$dashboard_unseen_msgs = add_query_arg( 'view', 'sent', $dashboard_msgs );
$dashboard_verification = add_query_arg( 'hpage', 'verification', $dash_profile_link );



$is_verified = get_post_meta(  wp_get_current_user(), 'fave_agency_verified', true );

// Récupérer le package
$package_id = get_user_active_package_id($userID);

// Récupère les valeurs actuelles
$listings = get_post_meta($package_id, 'fave_package_listings', true);
$points   = get_post_meta($package_id, 'fave_package_points', true);
$stats = get_post_meta($package_id, 'fave_statistiques_approfondies', true);
$badge = get_post_meta($package_id, 'fave_badge_professionnel', true);
$whatsapp = get_post_meta($package_id, 'fave_support_whatsapp', true);
$tableau_bord = get_post_meta($package_id, 'fave_tableau_bord', true);
$memeber_team = get_post_meta($package_id, 'fave_memeber_team', true);
$package_price = get_post_meta($package_id, 'fave_package_price', true);
$vitrine = get_post_meta($package_id, 'fave_vitrine', true);
$logo_marque = get_post_meta($package_id, 'fave_logo_marque', true);
$plan = get_post_meta($package_id, 'fave_plan', true);
$visibilite = get_post_meta($package_id, 'fave_visibilite', true);
$stats = get_post_meta($package_id, 'fave_stats', true);
$bonus_renouvellement = get_post_meta($package_id, 'fave_bonus_renouvellement', true);
$support_email = get_post_meta($package_id, 'fave_support_email', true);



$dashboard_crm = houzez_get_template_link_2('template/user_dashboard_crm.php');
$crm_leads = add_query_arg( 'hpage', 'leads', $dashboard_crm );
$crm_deals = add_query_arg( 'hpage', 'deals', $dashboard_crm );
$crm_enquiries = add_query_arg( 'hpage', 'enquiries', $dashboard_crm );
$crm_activities = add_query_arg( 'hpage', 'activities', $dashboard_crm );

$home_link = home_url('/');
$enable_paid_submission = houzez_option('enable_paid_submission');

// Initialize all active state variables
$parent_crm = $parent_props = $parent_agents = '';
$ac_crm = $ac_insight = $ac_profile = $ac_whatsapp = $ac_props = $ac_add_prop = $ac_fav = $ac_search = $ac_invoices = $ac_msgs = $ac_mem = $ac_gdpr = $ac_verification = '';
$ac_dashboard = $ac_activities = $ac_deals = $ac_leads = $ac_inquiries = '';



// Set active states based on current page
if( is_page_template( 'template/user_dashboard.php' ) ) {
    $ac_dashboard = 'active';
} elseif( is_page_template( 'template/user_dashboard_profile.php' ) ) {
    $ac_profile = 'active';
} elseif ( is_page_template( 'template/user_dashboard_properties.php' ) ) {
    $ac_props = 'active';
    $parent_props = "side-menu-parent-selected";
} elseif ( is_page_template( 'template/user_dashboard_submit.php' ) ) {
    $ac_add_prop = 'active';
} elseif ( is_page_template( 'template/user_dashboard_saved_search.php' ) ) {
    $ac_search = 'active';
} elseif ( is_page_template( 'template/user_dashboard_favorites.php' ) ) {
    $ac_fav = 'active';
} elseif ( is_page_template( 'template/user_dashboard_invoices.php' ) ) {
    $ac_invoices = 'active';
} elseif ( is_page_template( 'template/user_dashboard_messages.php' ) ) {
    $ac_msgs = 'active';
} elseif ( is_page_template( 'template/user_dashboard_membership.php' ) ) {
    $ac_mem = 'active';
} elseif ( is_page_template( 'template/user_dashboard_gdpr.php' ) ) {
    $ac_gdpr = 'active';
} elseif ( is_page_template( 'template/user_dashboard_insight.php' ) ) {
    $ac_insight = 'active';
} elseif ( is_page_template( 'template/user_dashboard_whatsapp.php' ) ) {
    $ac_whatsapp = 'active';
} elseif ( is_page_template( 'template/user_dashboard_crm.php' ) ) {
    $ac_crm = 'active';
    $parent_crm = "side-menu-parent-selected";
    
    // Set active states for CRM sub-pages
    if( isset($_GET['hpage']) ) {
        switch($_GET['hpage']) {
            case 'activities':
                $ac_activities = 'active';
                break;
            case 'deals':
                $ac_deals = 'active';
                break;
            case 'leads':
                $ac_leads = 'active';
                break;
            case 'enquiries':
                $ac_inquiries = 'active';
                break;
        }
    }
}


//for debug
// var_dump($dashboard_crm, $is_verified, $package_id);


$agency_agents = add_query_arg( 'agents', 'list', $dash_profile_link );
$agency_agent_add = add_query_arg( 'agents', 'add_new', $dash_profile_link );


//$agents_query = Houzez_Query::loop_agency_agents(get_the_ID());
//echo esc_attr($agents_query->found_posts); 

// Tableau de configuration des badges
$package = [
    1240 => ['total-agent' => 3],
    1239 => ['total-agent' => 10],
    1241 => ['total-agent' => 0],
];
	


$meta_query = array(
    array(
        'key' => 'fave_agent_agency',
        'value' => $userID
    )
);

// Get total users for pagination
$total_query = new WP_User_Query(array(
    'role' => 'houzez_agent',
    'meta_query' => $meta_query,
    'search' => !empty($search_term) ? '*' . $search_term . '*' : '',
    'search_columns' => !empty($search_term) ? array('user_login', 'user_email', 'display_name', 'user_nicename', 'first_name', 'last_name') : array(),
    'fields' => 'ID',
    'count_total' => true
));

$total_agents = $total_query->get_total();

// Vérifier si le package est 1241 et comparer avec la limite définie
if ($memeber_team == $total_agents) {
	$agency_agent_add = null;
	$agency_agents = null;
} 


$all = add_query_arg( 'prop_status', 'all', $dashboard_properties );
$mine_link = add_query_arg( 'prop_status', 'mine', $dashboard_properties );
$approved = add_query_arg( 'prop_status', 'approved', $dashboard_properties );
$pending = add_query_arg( 'prop_status', 'pending', $dashboard_properties );
$expired = add_query_arg( 'prop_status', 'expired', $dashboard_properties );
$draft = add_query_arg( 'prop_status', 'draft', $dashboard_properties );
$on_hold = add_query_arg( 'prop_status', 'on_hold', $dashboard_properties );
$disapproved = add_query_arg( 'prop_status', 'disapproved', $dashboard_properties );

$ac_approved = $ac_pending = $ac_expired = $ac_disapproved = $ac_all = $ac_mine  = $ac_draft = $ac_on_hold = $ac_agents = $ac_agent_new = '';

if( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'approved' ) {
    $ac_approved = $ac_props = 'class=active';

} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'pending' ) {
    $ac_pending = $ac_props = 'class=active';

} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'expired' ) {
    $ac_expired = $ac_props = 'class=active';
} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'disapproved' ) {
    $ac_disapproved = $ac_props = 'class=active';
} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'approved' ) {
    $ac_approved = $ac_props = 'class=active';
} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'draft' ) {
    $ac_draft = $ac_props = 'class=active';
} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'on_hold' ) {
    $ac_on_hold = $ac_props = 'class=active';
} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'all' ) {
    $ac_all = $ac_props = 'class=active';
} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'mine' ) {
    $ac_mine = $ac_props = 'class=active';
}

if( isset( $_GET['agents'] ) && $_GET['agents'] == 'list' ) {
    $ac_agents = 'class=active';
    $ac_profile = '';
} elseif( isset( $_GET['agents'] ) && $_GET['agents'] == 'add_new' ) {
    $ac_agents = 'class=active';
    $ac_agent_new = 'class=active';
    $ac_profile = '';
} elseif( isset( $_GET['hpage'] ) && $_GET['hpage'] == 'verification' ) {
    $ac_verification = 'active';
    $ac_profile = '';
}

$all_post_count = houzez_user_posts_count('any');
$publish_post_count = houzez_user_posts_count('publish');
$pending_post_count = houzez_user_posts_count('pending');
$draft_post_count = houzez_user_posts_count('draft');
$on_hold_post_count = houzez_user_posts_count('on_hold');
$disapproved_post_count = houzez_user_posts_count('disapproved');
$expired_post_count = houzez_user_posts_count('expired');

$houzez_check_role = houzez_check_role();
?>

<div class="sidebar-nav">
    <?php if ( !is_user_logged_in() ) { ?>

        <div class="nav-box">
            <ul>
                <li>
                    <a href="<?php echo esc_url($dashboard_favorites); ?>" class="<?php echo esc_attr($ac_fav); ?>">
                        <i class="houzez-icon icon-love-it"></i>
                        <span><?php echo houzez_option('dsh_favorite', 'Favourites'); ?></span>
                    </a>
                </li>
            </ul>
        </div>

    <?php } else { ?>

        <?php if( $houzez_check_role ): ?>
	
		<?php if ( is_user_logged_in()  && ! houzez_is_admin() ) : 
				$user_id = get_current_user_id();
				$package_id = get_user_active_package_id($user_id);
				$ads_remaining = houzez_get_remaining_listings($user_id);
				$user_points = get_post_meta($package_id, 'fave_package_points', true);

				if (empty($user_points)) $user_points = 0;
				if (empty($ads_remaining)) $ads_remaining = 0;
			?>

			<!-- Dashboard Stats Sans Background -->
			<div class="nav-box">
				<h5><?php echo esc_html__('Mes Stats', 'houzez'); ?></h5>
				<ul>
					<li>
						<a href="#" class="d-flex justify-content-between align-items-center">
							<span><i class="houzez-icon icon-gauge-dashboard-1 me-1"></i> <?php echo houzez_option('Points', 'Points'); ?></span>
							<span class="stats-badge"><?php echo $user_points; ?></span>
						</a>
					</li>
					<li>
						<a href="#" class="d-flex justify-content-between align-items-center">
							<span><i class="houzez-icon icon-gauge-dashboard-1 me-1"></i> <?php echo houzez_option('Annonces restantes', 'Annonces restantes'); ?></span>
							<span class="stats-badge"><?php echo $ads_remaining; ?></span>
						</a>
					</li>
				</ul>
			</div>
			<?php endif; ?>
	
	
	
        <div class="nav-box">
            <h5><?php echo houzez_option('dsh_overview', 'Overview'); ?></h5>
            <ul>
				
                <?php if( !empty( $dashboard_link ) ): ?>
                <li>
                    <a href="<?php echo esc_url($dashboard_link); ?>" class="<?php echo esc_attr($ac_dashboard); ?>">
                        <i class="houzez-icon icon-gauge-dashboard-1"></i>
                        <span><?php echo houzez_option('dsh_dashboard', 'Dashboard'); ?></span>
                    </a>
                </li>
                <?php endif; ?>
				

				
				
				
                <?php if( !empty( $dashboard_crm ) && ($package_id == 1239 || $package_id == 1240) ): ?>
                <li>
                    <a href="<?php echo esc_url($crm_activities); ?>" class="<?php echo esc_attr($ac_activities); ?>">
                        <i class="houzez-icon icon-list-to-do"></i>
                        <span><?php echo houzez_option('dsh_activities', 'Activities'); ?></span>
                    </a>
                </li>
                <?php endif; ?>
				
				
				
				
                <?php if( !empty( $dashboard_insight ) && ($package_id == 1239 || $package_id == 1240) ): ?>
                <li>
                    <a href="<?php echo esc_url($dashboard_insight); ?>" class="<?php echo esc_attr($ac_insight); ?>">
                        <i class="houzez-icon icon-analytics-pie-1"></i>
                        <span><?php echo houzez_option('dsh_insight', 'Insights'); ?></span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if( $houzez_check_role && !empty( $dashboard_crm ) ): ?>
        <div class="nav-box">
            <h5><?php echo houzez_option('dsh_crm', 'CRM'); ?></h5>
            <ul>
                <li>
                    <a href="<?php echo esc_url($crm_deals); ?>" class="<?php echo esc_attr($ac_deals); ?>">
                        <i class="houzez-icon icon-business-contract-handshake-sign"></i>
                        <span><?php echo houzez_option('dsh_deals', 'Deals'); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url($crm_leads); ?>" class="<?php echo esc_attr($ac_leads); ?>">
                        <i class="houzez-icon icon-single-neutral-flag-2"></i>
                        <span><?php echo houzez_option('dsh_leads', 'Leads'); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url($crm_enquiries); ?>" class="<?php echo esc_attr($ac_inquiries); ?>">
                        <i class="houzez-icon icon-single-neutral-question"></i>
                        <span><?php echo houzez_option('dsh_inquiries', 'Inquiries'); ?></span>
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>

        <?php if( ($houzez_check_role && (!empty( $dashboard_properties ) || !empty( $dashboard_add_listing ))) || !empty( $dashboard_favorites ) ): ?>
        <div class="nav-box">
            <h5><?php echo houzez_option('dsh_props', 'Properties'); ?></h5>
            <ul>
                <?php if( $houzez_check_role && !empty( $dashboard_properties ) ): ?>
                <li>
                    <a href="<?php echo esc_url($dashboard_properties); ?>" class="<?php echo esc_attr($ac_props); ?>">
                        <i class="houzez-icon icon-building-cloudy"></i>
                        <span><?php echo houzez_option('dsh_props', 'Properties'); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if( $houzez_check_role && !empty( $dashboard_add_listing ) ): ?>
                <li>
                    <a href="<?php echo esc_url($dashboard_add_listing); ?>" class="<?php echo esc_attr($ac_add_prop); ?>">
                        <i class="houzez-icon icon-add-circle"></i>
                        <span><?php echo houzez_option('dsh_create_listing', 'Create a Listing'); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if( !empty( $dashboard_favorites ) ): ?>
                <li>
                    <a href="<?php echo esc_url($dashboard_favorites); ?>" class="<?php echo esc_attr($ac_fav); ?>">
                        <i class="houzez-icon icon-love-it"></i>
                        <span><?php echo houzez_option('dsh_favorite', 'Favourites'); ?></span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if( !empty( $dash_profile_link ) && ( houzez_is_agency() &&  $memeber_team > 0  ) ) : ?>
        <div class="nav-box">
            <h5><?php echo houzez_option('dsh_team', 'Team'); ?></h5>
            <ul>
                <li>
                    <a href="<?php echo esc_url($agency_agents); ?>" class="<?php echo esc_attr($ac_agents); ?>">
                        <i class="houzez-icon icon-multiple-man-woman-1"></i>
                        <span><?php echo houzez_option('dsh_agents', 'Agents') . ' ('.$total_agents.' / '.$memeber_team.')'; ?></span>
                    </a>
                </li>
				
				<?php if( !is_null( $agency_agent_add ) ): ?>
                <li>
                    <a href="<?php echo esc_url($agency_agent_add); ?>" class="<?php echo esc_attr($ac_agent_new); ?>">
                        <i class="houzez-icon icon-single-neutral-actions-add"></i>
                        <span><?php echo houzez_option('dsh_addnew', 'Add New Agent'); ?></span>
                    </a>
                </li>
				<?php endif; ?>
				
            </ul>
        </div>
        <?php endif; ?>

        <?php if( (!empty($dashboard_membership) && $enable_paid_submission == 'membership' && $houzez_check_role && ! houzez_is_admin()) || !empty($dashboard_search) || (!empty( $dashboard_invoices ) && $houzez_check_role) || !empty( $dashboard_msgs ) ): ?>
        <div class="nav-box">
            <h5><?php echo houzez_option('dsh_other', 'Other'); ?></h5>
            <ul>
                <?php if( !empty($dashboard_membership) && $enable_paid_submission == 'membership' && $houzez_check_role && ! houzez_is_admin()): ?>
                <li>
                    <a href="<?php echo esc_url($dashboard_membership); ?>" class="<?php echo esc_attr($ac_mem); ?>">
                        <i class="houzez-icon icon-task-list-text-1"></i>
                        <span><?php echo houzez_option('dsh_membership', 'Membership'); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if( !empty($dashboard_search) ): ?>
                <li>
                    <a href="<?php echo esc_url($dashboard_search); ?>" class="<?php echo esc_attr($ac_search); ?>">
                        <i class="houzez-icon icon-search"></i>
                        <span><?php echo houzez_option('dsh_saved_searches', 'Saved Searches'); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if( !empty( $dashboard_invoices ) && $houzez_check_role ): ?>
                <li>
                    <a href="<?php echo esc_url($dashboard_invoices); ?>" class="<?php echo esc_attr($ac_invoices); ?>">
                        <i class="houzez-icon icon-accounting-document"></i>
                        <span><?php echo houzez_option('dsh_invoices', 'Invoices'); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if( !empty( $dashboard_msgs ) ): ?>
                <li>
                    <a href="<?php echo esc_url($dashboard_msgs); ?>" class="<?php echo esc_attr($ac_msgs); ?>">
                        <i class="houzez-icon icon-messages-bubble"></i>
                        <span><?php echo houzez_option('dsh_messages', 'Messages'); ?></span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if( !empty( $dash_profile_link ) || !empty($dashboard_gdpr) || true ): // Always show Account section because Logout is always available ?>
        <div class="nav-box">
            <h5><?php echo houzez_option('dsh_account', 'Account'); ?></h5>
            <ul>
                <?php if( !empty( $dash_profile_link ) ): ?>
                    <li>
                        <a href="<?php echo esc_url($dash_profile_link); ?>" class="<?php echo esc_attr($ac_profile); ?>">
                            <i class="houzez-icon icon-single-neutral-circle"></i>
                            <span><?php echo houzez_option('dsh_profile', 'My Profile'); ?></span>
                        </a>
                    </li>

                    <?php if( (houzez_is_agency() || houzez_is_agent() || houzez_is_owner() ) && houzez_option('enable_user_verification', 0) ) : ?>
                    <li>
                        <a href="<?php echo esc_url($dashboard_verification); ?>" class="<?php echo esc_attr($ac_verification); ?>">
                            <i class="houzez-icon icon-check-circle-1"></i>
                            <span><?php echo houzez_option('dsh_verification', 'Verification'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                <?php endif; ?>

                <?php if (!empty($dashboard_gdpr)) : ?>
                    <li>
                        <a href="<?php echo esc_url($dashboard_gdpr); ?>" class="<?php echo esc_attr($ac_gdpr); ?>">
                            <i class="houzez-icon icon-settings-gear-64-1"></i>
                            <span><?php echo houzez_option('dsh_gdpr', 'GDPR Data Request'); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <li>
                    <a href="<?php echo wp_logout_url(home_url()); ?>">
                        <i class="houzez-icon icon-logout-1"></i>
                        <span><?php echo houzez_option('dsh_logout', 'Logout'); ?></span>
                    </a>
                </li>
				  <?php if( (!empty($dashboard_membership)  && houzez_is_agency() ) ): ?>
				 <li>
                  <a href="<?php echo esc_url($dashboard_wha); ?>" class="<?php echo esc_attr($ac_whatsapp); ?>">
                        <i class="houzez-icon icon-logout-1"></i>
                        <span><?php echo houzez_option('dsh_support', 'Support'); ?></span>
                    </a>
                </li>
				  <?php endif; ?>
				
            </ul>
        </div>
        <?php endif; ?>
	
	

    <?php } ?>
</div>