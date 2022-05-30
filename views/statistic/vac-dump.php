<?php

/* @var $this \yii\web\View */
/* @var $models \app\models\Vacancy[]|array|\yii\db\ActiveRecord[] */


$string = '';
foreach ($models as $model) {
    $string .= $model->{'job-name'}
        . ' ( ' . $model->company->name . ')'
        . ' ' . $model->salary_min . ' руб.'
        . ' ' . "https://trudvsem.ru/vacancy/card/{$model->company->companycode}/{$model->uid}"
        . "\n";
}
echo $string;
