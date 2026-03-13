// web/js/modal-handler.js

// --- 1. MODAL ÖFFNEN (Funktioniert für alle Elemente mit .modal-trigger) ---
$(document).off('click', '.modal-trigger').on('click', '.modal-trigger', function (e) {
    e.preventDefault();
    var $button = $(this);
    var url = $button.attr('value') || $button.attr('data-url');
    var title = $button.attr('title') || 'Information';

    var modalElement = document.getElementById('modal');
    if (!modalElement) return; // Sicherheitsscheck

    var modal = bootstrap.Modal.getOrCreateInstance(modalElement);

    $('#modal .modal-title').html(title);
    $('#modalContent').html('<div class="text-center p-5"><div class="spinner-border text-primary"></div></div>');

    modal.show();

    $('#modalContent').load(url, function(response, status, xhr) {
        if (status === "error") {
            $('#modalContent').html('<div class="alert alert-danger">Fehler beim Laden: ' + xhr.status + '</div>');
        }
    });
});

// --- 2. FORMULAR SPEICHERN (Zentral für alle Formulare im Modal) ---
$(document).off('beforeSubmit', '#homework-form').on('beforeSubmit', '#homework-form', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    var $form = $(this);

    // Schutz gegen Mehrfach-Senden
    if ($form.data('is-submitting')) return false;
    $form.data('is-submitting', true);

    var $btn = $('#save-button');
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Speichert...');

    $.ajax({
        url: $form.attr('action'),
        type: 'POST',
        data: $form.serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success === true) {
                var modalInstance = bootstrap.Modal.getInstance(document.getElementById('modal'));
                if (modalInstance) modalInstance.hide();

                // Wir nutzen location.reload(), um die Seite nach dem Speichern frisch zu laden
                window.location.reload();
            } else {
                $form.data('is-submitting', false);
                $btn.prop('disabled', false).text('Aufgabe speichern');
                $form.yiiActiveForm('updateMessages', response, true);
            }
        },
        error: function() {
            $form.data('is-submitting', false);
            $btn.prop('disabled', false).text('Aufgabe speichern');
            alert('Ein Serverfehler ist aufgetreten.');
        }
    });

    return false;
});

// --- 3. STATUS-CHECKBOX (Sofort-Update) ---
$(document).off('change', '.finish-checkbox').on('change', '.finish-checkbox', function() {
    var checkbox = $(this);
    var id = checkbox.data('id');
    var toggleUrl = checkbox.data('toggle-url');

    var data = { id: id };
    data[yii.getCsrfParam()] = yii.getCsrfToken();

    $.ajax({
        url: toggleUrl,
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                window.location.reload();
            } else {
                alert('Fehler: ' + (res.error || 'Unbekannter Fehler'));
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        }
    });
});