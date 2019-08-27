<?php

use yii\db\Migration;

/**
 * Handles the creation of table `permission_actions`.
 */
class m190306_150813_create_permission_actions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('permission_actions', [
            'id' => $this->primaryKey(),
            'perm_name' => $this->string(64)->notNull(),
            'action' => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('permission_actions');
    }
}
