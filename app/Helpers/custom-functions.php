<?php

/**
 * Create directory
 */
if (!function_exists('createDir')) {
    function createDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
    
        return $dirPath;
    }
}

/**
 * Url origin
 */
if (!function_exists('urlOrigin')) {
    function urlOrigin( $s, $use_forwarded_host = false )
    {
        $ssl = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
        $sp = strtolower( $s['SERVER_PROTOCOL'] );
        $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
        $port = $s['SERVER_PORT'];
        $port = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
        $host = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
        $host = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }
}

/**
 * Full url
 */
if (!function_exists('getFullUrl')) {
    function getFullUrl( $s, $use_forwarded_host = false )
    {
        return urlOrigin($s, $use_forwarded_host) . $s['REQUEST_URI'];
    }
}

/**
 * Get dist url
 */
if (!function_exists('distUrl')) {
    function distUrl($path = '')
    {
        return get_stylesheet_directory_uri() . '/dist' . ($path !== '' ? '/' . $path : '');
    }
}

/**
 * Get user data
 */
if (!function_exists('user')) {
    function user($userID = false)
    {
        $userID = $userID ? $userID : get_current_user_ID();

        return get_userdata($userID);
    }
}

/**
 * Get user meta
 */
if (!function_exists('userMeta')) {
    function userMeta($userID = false)
    {
        $userID = $userID ? $userID : get_current_user_ID();
        
        return get_user_meta($userID);
    }
}

/**
 * Get view html
 */
if (!function_exists('view')) {
    function view($path, $data = [])
    {
        extract($data);

        $path = str_replace('.', '/', $path);

        ob_start();
            include(WP_POWER_VIEWS_DIR . '/resources/views/' . $path . '.php');
        return ob_get_clean();
    }
}

/**
 * 404 error abort
 */
if (!function_exists('abort_404')) {
    function abort_404()
    {
        global $wp_query;

        $wp_query->set_404();

        status_header(404);
        get_template_part(404);

        exit;
    }
}

/**
 * Slugify string
 */
if (!function_exists('slugify')) {
    function slugify($string)
    {
        $string = preg_replace('~[^\pL\d]+~u', '-', $string);
        $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
        $string = preg_replace('~[^-\w]+~', '', $string);
        $string = trim($string, '-');
        $string = preg_replace('~-+~', '-', $string);
        $string = strtolower($string);

        if (empty($string)) {
            return 'n-a';
        }

        return $string;
    }
}

/**
 * In array multidimensional
 */
if (!function_exists('inArrayR')) {
    function inArrayR($needle, $haystack, $strict = false)
    {
        if (is_array($haystack) && !empty($haystack)) {
            foreach ($haystack as $item) {
                if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && inArrayR($needle, $item, $strict))) {
                    return true;
                }
            }
        }

        return false;
    }
}

/**
 * Load template from a location
 */
if (!function_exists('customTemplatePartLoading')) {
    function customTemplatePartLoading($filename)
    {
        add_filter('page_template', function ($template) use ($filename) {
            $post = get_post();
            $pageTemplate = get_post_meta($post->ID, '_wp_page_template', true);

            if (basename($pageTemplate) === $filename) {
                $template = dirname(dirname(dirname(__FILE__))) . '/resources/views/pages/' . $filename;
            }
                
            return $template;
        });
    }
}

/**
 * Photoshop letter spacing to pt
 */
if (!function_exists('PSLetterSpacingToPt')) {
    function PSLetterSpacingToPt($PSLetterSpacing, $ptFontSize)
    {
        return $PSLetterSpacing * $ptFontSize * 1.3281472327365 / 1000;
    }
}

/**
 * Convert PNG image to JPG
 */
if (!function_exists('PNGToJPG')) {
    function PNGToJPG($filePath, $extension = 'jpg')
    {
        $fileDirPath = dirname($filePath);
        $filename = basename($filePath, '.png');
        $outputFilePath = $fileDirPath . '/' . $filename . '.' . $extension;
        $image = imagecreatefrompng($filePath);
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        $quality = 100;

        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);
        imagejpeg($bg, $outputFilePath, $quality);
        imagedestroy($bg);

        return $outputFilePath;
    }
}

