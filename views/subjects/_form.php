<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm; // Nutze bootstrap5 für sauberes Styling
?>

<div class="subjects-form">
    <?php $form = ActiveForm::begin(['id' => 'subject-form']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([
            1 => 'Aktiv',
            0 => 'Inaktiv'
    ], ['class' => 'form-select']) ?>

    <div class="form-group mt-3 text-end">
        <?= Html::submitButton('Speichern', ['class' => 'btn btn-success px-4']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>