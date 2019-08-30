<?php

namespace backend\modules\blog\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_tags}}`.
 */
class m190830_132956_create_blog_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%blog_tags}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'status' => $this->integer(1)->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('{{%idx-blog_tags-alias}}', '{{%blog_tags}}', 'alias');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_tags}}');
    }
}
