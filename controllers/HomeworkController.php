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
        $homeworks = Homework::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['due_date' => SORT_ASC])
            ->all();

        return $this->render('index', [
            'homeworks' => $homeworks,
        ]);
    }

    public function actionCreate()
    {
        $model = new Homework();

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->id;
            if ($model->save()) {
                return $this->redirect(['index']);
            }
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
}