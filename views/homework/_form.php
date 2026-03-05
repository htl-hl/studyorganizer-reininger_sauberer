<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Subjects;
use app\models\Teachers;

$form = ActiveForm::begin([
        'id' => 'homework-form',
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
]);
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
                    ArrayHelper::map(Subjects::find()
                    ->where(['status' => 1])
                    ->orderBy(['name' => SORT_ASC])
                    ->all(),
                    'id',
                    'name',
                    ),
                    ['prompt' => 'Fach auswählen...', 'id' => 'subject-id', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-6">
            <?php
            $teachers = [];
            if ($model->subject_id) {
                $teachersData = Teachers::find()
                        ->innerJoin('Subject_Has_Teacher sht', 'sht.teacher_id = Teachers.id')
                        ->where(['sht.subject_id' => $model->subject_id])
                        ->all();

                $teachers = ArrayHelper::map($teachersData, 'id', function($t) {
                    return $t->firstname . ' ' . $t->lastname;
                });
            }
            ?>

            <?= $form->field($model, 'teacher_id')->dropDownList(
                    $teachers,
                    [
                            'prompt' => $model->subject_id ? 'Lehrer auswählen' : 'Zuerst Fach wählen',
                            'id' => 'teacher-id',
                            'class' => 'form-select'
                    ]
            ) ?>
        </div>
    </div>

    <div class="form-group mt-4">
        <?= Html::submitButton('Aufgabe speichern', [
                'class' => 'btn btn-primary w-100 shadow-sm',
                'id' => 'save-button'
        ]) ?>
    </div>

<?php ActiveForm::end(); ?>

<?php
$currentTeacherId = $model->teacher_id ?? '';
$teachersUrl = \yii\helpers\Url::to(['homework/teachers-by-subject']);

$this->registerJs("
function updateTeachers(subjectId, selectedTeacherId = null) {
    if(subjectId) {
        $.getJSON('$teachersUrl', {id: subjectId}, function(data) {
            var items = '<option value=\"\">Lehrer auswählen</option>';
            $.each(data, function(key, value){
                var selected = (selectedTeacherId && key == selectedTeacherId) ? ' selected' : '';
                items += '<option value=\"'+key+'\"'+selected+'>'+value+'</option>';
            });
            $('#teacher-id').html(items);
        });
    } else {
        $('#teacher-id').html('<option value=\"\">Zuerst Fach wählen</option>');
    }
}

$(document).off('change', '#subject-id').on('change', '#subject-id', function() {
    updateTeachers($(this).val());
});

var initialSubject = $('#subject-id').val();
if(initialSubject && !$('#teacher-id').val()) {
    updateTeachers(initialSubject, '$currentTeacherId');
}
");
?>