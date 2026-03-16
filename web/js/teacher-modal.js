window.showTeacherModal = function(url, title) {
    $('#teacherModalTitle').text(title);
    $('#teacherModalContent').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
    $('#teacherModal').modal('show');

    $.get(url).done(function(data) {
        $('#teacherModalContent').html(data);
    }).fail(function() {
        $('#teacherModalContent').html('<div class="p-4 alert alert-danger">Fehler beim Laden.</div>');
    });
};