<?php

namespace app\models;

use Yii;

/**
 *
 * @property Vacancy[] $vacancies
 */
class Schedule extends scheme\Schedule
{
    use DecoderTrait;

    /**
     * Gets query for [[Vacancies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVacancies()
    {
        return $this->hasMany(Vacancy::className(), ['schedule_id' => 'id']);
    }
}
