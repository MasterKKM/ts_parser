<?php

namespace app\controllers;

use app\models\Schedule;
use yii\data\ActiveDataProvider;

/**
 * ScheduleController implements the CRUD actions for Schedule model.
 */
class ScheduleController extends BaseController
{
    /**
     * Lists all Schedule models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Schedule::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
