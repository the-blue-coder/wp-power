import Ajax from '../inc/Ajax';

class Examples {
    constructor() {
        this.ajax = new Ajax();
    }

    init() {
        let self = this;

        self.ajax();
    }

    ajax() {
        let self = this;

        let triggerBtn = $('#btn-id');

        triggerBtn.on('click', function (e) {
            e.preventDefault();

            let thisBtn = $(this);

            let data = {
                
            };

            const done = function () {};

            const fail = function () {};

            const always = function () {
                thisBtn.removeClass('disabled');
            };

            thisBtn.addClass('disabled');

            self.ajax.get(window.location.href, 'JSON', data, done, fail, always);
        });
    }
}

export default Examples;