<?php

namespace app\controllers;

use app\models\SocialProtected;
use yii\data\ActiveDataProvider;

/**
 * SocialController implements the CRUD actions for SocialProtected model.
 */
class SocialController extends BaseController
{
    /**
     * Lists all SocialProtected models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => SocialProtected::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
