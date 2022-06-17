<?php

namespace app\helpers;
/**
 * Вакцина от Ростелекомовского трояна. Имитирует признак работы пейлоада в ссыке на JS файлы.
 * Используется для защиты пользователейй заедших на страницу, при невозможности использования https.
 * Https - лучшая защита.
 */

use yii\web\View;

class ViewVaccinated extends View
{
    public function registerJsFile($url, $options = [], $key = null)
    {
        if (strpos($url, '?') === false) {
            $url .= '?';
        }
        return parent::registerJsFile($url, $options, $key);
    }
}
