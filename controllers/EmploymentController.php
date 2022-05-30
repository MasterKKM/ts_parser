<?php

namespace app\controllers;

use app\models\Employment;
use yii\data\ActiveDataProvider;

/**
 * EmploymentController implements the CRUD actions for Employment model.
 */
class EmploymentController extends BaseController
{
    /**
     * Lists all Employment models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Employment::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