/**
 * Convert RGB image to CMYK
 */
if (!function_exists('RGBToCMYK')) {
    function RGBToCMYK($fileDirPath, $filenameWithExt, $iccProfile, $pngNewExt = 'jpg', $getFullPath = false)
    {
        $filePath = $fileDirPath . (endsWith($fileDirPath, '/') || endsWith($fileDirPath, '\\') ? '' : '/') . $filenameWithExt;
        $filePathInfo = pathinfo($filePath);
        $outputFilename = 'cmyk_' . $filePathInfo['filename'];
        $outputFileExt = $filePathInfo['extension'];
        
        if (strtolower($outputFileExt) === 'png') {
            $filePath = PNGToJPG($filePath, $pngNewExt);
            $outputFileExt = $pngNewExt;
        }
        
        $outputFilePath = $filePathInfo['dirname'] . '/' . $outputFilename . '.' . $outputFileExt;

        $image = new Imagick();
        $iccCmyk = file_get_contents($iccProfile);

        $image->clear();
        $image->readImage($filePath);

        if ($image->getImageColorspace() === Imagick::COLORSPACE_CMYK) { 
            return $filenameWithExt;
        }

        $image->profileImage('icc', $iccCmyk);

        unset($iccCmyk);

        $image->transformImageColorspace(Imagick::COLORSPACE_CMYK);
        $image->writeImage($outputFilePath);

        if ($getFullPath) {
            return $fileDirPath . (endsWith($fileDirPath, '/') || endsWith($fileDirPath, '\\') ? '' : '/') . $outputFilename . '.' . $outputFileExt;
        }
        
        return $outputFilename . '.' . $outputFileExt;
    }
}

/**
 * Custom redirect function
 */
if (!function_exists('customRedirect')) {
    function customRedirect($location, $status = 302, $xRedirectBy = 'WordPress')
    {
        if (!headers_sent()) {
            wp_redirect($location, $status, $xRedirectBy);
        } else {
            echo '
                <script>
                    window.location.href = "' . $location . '";
                </script>
            ';
        }
    }
}

/**
 * Check if string starts with
 */
if (!function_exists('startsWith')) {
    function startsWith($haystack, $needle) 
    {
        $length = strlen($needle);

        return substr($haystack, 0, $length) === $needle;
    }
}

/**
 * Check if string ends with
 */
if (!function_exists('endsWith')) {
    function endsWith($haystack, $needle) 
    {
    $length = strlen( $needle );

    if (!$length) {
        return true;
    }

    return substr($haystack, -$length) === $needle;
    }
}

/**
 * Get WooCommerce variation short name (without parent suffix)
 */
if (!function_exists('getVariationShortName')) {
    function getVariationShortName($variation, $parentProduct)
    {
        $variationName = $variation->get_name();
        $parentProductName = $parentProduct->get_name();

        return str_replace($parentProductName . ' - ', ' ', $variationName);
    }
}

/**
 * Sort terms hierarchically
 */
if (!function_exists('sortTermsHierarchically')) {
    function sortTermsHierarchically(Array &$cats, Array &$into, $parentID = 0)
    {
        foreach ($cats as $i => $cat) {
            if ($cat->parent == $parentID) {
                $into[$cat->term_id] = $cat;
                unset($cats[$i]);
            }
        }

        foreach ($into as $topCat) {
            $topCat->children = [];
            sortTermsHierarchically($cats, $topCat->children, $topCat->term_id);
        }
    }
}

/**
 * Get recaptcha response
 */
if (!function_exists('getRecaptchaResponse')) {
    function getRecaptchaResponse($recaptchaToken)
    {
        $recaptchaResponse = file_get_contents(WP_POWER_RECAPTCHA_URL . '?secret=' . WP_POWER_API_KEYS['google']['recaptcha']['secretKey'] . '&response=' . $recaptchaToken);
        
        return json_decode($recaptchaResponse);
    }
}

/**
 * Check if recaptcha is good to go
 */
if (!function_exists('isRecaptchaValid')) {
    function isRecaptchaValid($recaptchaResponse, $action)
    {
        if (!is_object($recaptchaResponse)) {
            return false;
        }
        
        return $recaptchaResponse->success && $recaptchaResponse->score > 0.5 && $recaptchaResponse->action === $action;
    }
}

