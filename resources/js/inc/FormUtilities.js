import Ajax from './Ajax';
import Helpers from './Helpers';

class FormUtilities {
    constructor() {
        this.ajax = new Ajax();
        this.helpers = new Helpers();
    }

    autoDiscover() {
        let self = this;

        let forms = $('form.c-ajax-form');

        forms.on('submit', function (e) {
            e.preventDefault();

            let thisForm = $(this);
            
            self.doAjaxSubmit(thisForm);
        });
    }

    doAjaxSubmit(form, resetAfterSuccess = true) {
        let self = this;

        let formID = form.attr('id');
        let method = form.attr('method');
        let endpoint = form.attr('action');
        let recaptchaAction = form.attr('data-recaptcha-action');
        let submitBtn = form.find('[type="submit"]');
        let formMessageWrapper = form.find('.form-message');
        let fileFakeBtn = form.find('.file-fake-btn');

        const done = function (response) {
            if (typeof(response.redirectUrl) !== 'undefined' && response.redirectUrl) {
                window.location.href = response.redirectUrl;
            } else {
                if (resetAfterSuccess) {
                    form.trigger('reset');
                }
                
                fileFakeBtn.html('Ajouter un fichier');
            }
        };

        const fail = function (response) {
            if (response.responseJSON.errorType === 'recaptcha') {
                alert('Erreur au niveau de Google Recaptcha, la page va se recharger.');
                window.location.reload();
            }

            self.helpers.formErrors(form, response.responseJSON.errors);

            let firstError = $('#' + formID).find('.error:not(:empty)').eq(0);

            self.helpers.scrollToElement('default', firstError, -100);
        };

        const always = function (response) {
            let message = response.message ?? response.responseJSON.message;

            submitBtn.removeAttr('disabled');

            if (typeof(message) !== 'undefined' && response.status !== 422) {
                formMessageWrapper.html(message);
            }
        }

        const doAjax = function (recaptchaToken) {
            let formData = new FormData(form[0]);

            if (recaptchaToken) {
                formData.append(
                    'recaptcha_token',
                    recaptchaToken
                );
            }

            self.ajax.sendWithFiles(endpoint, method, 'JSON', formData, done, fail, always);
        };

        if (submitBtn[0].hasAttribute('disabled')) {
            return;
        }

        if (formMessageWrapper.length === 0) {
            formMessageWrapper = $('.form-message[data-form="#' + formID + '"]');
        }

        submitBtn.attr('disabled', 'disabled');
        formMessageWrapper.empty();
        form.find('.error').empty();

        if (!recaptchaAction) {
            doAjax();
            return;
        }

        grecaptcha.ready(function () {
            grecaptcha.execute(lsdvWP.PHPToJS.apiKeys.google.recaptcha.siteKey, {action: recaptchaAction})
                        .then(function (recaptchaToken) {
                            doAjax(recaptchaToken);
                        })
            ;
        });
    }
}

export default FormUtilities;