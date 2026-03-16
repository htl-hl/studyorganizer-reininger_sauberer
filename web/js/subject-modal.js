window.showSubjectModal = function(url, title) {
    console.log("Lade URL: " + url);

    var modal = $('#subjectModal');
    var modalTitle = $('#modalTitle');
    var modalContent = $('#modalContent');

    modalTitle.text(title);
    modalContent.html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
    modal.modal('show');

    $.get(url)
        .done(function(data) {
            modalContent.html(data);
        })
        .fail(function(xhr) {
            console.error(xhr);
            modalContent.html('<div class="p-4"><div class="alert alert-danger">Fehler: ' + xhr.status + ' - ' + xhr.statusText + '<br><small>Pfad: ' + url + '</small></div></div>');
        });
};