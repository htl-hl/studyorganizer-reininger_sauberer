<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LoginForm */

$this->title = 'Login';

?>

<div class="login-box">
    <h2>Login</h2>
    <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['autocomplete' => 'off']
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['placeholder'=>'Benutzername', 'autofocus'=>true])->label(false) ?>
    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Passwort'])->label(false) ?>
    <?= $form->field($model, 'rememberMe')->checkbox(['label' => 'Angemeldet bleiben']) ?>
    <?= Html::submitButton('Login', ['class'=>'btn btn-primary w-100', 'name'=>'login-button']) ?>

    <a href="<?= \yii\helpers\Url::to(['site/register']) ?>">Noch kein Konto? Registrieren</a>

    <?php ActiveForm::end(); ?>
</div>