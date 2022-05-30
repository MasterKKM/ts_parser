<?php
/* @var $this \yii\web\View */

/* @var $dataProvider \yii\data\ActiveDataProvider */

/* @var $title string */

use app\models\Vacancy;
use app\widgets\MainGridView;
use yii\helpers\Html;

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="statistic-vacancies">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= MainGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'Профессия',
                'value' => function (Vacancy $model) {
                    return $model->{'job-name'};
                },
            ],
            [
                'label' => 'Число вакансий',
                'attribute' => 'salary_max',
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'label' => 'Число рабочих мест',
                'attribute' => 'work_places',
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'label' => 'Лучшая зарплатиа',
                'attribute' => 'salary_min',
                'contentOptions' => ['class' => 'text-right'],
            ],
        ],
    ]); ?>
</div>
