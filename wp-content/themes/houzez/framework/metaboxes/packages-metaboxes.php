<?php
if( !function_exists('houzez_packages_metaboxes') ) {

    function houzez_packages_metaboxes( $meta_boxes ) {
        $houzez_prefix = 'fave_';
        
		
		
		/*
        |--------------------------------------------------------
        | 1. Informations générales du package
        |--------------------------------------------------------
        */
        $meta_boxes[] = array(
            'title' => esc_html__('Informations Générales', 'houzez'),
            'post_types' => array('houzez_packages'),
            'fields' => array(
				
				
				
                array(
                    'id' => "{$houzez_prefix}billing_time_unit",
                    'name' => esc_html__( 'Billing Period', 'houzez' ),
                    'type' => 'select',
                    'std' => "",
                    'options' => array( 'Day' => esc_html__('Day', 'houzez' ), 'Week' => esc_html__('Week', 'houzez' ), 'Month' => esc_html__('Month', 'houzez' ), 'Year' => esc_html__('Year', 'houzez' 						) ),
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}billing_unit",
                    'name' => esc_html__( 'Billing Frequency', 'houzez' ),
                    'placeholder' => esc_html__( 'Enter the frequency number', 'houzez' ),
                    'type' => 'text',
                    'std' => "0",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_price",
                    'name' => esc_html__( 'Package Price ', 'houzez' ),
                    'placeholder' => esc_html__( 'Enter the price', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_stripe_id",
                    'name' => esc_html__( 'Package Stripe id (Example: gold_pack)', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_visible",
                    'name' => esc_html__( 'Is It Visible?', 'houzez' ),
                    'type' => 'select',
                    'std' => "",
                    'options' => array( 'yes' => esc_html__( 'Yes', 'houzez' ), 'no' => esc_html__( 'No', 'houzez' ) ),
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}stripe_taxId",
                    'name' => esc_html__( 'Stripe Tax ID', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'placeholder' => esc_html__( 'Enter your stripe account tax id.', 'houzez' ),
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_tax",
                    'name' => esc_html__( 'Taxes', 'houzez' ),
                    'placeholder' => esc_html__( 'Enter the tax percentage (Only digits)', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,

                ),
                array(
                    'id' => "{$houzez_prefix}package_images",
                    'name' => esc_html__( 'How many images are included per listing?', 'houzez' ),
                    'placeholder' => esc_html__( 'Enter the number of images', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,

                ),
                array(
                    'id' => "{$houzez_prefix}unlimited_images",
                    'name' => esc_html__( "Unlimited Images", 'houzez' ),
                    'type' => 'checkbox',
                    'desc' => esc_html__('Same as defined in Theme Options', 'houzez'),
                    'std' => "",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_popular",
                    'name' => esc_html__( 'Is Popular/Featured?', 'houzez' ),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array( 'no' => esc_html__( 'No', 'houzez' ), 'yes' => esc_html__( 'Yes', 'houzez' ) ),
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_custom_link",
                    'name' => esc_html__( 'Custom Link', 'houzez' ),
                    'desc' => esc_html__('Leave empty if you do not want to custom link.', 'houzez'),
                    'placeholder' => esc_html__( 'Enter the custom link', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,

                ),

            ),
        );

        /*
        |--------------------------------------------------------
        | 2. Visibilité & Avantages publics
        |--------------------------------------------------------
        */
        $meta_boxes[] = array(
            'title' => esc_html__('Visibilité & Avantages', 'houzez'),
            'post_types' => array('houzez_packages'),
            'fields' => array(
					// ✅package_points
				   array(
                    'id' => "{$houzez_prefix}package_points",
                    'name' => esc_html__('Points du package', 'houzez'),
                    'type' => 'number',
                    'std' => 0,
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_featured_listings",
                    'name' => esc_html__( 'How many Featured listings are included?', 'houzez' ),
                    'placeholder' => esc_html__( 'Enter the number of listings', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,
                )
				,

                array(
                    'id' => "{$houzez_prefix}package_listings",
                    'name' => esc_html__( 'How many listings are included?', 'houzez' ),
                    'placeholder' => esc_html__( 'Enter the number of listings', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,

                ),
                array(
                    'id' => "{$houzez_prefix}unlimited_listings",
                    'name' => esc_html__( "Unlimited listings", 'houzez' ),
                    'type' => 'checkbox',
                    'desc' => esc_html__('Unlimited listings', 'houzez'),
                    'std' => "",
                    'columns' => 6,
                ),
				// ✅ bonus_renouvellement
				 array(
                    'id' => "{$houzez_prefix}bonus_renouvellement",
                    'name' => esc_html__('Bonus renouvellement', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array(
                        '0%' => esc_html__('0%', 'houzez'),
                        '5%' => esc_html__('5%', 'houzez'),
                        '10%' => esc_html__('10%', 'houzez'),
                        '15%' => esc_html__('15%', 'houzez'),
                        '20%' => esc_html__('20%', 'houzez'),
                    ),
                    'columns' => 6,
                )
                ,
				
				// ✅ tableau_bord
				    array(
                    'id' => "{$houzez_prefix}tableau_bord",
                    'name' => esc_html__('Type tableau de Bord', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array('Basique' => esc_html__('Basique (boost- Top et Urgent)', 'houzez'), 
									   'Intermidiaire' => esc_html__('Intermidiaire (Statistiques Nombre de vues- clics- et leads)', 'houzez'), 
									   'avance'=> esc_html__('Avancé (Tous + API intégration CRM, site web agence)', 'houzez'), ),
                ),
				
				// ✅ badge_professionnel
				 array(
                    'id' => "{$houzez_prefix}badge_professionnel",
                    'name' => esc_html__('Badge professionnel (Affiché au Grand Public)', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array(
										'Selectionner' => esc_html__('Selectionner', 'houzez'), 
										'Badge Magasin' => esc_html__('Badge Magasin', 'houzez'), 
									    'Badge Professionnel' => esc_html__('Badge Professionnel', 'houzez'), ),
                    'columns' => 6,
                ),
				
				// ✅ memeber_team 
				array(
                    'id' => "{$houzez_prefix}memeber_team",
                    'name' => esc_html__('Membres dÉquipe (Affiché au Grand Public)', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array(
						'0' => esc_html__('0 Membre', 'houzez'), 
						'3' => esc_html__('3 Membres d Équipe', 'houzez'), 
						'10' => esc_html__('10 Membres d Équipe', 'houzez'), ),
                    'columns' => 6,
                ),
				  // ✅ Nouveau champ : Vitrine Exclusive 
                array(
                    'id' => "{$houzez_prefix}vitrine",
                    'name' => esc_html__('Vitrine Exclusive (Affiché au Grand Public)', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array('yes' => esc_html__('Visible', 'houzez'), 'no' => esc_html__('Non Visible', 'houzez'), ),
                    'columns' => 6,
                ),
                // ✅ Nouveau champ : Logo & Marque 
                array(
                    'id' => "{$houzez_prefix}logo_marque",
                    'name' => esc_html__('Logo & Marque (Affiché au Grand Public)', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array('yes' => esc_html__('Visible', 'houzez'), 'no' => esc_html__('Non Visible', 'houzez'), ),
                    'columns' => 6,
                ),
                // ✅ Nouveau champ : Plan 
                array(
                    'id' => "{$houzez_prefix}plan",
                    'name' => esc_html__('Plan (Affiché au Grand Public)', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array(
						'Selectionner' => esc_html__('Selectionner', 'houzez'), 
						'Plan de Standard+' => esc_html__('Plan de Standard+', 'houzez'), 
						'Plan Business+' => esc_html__('Plan Business+', 'houzez'), ),
                    'columns' => 6,
                ),
                // ✅ Nouveau champ : Visibilité
                array(
                    'id' => "{$houzez_prefix}visibilite",
                    'name' => esc_html__('Visibilité (Affiché au Grand Public)', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array(
						'Selectionner' => esc_html__('Selectionner', 'houzez'), 
						'Visibilité +' => esc_html__('Visibilité +', 'houzez'), 
						'Visibilité +++' => esc_html__('Visibilité +++', 'houzez'), ),
                    'columns' => 6,
                ),

				
				
				
				
				 // ✅ Nouveau champ : Visibilité
				array(
                    'id' => "{$houzez_prefix}boosts",
                    'name' => esc_html__('Programmation des Boosts (Affiché au Grand Public)', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array('yes' => esc_html__('Visible', 'houzez'), 'no' => esc_html__('Non Visible', 'houzez'), ),
                    'columns' => 6,
                ),
				  // ✅ Nouveau champ : Visibilité
                array(
                    'id' => "{$houzez_prefix}stats",
                    'name' => esc_html__('Statistiques (Affiché au Grand Public)', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array('
										no' => esc_html__('Non Visible', 'houzez'), 
									   'Statistiques de Visibilité' => esc_html__('Statistiques de Visibilité', 'houzez'), 
									   'Statistiques Approfondies' => esc_html__('Statistiques Approfondies', 'houzez'), ),
                    'columns' => 6,
                ),
				// ✅ Nouveau champ : Visibilité
                array(
                    'id' => "{$houzez_prefix}support",
                    'name' => esc_html__('Support (Affiché au Grand Public)', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array(
						'Accompagnement & Support' => esc_html__('Accompagnement & Support', 'houzez'), 
						'Support Prioritaire VIP' => esc_html__('Support Prioritaire VIP', 'houzez'), 
						'Support Whatsapp' => esc_html__('Support Whatsapp', 'houzez'), ),
                    'columns' => 6,
                ),
				
				

            ),
        );
		
		
		
		
		
		$meta_boxes[] = array(
            'title' => esc_html__('Communication', 'houzez'),
            'post_types' => array('houzez_packages'),
            'fields' => array(


                 // ✅ Nouveau champ : Support WhatsApp 
                array(
                    'id' => "{$houzez_prefix}support_whatsapp",
                    'name' => esc_html__('Discussion WhatsApp', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array(
                        'yes' => esc_html__('Yes', 'houzez'),
                        'no' => esc_html__('No', 'houzez'),
                    ),
                    'columns' => 6,
                ),
                // ✅ Nouveau champ : Support Email 
                array(
                    'id' => "{$houzez_prefix}support_email",
                    'name' => esc_html__('Support Email', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array(
                        'yes' => esc_html__('Yes', 'houzez'),
                        'no' => esc_html__('No', 'houzez'),
                    ),
                    'columns' => 6,
                ),
                // ✅ Nouveau champ : Support Ouverture de ticket 
                array(
                    'id' => "{$houzez_prefix}support_ticket",
                    'name' => esc_html__('Support Ouverture de ticket', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array('yes' => esc_html__('Yes', 'houzez'), 
									   'no' => esc_html__('No', 'houzez'), ),
                    'columns' => 6,
                ),
                // ✅ Nouveau champ : Support Téléphone 
                array(
                    'id' => "{$houzez_prefix}support_telephone",
                    'name' => esc_html__('Support téléphone', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array('yes' => esc_html__('Yes', 'houzez'), 
									   'no' => esc_html__('No', 'houzez'), ),
                    'columns' => 6,
                ),
                // ✅ Nouveau champ : Support Appel centre 
                array(
                    'id' => "{$houzez_prefix}support_appel_centre",
                    'name' => esc_html__('Support Appel centre', 'houzez'),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array('yes' => esc_html__('Yes', 'houzez'), 
									   'no' => esc_html__('No', 'houzez'), ),
                    'columns' => 6,
                ),

            ),
        );


		
	
        

        return apply_filters('houzez_packages_meta', $meta_boxes);

    }

    add_filter( 'rwmb_meta_boxes', 'houzez_packages_metaboxes' );
}