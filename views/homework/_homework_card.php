<?php
use yii\helpers\Html;
use yii\helpers\Url;

$dueDateTime = new DateTime($task->due_date);
$diff = $today->diff($dueDateTime);
$daysRemaining = (int)$diff->format("%r%a");

// Styling logic based on status and time
$badgeClass = 'bg-light text-dark border';
if (!$isFinished) {
    if ($daysRemaining < 1) $badgeClass = 'bg-danger text-white';
    elseif ($daysRemaining < 7) $badgeClass = 'bg-warning text-dark';
    elseif ($daysRemaining < 14) $badgeClass = 'bg-primary text-white';
} else {
    $badgeClass = 'bg-secondary text-white';
}
?>

<div class="col">
    <div class="card h-100 shadow-sm hover-shadow <?= $isFinished ? 'is-finished' : '' ?>"
         style="cursor: pointer;"
         onclick="window.location='<?= Url::to(['view', 'id' => $task->id]) ?>'">

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title text-primary mb-0 <?= $isFinished ? 'text-decoration-line-through text-muted' : '' ?>">
                    <?= Html::encode($task->title) ?>
                </h5>
                <span class="badge <?= $badgeClass ?>">
                    <?= Yii::$app->formatter->asDate($task->due_date, 'php:d.m.') ?>
                </span>
            </div>

            <p class="card-text text-muted small">
                <i class="bi bi-calendar-event"></i>
                <?= Yii::$app->formatter->asDate($task->due_date, 'php:l') ?>
                <?php if (!$isFinished): ?>
                    <?php if ($daysRemaining == 0): ?> <span class="text-danger fw-bold">(Heute!)</span>
                    <?php elseif ($daysRemaining < 0): ?> <span class="text-secondary fw-bold">(Überfällig!)</span>
                    <?php endif; ?>
                <?php endif; ?>
            </p>
        </div>

        <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between align-items-center">
            <div class="form-check m-0">
                <input class="form-check-input finish-checkbox"
                       type="checkbox"
                       data-id="<?= $task->id ?>"
                    <?= $isFinished ? 'checked' : '' ?>
                       onclick="event.stopPropagation();">
                <label class="form-check-label small text-muted">Erledigt</label>
            </div>
            <?= Html::a('Details →', ['view', 'id' => $task->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
        </div>
    </div>
</div>