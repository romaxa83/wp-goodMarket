<?php

namespace backend\modules\blog\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_tag}}`.
 */
class m190830_132956_create_blog_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%blog_tag}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string()->notNull(),
            'status' => $this->integer(1)->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('{{%idx-blog_tag-alias}}', '{{%blog_tag}}', 'alias');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_tags}}');
    }
}
