<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Homework $model */

// Add this at the top of your view file
\app\assets\AppAsset::register($this);
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Homeworks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Logic for status badge colors
$statusClass = $model->status === 'Completed' ? 'bg-success' : 'bg-warning text-dark';
?>

<div class="homework-view container mt-4">

    <div class="d-flex gap-2">
        <?= Html::button('Update', [
                'value' => Url::to(['homework/update', 'id' => $model->id]),
                'class' => 'btn btn-outline-primary px-4 modal-trigger-button',
                'id' => 'modalButton'
        ]) ?>

        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-outline-danger px-4',
                'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post', // Crucial for security
                ],
        ]) ?>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="text-muted mb-3">Task Description</h5>
                    <div class="p-3 bg-white rounded border" style="min-height: 200px; font-size: 1.1rem;">
                        <?= $model->description ? nl2br(Html::encode($model->description)) : '<em>No description provided.</em>' ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="mb-0">Assignment Info</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between py-3">
                            <span class="text-muted">Status</span>
                            <span class="badge rounded-pill <?= $statusClass ?>"><?= Html::encode($model->status) ?></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between py-3">
                            <span class="text-muted">Subject</span>
                            <span class="fw-bold text-primary">
                                <?= Html::encode($model->subject->name ?? 'N/A') ?>
                            </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between py-3">
                            <span class="text-muted">Teacher</span>
                            <span class="fw-bold">
                                <?= $model->teacher ? Html::encode($model->teacher->firstname . ' ' . $model->teacher->lastname) : 'N/A' ?>
                            </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between py-3">
                            <span class="text-muted">Due Date</span>
                            <span class="text-danger fw-bold">
                                <i class="bi bi-calendar-event"></i>
                                <?= Yii::$app->formatter->asDate($model->due_date, 'medium') ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// 2. ADD THE MODAL HTML (Needed on this page too!)
Modal::begin([
        'title' => '<h4 class="mb-0">Hausaufgabe bearbeiten</h4>',
        'id' => 'modal',
        'size' => 'modal-lg',
]);
echo "<div id='modalContent'><div class='text-center p-4'><div class='spinner-border text-primary' role='status'></div></div></div>";
Modal::end();

// 3. REGISTER THE JS (Reuse your logic from Index)
$js = <<<JS
$(document).on('click', '.update-modal-click', function(e){
    e.preventDefault();
    var url = $(this).attr('value');
    var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal'));
    $('#modalContent').html('<div class="text-center p-4"><div class="spinner-border text-primary"></div></div>');
    modal.show();
    $('#modalContent').load(url);
});

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
JS;
$this->registerJs($js);
?>