<?php

use app\models\Vacancy;

/** @var Vacancy[] $models */

/**
 * @param string $str
 * @param int $leen
 * @return string
 */
function m_str_spaces(string $str, int $leen): string
{
    $m = $leen - iconv_strlen($str);
    return $str . str_repeat(' ', $m);
}


$maxJob = 0;
$maxWork = 8;
$maxSalary = 8;

foreach ($models as $model) {
    $maxJob = iconv_strlen($model->{'job-name'}) > $maxJob ? iconv_strlen($model->{'job-name'}) : $maxJob;
    $maxWork = iconv_strlen($model->work_places) > $maxWork ? iconv_strlen($model->work_places) : $maxWork;
    $maxSalary = iconv_strlen($model->salary_min) > $maxSalary ? iconv_strlen($model->salary_min) : $maxSalary;
}

$string = '';
foreach ($models as $model) {
    $string .= '| '
        . m_str_spaces($model->{'job-name'}, $maxJob)
        . ' | ' . str_pad($model->salary_max, 8, ' ', STR_PAD_LEFT)
        . ' | ' . str_pad($model->work_places, $maxWork, ' ', STR_PAD_LEFT)
        . ' | ' . str_pad($model->salary_min, $maxSalary, ' ', STR_PAD_LEFT)
        . " |\n";
}
echo $string;
