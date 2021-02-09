<?php

/**
 * Disable WordPress Rest API for external requests if applicable
 */
add_action('rest_api_init', function() {
    $whitelistIPs = ['127.0.0.1', '::1'];

    if (
        !WP_POWER_ALLOW_EXTERNAL_REST_API_REQUESTS && 
        !in_array($_SERVER['REMOTE_ADDR'], $whitelistIPs)
    ) 
    {
        die('REST API is disabled.');
    }
}, 1);