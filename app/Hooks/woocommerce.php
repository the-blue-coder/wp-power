<?php

/**
 * Make WooCommerce functions work in WP Rest API
 */
add_filter('woocommerce_is_rest_api_request', function ($isRestAPIRequest) {
    if (empty($_SERVER['REQUEST_URI'])) {
        return $isRestAPIRequest;
    }

    if (defined('WP_POWER_APP_SLUG') && strpos($_SERVER['REQUEST_URI'], WP_POWER_APP_SLUG) === false) {
        return $isRestAPIRequest;
    }

    return false;
});

/**
 * Add WooCommerce theme support
 */
add_action('after_setup_theme', function () {
    if (!defined('APP_TD')) {
        add_theme_support('woocommerce');
    }
});

/**
 * Templates overriding
 */
add_filter('woocommerce_locate_template', function ($template, $templateName, $templatePath) {
    $isThemosis = defined('APP_TD');
    $viewsDir = $isThemosis ? get_stylesheet_directory() . '/views' : WP_POWER_VIEWS_DIR;
    $re = '/woocommerce\/(templates\/)?(.*)/m';

    preg_match($re, $template, $matches);

    if (isset($matches[2]) && !empty($matches[2]) && file_exists($viewsDir . '/woocommerce/' . $matches[2])) {
        $template = $viewsDir . '/woocommerce/' . $matches[2];
    }

    return $template;
}, 10, 3);