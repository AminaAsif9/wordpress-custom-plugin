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

    // Add setting for YouTube video background
    $wp_customize->add_setting('smm_youtube_bg_video', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    // Add control for YouTube video background
    $wp_customize->add_control('smm_youtube_bg_video_control', array(
        'label'    => __('YouTube Video Background URL', 'smm'),
        'section'  => 'smm_settings_section',
        'settings' => 'smm_youtube_bg_video',
        'type'     => 'url',
    ));
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
        // Get the YouTube video background URL
        $youtube_bg_video = get_theme_mod('smm_youtube_bg_video');

        // Default background image URL
        $default_bg_image = plugins_url('assets/img/coming-soon.png', __FILE__);

        // Output the maintenance mode page
        echo '<div style="position: relative; width: 100%; height: 100vh; overflow: hidden; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; font-family: Arial, sans-serif; color: white;">';

        if ($youtube_bg_video) {
            // Embed YouTube video as background
            echo '<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: -1;" src="https://www.youtube.com/embed/' . esc_attr(extract_youtube_id($youtube_bg_video)) . '?autoplay=1&mute=1&loop=1&playlist=' . esc_attr(extract_youtube_id($youtube_bg_video)) . '&controls=0&showinfo=0&autohide=1&modestbranding=1" frameborder="0" allow="autoplay; loop; muted" allowfullscreen></iframe>';
        } elseif ($mobile_bg_image) {
            // Apply background image if it exists
            echo '<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;  z-index: -1; background-image: url(' . esc_url($mobile_bg_image) . '); background-size: cover; background-position: center;"></div>';
        } else {
            // Apply default background image
            echo '<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;  z-index: -1; background-image: url(' . esc_url($default_bg_image) . '); background-size: cover; background-position: center;"></div>';
        }

        // Content on top of the background
        echo '<h1 style="font-size: 46px; font-weight: bold; margin: 0;">Under Construction</h1>';
        echo '<h2 style="font-size: 22px; margin-top: 10px;">We will be back soon.</h2>';
        echo '</div>';

        exit;
    }
}
add_action('template_redirect', 'smm_maintenance_mode');

// Helper function to extract YouTube video ID from URL
function extract_youtube_id($url) {
    parse_str(parse_url($url, PHP_URL_QUERY), $query);
    return $query['v'] ?? '';
}

?>
