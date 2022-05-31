<?php

use app\models\Employment;
use app\models\SocialProtected;
use app\models\Vacancy;
use app\widgets\MainGridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $filterModel app\models\Vacancy */


$this->title = 'Вакансии';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vacancy-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= MainGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => [
            'id',
            [
                // TODO Добавить альтернативное поле для job-name, или согласовать переименование.
                'attribute' => 'uid',
                'filter' => true,
                'label' => 'Профессия',
                'value' => function (Vacancy $model) {
                    return $model->{'job-name'};
                },
            ],
            [
                'attribute' => 'company_id',
                'filter' => true,
                'value' => function (Vacancy $model) {
                    return $model->company->name;
                },
            ],
            [
                'attribute' => 'work_places',
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'attribute' => 'social_protected_id',
                'filter' => SocialProtected::createForSelect(),
                'value' => function (Vacancy $model) {
                    return $model->socialProtected === null ? '' : $model->socialProtected->name;
                },
                'contentOptions' => ['class' => 'text-center'],
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
                'filter' => ArrayHelper::map(Employment::find()->all(), 'id', 'name'),
                'contentOptions' => ['class' => 'text-center'],
                'value' => function (Vacancy $model) {
                    return $model->employment->name;
                },
            ],
            //'schedule_id',
            //'social_protected_id',
            //'create_at',
            //'update_at',
            [
                'class' => ActionColumn::className(),
                'template' => '{view}',
            ],
        ],
    ]); ?>


</div>
