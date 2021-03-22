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
        WPRestNonce: $('meta[name="nonce-token"]').attr('content')
    },

    PHPToJSAdmin: {
        
    }
};

/**
 * Force reload browser nav
 */
$(window).on('popstate', function () {
    window.location.reload();
});

/**
 * Ajax WP nonce token
 */
$.ajaxSetup({
    headers: {
        'X-WP-Nonce': WPPower.PHPToJS.WPRestNonce
    }
});