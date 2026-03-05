<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Homework $model */

$this->title = 'Update Homework: ' . $model->title;

?>
<div class="homework-update p-3">
    <?= $this->render('_form', [
            'model' => $model,
    ]) ?>
</div>
