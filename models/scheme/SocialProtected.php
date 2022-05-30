<?php

namespace app\models\scheme;

use Yii;

/**
 * This is the model class for table "social_protected".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property Vacancy[] $vacancies
 */
class SocialProtected extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'social_protected';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

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
