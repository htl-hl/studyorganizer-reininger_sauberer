<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
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
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->role !== 'admin';
                        }
                    ],
                ],
            ],
            // ADD THIS SECTION:
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'toggle-status' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $currentUserId = Yii::$app->user->id;

        $activeHomeworks = Homework::find()
            ->where(['user_id' => $currentUserId])
            ->andWhere(['!=', 'status', 'Finished'])
            ->orWhere(['and', ['user_id' => $currentUserId], ['status' => null]])
            ->orderBy(['due_date' => SORT_ASC])
            ->all();

        $finishedHomeworks = Homework::find()
            ->where(['user_id' => $currentUserId])
            ->andWhere(['status' => 'Finished'])
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
            ->where(['subject_id' => $id, 'status' => 1])
            ->all();

        return ArrayHelper::map($teachers, 'id', function($t){
            return $t->firstname . ' ' . $t->lastname;
        });
    }

    public function actionToggleStatus()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $model = Homework::findOne($id);

        if ($model) {
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

    public function actionUpdate($id)
    {
        $model = Homework::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);

        if (!$model) {
            throw new \yii\web\ForbiddenHttpException('Zugriff verweigert.');
        }

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($model->save()) {
                return ['success' => true];
            }
            return \yii\bootstrap5\ActiveForm::validate($model);
        }

        // This is the key: renderAjax avoids loading the layout (header/footer) inside the modal
        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = Homework::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);

        if ($model) {
            $model->delete();
        }

        return $this->redirect(['index']);
    }
}