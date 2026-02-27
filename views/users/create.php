<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<h1>Registrierung</h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'username')->textInput() ?>
<?= $form->field($model, 'password')->passwordInput() ?>

<div class="form-group">
    <?= Html::submitButton('Registrieren', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
