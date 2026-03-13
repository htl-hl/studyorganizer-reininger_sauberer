<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
/** @var array $subjects */
?>

<div class="teachers-form p-4">

    <?php $form = ActiveForm::begin(['id' => 'teacher-form-modal']); ?>

    <div class="row g-3">
        <div class="col-md-6">
            <?= $form->field($model, 'firstname')->textInput(['maxlength' => true, 'placeholder' => 'Vorname'])->label('Vorname') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'lastname')->textInput(['maxlength' => true, 'placeholder' => 'Nachname'])->label('Nachname') ?>
        </div>
    </div>

    <div class="mt-3">
        <label class="form-label fw-bold text-secondary small text-uppercase" style="letter-spacing: 1px;">
            Zuständige Fächer
        </label>

        <div class="p-3 border rounded-3 bg-light shadow-sm">
            <?= $form->field($model, 'subject_ids')->checkboxList($subjects, [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $isChecked = $checked ? 'checked' : '';
                        return "
                <div class='form-check form-check-inline me-3 mb-2'>
                    <input type='checkbox' class='form-check-input' name='{$name}' value='{$value}' id='subject_{$value}' {$isChecked}>
                    <label class='form-check-label' for='subject_{$value}'>{$label}</label>
                </div>";
                    }
            ])->label(false) ?>
        </div>
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