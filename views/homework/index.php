<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Homework[] $homeworks */

$this->title = 'Hausaufgaben Übersicht';
$this->params['breadcrumbs'][] = $this->title;

$today = new DateTime('today');
$weekStart = clone $today;
$weekStart->modify('monday this week');

$sections = [
        'Aktuelle Woche' => [],
        'Nächste Woche' => [],
        'Später' => [],
];

foreach ($homeworks as $hw) {
    $dueDate = new DateTime($hw->due_date);
    $interval = $weekStart->diff($dueDate);
    $weeksDiff = (int)floor($interval->format('%r%a') / 7);

    if ($weeksDiff == 0) $sections['Aktuelle Woche'][] = $hw;
    elseif ($weeksDiff == 1) $sections['Nächste Woche'][] = $hw;
    else $sections['Später'][] = $hw;
}
?>

<div class="homework-index container">

    <div class="d-flex justify-content-between align-items-end border-bottom pb-3 mb-4">
        <div>
            <h1 class="display-5 fw-bold text-dark mb-0"><?= Html::encode($this->title) ?></h1>
        </div>
        <div>
            <?= Html::a(
                    '<i class="bi bi-plus-lg me-2"></i> Aufgabe hinzufügen',
                    ['create'],
                    ['class' => 'btn btn-success btn-lg rounded-3 shadow px-4']
            ) ?>
        </div>
    </div>
    <?php foreach ($sections as $title => $tasks): ?>
        <?php if (!empty($tasks)): ?>
            <h3 class="border-bottom pb-2 mb-3 mt-4 text-secondary"><?= $title ?></h3>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                <?php foreach ($tasks as $task):
                    $dueDateTime = new DateTime($task->due_date);
                    $now = new DateTime('today');
                    $diff = $now->diff($dueDateTime);
                    $daysRemaining = (int)$diff->format("%r%a");

                    $badgeClass = 'bg-light text-dark border';

                    if ($daysRemaining < 1) {
                        $badgeClass = 'bg-danger text-white';
                    } elseif ($daysRemaining < 7) {
                        $badgeClass = 'bg-warning text-dark';
                    } elseif ($daysRemaining < 14) {
                        $badgeClass = 'bg-primary text-white';
                    }
                    ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm hover-shadow" style="cursor: pointer;"
                             onclick="window.location='<?= Url::to(['view', 'id' => $task->id]) ?>'">

                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title text-primary mb-0">
                                        <?= Html::encode($task->title) ?>
                                    </h5>
                                    <span class="badge <?= $badgeClass ?>">
                        <?= Yii::$app->formatter->asDate($task->due_date, 'php:d.m.') ?>
                    </span>
                                </div>

                                <p class="card-text text-muted small">
                                    <i class="bi bi-calendar-event"></i>
                                    <?= Yii::$app->formatter->asDate($task->due_date, 'php:l') ?>
                                    <?php if ($daysRemaining == 0): ?>
                                        <span class="text-danger fw-bold">(Heute fällig!)</span>
                                    <?php elseif ($daysRemaining < 0): ?>
                                        <span class="text-secondary fw-bold">(Überfällig!)</span>
                                    <?php endif; ?>
                                </p>
                            </div>

                            <div class="card-footer bg-transparent border-top-0 text-end">
                                <?= Html::a('Details →', ['view', 'id' => $task->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-3px);
        transition: all 0.2s ease-in-out;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15)!important;
    }
    .card { transition: all 0.2s; }
</style>