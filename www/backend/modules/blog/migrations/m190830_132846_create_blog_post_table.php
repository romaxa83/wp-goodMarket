<?php

namespace backend\modules\blog\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_posts}}`.
 */
class m190830_132846_create_blog_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%blog_post}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'country_id' => $this->integer(),
            'author_id' => $this->integer(),
            'title' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'description' => $this->string(),
            'content' => 'MEDIUMTEXT',
            'media_id' => $this->integer(),
            'views' => $this->integer()->defaultValue(0),
            'likes' => $this->integer()->defaultValue(0),
            'links' => $this->integer()->defaultValue(0),
            'comments' => $this->integer()->notNull()->defaultValue(0),
            'position' => $this->integer(1)->notNull()->defaultValue(0),
            'status' => $this->integer(1),
            'published_at' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-blog_post-alias}}', '{{%blog_post}}', 'alias');
        $this->createIndex('{{%idx-blog_post-category_id}}', '{{%blog_post}}', 'category_id');
        $this->createIndex('{{%idx-blog_post-author_id}}', '{{%blog_post}}', 'author_id');

        $this->addForeignKey('{{%fk-blog_post-category_id}}', '{{%blog_post}}', 'category_id', '{{%blog_category}}', 'id');
        $this->addForeignKey('{{%fk-blog_post-author_id}}', '{{%blog_post}}', 'author_id', '{{%user}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_posts}}');
    }
}
