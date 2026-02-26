<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use app\models\Homework;
use app\models\Subjects;
use app\models\Teachers;

class HomeworkController extends Controller
{
    public function actionIndex()
    {
        $homeworks = Homework::find()
            ->orderBy(['due_date' => SORT_ASC])
            ->all();

        return $this->render('index', [
            'homeworks' => $homeworks,
        ]);
    }

    public function actionTeachersBySubject($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $teachers = Teachers::find()
            ->innerJoin('Subject_Has_Teacher sht', 'sht.teacher_id = Teachers.id')
            ->where(['sht.subject_id' => $id])
            ->all();

        return ArrayHelper::map($teachers, 'id', function($t){
            return $t->firstname . ' ' . $t->lastname;
        });
    }

    // Create Action
    public function actionCreate()
    {
        $model = new Homework();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['model' => Homework::findOne($id)]);
    }
}