<?php
/** @var app\models\Teachers $model */
/** @var array $subjects */ // Das ist die Liste aus dem Controller
?>

<?= $this->render('_form', [
        'model' => $model,
        'subjects' => $subjects, // Hier wird die Variable an das Formular durchgereicht
]) ?>