<?php
namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Class m191002_143806_createPageTextLang
 */
class m191002_143806_createPageTextLang extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%page_text_lang}}', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer(),
            'lang_id' => $this->integer(),
            'text' => $this->string()->notNull()
        ], $tableOptions);
        
        $this->addForeignKey('{{%fk-page_text_lang-id}}','{{%page_text_lang}}', 'page_id', '{{%page_text}}', 'id',  'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-page_text_lang_lang-id}}','{{%page_text_lang}}', 'lang_id', '{{%lang}}', 'id',  'CASCADE', 'RESTRICT');

        $this->dropColumn('page_text', 'text');
        $this->addColumn('page_text', 'category_id', $this->integer()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page_text_lang}}');

        $this->addColumn('page_text', 'text', $this->string()->notNull());
        $this->dropColumn('page_text', 'description');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191002_143806_createPageTextLang cannot be reverted.\n";

        return false;
    }
    */
}
