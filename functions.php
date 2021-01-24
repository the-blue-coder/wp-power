<?php

/**
 * Composer
 */
require 'vendor/autoload.php';

/**
 * Configs
 */
require 'config/paths.php';
require 'config/api-keys.php';
require 'config/woocommerce.php';

/**
 * Helpers
 */
require 'app/helpers/custom-functions.php';

/**
 * Services
 */


/**
 * WP
 */
require 'app/wp/assets.php';
require 'app/wp/actions.php';
require 'app/wp/filters.php';
require 'app/wp/woocommerce.php';
require 'app/wp/templates.php';
require 'app/wp/post-types.php';
require 'app/wp/shortcodes.php';

/**
 * WP Rest API
 */
add_action('rest_api_init', function () {
    require 'app/wp/wp-rest-api.php';
});