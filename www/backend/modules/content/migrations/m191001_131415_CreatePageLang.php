<?php
namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Class m191001_131415_CreatePageLang
 */
class m191001_131415_CreatePageLang extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%page_lang}}', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer(),
            'lang_id' => $this->integer(),
            'title' => $this->string()->notNull()
        ], $tableOptions);
        
        $this->addForeignKey('{{%fk-page_lang-id}}','{{%page_lang}}', 'page_id', '{{%page}}', 'id',  'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-page_lang_lang-id}}','{{%page_lang}}', 'lang_id', '{{%lang}}', 'id',  'CASCADE', 'RESTRICT');

        $this->dropColumn('page', 'lang');
        $this->dropColumn('page', 'title');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page_lang}}');

        $this->addColumn('page', 'lang', $this->string()->notNull());
        $this->addColumn('page', 'title', $this->string()->notNull());
    }
}
