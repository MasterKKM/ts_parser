<?php


namespace app\models\form;


use app\models\Vacancy;
use yii\base\Model;
use yii\db\ActiveQuery;

/**
 * Class JobName
 * @property string job_name
 * @property int as_texts
 * @property int social_protected_id
 * @package app\models\form
 */
class JobName extends Model
{
    public string $job_name = '';
    public int $as_texts = 0;
    public int $social_protected_id = 0;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['job_name', 'string'],
            ['job_name', 'default', 'value' => ''],
            [['as_texts', 'social_protected_id'], 'integer'],
            ['as_texts', 'default', 'value' => 0],
            ['social_protected_id', 'default', 'value' => 0],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'job_name' => 'Часть названия вакансии',
            'social_protected_id' => 'Социальное резервирование',
            'as_texts' => 'Вывести в виде текста',
        ];
    }

    public function createRequest(): ActiveQuery
    {
        $vacancy = Vacancy::find()
            ->select('TRUNCATE(salary_min/10000,0) AS `salary`, COUNT(*) AS `count`, SUM(`work_places`) AS `work_places`')
            ->where('work_places IS NOT NULL AND work_places <> 0 AND `salary_min` <> 0') // Число рабочих мест получено, вакансия не скрыта и зарплата указана.
            ->andWhere(['like', 'job-name', $this->job_name])
            ->groupBy('salary');

        switch (true) {
            case ($this->social_protected_id > 0):
                $vacancy->andWhere(['social_protected_id' => $this->social_protected_id]);
                break;
            case ($this->social_protected_id == 0):
                $vacancy->andWhere(['social_protected_id' => null]);
                break;
            case ($this->social_protected_id == -1):
                $vacancy->andWhere('social_protected_id IS NOT NULL');
                break;
        }
        return $vacancy->asArray();
    }
}
