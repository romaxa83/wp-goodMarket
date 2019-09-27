<?php

namespace backend\modules\blog\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_tag_assignments}}`.
 */
class m190830_133042_create_blog_tag_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('{{%blog_tag_assignment}}', [
            'post_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-blog_tag_assignment}}', '{{%blog_tag_assignment}}', ['post_id', 'tag_id']);

        $this->createIndex('{{%idx-blog_tag_assignment-post_id}}', '{{%blog_tag_assignment}}', 'post_id');
        $this->createIndex('{{%idx-blog_tag_assignment-tag_id}}', '{{%blog_tag_assignment}}', 'tag_id');

        $this->addForeignKey('{{%fk-blog_tag_assignment-post_id}}', '{{%blog_tag_assignment}}', 'post_id', '{{%blog_post}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-blog_tag_assignment-tag_id}}', '{{%blog_tag_assignment}}', 'tag_id', '{{%blog_tag}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-blog_tag_assignment-post_id}}', '{{%blog_tag_assignment}}');
        $this->dropForeignKey('{{%fk-blog_tag_assignment-tag_id}}', '{{%blog_tag_assignment}}');

        $this->dropTable('{{%blog_tag_assignment}}');
    }
}
