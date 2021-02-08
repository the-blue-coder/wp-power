<?php

/**
 * Composer
 */
require 'vendor/autoload.php';

/**
 * Configs
 */
require 'config/global.php';
require 'config/mail.php';
require 'config/paths.php';
require 'config/api-keys.php';
require 'config/api-endpoints.php';
require 'config/woocommerce.php';

/**
 * Helpers
 */
require 'app/helpers/custom-functions.php';

/**
 * WP
 */
require 'app/wp/updates-handler.php';
require 'app/wp/assets.php';
require 'app/wp/hooks.php';
require 'app/wp/woocommerce.php';
require 'app/wp/templates.php';
require 'app/wp/post-types.php';
require 'app/wp/shortcodes.php';
require 'app/wp/meta-boxes.php';

/**
 * WP Rest API
 */
add_action('rest_api_init', function () {
    require 'routes/wp-rest-api.php';
});