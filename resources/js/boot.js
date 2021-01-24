/**
 * Global vars
 */
window.WPPower = {
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