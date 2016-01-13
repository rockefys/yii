<?php

namespace app\controllers;

use Yii;
use app\models\Atest;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AtestController implements the CRUD actions for Atest model.
 */
class AtestController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Atest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Atest::find(),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
        $model = new Atest();
        return $this->render('@common/views/common/index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'columns' => Atest::attributeLists()
        ]);
    }

    /**
     * Displays a single Atest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('@common/views/common/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Atest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Atest();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('@common/views/common/create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Atest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('@common/views/common/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Atest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Atest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Atest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Atest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
