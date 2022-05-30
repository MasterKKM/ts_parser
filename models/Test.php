<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "test".
 *
 * @property int $id
 * @property string $name Работодатель
 * @property string $vacancy Вакансия
 * @property int $min_price Минимальная оплата
 * @property int $max_price Максимальная оплата
 * @property string|null $uin_vacancy Код вакансии
 * @property string|null $uin_employer Код работодателя
 * @property int|null $is_disabled Для инвалидов
 * @property int|null $workPlaces Количество мест
 * @property string|null $busyType Занятость
 * @property int|null $publishedDate Опубликовано
 * @property string|null $scheduleType График
 */
class Test extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'vacancy', 'min_price', 'max_price'], 'required'],
            [['min_price', 'max_price', 'is_disabled', 'workPlaces', 'publishedDate'], 'integer'],
            [['name', 'vacancy'], 'string', 'max' => 254],
            [['uin_vacancy', 'uin_employer', 'busyType', 'scheduleType'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Работодатель',
            'vacancy' => 'Вакансия',
            'min_price' => 'Минимальная оплата',
            'max_price' => 'Максимальная оплата',
            'uin_vacancy' => 'Код вакансии',
            'uin_employer' => 'Код работодателя',
            'is_disabled' => 'Для инвалидов',
            'workPlaces' => 'Количество мест',
            'busyType' => 'Занятость',
            'publishedDate' => 'Опубликовано',
            'scheduleType' => 'График',
        ];
    }

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
                    case 'name':
                    case 'vacancy':
                        $query->andWhere(['like', $field, $this->$field]);
                        break;
                    case 'min_price':
                        $query->andWhere(['>=', $field, $this->$field]);
                        break;
                    case 'max_price':
                        $query->andWhere(['<=', $field, $this->$field]);
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
