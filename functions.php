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
 * Hooks
 */
require 'app/hooks/updates-handler.php';
require 'app/hooks/assets.php';
require 'app/hooks/templates.php';
require 'app/hooks/post-types.php';
require 'app/hooks/post-statuses.php';
require 'app/hooks/taxonomies.php';
require 'app/hooks/user-taxonomies.php';
require 'app/hooks/shortcodes.php';
require 'app/hooks/meta-boxes.php';
require 'app/hooks/user-roles.php';
require 'app/hooks/woocommerce.php';
require 'app/hooks/misc.php';

/**
 * WP Rest API
 */
add_action('rest_api_init', function () {
    require 'routes/wp-rest-api.php';
});