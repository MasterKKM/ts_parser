<?php

use app\models\SocialProtected;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\form\JobName */
/* @var $form ActiveForm */
/* @var $table null|array */
?>
<div class="job_counts">

    <h1>Статистика по распределению вакансий(рабочих мест) по зарплатам</h1>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'job_name') ?>
    <?= $form->field($model, 'social_protected_id')->dropDownList(SocialProtected::createForSelect()) ?>
    <?= $form->field($model, 'as_texts')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Ok', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <div>
        <?php if ($table !== null):
            foreach ($table as $item) :?>
                <p class="m-0">
                    <?= 'От ' . (10000 * $item['salary']) . ' до ' . (10000 * (1 + $item['salary'])); ?>
                    вакансий: <?= $item['count'] ?>
                    рабочих мест: <?= $item['work_places'] ?>
                </p>

            <?php endforeach; endif; ?>

    </div>
</div><!-- job_counts -->
