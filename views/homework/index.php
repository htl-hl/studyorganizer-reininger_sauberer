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
        'Aktuelle Woche' => [],
        'Nächste Woche' => [],
        'Später' => [],
];

// Group ONLY Active tasks into weekly sections
foreach ($activeHomeworks as $hw) {
    $dueDate = new DateTime($hw->due_date);
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
                            'value' => Url::to(['create']),
                            'class' => 'btn btn-success btn-lg rounded-3 shadow px-4',
                            'id' => 'modalButton'
                    ]
            ) ?>
        </div>

        <?php
        Modal::begin([
                'title' => '<h4 class="mb-0">Neue Hausaufgabe</h4>',
                'id' => 'modal',
                'size' => 'modal-lg',
        ]);
        echo "<div id='modalContent'><div class='text-center p-4'><div class='spinner-border text-primary' role='status'></div></div></div>";
        Modal::end();
        ?>

        <?php foreach ($sections as $title => $tasks): ?>
            <?php if (!empty($tasks)): ?>
                <h3 class="border-bottom pb-2 mb-3 mt-5 text-secondary small text-uppercase fw-bold"><?= $title ?></h3>
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

    <style>
        .wrap > .container, main > .container { padding-top: 0 !important; }
        .homework-index { margin-top: -30px; }
        .hover-shadow:hover { transform: translateY(-3px); transition: all 0.2s; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15)!important; }
        .card { transition: all 0.2s; border-radius: 12px; }
        .is-finished { opacity: 0.6; background-color: #f8f9fa; border: 1px dashed #dee2e6 !important; filter: grayscale(0.5); }
        .text-decoration-line-through { text-decoration: line-through !important; }
    </style>

<?php
$toggleUrl = Url::to(['homework/toggle-status']);
$js = <<<JS
$(document).on('change', '.finish-checkbox', function() {
    let checkbox = $(this);
    let id = checkbox.data('id');

    // Manually construct the URL with the ID to avoid routing issues
    let targetUrl = '{$toggleUrl}' + ( '{$toggleUrl}'.includes('?') ? '&' : '?') + 'id=' + id;

    $.ajax({
        url: targetUrl,
        type: 'POST',
        // This line sends the security token Yii requires for POST requests
        data: {
            _csrf: yii.getCsrfToken() 
        },
        success: function(data) {
            if (data.success) {
                location.reload(); 
            } else {
                alert('Server error: ' + (data.error || 'Unknown error'));
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        },
        error: function(xhr) {
            console.error("Status: " + xhr.status);
            console.error("Response: " + xhr.responseText);
            alert('Critical Error: Could not reach the server. (Status: ' + xhr.status + ')');
        }
    });
});
JS;
$this->registerJs($js);
?>