<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Subjects;

$form = ActiveForm::begin();
?>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'due_date')->label('Abgabedatum')->input('date', [
                    'class' => 'form-control',
                    'min' => date('Y-m-d')
            ]) ?>
        </div>
    </div>

<?= $form->field($model, 'description')->textarea(['rows' => 4])->label('Beschreibung') ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'subject_id')->dropDownList(
                    ArrayHelper::map(Subjects::find()->all(), 'id', 'name'),
                    ['prompt' => 'Fach auswählen...', 'id' => 'subject-id', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'teacher_id')->dropDownList(
                    [],
                    ['prompt' => 'Zuerst Fach wählen', 'id' => 'teacher-id', 'class' => 'form-select']
            ) ?>
        </div>
    </div>

    <div class="form-group mt-4">
        <?= Html::submitButton('Aufgabe speichern', ['class' => 'btn btn-primary w-100 shadow-sm']) ?>
    </div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs("
$('#subject-id').on('change', function() {
    var subjectId = $(this).val();
    if(subjectId) {
        $.getJSON('" . \yii\helpers\Url::to(['homework/teachers-by-subject']) . "', {id: subjectId}, function(data) {
            var items = '<option value=\"\">Lehrer auswählen</option>';
            $.each(data, function(key, value){
                items += '<option value=\"'+key+'\">'+value+'</option>';
            });
            $('#teacher-id').html(items);
        });
    } else {
        $('#teacher-id').html('<option value=\"\">Zuerst Fach wählen</option>');
    }
});
");
?>