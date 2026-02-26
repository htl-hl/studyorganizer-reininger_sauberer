<?php
use yii\helpers\Html;
use app\models\Homework;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Homeworks';
$this->params['breadcrumbs'][] = $this->title;

$homeworks = Homework::find()->orderBy(['due_date'=>SORT_ASC])->all();

$days = ['Montag','Dienstag','Mittwoch','Donnerstag','Freitag'];

$weeks = [
        'Aktuelle Woche' => [],
        'Nächste Woche' => [],
        'Übernächste Woche' => [],
];

$today = strtotime('today');
$weekStart = strtotime('monday this week', $today);

foreach ($homeworks as $hw) {
    $due = strtotime($hw->due_date);
    $diffWeeks = floor(($due - $weekStart)/(7*24*60*60));
    if ($diffWeeks == 0) $weeks['Aktuelle Woche'][] = $hw;
    elseif ($diffWeeks == 1) $weeks['Nächste Woche'][] = $hw;
    elseif ($diffWeeks == 2) $weeks['Übernächste Woche'][] = $hw;
}
?>

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <div>
            <?= Html::a('Add', ['create'], ['class'=>'btn btn-primary']) ?>
        </div>
        <div>
            <?= Html::a('Logout', ['/site/logout'], ['class'=>'btn btn-danger', 'data-method'=>'post']) ?>
        </div>
    </div>

    <table class="table table-bordered text-center">
        <thead>
        <tr>
            <th>Woche</th>
            <?php foreach ($days as $day): ?>
                <th><?= $day ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($weeks as $weekName => $weekTasks): ?>
            <tr>
                <td class="fw-bold align-middle"><?= $weekName ?></td>
                <?php foreach ($days as $day): ?>
                    <td style="min-width:120px; vertical-align:top;">
                        <?php
                        foreach ($weekTasks as $task) {
                            if (strtolower(date('l', strtotime($task->due_date))) === strtolower($day)) {
                                echo Html::a(Html::encode($task->title), ['view', 'id'=>$task->id], ['class'=>'btn btn-outline-primary btn-sm mb-1 d-block']);
                            }
                        }
                        ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>