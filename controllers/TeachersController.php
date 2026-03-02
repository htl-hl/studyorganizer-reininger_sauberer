<?php

namespace app\controllers;

use Yii;
use app\models\Teachers;
use app\models\TeachersSearch;
use app\models\Subjects; // Wichtig für das Dropdown
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class TeachersController extends Controller
{
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => ['delete' => ['POST']],
            ],
        ];
    }

    public function actionIndex() {
        $searchModel = new TeachersSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);    }

    public function actionCreate() {
        $model = new Teachers();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => true];
            }
            return $this->redirect(['index']);
        }
        return $this->renderAjax('create', ['model' => $model]);
    }

    public function actionUpdate($id) {
        $model = Teachers::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => true];
            }
            return $this->redirect(['index']);
        }
        return $this->renderAjax('update', ['model' => $model]);
    }
}