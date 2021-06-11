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
add_filter('woocommerce_template_path', function(){
	// return 'views/e-shop/woo-templates/';
});