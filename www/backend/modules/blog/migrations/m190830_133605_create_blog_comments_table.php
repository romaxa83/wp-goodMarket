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

        $this->createTable('{{%blog_comment}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'parent_id' => $this->integer(),
            'text' => $this->text()->notNull(),
            'active' => $this->boolean()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-blog_comment-post_id}}', '{{%blog_comment}}', 'post_id');
        $this->createIndex('{{%idx-blog_comment-user_id}}', '{{%blog_comment}}', 'user_id');
        $this->createIndex('{{%idx-blog_comment-parent_id}}', '{{%blog_comment}}', 'parent_id');

        $this->addForeignKey('{{%fk-blog_comment-post_id}}', '{{%blog_comment}}', 'post_id', '{{%blog_post}}', 'id', 'CASCADE');
        $this->addForeignKey('{{%fk-blog_comment-user_id}}', '{{%blog_comment}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('{{%fk-blog_comment-parent_id}}', '{{%blog_comment}}', 'parent_id', '{{%blog_comment}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-blog_comment-post_id}}', '{{%blog_comment}}');
        $this->dropForeignKey('{{%fk-blog_comment-user_id}}', '{{%blog_comment}}');
        $this->dropForeignKey('{{%fk-blog_comment-parent_id}}', '{{%blog_comment}}');

        $this->dropTable('{{%blog_comment}}');
    }
}
