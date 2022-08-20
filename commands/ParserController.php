<?php

namespace app\commands;

use app\models\console\Loader;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\console\Controller;

class ParserController extends Controller
{

    /**
     * Общая загрузка вакансий заданного региона.
     * @throws GuzzleException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws \Exception
     */
    public function actionAll()
    {
        Yii::$app->log->traceLevel = 3;
        Yii::$app->cache->flush();
        /** @var Loader $loader */
        $loader = Yii::$container->get('Loader');
        $loader->loadAndParse();
        $loader->countLoader();
    }

    /**
     * Загрузка основных данных через API.
     * @throws GuzzleException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
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
     * Дополнительная загрузка кол-ва вакансий.
     * (Через не документированный запрос)
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
     * Обновление кол-ва вакансий.
     * (Через не документированный запрос)
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws \Exception
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
