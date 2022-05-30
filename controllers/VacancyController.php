<?php

namespace app\controllers;

use app\models\Vacancy;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * VacancyController implements the CRUD actions for Vacancy model.
 */
class VacancyController extends BaseController
{

    /**
     * Lists all Vacancy models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $filterModel = new Vacancy;
        $filterModel->load(\Yii::$app->request->queryParams);

        $dataProvider = new ActiveDataProvider([
            'query' => $filterModel->search(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filterModel' => $filterModel,
        ]);
    }

    /**
     * Displays a single Vacancy model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Vacancy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Vacancy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vacancy::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
