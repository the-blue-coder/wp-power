<?php

/**
 * Make WooCommerce functions work in WP Rest API
 */
add_filter('woocommerce_is_rest_api_request', function ($isRestAPIRequest) {
    if (empty($_SERVER['REQUEST_URI'])) {
        return $isRestAPIRequest;
    }

    if (strpos($_SERVER['REQUEST_URI'], 'wp-power') === false) {
        return $isRestAPIRequest;
    }

    return false;
});

/**
 * Add WooCommerce theme support
 */
add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
});

/**
 * Templates overriding
 */
add_filter('woocommerce_locate_template', function ($template, $templateName, $templatePath) {
    $re = '/woocommerce\/(templates\/)?(.*)/m';

    preg_match($re, $template, $matches);

    if (isset($matches[2]) && !empty($matches[2]) && file_exists( WP_POWER_VIEWS_DIR . '/woocommerce/' . $matches[2] )) {
        $template = WP_POWER_VIEWS_DIR . '/woocommerce/' . $matches[2];
    }

    return $template;
}, 10, 3);