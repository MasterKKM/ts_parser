<?php

namespace app\models\form;

use yii\base\Model;

class CompanyName extends Model
{
    public string $company = '';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['company', 'required'],
            ['company', 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'company' => 'Часть названия компании',
        ];
    }
}
