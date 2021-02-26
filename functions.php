<?php

/**
 * Composer
 */
require 'vendor/autoload.php';

/**
 * Configs
 */
require 'config/global.php';
require 'config/database.php';
require 'config/paths.php';
require 'config/mail.php';
require 'config/api-keys.php';
require 'config/api-endpoints.php';
require 'config/woocommerce.php';

/**
 * Helpers
 */
require 'app/Helpers/custom-functions.php';

/**
 * Hooks
 */
require 'app/Hooks/updates-handler.php';
require 'app/Hooks/assets.php';
require 'app/Hooks/templates.php';
require 'app/Hooks/post-types.php';
require 'app/Hooks/post-statuses.php';
require 'app/Hooks/taxonomies.php';
require 'app/Hooks/user-taxonomies.php';
require 'app/Hooks/shortcodes.php';
require 'app/Hooks/meta-boxes.php';
require 'app/Hooks/user-roles.php';
require 'app/Hooks/wp-crons.php';
require 'app/Hooks/woocommerce.php';
require 'app/Hooks/misc.php';

/**
 * WP Rest API
 */
add_action('rest_api_init', function () {
    require 'routes/wp-rest-api.php';
});