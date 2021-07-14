/**
 * Global vars
 */
window.WPPower = {
    breakpoints: {
        sm: 576,
        md: 768,
        lg: 991,
        xl: 1280
    },
    
    configs: {},

    jQueryObjects: {},

    PHPToJS: {
        WPRestNonce: $('meta[name="wp-rest-nonce-token"]').attr('content')
    },

    PHPToJSAdmin: {
        
    }
};

/**
 * Ajax WP nonce token
 */
$.ajaxSetup({
    headers: {
        'X-WP-Nonce': WPPower.PHPToJS.WPRestNonce
    }
});