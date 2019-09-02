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
        
        $this->createTable('{{%blog_category}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string()->notNull(),
            'status' => $this->integer(1)->defaultValue(1),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-blog_category-alias}}', '{{%blog_category}}', 'alias');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_categories}}');
    }
}
