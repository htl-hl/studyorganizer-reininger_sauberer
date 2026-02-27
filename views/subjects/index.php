<?php
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Fächerverwaltung';
?>

    <div class="subjects-index container py-4">

        <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <h1 class="h4 fw-bold mb-0 ps-2"><?= Html::encode($this->title) ?></h1>
                <?= Html::button('<i class="bi bi-plus-lg"></i> Neues Fach', [
                        'class' => 'btn btn-light btn-sm fw-bold px-3 py-2 shadow-sm text-primary',
                        'onclick' => "showSubjectModal('" . Url::to(['subjects/create']) . "', 'Neues Fach erstellen')"
                ]) ?>
            </div>
        </div>

        <?php Pjax::begin(['id' => 'subjects-pjax']); ?>
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
                        'summary' => false,
                        'columns' => [
                                [
                                        'attribute' => 'name',
                                        'contentOptions' => ['class' => 'fw-bold py-3'],
                                ],
                                [
                                        'attribute' => 'status',
                                        'format' => 'raw',
                                        'contentOptions' => ['class' => 'text-center'],
                                        'value' => function ($model) {
                                            return $model->status
                                                    ? '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Aktiv</span>'
                                                    : '<span class="badge bg-light text-muted border rounded-pill px-3">Inaktiv</span>';
                                        }
                                ],
                                [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{update}',
                                        'contentOptions' => ['class' => 'text-end px-4'],
                                        'buttons' => [
                                                'update' => function ($url, $model) {
                                                    return Html::button('Bearbeiten', [
                                                            'class' => 'btn btn-outline-primary btn-sm px-3 rounded-1',
                                                            'data-url' => Url::to(['subjects/update', 'id' => $model->id]),
                                                            'onclick' => "showSubjectModal($(this).data('url'), 'Fach bearbeiten')"
                                                    ]);
                                                },
                                        ],
                                ],
                        ],
                ]); ?>
            </div>
        </div>
        <?php Pjax::end(); ?>
    </div>

<?php
Modal::begin([
        'id' => 'subjectModal',
        'title' => '<h5 id="modalTitle" class="fw-bold mb-0">Details</h5>',
        'size' => 'modal-md',
        'centerVertical' => true,
        'bodyOptions' => ['id' => 'modalContent', 'class' => 'p-0'],
]);
Modal::end();
?>

<?php
$js = <<<JS
    window.showSubjectModal = function(url, title) {
        console.log("Lade URL: " + url); // Debugging: Schau in die Browser-Konsole (F12)
        $('#modalTitle').text(title);
        $('#modalContent').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
        $('#subjectModal').modal('show');
        
        $.get(url)
            .done(function(data) {
                $('#modalContent').html(data);
            })
            .fail(function(xhr) {
                console.error(xhr);
                $('#modalContent').html('<div class="p-4"><div class="alert alert-danger">Fehler: ' + xhr.status + ' - ' + xhr.statusText + '<br><small>Pfad: ' + url + '</small></div></div>');
            });
    }
JS;
$this->registerJs($js);
?>