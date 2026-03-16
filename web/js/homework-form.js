
function updateTeachers(subjectId, teachersUrl, selectedTeacherId = null) {
    var teacherDropdown = $('#teacher-id');

    if (subjectId) {
        $.getJSON(teachersUrl, {id: subjectId}, function(data) {
            var items = '<option value="">Lehrer auswählen</option>';
            $.each(data, function(key, value) {
                var selected = (selectedTeacherId && key == selectedTeacherId) ? ' selected' : '';
                items += '<option value="' + key + '"' + selected + '>' + value + '</option>';
            });
            teacherDropdown.html(items);
        });
    } else {
        teacherDropdown.html('<option value="">Zuerst Fach wählen</option>');
    }
}

// Event-Handler
$(document).off('change', '#subject-id').on('change', '#subject-id', function() {
    var teachersUrl = $(this).data('url');
    updateTeachers($(this).val(), teachersUrl);
});

// Initialisierung beim Laden (für Update-Szenarien)
$(document).ready(function() {
    var subjectDropdown = $('#subject-id');
    var initialSubject = subjectDropdown.val();
    var currentTeacherId = subjectDropdown.data('current-teacher');
    var teachersUrl = subjectDropdown.data('url');

    if (initialSubject && !$('#teacher-id').val()) {
        updateTeachers(initialSubject, teachersUrl, currentTeacherId);
    }
});