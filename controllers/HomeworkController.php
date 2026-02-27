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

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        // Use renderAjax instead of render
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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

        // Wir suchen direkt in der Teachers-Tabelle nach der subject_id
        $teachers = Teachers::find()
            ->where(['subject_id' => $id])
            ->andWhere(['status' => 1]) // Optional: nur aktive Lehrer anzeigen
            ->all();

        return ArrayHelper::map($teachers, 'id', function($t){
            return $t->firstname . ' ' . $t->lastname;
        });
    }

    public function actionToggleStatus($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // We allow both POST and GET for this test to ensure it works
        $model = Homework::findOne($id);

        if ($model) {
            $model->status = ($model->status === 'Finished') ? 'Open' : 'Finished';
            if ($model->save()) {
                return ['success' => true, 'newStatus' => $model->status];
            }
        }

        return ['success' => false, 'error' => 'Model not found or not saved'];
    }
}