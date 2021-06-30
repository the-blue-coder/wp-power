import Ajax from './inc/Ajax';
import Helpers from './inc/Helpers';
import FormUtilities from './inc/FormUtilities';

/**
 * Global vars
 */
window.WPPower = {
    ajax: new Ajax(),
    helpers: new Helpers(),
    formUtilities: new FormUtilities(),

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
 * Ajax WP nonce token
 */
$.ajaxSetup({
    headers: {
        'X-WP-Nonce': WPPower.PHPToJS.WPRestNonce
    }
});