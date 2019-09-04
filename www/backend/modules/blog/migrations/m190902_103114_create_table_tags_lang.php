<?php
namespace backend\modules\blog\migrations;

use yii\db\Migration;

/**
 * Class m190902_103114_create_lang_table_blog
 */
class m190902_103114_create_table_tags_lang extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%blog_tag_lang}}', [
            'id' => $this->primaryKey(),
            'tag_id' => $this->integer(),
            'lang_id' => $this->integer(),
            'title' => $this->string()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('{{%fk-blog_tag_lang-id}}','{{%blog_tag_lang}}', 'tag_id', '{{%blog_tag}}', 'id',  'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-blog_tag_lang_lang-id}}','{{%blog_tag_lang}}', 'lang_id', '{{%lang}}', 'id',  'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_tag_lang}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190902_103114_create_lang_table_blog cannot be reverted.\n";

        return false;
    }
    */
}
