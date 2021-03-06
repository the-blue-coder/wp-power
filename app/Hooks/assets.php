<?php

/**
 * Front CSS and JS
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('bootstrap-css', WP_POWER_DIST_URL . '/css/bootstrap.min.css');
    wp_enqueue_style('custom-css', WP_POWER_DIST_URL . '/css/custom.min.css', [], rand(1, 100000));

    wp_enqueue_script('bootstrap-js', WP_POWER_DIST_URL . '/js/bootstrap.bundle.min.js', [], '', true);
    wp_enqueue_script('custom-js', WP_POWER_DIST_URL . '/js/custom.min.js', [], rand(1, 100000), true);
});

/**
 * Admin CSS and JS
 */
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('admin-css', WP_POWER_DIST_URL . '/css/admin.min.css', [], rand(1, 100000));

    wp_enqueue_script('admin-js', WP_POWER_DIST_URL . '/js/admin.min.js', [], rand(1, 100000), true);
});