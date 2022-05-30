<?php


namespace app\widgets;


use yii\grid\GridView;

/**
 * Class MainGridView
 * Используется для нормального отображения пагинатора.
 * @package app\widgets
 */
class MainGridView extends GridView
{
    public $pager = [
        'options' => [
            'class' => 'pagination',
        ],
        'linkContainerOptions' => [
            'class' => 'page-item',
        ],
        'linkOptions' => [
            'class' => 'page-link',
        ],
        'prevPageCssClass' => '',
        'disabledListItemSubTagOptions' => [
            'tag' => 'a',
            'aria-disabled' => 'true',
            'href' => '#',
            'class' => 'page-link',
        ],
    ];
}
