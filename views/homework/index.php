<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Modal;

/** @var yii\web\View $this */
/** @var app\models\Homework[] $activeHomeworks */
/** @var app\models\Homework[] $finishedHomeworks */

$this->title = 'Hausaufgaben Übersicht';

$today = new DateTime('today');
$weekStart = (clone $today)->modify('monday this week');

$sections = [
        'Überfällig' => [],
        'Aktuelle Woche' => [],
        'Nächste Woche' => [],
        'Später' => [],
];

foreach ($activeHomeworks as $hw) {
    $dueDate = new DateTime($hw->due_date);

    // 1. Group Overdue
    if ($dueDate < $today) {
        $sections['Überfällig'][] = $hw;
        continue;
    }

    // 2. Group by Week
    $weeksDiff = (int)floor($weekStart->diff($dueDate)->format('%r%a') / 7);

    if ($weeksDiff == 0) $sections['Aktuelle Woche'][] = $hw;
    elseif ($weeksDiff == 1) $sections['Nächste Woche'][] = $hw;
    else $sections['Später'][] = $hw;
}
?>

<div class="homework-index container mt-1">

    <div class="d-flex justify-content-between align-items-end border-bottom pb-3 mb-3">
        <h1 class="display-5 fw-bold text-dark mb-0"><?= Html::encode($this->title) ?></h1>

        <?= Html::button(
                '<i class="bi bi-plus-lg me-2"></i> Aufgabe hinzufügen',
                [
                        'data-url' => Url::to(['homework/create']),
                        'class' => 'btn btn-success btn-lg rounded-3 shadow px-4 modal-trigger',
                        'title' => 'Neue Hausaufgabe'
                ]
        ) ?>
    </div>

    <?php foreach ($sections as $title => $tasks): ?>
        <?php if (!empty($tasks)): ?>
            <?php $titleClass = ($title === 'Überfällig') ? 'text-danger' : 'text-secondary'; ?>

            <h3 class="border-bottom pb-2 mb-3 mt-5 <?= $titleClass ?> small text-uppercase fw-bold">
                <?= $title ?>
                <?php if ($title === 'Überfällig'): ?>
                    <i class="bi bi-exclamation-triangle-fill ms-1"></i>
                <?php endif; ?>
            </h3>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                <?php foreach ($tasks as $task): ?>
                    <?= $this->render('_homework_card', ['task' => $task, 'isFinished' => false, 'today' => $today]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if (!empty($finishedHomeworks)): ?>
        <h3 class="border-bottom pb-2 mb-3 mt-5 text-success small text-uppercase fw-bold">Erledigt</h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
            <?php foreach ($finishedHomeworks as $task): ?>
                <?= $this->render('_homework_card', ['task' => $task, 'isFinished' => true, 'today' => $today]) ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

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
