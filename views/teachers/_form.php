<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Subjects;
?>

    <div class="p-2">
        <?php $form = ActiveForm::begin([
                'id' => 'teachers-form-ajax',
                'enableClientValidation' => true,
        ]); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'firstname')->textInput([
                        'maxlength' => true,
                        'class' => 'form-control form-control-sm bg-light border-0'
                ])->label('Vorname') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'lastname')->textInput([
                        'maxlength' => true,
                        'class' => 'form-control form-control-sm bg-light border-0'
                ])->label('Nachname') ?>
            </div>
        </div>

        <?= $form->field($model, 'subject_id')->dropDownList(
                ArrayHelper::map(Subjects::find()->where(['status' => 1])->all(), 'id', 'name'),
                [
                        'prompt' => 'Fach auswählen...',
                        'class' => 'form-select form-select-sm bg-light border-0'
                ]
        )->label('Zugeordnetes Fach') ?>

        <?= $form->field($model, 'status')->dropDownList([
                1 => 'Aktiv',
                0 => 'Inaktiv'
        ], ['class' => 'form-select form-select-sm bg-light border-0']) ?>

        <div class="mt-4 d-grid">
            <?= Html::submitButton('Speichern', ['class' => 'btn btn-primary btn-sm rounded-pill shadow-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

<?php
$js = <<<JS
$('form#teachers-form-ajax').on('beforeSubmit', function(e) {
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