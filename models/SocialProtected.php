<?php

namespace app\models;

use yii\helpers\ArrayHelper;

/**
 * @property Vacancy[] $vacancies
 */
class SocialProtected extends scheme\SocialProtected
{
    use DecoderTrait;

    /**
     * Gets query for [[Vacancies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVacancies()
    {
        return $this->hasMany(Vacancy::className(), ['social_protected_id' => 'id']);
    }

    public static function createForSelect(): array
    {
        return ArrayHelper::merge(['0' => 'Без ограничений', '-1' => 'Все социальные вакансии'], ArrayHelper::map(self::find()->all(), 'id', 'name'));
    }
}
