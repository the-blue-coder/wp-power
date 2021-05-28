import Ajax from '../inc/Ajax';

class Forms {
    constructor() {
        this.ajax = new Ajax();

        this.forms = {
            form1: $('.form1-class')
        };
    }

    init() {
        let self = this;

        self.ajaxSubmit();
    }

    ajaxSubmit() {
        let self = this;

        for (let [key, form] of Object.entries(self.forms)) {
            form.on('submit', function (e) {
                e.preventDefault();
                self.doAjaxSubmit(form);
            });
        }
    }

    doAjaxSubmit(thisForm) {
        let self = this;

        let thisFormID = thisForm.attr('id');
        let method = thisForm.attr('method');
        let endpoint = thisForm.attr('action');
        let recaptchaAction = thisForm.attr('data-recaptcha-action');
        let submitBtn = thisForm.find('[type="submit"]');
        let formMessageWrapper = thisForm.find('.form-message');
        let data = thisForm.serializeArray();

        const done = function (response) {
            if (typeof(response.redirectUrl) !== 'undefined' && response.redirectUrl) {
                window.location.href = response.redirectUrl;
            } else {
                thisForm.trigger('reset');
            }
        };

        const fail = function (response) {
            if (response.responseJSON.errorType === 'recaptcha') {
                alert('Erreur au niveau de Google Recaptcha, la page va se recharger.');
                window.location.reload();
            }
        };

        const always = function (response) {
            let message = response.message ?? response.responseJSON.message;

            submitBtn.removeAttr('disabled');

            if (typeof(message) !== 'undefined') {
                formMessageWrapper.html(message);
            }
        }

        const doAjax = function () {
            self.ajax.send(endpoint, method, 'JSON', data, done, fail, always);
        };

        if (submitBtn[0].hasAttribute('disabled')) {
            return;
        }

        if (formMessageWrapper.length === 0) {
            formMessageWrapper = $('.form-message[data-form="#' + thisFormID + '"]');
        }

        submitBtn.attr('disabled', 'disabled');
        formMessageWrapper.empty();

        if (!recaptchaAction) {
            doAjax();
            return;
        }

        grecaptcha.ready(function () {
            grecaptcha.execute(emfps.PHPToJS.apiKeys.google.recaptcha.siteKey, {action: recaptchaAction})
                      .then(function (recaptchaToken) {
                          data.push({
                              name: 'recaptcha_token',
                              value: recaptchaToken
                          });

                          doAjax();
                      })
            ;
        });
    }
}

export default Forms;