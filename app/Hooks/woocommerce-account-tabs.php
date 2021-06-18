<?php

/**
 * The endpoints
 */
$customEndpoints = [
    
];

$externalUrlsSlugs = [
    
];

/**
 * Remove some tabs in "my account" section
 */
add_filter('woocommerce_account_menu_items', function ($menuLinks) {
    // unset($menuLinks['orders']);

    return $menuLinks;
});

/**
 * Register new endpoints slugs to use for My Account page
 */
add_action('init', function () use ($customEndpoints) {
    foreach ($customEndpoints as $slug => $label) {
        add_rewrite_endpoint($slug, EP_ROOT | EP_PAGES);
    }
});

/**
 * Add new query vars
 */
add_filter('woocommerce_get_query_vars', function ($vars) use ($customEndpoints) {
    $customEndpointsSlugs = array_keys($customEndpoints);
    $vars = array_merge($vars, $customEndpointsSlugs);
    
    return $vars;
});
  
/**
 * Insert the new endpoints into the My Account menu
 */
add_filter('woocommerce_account_menu_items', function ($menuLinks) use ($customEndpoints) {
    $menuLinks = array_slice($menuLinks, 0, 1, true)
	             + $customEndpoints 
	             + array_slice($menuLinks, 1, NULL, true)
    ;
 
	return $menuLinks;
});

/**
 * Set external urls
 */
add_filter('woocommerce_get_endpoint_url', function ($url, $endpoint, $value, $permalink) use ($externalUrlsSlugs) {
    if (in_array($endpoint, $externalUrlsSlugs)) {
        return '#';
    }

    return $url;
}, 10, 4);

/**
 * Add content to the new endpoints
 */
foreach ($customEndpoints as $slug => $label) {
    if (in_array($slug, $externalUrlsSlugs)) {
        continue;
    }

    add_action('woocommerce_account_' . $slug . '_endpoint', function () use ($slug) {
        echo 'content';
    });
}