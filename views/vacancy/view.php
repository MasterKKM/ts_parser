<?php

use app\models\Vacancy;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Vacancy */

$this->title = 'Вакансия №' . $model->id . ' ' . $model->{'job-name'};
$this->params['breadcrumbs'][] = ['label' => 'Вакансии', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="vacancy-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'uid',
            [
                'attribute' => 'company_id',
                'value' => function (Vacancy $model) {
                    return $model->company->name;
                },
            ],
            'source',
            'creation-date',
            'work_places',
            'salary_min',
            'salary_max',
            'job-name',
            [
                'attribute' => 'employment_id',
                'value' => function (Vacancy $model) {
                    return $model->employment->name;
                },
            ],
            [
                'attribute' => 'schedule_id',
                'value' => function (Vacancy $model) {
                    return $model->schedule->name;
                },
            ],
            [
                'attribute' => 'social_protected_id',
                'value' => function (Vacancy $model) {
                    return $model->socialProtected === null ? '' : $model->socialProtected->name;
                },
            ],
            [
                'label' => 'Ссылка на источник',
                'format' => 'url',
                'value' => function (Vacancy $model) {
                    return "https://trudvsem.ru/vacancy/card/{$model->company->companycode}/{$model->uid}";
                }
            ],
        ],
    ]) ?>

</div>
