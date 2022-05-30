<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employment".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $create_at
 * @property int|null $update_at
 *
 * @property Vacancy[] $vacancies
 */
class Employment extends scheme\Employment
{
    use DecoderTrait;

    /**
     * Gets query for [[Vacancies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVacancies()
    {
        return $this->hasMany(Vacancy::className(), ['employment_id' => 'id']);
    }
}
