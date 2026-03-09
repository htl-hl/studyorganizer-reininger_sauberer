// web/js/modal-handler.js

$(document).on('click', '.modal-trigger', function (e) {
    e.preventDefault();

    var $button = $(this);
    var url = $button.attr('value') || $button.attr('data-url');
    var title = $button.attr('title') || 'Information';

    var modalElement = document.getElementById('modal');
    var modal = bootstrap.Modal.getOrCreateInstance(modalElement);

    $('#modal .modal-title').html(title);
    $('#modalContent').html('<div class="text-center p-4"><div class="spinner-border text-primary"></div></div>');

    modal.show();

    $('#modalContent').load(url, function(response, status, xhr) {
        if (status === "error") {
            $('#modalContent').html('<div class="alert alert-danger">Error: ' + xhr.status + '</div>');
        }
    });
});

// Universal Form Submission inside Modal
$(document).on('beforeSubmit', '#homework-form', function(e) {
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success === true) {
                bootstrap.Modal.getInstance(document.getElementById('modal')).hide();
                location.reload();
            } else {
                form.yiiActiveForm('updateMessages', response, true);
            }
        }
    });
    return false;
});