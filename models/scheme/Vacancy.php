<?php

namespace app\models\scheme;

use Yii;

/**
 * This is the model class for table "vacancy".
 *
 * @property int $id
 * @property string $uid UID вакансии
 * @property int $company_id Работодатель
 * @property string|null $source Источник
 * @property string|null $creation-date Дата внесения
 * @property float|null $salary_min Минимальная зарплата
 * @property float|null $salary_max Максимальная зарплата
 * @property string|null $job-name Профессия
 * @property int|null $employment_id Тип занятости
 * @property int|null $schedule_id График
 * @property int|null $social_protected_id Социальный резерв
 * @property int|null $work_places кол-во вакансий
 *
 * @property Company $company
 * @property Employment $employment
 * @property Schedule $schedule
 * @property SocialProtected $socialProtected
 */
class Vacancy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vacancy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'company_id'], 'required'],
            [['company_id', 'employment_id', 'schedule_id', 'social_protected_id', 'work_places'], 'integer'],
            [['salary_min', 'salary_max'], 'number'],
            [['uid'], 'string', 'max' => 50],
            [['source'], 'string', 'max' => 120],
            [['creation-date'], 'string', 'max' => 25],
            [['job-name'], 'string', 'max' => 255],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['employment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employment::className(), 'targetAttribute' => ['employment_id' => 'id']],
            [['schedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schedule::className(), 'targetAttribute' => ['schedule_id' => 'id']],
            [['social_protected_id'], 'exist', 'skipOnError' => true, 'targetClass' => SocialProtected::className(), 'targetAttribute' => ['social_protected_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'UID вакансии',
            'company_id' => 'Работодатель',
            'source' => 'Источник',
            'creation-date' => 'Дата внесения',
            'salary_min' => 'Минимальная зарплата',
            'salary_max' => 'Максимальная зарплата',
            'job-name' => 'Профессия',
            'employment_id' => 'Тип занятости',
            'schedule_id' => 'График',
            'social_protected_id' => 'Социальный резерв',
            'work_places' => 'кол-во вакансий',
        ];
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * Gets query for [[Employment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployment()
    {
        return $this->hasOne(Employment::className(), ['id' => 'employment_id']);
    }

    /**
     * Gets query for [[Schedule]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSchedule()
    {
        return $this->hasOne(Schedule::className(), ['id' => 'schedule_id']);
    }

    /**
     * Gets query for [[SocialProtected]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocialProtected()
    {
        return $this->hasOne(SocialProtected::className(), ['id' => 'social_protected_id']);
    }
}
