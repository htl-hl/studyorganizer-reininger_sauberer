<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap5\Modal;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Fächer';
?>

    <div class="subjects-index card shadow-sm p-4 border-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-dark fw-bold"><?= Html::encode($this->title) ?></h1>
            <?= Html::button('＋ Neues Fach', [
                    'value' => Url::to(['subjects/create']),
                    'class' => 'btn btn-success rounded-pill px-4 shadow-sm',
                    'id' => 'modalButton'
            ]) ?>
        </div>

        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => false,
                'tableOptions' => ['class' => 'table table-hover align-middle'],
                'columns' => [
                        [
                                'attribute' => 'name',
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
                                'template' => '{update}',
                                'contentOptions' => ['style' => 'width: 100px; text-align: right;'],
                                'buttons' => [
                                        'update' => function ($url, $model) {
                                            return Html::button('Bearbeiten', [
                                                    'value' => Url::to(['subjects/update', 'id' => $model->id]),
                                                    'class' => 'btn btn-sm btn-outline-primary editModalButton rounded-pill px-3',
                                            ]);
                                        },
                                ],
                        ],
                ],
        ]); ?>
    </div>

<?php
// Das Modal-Fenster für Fächer
Modal::begin([
        'title' => '<h5 id="modalTitle" class="fw-bold m-0"></h5>', // ID für dynamischen Titel
        'id' => 'modal',
        'size' => 'modal-md',
        'headerOptions' => ['class' => 'border-0'],
]);
echo "<div id='modalContent'></div>";
Modal::end();
$js = <<<JS
    $(document).on('click', '#modalButton, .editModalButton', function(){
        var isEdit = $(this).hasClass('editModalButton');
        
        $('#modalTitle').text(isEdit ? 'Fach bearbeiten' : 'Fach hinzufügen');
        
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
JS;
$this->registerJs($js);
?>