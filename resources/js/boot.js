/**
 * Global vars
 */
window.freexcomics = {
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
        'X-WP-Nonce': freexcomics.PHPToJS.WPRestNonce
    }
});