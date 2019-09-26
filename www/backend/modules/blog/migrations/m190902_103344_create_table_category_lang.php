<?php
namespace backend\modules\blog\migrations;

use yii\db\Migration;

/**
 * Class m190902_103344_create_table_category_lang
 */
class m190902_103344_create_table_category_lang extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        
        $this->createTable('{{%blog_category_lang}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'lang_id' => $this->integer(),
            'title' => $this->string()->notNull()
        ], $tableOptions);

        $this->addForeignKey('{{%fk-blog_category_lang-id}}','{{%blog_category_lang}}', 'category_id', '{{%blog_category}}', 'id',  'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-blog_lang_category_lang-id}}','{{%blog_category_lang}}', 'lang_id', '{{%lang}}', 'id',  'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_category_lang}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190902_103344_create_table_category_lang cannot be reverted.\n";

        return false;
    }
    */
}
