<?php

/* @var $this \yii\web\View */
/* @var $model app\models\form\JobName */
/* @var $table null|array */


$string = '';
$t = [];
foreach ($table as $item) {
    foreach ($item as $key => $value) {
        $count = $t[$key] ?? 0;
        if (mb_strlen($value) > $count) {
            $count = mb_strlen($value);
        }
        $t[$key] = $count;
    }
}
echo '+---------------------+----------+--------------+
|  Диапазон  запрлат  | Вакансий | Рабочих мест |
+---------------------+----------+--------------+
';

foreach ($table as $item) :
    $line = 'От ' . (10000 * $item['salary']) . ' до ' . (10000 * (1 + $item['salary']));
    echo "| $line" . str_repeat(' ', 20 - iconv_strlen($line)) . '|';
    echo str_pad($item['count'], 9, ' ', STR_PAD_LEFT) . ' | ';
    echo str_pad($item['work_places'], 12, ' ', STR_PAD_LEFT);
    echo " |\n";
endforeach;
echo "+---------------------+----------+--------------+\n";
