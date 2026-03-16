<?php
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

$this->title = 'Lehrerverwaltung';
?>

    <div class="teachers-index container py-4">

        <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <h1 class="h4 fw-bold mb-0 ps-2"><?= Html::encode($this->title) ?></h1>
                <?= Html::button('<i class="bi bi-plus-lg"></i> Neuer Lehrer', [
                        'class' => 'btn btn-light btn-sm fw-bold px-3 py-2 shadow-sm text-primary',
                        'onclick' => "showTeacherModal('" . Url::to(['teachers/create']) . "', 'Neuen Lehrer registrieren')"
                ]) ?>
            </div>
        </div>

        <?php Pjax::begin(['id' => 'teachers-pjax']); ?>
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
                        'summary' => false,
                        'columns' => [
                                [
                                        'label' => 'Name',
                                        'value' => function($model) {
                                            return $model->firstname . ' ' . $model->lastname;
                                        },
                                ],
                                [
                                        'label' => 'Fächer',
                                        'format' => 'raw',
                                        'value' => function($model) {
                                            $subjects = \yii\helpers\ArrayHelper::getColumn($model->subjects, 'name');

                                            if (empty($subjects)) {
                                                return '<span class="text-muted small italic">Kein Fach</span>';
                                            }

                                            $html = '';
                                            foreach ($subjects as $name) {
                                                $html .= Html::tag('span', Html::encode($name), [
                                                        'class' => 'badge bg-primary-subtle text-primary border border-primary-subtle me-1 rounded-pill fw-normal'
                                                ]);
                                            }
                                            return $html;
                                        }
                                ],
                                [
                                        'attribute' => 'status',
                                        'format' => 'raw',
                                        'headerOptions' => ['class' => 'text-center'],
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
                                                            'onclick' => "showTeacherModal('" . Url::to(['teachers/update', 'id' => $model->id]) . "', 'Lehrer bearbeiten')"
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
        'id' => 'teacherModal',
        'title' => '<h5 id="teacherModalTitle" class="fw-bold mb-0 text-primary">Details</h5>',
        'size' => 'modal-md',
        'centerVertical' => true,
        'bodyOptions' => ['id' => 'teacherModalContent', 'class' => 'p-0'],
]);
Modal::end();
?>

<?php
$this->registerJsFile('@web/js/teacher-modal.js', [
    'depends' => [\yii\web\JqueryAsset::class]
]);
?>