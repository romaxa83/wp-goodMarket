<?php

namespace backend\modules\blog\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_comments}}`.
 */
class m190830_133605_create_blog_comments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%blog_comments}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'parent_id' => $this->integer(),
            'text' => $this->text()->notNull(),
            'active' => $this->boolean()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-blog_comments-post_id}}', '{{%blog_comments}}', 'post_id');
        $this->createIndex('{{%idx-blog_comments-user_id}}', '{{%blog_comments}}', 'user_id');
        $this->createIndex('{{%idx-blog_comments-parent_id}}', '{{%blog_comments}}', 'parent_id');

        $this->addForeignKey('{{%fk-blog_comments-post_id}}', '{{%blog_comments}}', 'post_id', '{{%blog_posts}}', 'id', 'CASCADE');
        $this->addForeignKey('{{%fk-blog_comments-user_id}}', '{{%blog_comments}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('{{%fk-blog_comments-parent_id}}', '{{%blog_comments}}', 'parent_id', '{{%blog_comments}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-blog_comments-post_id}}', '{{%blog_comments}}');
        $this->dropForeignKey('{{%fk-blog_comments-user_id}}', '{{%blog_comments}}');
        $this->dropForeignKey('{{%fk-blog_comments-parent_id}}', '{{%blog_comments}}');

        $this->dropTable('{{%blog_comments}}');
    }
}
