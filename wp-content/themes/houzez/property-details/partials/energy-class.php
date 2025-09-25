<?php
global $energy_class, $ele_settings;

/**
 * Energy class data for property listings
 * Displays energy efficiency information in a structured format
 */

// Get energy data from listing
$energy_data = [
    'energy_class' => [
        'value' => $energy_class,
        'title' => isset($ele_settings['energetic_class_title']) && !empty($ele_settings['energetic_class_title']) 
                ? $ele_settings['energetic_class_title'] 
                : houzez_option('spl_energetic_cls', 'Energetic class')
    ],
    'energy_global_index' => [
        'value' => houzez_get_listing_data('energy_global_index'),
        'title' => isset($ele_settings['global_energy_index']) && !empty($ele_settings['global_energy_index']) 
                ? $ele_settings['global_energy_index'] 
                : houzez_option('spl_energy_index', 'Global energy performance index')
    ],
    'renewable_energy_index' => [
        'value' => houzez_get_listing_data('renewable_energy_global_index'),
        'title' => isset($ele_settings['renewable_energy_index']) && !empty($ele_settings['renewable_energy_index']) 
                ? $ele_settings['renewable_energy_index'] 
                : houzez_option('spl_energy_renew_index', 'Renewable energy performance index')
    ],
    'energy_performance' => [
        'value' => houzez_get_listing_data('energy_performance'),
        'title' => isset($ele_settings['energy_performance']) && !empty($ele_settings['energy_performance']) 
                ? $ele_settings['energy_performance'] 
                : houzez_option('spl_energy_build_performance', 'Energy performance of the building')
    ],
    'epc_current_rating' => [
        'value' => houzez_get_listing_data('epc_current_rating'),
        'title' => isset($ele_settings['epc_current_rating']) && !empty($ele_settings['epc_current_rating']) 
                ? $ele_settings['epc_current_rating'] 
                : houzez_option('spl_energy_ecp_rating', 'EPC Current Rating')
    ],
    'epc_potential_rating' => [
        'value' => houzez_get_listing_data('epc_potential_rating'),
        'title' => isset($ele_settings['epc_potential_rating']) && !empty($ele_settings['epc_potential_rating']) 
                ? $ele_settings['epc_potential_rating'] 
                : houzez_option('spl_energy_ecp_p', 'EPC Potential Rating')
    ]
];

// Get energy class title for the indicator
$energy_class_title = isset($ele_settings['energy_class_title']) && !empty($ele_settings['energy_class_title']) 
                    ? $ele_settings['energy_class_title'] 
                    : houzez_option('spl_energy_cls', 'Energy class');

// Get energy class array from options
$energy_array = houzez_option('energy_class_data', 'A+, A, B, C, D, E, F, G, H'); 
$energy_array = array_map('trim', explode(',', $energy_array));
$total_records = count($energy_array);
?>

<!-- Energy information list -->
<ul class="class-energy-list list-lined list-unstyled" role="list">
    <?php foreach ($energy_data as $key => $data): ?>
        <?php if (!empty($data['value'])): ?>
            <li class="d-flex justify-content-between">
                <div class="ist-lined-item w-100 py-2 justify-content-between d-flex">
                    <strong><?php echo esc_attr($data['title']); ?>:</strong>
                    <span><?php echo esc_attr($data['value']); ?></span>
                </div>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

<!-- Energy class indicator -->
<ul class="class-energy d-flex justify-content-between list-unstyled energy-class-<?php echo esc_attr($total_records); ?>" role="list">
    <?php 
    if (!empty($energy_array)) {
        foreach ($energy_array as $energy) {
            $indicator_energy = '';
            $energy = trim($energy);
            
            // Add indicator if this is the current energy class
            if ($energy == $energy_class) {
                $indicator_energy = sprintf(
                    '<div class="indicator-energy" data-energyclass="%1$s">%2$s | %3$s %1$s</div>',
                    esc_attr($energy_class),
                    esc_attr($energy_data['energy_global_index']['value']),
                    esc_attr($energy_class_title)
                );
            }
            
            echo '<li class="class-energy-indicator flex-fill">' . 
                 $indicator_energy . 
                 '<span class="energy-' . esc_attr($energy) . '">' . esc_attr($energy) . '</span>' . 
                 '</li>';
        }
    }
    ?>
</ul>