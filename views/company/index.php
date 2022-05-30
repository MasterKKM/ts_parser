<?php

use app\models\Company;
use app\widgets\MainGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $filterModel app\models\Company */

$this->title = 'Компании';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= MainGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => [
            'id',
            'name',
            'inn',
            'email:email',
            [
                'attribute' => 'hr-agency',
                'filter' => false,
                'value' => function (Company $model) {
                    $i = 'hr-agency';
                    return $model->$i == 0 ? 'Нет' : 'Да';
                }
            ],
            'companycode',
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
            ],
        ],
    ]); ?>

</div>
