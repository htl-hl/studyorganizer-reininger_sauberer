<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Homework $model */

\app\assets\AppAsset::register($this);
$this->title = $model->title;

$dueDate = new DateTime($model->due_date);
$today = new DateTime('today');
$diff = $today->diff($dueDate);
$daysLeft = (int)$diff->format("%r%a");
?>

    <div class="homework-view container py-5">

        <div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom">
            <h1 class="display-5 fw-bold text-dark m-0"><?= Html::encode($model->title) ?></h1>

            <div class="d-flex gap-2">
                <?= Html::a('<i class="bi bi-check-circle me-2"></i>Erledigt', ['homework/finish-and-go-back', 'id' => $model->id], [
                        'class' => 'btn btn-outline-success rounded-pill px-4 shadow-sm',
                        'data' => [
                                'method' => 'post',
                                'confirm' => 'Möchtest du diese Aufgabe als erledigt markieren und zurück zur Übersicht?',
                        ],
                        'style' => $model->status === 'Finished' ? 'display:none' : ''
                ]) ?>

                <?= Html::button('<i class="bi bi-pencil-square me-2"></i>Bearbeiten', [
                        'class' => 'btn btn-outline-primary rounded-pill px-4 modal-trigger shadow-sm',
                        'data-url' => Url::to(['homework/update', 'id' => $model->id]),
                        'title' => 'Hausaufgabe bearbeiten'
                ]) ?>

                <?= Html::a('<i class="bi bi-trash3 me-2"></i>Löschen', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-outline-danger rounded-pill px-4 shadow-sm',
                        'data' => [
                                'confirm' => 'Möchtest du diese Aufgabe wirklich löschen?',
                                'method' => 'post',
                        ],
                ]) ?>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="row g-3 text-center">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                            <label class="text-uppercase small fw-bold text-muted mb-1 d-block">Fach</label>
                            <span class="fs-4 fw-bold text-dark">
                                <?= Html::encode($model->subject->name ?? 'N/A') ?>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                            <label class="text-uppercase small fw-bold text-muted mb-1 d-block">Lehrer</label>
                            <span class="fs-4 fw-bold text-dark">
                            <?= $model->teacher ? Html::encode($model->teacher->firstname . ' ' . $model->teacher->lastname) : 'Nicht zugewiesen' ?>
                        </span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white text-white">
                            <label class="text-uppercase small fw-bold text-muted mb-1 d-block">Abgabe bis</label>
                            <div class="fs-4 fw-bold text-dark mb-1"><?= Yii::$app->formatter->asDate($model->due_date, 'medium') ?></div>
                            <?php if ($model->status !== 'Finished'): ?>
                                <?php if ($daysLeft >= 14): ?>
                                    <small class="text-muted">Noch <?= $daysLeft ?> Tage</small>
                                <?php elseif ($daysLeft >= 7): ?>
                                    <small class="text-primary fw-bold">Noch <?= $daysLeft ?> Tage</small>
                                <?php elseif ($daysLeft > 1): ?>
                                    <small class="text-warning fw-bold">Noch <?= $daysLeft ?> Tage</small>
                                <?php elseif ($daysLeft >= 0): ?>
                                    <small class="text-danger fw-bold">Noch <?= $daysLeft ?> Tage</small>
                                <?php elseif ($daysLeft < 0): ?>
                                    <small class="text-danger fw-bold">Überfällig</small>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-light py-3 px-4 border-0">
                        <h6 class="m-0 fw-bold text-secondary text-uppercase small">Aufgabenbeschreibung</h6>
                    </div>
                    <div class="card-body p-4 p-md-5 bg-white rounded-bottom-4">
                        <?php if ($model->description): ?>
                            <div class="fs-5 text-dark"
                                 style="line-height: 1.8; white-space: pre-wrap;"><?= Html::encode($model->description) ?></div>
                        <?php else: ?>
                            <p class="text-muted fst-italic m-0">Keine Beschreibung vorhanden.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-12 text-center mt-3">
                <?= Html::a('Zurück zur Übersicht', ['index'], ['class' => 'btn btn-link text-muted text-decoration-none small']) ?>
            </div>
        </div>
    </div>

<?php

Modal::begin([
        'title' => '',
        'id' => 'modal',
        'size' => 'modal-lg',
]);

echo '<div id="modalContent"></div>';

Modal::end();
?>