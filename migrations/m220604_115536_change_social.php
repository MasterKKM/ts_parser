<?php

use yii\db\Migration;

/**
 * Class m220604_115536_change_social
 */
class m220604_115536_change_social extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('social_protected', 'name', $this->text()->notNull()->comment('Социальное резервирование'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('social_protected', 'name', $this->string(255));
    }
}
