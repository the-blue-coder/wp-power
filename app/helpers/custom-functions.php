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
 * Filter posts by custom taxonomies in admin
 */
if (!function_exists('adminCustomTaxonomyFilters')) {
    function adminCustomTaxonomyFilters($cases, $selectLabel = 'Filter by')
    {
        add_action('restrict_manage_posts', function () use ($cases, $selectLabel) {
            global $typenow;
        
            foreach ($cases as $case) {
                $postType = $case['postType'];
                $taxonomies = $case['taxonomies'];
        
                if ($typenow === $postType) {
                    foreach ($taxonomies as $taxonomy) {
                        $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
                        $infoTaxonomy = get_taxonomy($taxonomy);
            
                        if (is_object($infoTaxonomy)) {
                            wp_dropdown_categories(array(
                                'show_option_all' => sprintf( __($selectLabel . ' %s', 'textdomain'), strtolower($infoTaxonomy->labels->singular_name)),
                                'taxonomy' => $taxonomy,
                                'name' => $taxonomy,
                                'orderby' => 'name',
                                'selected' => $selected,
                                'show_count' => true,
                                'hide_empty' => true,
                            ));
                        }
                    }
                }
            }
        });

        add_filter('parse_query', function ($query) use ($cases) {
            global $pagenow;
        
            $qVars = &$query->query_vars;
        
            foreach ($cases as $case) {
                $postType = $case['postType'];
                $taxonomies = $case['taxonomies'];
        
                foreach ($taxonomies as $taxonomy) {
                    if (
                        $pagenow === 'edit.php' && 
                        isset($qVars['post_type']) && 
                        $qVars['post_type'] == $postType && 
                        isset($qVars[$taxonomy]) && 
                        is_numeric($qVars[$taxonomy]) && 
                        (int) $qVars[$taxonomy] !== 0 
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