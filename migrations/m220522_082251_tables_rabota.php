<?php

use yii\db\Migration;

/**
 * Class m220522_082251_tables_rabota
 */
class m220522_082251_tables_rabota extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Работодатель
        $this->createTable('company', [
            'id' => $this->primaryKey(),
            'companycode' => $this->string(50)->notNull()->comment('UID работодателя'),
            'inn' => $this->string(13)->comment('ИНН'),
            'hr-agency' => $this->boolean()->comment('Это рекрутер'),
            'name' => $this->string(255)->notNull()->comment('Название'),
            'email' => $this->string(128)->comment('email'),
        ]);

        // Тип занятости
        $this->createTable('employment', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
        ]);

        // Социальная нагрузка
        $this->createTable('social_protected', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
        ]);

        // График работы
        $this->createTable('schedule', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
        ]);

        // Вакансии
        $this->createTable('vacancy', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(50)->notNull()->comment('UID вакансии'),
            'company_id' => $this->integer()->notNull()->comment('Работодатель'),
            'source' => $this->string(120)->comment('Источник'),
            'creation-date' => $this->string(25)->comment('Дата внесения'),
            'salary_min' => $this->decimal(12, 2)->comment('Минимальная зарплата'),
            'salary_max' => $this->decimal(12, 2)->comment('Максимальная зарплата'),
            'job-name' => $this->string('255')->comment('Профессия'),
            'employment_id' => $this->integer()->comment('Тип занятости'),
            'schedule_id' => $this->integer()->comment('График'),
            'social_protected_id' => $this->integer()->comment('Социальный резерв'),
        ]);

        $this->addForeignKey('foreign_vacancy_company', 'vacancy', 'company_id', 'company', 'id');
        $this->addForeignKey('foreign_vacancy_employment', 'vacancy', 'employment_id', 'employment', 'id');
        $this->addForeignKey('foreign_vacancy_schedule', 'vacancy', 'schedule_id', 'schedule', 'id');
        $this->addForeignKey('foreign_vacancy_social_protected', 'vacancy', 'social_protected_id', 'social_protected', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('foreign_vacancy_social_protected', 'vacancy');
        $this->dropForeignKey('foreign_vacancy_schedule', 'vacancy');
        $this->dropForeignKey('foreign_vacancy_employment', 'vacancy');
        $this->dropForeignKey('foreign_vacancy_company', 'vacancy');

        $this->dropTable('vacancy');
        $this->dropTable('schedule');
        $this->dropTable('social_protected');
        $this->dropTable('employment');
        $this->dropTable('company');
    }
}
