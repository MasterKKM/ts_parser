<?php

use yii\db\Migration;

/**
 * Class m220524_120250_field_work_places
 */
class m220524_120250_field_work_places extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('vacancy', 'work_places', $this->integer()->comment('кол-во вакансий'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('vacancy', 'work_places');
    }
}
