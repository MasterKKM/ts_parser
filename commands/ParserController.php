<?php

namespace app\commands;

use app\models\console\Loader;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\console\Controller;

class ParserController extends Controller
{
    /**
     * @throws GuzzleException
     */
    public function actionBase()
    {
        Yii::$app->log->traceLevel = 3;
        Yii::$app->cache->flush();
        /** @var Loader $loader */
        $loader = Yii::$container->get('Loader');
        $loader->loadAndParse();
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws \Exception
     */
    public function actionPlaces()
    {
        Yii::$app->log->traceLevel = 3;
        Yii::$app->cache->flush();
        /** @var Loader $loader */
        $loader = Yii::$container->get('Loader');
        $loader->countLoader();
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionPlacesUpdate()
    {
        Yii::$app->log->traceLevel = 3;
        Yii::$app->cache->flush();
        /** @var Loader $loader */
        $loader = Yii::$container->get('Loader');
        $loader->workPlacesUpdate();
    }
}
