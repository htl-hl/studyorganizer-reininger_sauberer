// web/js/modal-handler.js

$(document).on('click', '.modal-trigger', function (e) {
    e.preventDefault();

    var $button = $(this);
    // Support both 'value' attribute and 'data-url' attribute
    var url = $button.attr('value') || $button.data('url');
    var title = $button.attr('title') || 'Information';

    var modalElement = document.getElementById('modal');
    if (!modalElement) {
        console.error("Modal HTML structure missing from this page!");
        return;
    }

    var modal = bootstrap.Modal.getOrCreateInstance(modalElement);

    // Update title and show spinner
    $('#modal .modal-title').html(title);
    $('#modalContent').html('<div class="text-center p-4"><div class="spinner-border text-primary"></div></div>');

    modal.show();
    $('#modalContent').load(url);
});

// Universal Form Submission for Modals
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