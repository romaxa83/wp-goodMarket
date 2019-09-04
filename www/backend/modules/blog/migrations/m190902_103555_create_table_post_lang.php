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
            'title' => $this->string()->notNull(),
            'description' => $this->string(),
            'comments' => $this->integer()->notNull()->defaultValue(0),
        ], $tableOptions);
        
        $this->addForeignKey('{{%fk-blog_post_lang-id}}','{{%blog_category_lang}}', 'category_id', '{{%blog_category}}', 'id',  'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-blog_lang_post_lang-id}}','{{%blog_category_lang}}', 'lang_id', '{{%lang}}', 'id',  'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // echo "m190902_103555_create_table_post_lang cannot be reverted.\n";

        // return false;
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
