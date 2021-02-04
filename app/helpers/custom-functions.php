<?php

/**
 * Create directory
 */
function createDir($dirPath)
{
    if (!is_dir($dirPath)) {
        mkdir($dirPath, 0777, true);
    }

    return $dirPath;
}

/**
 * Url origin
 */
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

/**
 * Full url
 */
function fullUrl( $s, $use_forwarded_host = false )
{
    return urlOrigin($s, $use_forwarded_host) . $s['REQUEST_URI'];
}

/**
 * Get user data
 */
function user($userID = false)
{
    $userID = $userID ? $userID : get_current_user_ID();

    return get_userdata($userID);
}

/**
 * Get user meta
 */
function userMeta($userID = false)
{
    $userID = $userID ? $userID : get_current_user_ID();
    
    return get_user_meta($userID);
}

/**
 * Get view html
 */
function view($path, $data = [])
{
    extract($data);

    $path = str_replace('.', '/', $path);

    ob_start();
        include(WP_POWER_VIEWS_DIR . '/resources/views/' . $path . '.php');
    return ob_get_clean();
}

/**
 * 404 error abort
 */
function abort_404()
{
    global $wp_query;

    $wp_query->set_404();

    status_header(404);
    get_template_part(404);

    exit;
}

/**
 * Slugify string
 */
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

/**
 * In array multidimensional
 */
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

/**
 * Load template from a location
 */
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

/**
 * Photoshop letter spacing to pt
 */
function PSLetterSpacingToPt($PSLetterSpacing, $ptFontSize)
{
    return $PSLetterSpacing * $ptFontSize * 1.3281472327365 / 1000;
}

/**
 * Convert PNG image to JPG
 */
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

/**
 * Convert RGB image to CMYK
 */
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

/**
 * Custom redirect function
 */
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

/**
 * Check if string starts with
 */
function startsWith($haystack, $needle) 
{
    $length = strlen($needle);

    return substr($haystack, 0, $length) === $needle;
}

/**
 * Check if string ends with
 */
function endsWith($haystack, $needle) 
{
   $length = strlen( $needle );

   if (!$length) {
       return true;
   }

   return substr($haystack, -$length) === $needle;
}

/**
 * Get WooCommerce variation short name (without parent suffix)
 */
function getVariationShortName($variation, $parentProduct)
{
    $variationName = $variation->get_name();
    $parentProductName = $parentProduct->get_name();

    return str_replace($parentProductName . ' - ', ' ', $variationName);
}

/**
 * Sort terms hierarchically
 */
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

/**
 * Get recaptcha response
 */
function getRecaptchaResponse($recaptchaToken)
{
    $recaptchaResponse = file_get_contents(WP_POWER_RECAPTCHA_URL . '?secret=' . WP_POWER_API_KEYS['google']['recaptcha']['secretKey'] . '&response=' . $recaptchaToken);
    
    return json_decode($recaptchaResponse);
}

/**
 * Check if recaptcha is good to go
 */
function recaptchaIsValid($recaptchaResponse, $action)
{
	return $recaptchaResponse->success && $recaptchaResponse->score > 0.5 && $recaptchaResponse->action === $action;
}