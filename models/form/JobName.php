<?php


namespace app\models\form;


use app\models\Vacancy;
use yii\base\Model;

/**
 * Class JobName
 * @property string job_name
 * @package app\models\form
 */
class JobName extends Model
{
    public string $job_name = '';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['job_name', 'string'],
            ['job_name', 'default', 'value' => ''],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'job_name' => 'Часть названия вакансии',
        ];
    }

    public function createRequest()
    {
        return Vacancy::find()
            ->select('TRUNCATE(salary_min/10000,0) AS `salary`, COUNT(*) AS `count`, SUM(`work_places`) AS `work_places`')
            ->where('salary_min IS NOT NULL AND salary_min <> 0')
            ->andWhere(['like', 'job-name', $this->job_name])
            ->groupBy('salary')
            ->orderBy('`salary` DESC')
            ->asArray();
    }
}
