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

// web/js/modal-handler.js

// --- 1. MODAL TRIGGER LOGIC ---
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

// --- 2. FORM SUBMISSION INSIDE MODAL ---
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

// --- 3. CHECKBOX LOGIC (The Missing Piece) ---
$(document).on('change', '.finish-checkbox', function() {
    var checkbox = $(this);
    var id = checkbox.data('id');
    var toggleUrl = checkbox.data('toggle-url'); // We get the URL from the checkbox data attribute

    var data = { id: id };
    // Yii2 needs the CSRF token for POST requests
    data[yii.getCsrfParam()] = yii.getCsrfToken();

    $.ajax({
        url: toggleUrl,
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                location.reload();
            } else {
                alert('Fehler: ' + (res.error || 'Unbekannter Fehler'));
                checkbox.prop('checked', !checkbox.prop('checked')); // Revert if failed
            }
        },
        error: function(xhr) {
            alert('Server-Fehler: ' + xhr.status);
            checkbox.prop('checked', !checkbox.prop('checked'));
        }
    });
});