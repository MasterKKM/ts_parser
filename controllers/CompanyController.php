<?php

namespace app\controllers;

use app\models\Company;
use app\models\Vacancy;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends BaseController
{
    /**
     * Lists all Company models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $filterModel = new Company();
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
     * Displays a single Company model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Vacancy::find()->where(['company_id' => $id]),
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