/**
 * Validate recaptcha
 */
if (!function_exists('validateRecaptcha')) {
    function validateRecaptcha($recaptchaToken, $action)
    {
        $recaptchaResponse = getRecaptchaResponse($recaptchaToken);
        return isRecaptchaValid($recaptchaResponse, $action);
    }
}

 /**
 * Get IP info
 */
if (!function_exists('getIPInfo')) {
    function getIPInfo($ip = NULL, $purpose = "location", $deep_detect = TRUE) 
    {
        $output = NULL;

        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }

        $purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );

        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }

        return $output;
    }
}

/**
 * Check if a post type has some posts (WordPress)
 */
if (!function_exists('WPCheckIfPostTypeHasPosts')) {
    function WPCheckIfPostTypeHasPosts($slug)
    {
        global $wp_query;

        return $wp_query->found_posts > 0;
    }
}

/**
 * Filter posts by custom taxonomies in admin (WordPress)
 */
if (!function_exists('WPAdminCustomTaxonomyFilters')) {
    function WPAdminCustomTaxonomyFilters($cases, $selectLabel = 'Filter by', $textDomain = WP_POWER_TEXT_DOMAIN)
    {
        //Display dropdown
        add_action('restrict_manage_posts', function () use ($cases, $selectLabel, $textDomain) {
            global $typenow;
        
            foreach ($cases as $case) {
                $postType = $case['postType'];
                $taxonomies = $case['taxonomies'];
        
                if ($typenow === $postType) {
                    foreach ($taxonomies as $taxonomy) {
                        $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
                        $infoTaxonomy = get_taxonomy($taxonomy);
                        $termsNumber = wp_count_terms($infoTaxonomy->name, ['hide_empty' => true]);
            
                        if ($termsNumber > 0) {
                            wp_dropdown_categories([
                                'show_option_all' => sprintf( __($selectLabel . ' %s', $textDomain), strtolower($infoTaxonomy->labels->singular_name)),
                                'taxonomy' => $taxonomy,
                                'name' => $taxonomy,
                                'orderby' => 'name',
                                'selected' => $selected,
                                'show_count' => true,
                                'hide_empty' => true,
                            ]);
                        }
                    }
                }
            }
        });

        //Update filter query
        add_filter('parse_query', function ($query) use ($cases) {
            global $pagenow;
        
            $qVars = &$query->query_vars;
        
            foreach ($cases as $case) {
                $postType = $case['postType'];
                $hasPosts = WPCheckIfPostTypeHasPosts($postType);
                $taxonomies = $case['taxonomies'];
        
                foreach ($taxonomies as $taxonomy) {
                    $infoTaxonomy = get_taxonomy($taxonomy);
                    $termsNumber = wp_count_terms($infoTaxonomy->name, ['hide_empty' => false]);

                    if (
                        $pagenow === 'edit.php' && 
                        isset($qVars['post_type']) && 
                        $qVars['post_type'] === $postType && 
                        isset($qVars[$taxonomy]) && 
                        is_numeric($qVars[$taxonomy]) && 
                        (int) $qVars[$taxonomy] !== 0 &&
                        $termsNumber > 0
                    ) 
                    {
                        $term = get_term_by('id', $qVars[$taxonomy], $taxonomy);
                        $qVars[$taxonomy] = $term->slug;
                    }
                }
            }
        });
    }
}

/**
 * Create custom post status (WordPress)
 */
