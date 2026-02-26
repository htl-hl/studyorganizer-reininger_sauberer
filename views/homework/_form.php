<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Subjects;

$form = ActiveForm::begin();
?>

<?= $form->field($model, 'title')->textInput(['maxlength'=>true]) ?>
<?= $form->field($model, 'description')->textarea(['rows'=>6]) ?>
<?= $form->field($model, 'due_date')->textInput() ?>
<?= $form->field($model, 'status')->textInput(['maxlength'=>20]) ?>

<?= $form->field($model, 'subject_id')->dropDownList(
        ArrayHelper::map(Subjects::find()->all(), 'id', 'name'),
        ['prompt'=>'Fach auswählen', 'id'=>'subject-id']
) ?>

<?= $form->field($model, 'teacher_id')->dropDownList(
        [], // leer, wird per AJAX gefüllt
        ['prompt'=>'Lehrer auswählen', 'id'=>'teacher-id']
) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class'=>'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs("
$('#subject-id').on('change', function() {
    var subjectId = $(this).val();
    $.getJSON('" . Yii::$app->urlManager->createUrl(['homework/teachers-by-subject']) . "', {id: subjectId}, function(data) {
        var items = '<option value=\"\">Lehrer auswählen</option>';
        $.each(data, function(key, value){
            items += '<option value=\"'+key+'\">'+value+'</option>';
        });
        $('#teacher-id').html(items);
    });
});
");
?>