<?php

use yii\helpers\Html;

$this->title = 'Тестовая статистика';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="index-index">
    <h1><?= $this->title ?></h1>
    <div>
        <ui>
            <ul><?= HTML::a('Сборка по общедоступным вакансиям', ['vacancies']) ?></ul>
            <ul><?= HTML::a('Сборка по социальным вакансиям', ['soc-vacancies']) ?></ul>
            <ul><?= HTML::a('Текстовая сборка "Сто самых востребованных вакансий"', ['vacancies-dump']) ?></ul>
            <ul><?= HTML::a('Текстовая выборка "Сто лучших вакансий"', ['vac-dump']) ?></ul>
            <ul><?= HTML::a('Текстовая выборка "Количество вакансий"', ['count-places']) ?></ul>
        </ui>
    </div>
</div>
