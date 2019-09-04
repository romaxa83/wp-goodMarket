<?php

use yii\db\Migration;

/**
 * Handles the creation of table `guest`.
 */
class m180906_090333_create_guest_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('guest', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(50),
            'last_name' => $this->string(50),
            'email' => $this->string()->unique(),
            'phone' => $this->string(50)->unique(),
        ],  'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('guest');
    }
}
