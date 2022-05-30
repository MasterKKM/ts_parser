<?php

use app\models\Company;
use app\models\Employment;
use app\models\SocialProtected;
use app\models\Vacancy;
use app\widgets\MainGridView;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Company */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Компании', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'companycode',
            'inn',
            [
                'attribute' => 'hr-agency',
                'value' => function (Company $model) {
                    return $model->{'hr-agency'} == 0 ? 'Нет' : 'Да';
                }
            ],
            'name',
            'email:email',
            [
                'label' => 'Ссылка на источник',
                'format' => 'url',
                'value' => function (Company $model) {
                    return "https://trudvsem.ru/company/{$model->companycode}";
                }
            ],
        ],
    ]) ?>
    <div class="company-vacancy-view">
        <h3>Вакансии</h3>
        <?= MainGridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                [
                    'attribute' => 'job-name',
                    'label' => 'Профессия',
                    'value' => function (Vacancy $model) {
                        return $model->{'job-name'};
                    },
                ],
                'creation-date',
                [
                    'attribute' => 'social_protected_id',
                    'value' => function (Vacancy $model) {
                        return $model->socialProtected === null ? '' : $model->socialProtected->name;
                    },
                ],
                [
                    'attribute' => 'work_places',
                    'contentOptions' => ['class' => 'text-right'],
                ],
                [
                    'attribute' => 'salary_min',
                    'contentOptions' => ['class' => 'text-right'],
                ],
                [
                    'attribute' => 'salary_max',
                    'contentOptions' => ['class' => 'text-right'],
                ],
                [
                    'attribute' => 'employment_id',
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function (Vacancy $model) {
                        return $model->employment->name;
                    },
                ],
            ],
        ]); ?>

    </div>
</div>
