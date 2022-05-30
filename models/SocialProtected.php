<?php

namespace app\models;

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
}
