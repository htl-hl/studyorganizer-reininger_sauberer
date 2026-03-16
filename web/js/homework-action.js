$(document).off('click', '.modal-trigger').on('click', '.modal-trigger', function (e) {
    e.preventDefault();
    var $button = $(this);
    var url = $button.attr('value') || $button.attr('data-url');
    var title = $button.attr('title') || 'Information';

    var modalElement = document.getElementById('modal');
    if (!modalElement) return;

    var modal = bootstrap.Modal.getOrCreateInstance(modalElement);
    $('#modal .modal-title').text(title);
    $('#modalContent').html('<div class="text-center p-5"><div class="spinner-border text-primary"></div></div>');

    modal.show();

    $('#modalContent').load(url, function(response, status, xhr) {
        if (status === "error") {
            $('#modalContent').html('<div class="alert alert-danger">Fehler: ' + xhr.status + '</div>');
        } else {
            initTeacherDropdown();
        }
    });
});

$(document).off('beforeSubmit', '#homework-form').on('beforeSubmit', '#homework-form', function(e) {
    e.preventDefault();
    var $form = $(this);

    if ($form.data('is-submitting')) return false;
    $form.data('is-submitting', true);

    var $btn = $('#save-button');
    var originalText = $btn.text();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Speichert...');

    $.ajax({
        url: $form.attr('action'),
        type: 'POST',
        data: $form.serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success === true) {
                bootstrap.Modal.getInstance(document.getElementById('modal')).hide();
                window.location.reload();
            } else {
                $form.data('is-submitting', false);
                $btn.prop('disabled', false).text(originalText);
                $form.yiiActiveForm('updateMessages', response, true);
            }
        },
        error: function() {
            $form.data('is-submitting', false);
            $btn.prop('disabled', false).text(originalText);
            alert('Serverfehler beim Speichern.');
        }
    });
    return false;
});

function updateTeachers(subjectId, teachersUrl, selectedTeacherId = null) {
    var teacherDropdown = $('#teacher-id');
    if (!subjectId) {
        teacherDropdown.html('<option value="">Zuerst Fach wählen</option>');
        return;
    }

    $.getJSON(teachersUrl, {id: subjectId}, function(data) {
        var items = '<option value="">Lehrer auswählen</option>';
        $.each(data, function(key, value) {
            var selected = (selectedTeacherId && key == selectedTeacherId) ? ' selected' : '';
            items += '<option value="' + key + '"' + selected + '>' + value + '</option>';
        });
        teacherDropdown.html(items);
    });
}

function initTeacherDropdown() {
    var subjectDropdown = $('#subject-id');
    if (subjectDropdown.length) {
        var initialSubject = subjectDropdown.val();
        var currentTeacherId = subjectDropdown.data('current-teacher');
        var teachersUrl = subjectDropdown.data('url');
        if (initialSubject) {
            updateTeachers(initialSubject, teachersUrl, currentTeacherId);
        }
    }
}

$(document).on('change', '#subject-id', function() {
    updateTeachers($(this).val(), $(this).data('url'));
});

$(document).on('click', '.homework-card-clickable', function(e) {
    if ($(e.target).closest('.finish-checkbox, .form-check-label, .btn').length) {
        return;
    }

    var url = $(this).data('url');
    if (url) {
        window.location.href = url;
    }
});

$(document).on('click', '.finish-checkbox', function(e) {
    e.stopPropagation();
});

$(document).on('change', '.finish-checkbox', function(e) {
    e.stopPropagation();
    var checkbox = $(this);
    var data = { id: checkbox.data('id') };
    data[yii.getCsrfParam()] = yii.getCsrfToken();

    $.post(checkbox.data('toggle-url'), data, function(res) {
        if (res.success) window.location.reload();
        else alert('Fehler beim Aktualisieren.');
    }, 'json');
});