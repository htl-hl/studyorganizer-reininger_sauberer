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
                            'data-url' => Url::to(['homework/create']),
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
// --- 1. MODAL / ADD BUTTON LOGIC ---
// This will now work for BOTH Create and Update buttons
$(document).on('click', '#modalButton, .update-modal-click', function(e){
    e.preventDefault();
    
    // Check if it's the ID button or the Class button to get the URL
    var url = $(this).data('url') || $(this).attr('value');
    
    var modalElement = document.getElementById('modal');
    var modal = bootstrap.Modal.getOrCreateInstance(modalElement);
    
    $('#modalContent').html('<div class="text-center p-4"><div class="spinner-border text-primary"></div></div>');
    modal.show();
    
    $('#modalContent').load(url);
});

// --- 2. FORM SUBMIT LOGIC (Inside Modal) ---
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
                // If validation fails, update the form with error messages
                form.yiiActiveForm('updateMessages', response, true);
            }
        }
    });
    return false;
});

// --- 3. CHECKBOX LOGIC ---
$(document).on('change', '.finish-checkbox', function() {
    var checkbox = $(this);
    var id = checkbox.data('id');
    
    var csrfToken = yii.getCsrfToken();
    var csrfParam = yii.getCsrfParam();
    
    var data = { id: id };
    data[csrfParam] = csrfToken;

    $.ajax({
        url: '{$toggleUrl}',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                location.reload(); 
            } else {
                alert('Fehler: ' + (data.error || 'Unbekannter Fehler'));
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        },
        error: function(xhr) {
            console.error("Status: " + xhr.status);
            alert('Server-Fehler: ' + xhr.status);
            checkbox.prop('checked', !checkbox.prop('checked'));
        }
    });
});
JS;
$this->registerJs($js);
?>