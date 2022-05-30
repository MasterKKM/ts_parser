<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * @property Vacancy[] $vacancies
 */
class Company extends scheme\Company
{
    use DecoderTrait;

    public function rules()
    {
        return array_merge(
            [['hr-agency', function () {
                $i = 'hr-agency';
                $this->$i = (int)$this->$i;
            }]],
            parent::rules());
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
                    case 'hr-agency':
                        $query->andWhere(['=', $field, $this->$field]);
                        break;
                    default:
                        $query->andWhere(['like', $field, $this->$field]);
                        break;
                }
            }
        }
        return $query;
    }
}