if (!function_exists('WPCreateCustomPostStatus')) {
    function WPCreateCustomPostStatus($postType, $label, $slug)
    {
        add_action('init', function () use ($postType, $label, $slug) {
            register_post_status(
                $slug, 
                [
                    'label' => _x($label, $postType),
                    'label_count' => _n_noop($label . ' <span class="count">(%s)</span>', $label .' <span class="count">(%s)</span>'),
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true
                ]
            );
        });
        
        add_action('post_submitbox_misc_actions', function () use ($postType, $label, $slug) {
            global $post;
        
            if (is_object($post) && $post->post_type !== $postType) {
                return false;
            }
        
            $status = '';
        
            if (is_object($post) && $post->post_status === $slug) {
                $status = "
                    jQuery('#post-status-display').text('" . $label . "');
                    jQuery('select[name=\"post_status\"]' ).val('" . $slug . "');
                ";
            }
            
            echo "
                <script>
                    jQuery(document).ready(function() {
                        jQuery('select[name=\"post_status\"]' ).append( '<option value=\"" . $slug . "\">" . $label . "</option>' );
                        " . $status . "
                    });
                </script>
            ";
        });
        
        add_action('admin_footer-edit.php', function () use ($postType, $label, $slug) {
            global $post;
        
            if (is_object($post) && $post->post_type !== $postType) {
                return false;
            }
            
            echo "
                <script>
                    jQuery(document).ready( function() {
                        jQuery('select[name=\"_status\"]' ).append( '<option value=\"" . $slug . "\">" . $label . "</option>');
                    });
                </script>
            ";
        });
        
        add_filter('display_post_states', function ($states) use ($postType, $label, $slug) {
            global $post;
        
            $arg = get_query_var('post_status');
        
            if (is_object($post) && $arg !== $slug){
                if ($post->post_status === $slug) {
                    echo "
                        <script>
                            jQuery(document).ready(function() {
                                jQuery('#post-status-display').text('" . $label . "');
                            });
                        </script>
                    ";
        
                    return [$label];
                }
            }
        
            return $states;
        });
    }
}

/**
 * Remove directory recursively
 */
if (!function_exists('recursiveRemoveDirectory')) {
    function recursiveRemoveDirectory($directory)
    {
        foreach (glob("{$directory}/*") as $file) {
            if (is_dir($file)) { 
                recursiveRemoveDirectory($file);
            } else if (!is_link($file)) {
                unlink($file);
            }
        }
    
        rmdir($directory);
    }
}

/**
 * Convert array snake case to camel case
 */
if (!function_exists('convertKeysToCamelCase')) {
    function convertKeysToCamelCase($array) 
    {
        $keys = array_map(function ($i) {
            $parts = explode('_', $i);
            return array_shift($parts). implode('', array_map('ucfirst', $parts));
        }, array_keys($array));

        return array_combine($keys, $array);
    }
}

/**
 * Sort array of datetime strings
 */
if (!function_exists('usortDateTimeStringsArray')) {
    function usortDateTimeStringsArray($array)
    {
        usort($array, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });

        return $array;
    }
}

/**
 * Key sort array of datetime strings
 */
if (!function_exists('uksortDateTimeStringsArray')) {
    function uksortDateTimeStringsArray($array)
    {
        uksort($array, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });

        return $array;
    }
}

/**
 * Custom paginate links (copie from WordPress paginate_links
 * and removed the "Merge additional query vars found in the original URL into 'add_args' array." part
 */
