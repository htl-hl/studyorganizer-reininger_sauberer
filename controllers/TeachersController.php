<?php

namespace app\controllers;

use Yii;
use app\models\Teachers;
use app\models\TeachersSearch;
use app\models\Subjects;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;

class TeachersController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->request->isAjax) {
            $this->layout = false;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $searchModel = new TeachersSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Teachers();
        $subjects = ArrayHelper::map(Subjects::find()->where(['status' => 1])->all(), 'id', 'name');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'subjects' => $subjects,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $subjects = ArrayHelper::map(Subjects::find()->where(['status' => 1])->all(), 'id', 'name');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'subjects' => $subjects,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Teachers::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Lehrer nicht gefunden.');
    }
}