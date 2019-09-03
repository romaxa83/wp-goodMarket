<?php
namespace backend\modules\blog\migrations;

use yii\db\Migration;

/**
 * Class m190902_103555_create_table_post_lang
 */
class m190902_103555_create_table_post_lang extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%blog_post_lang}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer(),
            'lang_id' => $this->integer(),
            'title' => $this->string()->notNull(),
            'description' => $this->string(),
            'content' => $this->string()->notNull(),
        ], $tableOptions);
        
        $this->addForeignKey('{{%fk-blog_post_lang-id}}','{{%blog_post_lang}}', 'post_id', '{{%blog_post}}', 'id',  'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-blog_lang_post_lang-id}}','{{%blog_post_lang}}', 'lang_id', '{{%lang}}', 'id',  'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_post_lang}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190902_103555_create_table_post_lang cannot be reverted.\n";

        return false;
    }
    */
}
