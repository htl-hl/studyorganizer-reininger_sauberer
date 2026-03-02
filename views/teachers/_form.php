<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
/** @var array $subjects */
?>

<div class="teachers-form p-4">

    <?php $form = ActiveForm::begin(['id' => 'teacher-form-modal']); ?>

    <div class="row g-3">
        <div class="col-md-6">
            <?= $form->field($model, 'firstname')->textInput(['maxlength' => true, 'placeholder' => 'Vorname']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'lastname')->textInput(['maxlength' => true, 'placeholder' => 'Nachname']) ?>
        </div>
    </div>

    <div class="mt-3">
        <?= $form->field($model, 'subject_id')->dropDownList($subjects, [
                'prompt' => 'Fach auswählen...',
                'class' => 'form-select shadow-sm',
        ])->label('Zuständiges Fach') ?>
    </div>

    <div class="mt-3">
        <?= $form->field($model, 'status')->dropDownList([
                1 => 'Aktiv',
                0 => 'Inaktiv',
        ], ['class' => 'form-select shadow-sm']) ?>
    </div>

    <div class="form-group mt-4 text-end">
        <button type="button" class="btn btn-light border px-4 me-2" data-bs-dismiss="modal">Abbrechen</button>
        <?= Html::submitButton($model->isNewRecord ? 'Erstellen' : 'Änderungen speichern', ['class' => 'btn btn-success px-4 shadow-sm']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>