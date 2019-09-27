<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%slug_manager}}`.
 */
class m190212_062909_create_slug_manager_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%slug_manager}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string()->notNull(),
            'route' => $this->string()->notNull(),
            'template' => $this->string(),
            'parent_id' => $this->integer()->defaultValue(0),
            'post_id' => $this->integer()->defaultValue(0)
        ], $tableOptions);

        $this->createIndex(
            'idx-slug_manager-slug',
            '{{%slug_manager}}',
            'slug'
        );

        $this->createIndex(
            'idx-slug_manager-route',
            '{{%slug_manager}}',
            'route'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-slug_manager-slug',
            '{{%slug_manager}}'
        );

        $this->dropIndex(
            'idx-slug_manager-route',
            '{{%slug_manager}}'
        );
        $this->dropTable('{{%slug_manager}}');
    }
}