if (!function_exists('paginateLinks')) {
    function paginateLinks( $args = '' ) 
    {
        global $wp_query, $wp_rewrite;

        // Setting up default values based on the current URL.
        $pagenum_link = html_entity_decode( get_pagenum_link() );
        $url_parts    = explode( '?', $pagenum_link );

        // Get max pages and current page out of the current query, if available.
        $total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
        $current = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;

        // Append the format placeholder to the base URL.
        $pagenum_link = trailingslashit( $url_parts[0] ) . '%_%';

        // URL base depends on permalink settings.
        $format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
        $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

        $defaults = array(
            'base'               => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below).
            'format'             => $format, // ?page=%#% : %#% is replaced by the page number.
            'total'              => $total,
            'current'            => $current,
            'aria_current'       => 'page',
            'show_all'           => false,
            'prev_next'          => true,
            'prev_text'          => __( '&laquo; Previous' ),
            'next_text'          => __( 'Next &raquo;' ),
            'end_size'           => 1,
            'mid_size'           => 2,
            'type'               => 'plain',
            'add_args'           => array(), // Array of query args to add.
            'add_fragment'       => '',
            'before_page_number' => '',
            'after_page_number'  => '',
        );

        $args = wp_parse_args( $args, $defaults );

        if ( ! is_array( $args['add_args'] ) ) {
            $args['add_args'] = array();
        }

        // Merge additional query vars found in the original URL into 'add_args' array.
        // if ( isset( $url_parts[1] ) ) {
        // 	// Find the format argument.
        // 	$format       = explode( '?', str_replace( '%_%', $args['format'], $args['base'] ) );
        // 	$format_query = isset( $format[1] ) ? $format[1] : '';
        // 	wp_parse_str( $format_query, $format_args );

        // 	// Find the query args of the requested URL.
        // 	wp_parse_str( $url_parts[1], $url_query_args );

        // 	// Remove the format argument from the array of query arguments, to avoid overwriting custom format.
        // 	foreach ( $format_args as $format_arg => $format_arg_value ) {
        // 		unset( $url_query_args[ $format_arg ] );
        // 	}

        // 	$args['add_args'] = array_merge( $args['add_args'], urlencode_deep( $url_query_args ) );
        // }

        // Who knows what else people pass in $args.
        $total = (int) $args['total'];
        if ( $total < 2 ) {
            return;
        }
        $current  = (int) $args['current'];
        $end_size = (int) $args['end_size']; // Out of bounds? Make it the default.
        if ( $end_size < 1 ) {
            $end_size = 1;
        }
        $mid_size = (int) $args['mid_size'];
        if ( $mid_size < 0 ) {
            $mid_size = 2;
        }

        $add_args   = $args['add_args'];
        $r          = '';
        $page_links = array();
        $dots       = false;

        if ( $args['prev_next'] && $current && 1 < $current ) :
            $link = str_replace( '%_%', 2 == $current ? '' : $args['format'], $args['base'] );
            $link = str_replace( '%#%', $current - 1, $link );
            if ( $add_args ) {
                $link = add_query_arg( $add_args, $link );
            }
            $link .= $args['add_fragment'];

            $page_links[] = sprintf(
                '<a class="prev page-numbers" href="%s">%s</a>',
                /**
                 * Filters the paginated links for the given archive pages.
                 *
                 * @since 3.0.0
                 *
                 * @param string $link The paginated link URL.
                 */
                esc_url( apply_filters( 'paginate_links', $link ) ),
                $args['prev_text']
            );
        endif;

        for ( $n = 1; $n <= $total; $n++ ) :
            if ( $n == $current ) :
                $page_links[] = sprintf(
                    '<span aria-current="%s" class="page-numbers current">%s</span>',
                    esc_attr( $args['aria_current'] ),
                    $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number']
                );

                $dots = true;
            else :
                if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
                    $link = str_replace( '%_%', 1 == $n ? '' : $args['format'], $args['base'] );
                    $link = str_replace( '%#%', $n, $link );
                    if ( $add_args ) {
                        $link = add_query_arg( $add_args, $link );
                    }
                    $link .= $args['add_fragment'];

                    $page_links[] = sprintf(
                        '<a class="page-numbers" href="%s">%s</a>',
                        /** This filter is documented in wp-includes/general-template.php */
                        esc_url( apply_filters( 'paginate_links', $link ) ),
                        $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number']
                    );

                    $dots = true;
                elseif ( $dots && ! $args['show_all'] ) :
                    $page_links[] = '<span class="page-numbers dots">' . __( '&hellip;' ) . '</span>';

                    $dots = false;
                endif;
            endif;
        endfor;

        if ( $args['prev_next'] && $current && $current < $total ) :
            $link = str_replace( '%_%', $args['format'], $args['base'] );
            $link = str_replace( '%#%', $current + 1, $link );
            if ( $add_args ) {
                $link = add_query_arg( $add_args, $link );
            }
            $link .= $args['add_fragment'];

            $page_links[] = sprintf(
                '<a class="next page-numbers" href="%s">%s</a>',
                /** This filter is documented in wp-includes/general-template.php */
                esc_url( apply_filters( 'paginate_links', $link ) ),
                $args['next_text']
            );
        endif;

        switch ( $args['type'] ) {
            case 'array':
                return $page_links;

            case 'list':
                $r .= "<ul class='page-numbers'>\n\t<li>";
                $r .= implode( "</li>\n\t<li>", $page_links );
                $r .= "</li>\n</ul>\n";
                break;

            default:
                $r = implode( "\n", $page_links );
                break;
        }

        return $r;
    }
}