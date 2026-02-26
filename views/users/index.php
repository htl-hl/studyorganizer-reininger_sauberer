<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<h1>Login</h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'username')->textInput() ?>
<?= $form->field($model, 'password')->passwordInput() ?>

<div class="form-group">
    <?= Html::submitButton('Login', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

<hr>

<p>Noch keinen Account?</p>

<?= Html::a('Jetzt registrieren', ['site/register'], ['class' => 'btn btn-primary']) ?>
