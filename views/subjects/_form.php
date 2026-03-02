<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
?>

    <div class="p-2">
        <?php $form = ActiveForm::begin([
                'id' => 'subjects-form-ajax',
                'enableClientValidation' => true,
        ]); ?>

        <?= $form->field($model, 'name')->textInput([
                'maxlength' => true,
                'class' => 'form-control form-control-md bg-light border-0'
        ])->label('Fachname') ?>

        <?= $form->field($model, 'status')->dropDownList([
                1 => 'Aktiv',
                0 => 'Inaktiv'
        ], ['class' => 'form-select form-select-md bg-light border-0']) ?>

        <div class="mt-4 d-grid">
            <?= Html::submitButton('Speichern', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

<?php
$js = <<<JS
$('form#subjects-form-ajax').on('beforeSubmit', function(e) {
    var form = $(this);
    $.post(form.attr("action"), form.serialize())
        .done(function(result) {
            if(result.success) {
                $("#modal").modal('hide');
                location.reload();
            }
        });
    return false;
});
JS;
$this->registerJs($js);
?>