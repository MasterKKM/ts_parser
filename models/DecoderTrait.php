<?php


namespace app\models;

use yii\base\InvalidArgumentException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Trait DecoderTrait
 * @package app\models
 */
trait DecoderTrait
{
    /**
     * @param array $find
     * @param array $params
     * @return DecoderTrait|\yii\db\ActiveRecord
     */
    static public function insertOrUpdate(array $find, array $params)
    {
        /** @var \yii\db\ActiveRecord $model */
        $model = static::findOne($find);
        if (empty($model)) {
            $model = new self();
            $model->load($params, '');
        }
        if (!$model->save() || !$model->refresh()) {
            throw new InvalidArgumentException('Ошибка даннх объекта ' . get_class($model) . ' ' . print_r($model->errors, true));
        }
        return $model;
    }
}
