<?php

/**
 * Add WooCommerce theme support
 */
add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
});