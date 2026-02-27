<?php
/** @var app\models\Teachers $model */
/** @var array $subjects */
?>

<?= $this->render('_form', [
        'model' => $model,
        'subjects' => $subjects,
]) ?>