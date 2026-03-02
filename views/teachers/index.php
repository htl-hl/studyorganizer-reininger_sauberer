<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap5\Modal;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Lehrerverwaltung';
?>

    <div class="teachers-index card shadow-sm p-4 border-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-dark fw-bold"><?= Html::encode($this->title) ?></h1>
            <?= Html::button('＋ Neuer Lehrer', [
                    'value' => Url::to(['teachers/create']),
                    'class' => 'btn btn-success rounded-pill px-4 shadow-sm',
                    'id' => 'modalButton'
            ]) ?>
        </div>

        <?= GridView::widget([
                'dataProvider' => $dataProvider,
            // filterModel entfernt -> Suchleiste weg
                'summary' => false,
                'tableOptions' => ['class' => 'table table-hover align-middle'],
                'columns' => [
                        [
                                'attribute' => 'firstname',
                                'label' => 'Vorname',
                        ],
                        [
                                'attribute' => 'lastname',
                                'label' => 'Nachname',
                                'contentOptions' => ['class' => 'fw-semibold'],
                        ],
                        [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function($model) {
                                    $class = $model->status ? 'bg-success' : 'bg-secondary';
                                    $text = $model->status ? 'Aktiv' : 'Inaktiv';
                                    return "<span class='badge rounded-pill $class'>$text</span>";
                                },
                        ],
                        [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update}', // Löschen und View entfernt
                                'contentOptions' => ['style' => 'width: 100px; text-align: right;'],
                                'buttons' => [
                                        'update' => function ($url, $model) {
                                            return Html::button('Bearbeiten', [
                                                    'value' => Url::to(['teachers/update', 'id' => $model->id]),
                                                    'class' => 'btn btn-sm btn-outline-primary editModalButton rounded-pill px-3',
                                            ]);
                                        },
                                ],
                        ],
                ],
        ]); ?>
    </div>

<?php
// Das Modal-Fenster
Modal::begin([
        'title' => '<h5 id="modalTitle" class="fw-bold m-0"></h5>',
        'id' => 'modal',
        'size' => 'modal-md',
        'headerOptions' => ['class' => 'border-0'],
]);
echo "<div id='modalContent'></div>";
Modal::end();

// JavaScript für den dynamischen Titel und das Laden des Formulars
$js = <<<JS
    $(document).on('click', '#modalButton, .editModalButton', function(){
        var isEdit = $(this).hasClass('editModalButton');
        $('#modalTitle').text(isEdit ? 'Lehrer bearbeiten' : 'Lehrer hinzufügen');
        
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
JS;
$this->registerJs($js);
?>