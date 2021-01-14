class Ajax {
    constructor() {
        this.spinner = $('#loading');
    }

    /**
     * Send AJAX request
     */
    send(url, type, dataType, data, done, fail, always) {
        $.ajax({
            url: '/' + url,
            type: type,
            dataType: dataType,
            data: data
        }).done(done).fail(fail).always(always);
    }

    /**
     * Send AJAX request with files
     */
    sendWithFiles(url, type, dataType, data, done, fail, always) {
        $.ajax({
            url: '/' + url,
            type: type,
            dataType: dataType,
            data: data,
            processData: false,
            contentType: false
        }).done(done).fail(fail).always(always);
    }

    /**
     * GET method
     */
    get(url, dataType, data, done, fail, always) {
        this.send(url, 'GET', dataType, data, done, fail, always);
    }

    /**
     * POST method
     */
    post(url, dataType, data, done, fail, always) {
        this.send(url, 'POST', dataType, data, done, fail, always);
    }

    /**
     * POST with file
     */
    postWithFiles(url, dataType, data, done, fail, always) {
        this.sendWithFiles(url, 'POST', dataType, data, done, fail, always);
    }

    /**
     * PUT method
     */
    put(url, dataType, data, done, fail, always) {
        this.send(url, 'PUT', dataType, data, done, fail, always);
    }

    /**
     * PATCH method
     */
    patch(url, dataType, data, done, fail, always) {
        this.send(url, 'PATCH', dataType, data, done, fail, always);
    }

    /**
     * DELETE method
     */
    delete(url, dataType, data, done, fail, always) {
        this.send(url, 'DELETE', dataType, data, done, fail, always);
    }

    /**
     * Show AJAX spinner
     */
    showSpinner() {
        this.spinner.fadeIn(150);
    }

    /**
     * Hide AJAX spinner
     */
    hideSpinner() {
        this.spinner.fadeOut(150);
    }
}

export default Ajax;