<?php
namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Class m191002_074100_CreatePageMetaLang
 */
class m191002_074100_CreatePageMetaLang extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%page_meta_lang}}', [
            'id' => $this->primaryKey(),
            'meta_id' => $this->integer(),
            'lang_id' => $this->integer(),
            'title' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'keywords' => $this->string()->notNull()
        ], $tableOptions);
        
        $this->addForeignKey('{{%fk-page_meta_lang-id}}','{{%page_meta_lang}}', 'meta_id', '{{%page_meta}}', 'id',  'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-page_meta_lang_lang-id}}','{{%page_meta_lang}}', 'lang_id', '{{%lang}}', 'id',  'CASCADE', 'RESTRICT');

        $this->dropColumn('page_meta', 'title');
        $this->dropColumn('page_meta', 'description');
        $this->dropColumn('page_meta', 'keywords');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page_meta_lang}}');

        $this->addColumn('page_meta', 'title', $this->string()->notNull());
        $this->addColumn('page_meta', 'description', $this->string()->notNull());
        $this->addColumn('page_meta', 'keywords', $this->string()->notNull());
    }
}
