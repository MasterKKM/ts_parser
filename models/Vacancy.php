<?php

namespace app\models;


use yii\db\ActiveQuery;

/**
 * @property Company $company
 * @property Employment $employment
 * @property Schedule $schedule
 */
class Vacancy extends scheme\Vacancy
{
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
     * Gets query for [[SocialProtected]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocialProtected()
    {
        return $this->hasOne(SocialProtected::className(), ['id' => 'social_protected_id']);
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

    public static function decode(array $data, bool $updateOnChanged = true): self
    {
        $data['uid'] = $data['id'];
        unset($data['id']);
        $model = static::findOne(['uid' => $data['uid']]);
        if ($model === null) {
            $model = new static;
        } elseif (!($updateOnChanged && $model->{'creation-date'} != $data['creation-date'])) {

            echo "\nДублирование записи: {$data['uid']}\n";
            return $model;
        }
        $model->load($data, '');

        $model->company_id = Company::insertOrUpdate(['companycode' => $data['company']['companycode']], $data['company'])->id;
        if (isset($data['employment'])) {
            $model->employment_id = Employment::insertOrUpdate(['name' => $data['employment']], ['name' => $data['employment']])->id;
        }
        if (isset($data['schedule'])) {
            $model->schedule_id = Schedule::insertOrUpdate(['name' => $data['schedule']], ['name' => $data['schedule']])->id;
        }
        if (isset($data['social_protected'])) {
            $model->social_protected_id = SocialProtected::insertOrUpdate(['name' => $data['social_protected']], ['name' => $data['social_protected']])->id;
        }
        if (!$model->save()) {
            echo 'Ошибка записи вакансии:' . print_r($model->errors, true);
            die;
        }
        return $model;
    }

    /**
     * @param array $params
     * @return ActiveQuery
     */
    public function search(array $params = []): ActiveQuery
    {
        $query = self::find();
        if (!empty($params) && !$this->load($params)) {
            return $query;
        }
        $fields = $this->fields();
        foreach ($fields as $field) {
            if (isset($this->$field) && $this->$field !== '') {
                switch ($field) {
                    case 'uid':
                    case 'job-name':
                        // Поиска по uid нет, по этому uid используется как альтернатива job-name в случаях глюков ввода.
                        $query->andWhere(['like', 'job-name', $this->$field]);
                        break;
                    case 'company_id':
                        $query->join('INNER JOIN', 'company', 'company.id = vacancy.company_id');
                        $query->andWhere(['like', 'company.name', $this->$field]);
                        break;
                    case 'salary_min':
                        $query->andWhere(['>=', $field, $this->$field]);
                        break;
                    case 'salary_max':
                        $query->andWhere(['<=', $field, $this->$field]);
                        break;
                    case 'social_protected_id':
                        switch ($this->$field) {
                            case '0':
                                $query->andWhere('`social_protected_id` IS NULL');
                                break;
                            case '-1':
                                $query->andWhere('`social_protected_id` IS NOT NULL');
                                break;
                            default:
                                $query->andWhere(['=', $field, $this->$field]);
                        }
                        break;
                    default:
                        $query->andWhere(['=', $field, $this->$field]);
                        break;
                }
            }
        }
        return $query;
    }
}
