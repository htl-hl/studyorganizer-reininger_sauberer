<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\models\Homework;
use app\models\Subjects;
use app\models\Teachers;

class HomeworkController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {

        $activeHomeworks = Homework::find()
            ->where(['!=', 'status', 'Finished'])
            ->orWhere(['status' => null])
            ->orderBy(['due_date' => SORT_ASC])
            ->all();


        $finishedHomeworks = Homework::find()
            ->where(['status' => 'Finished'])
            ->orderBy(['due_date' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'activeHomeworks' => $activeHomeworks,
            'finishedHomeworks' => $finishedHomeworks,
        ]);
    }

    public function actionCreate()
    {
        $model = new Homework();

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->id;

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($model->save()) {
                return ['success' => true];
            } else {
                return \yii\bootstrap5\ActiveForm::validate($model);
            }
        }

        return $this->renderAjax('create', ['model' => $model]);
    }

    public function actionView($id)
    {
        $model = Homework::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);

        if (!$model) {
            throw new ForbiddenHttpException('Zugriff verweigert.');
        }

        return $this->render('view', ['model' => $model]);
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

    public function actionToggleStatus() // Remove ($id) from here
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Get ID from POST data instead of URL parameter
        $id = Yii::$app->request->post('id');
        $model = Homework::findOne($id);

        if ($model) {
            // Basic security check: ensure the user owns this task
            if ($model->user_id !== Yii::$app->user->id) {
                return ['success' => false, 'error' => 'Unauthorized'];
            }

            $model->status = ($model->status === 'Finished') ? 'Open' : 'Finished';
            if ($model->save()) {
                return ['success' => true, 'newStatus' => $model->status];
            }
        }

        return ['success' => false, 'error' => 'Model not found or save failed'];
    }
}