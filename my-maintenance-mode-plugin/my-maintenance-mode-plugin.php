<?php
/** 
*Plugin Name: my maintenance mode plugin
*Plugin URL: http://wordpress.org
*Description: This is a maintenance mode/ Under construction plugin for WordPress.
*Author: Amina
*Version: 1.0.0
*/


// Enqueue Customizer JavaScript
function smm_customize_preview_js() {
    wp_enqueue_script('smm-customize-preview', plugins_url('/customizer.js', __FILE__), array('customize-preview'), null, true);
}
add_action('customize_preview_init', 'smm_customize_preview_js');

// Add Customizer settings
function smm_customize_register($wp_customize) {
    // Add section for maintenance mode settings
    $wp_customize->add_section('smm_settings_section', array(
        'title'    => __('Background', 'smm'),
        'priority' => 30,
    ));

    // Add setting for mobile background image
    $wp_customize->add_setting('smm_mobile_bg_image');

    // Add control for mobile background image
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'smm_mobile_bg_image_control', array(
        'label'    => __('Add Mobile Background Image', 'smm'),
        'section'  => 'smm_settings_section',
        'settings' => 'smm_mobile_bg_image',
    )));
}
add_action('customize_register', 'smm_customize_register');

// Function to display a simple maintenance mode page
function smm_maintenance_mode() {
    // Check if we're not on an admin page and if the request is not from an AJAX call
    if (!is_admin() && !defined('DOING_AJAX')) {
        // Set the HTTP headers
        header('HTTP/1.1 503 Service Unavailable');
        header('Status: 503 Service Unavailable');
        header('Retry-After: 3600');

        // Get the mobile background image URL
        $mobile_bg_image = get_theme_mod('smm_mobile_bg_image');

        // Default background image URL
        // Default background image URL
        $default_bg_image = plugins_url('assets/img/coming-soon.png', __FILE__);

        // Output the centered text with styles and background image for mobile
        echo '<div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh; text-align: center; font-family: Arial, sans-serif;';
        
        // Apply background image if it exists, otherwise use the default
        if ($mobile_bg_image) {
            echo ' background-image: url(' . esc_url($mobile_bg_image) . ');';
        } else {
            echo ' background-image: url(' . esc_url($default_bg_image) . ');';
        }

        echo ' background-size: cover; background-position: center;">';
        echo '<h1 style="font-size: 46px; font-weight: bold; margin: 0; color: white;">Under Construction</h1>';
        echo '<h2 style="font-size: 22px; margin-top: 10px; color: white;">We will be back soon.</h2>';
        echo '</div>';
        
        exit;
    }
}

// Hook the function to display the page before any content is rendered
add_action('template_redirect', 'smm_maintenance_mode');

?>