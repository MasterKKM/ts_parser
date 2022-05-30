<?php

namespace app\models\scheme;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string $companycode UID работодателя
 * @property string|null $inn ИНН
 * @property int|null $hr-agency Это рекрутер
 * @property string $name Название
 * @property string|null $email email
 *
 * @property Vacancy[] $vacancies
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['companycode', 'name'], 'required'],
            [['hr-agency'], 'integer'],
            [['companycode'], 'string', 'max' => 50],
            [['inn'], 'string', 'max' => 13],
            [['name'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'companycode' => 'UID работодателя',
            'inn' => 'ИНН',
            'hr-agency' => 'Это рекрутер',
            'name' => 'Название',
            'email' => 'email',
        ];
    }

    /**
     * Gets query for [[Vacancies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVacancies()
    {
        return $this->hasMany(Vacancy::className(), ['company_id' => 'id']);
    }
}
