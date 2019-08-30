<?php

namespace backend\modules\blog\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_categories}}`.
 */
class m190830_132627_create_blog_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        
        $this->createTable('{{%blog_categories}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'status' => $this->integer(1)->defaultValue(1),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->insert('{{%blog_categories}}', [
            'id' => 1,
            'title' => 'Все категории',
            'alias' => 'root',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
        ]);


        $this->createIndex('{{%idx-blog_categories-alias}}', '{{%blog_categories}}', 'alias');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_categories}}');
    }
}
