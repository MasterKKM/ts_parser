<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\form\JobName */
/* @var $form ActiveForm */
/* @var $table null|array */
?>
<div class="job_counts">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'job_name') ?>

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
