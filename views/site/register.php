<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Registrieren';
?>

    <h2>Registrieren</h2>
<?php $form = ActiveForm::begin([
        'id' => 'register-form',
        'options' => ['autocomplete'=>'off']
]); ?>

<?= $form->field($model, 'firstname')->textInput(['placeholder'=>'Vorname'])->label(false) ?>
<?= $form->field($model, 'lastname')->textInput(['placeholder'=>'Nachname'])->label(false) ?>
<?= $form->field($model, 'username')->textInput(['placeholder'=>'Username'])->label(false) ?>
<?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Password'])->label(false) ?>

    <div class="checkbox">
        <?= $form->field($model, 'rememberMe')->checkbox(['label' => 'Angemeldet bleiben'])->label(false) ?>
    </div>

<?= Html::submitButton('Registrieren', ['class'=>'btn btn-success w-100', 'name'=>'register-button']) ?>

    <a href="<?= \yii\helpers\Url::to(['site/login']) ?>">Schon ein Konto? Login</a>

<?php ActiveForm::end(); ?>