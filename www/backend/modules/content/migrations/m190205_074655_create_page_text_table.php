<?php

namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_text}}`.
 */
class m190205_074655_create_page_text_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%page_text}}', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'label' => $this->string()->notNull(),
            'type'  => $this->string()->notNull(),
            'text' => $this->text()
        ], $tableOptions);

        $this->createIndex(
            'idx-page_text-page_id',
            '{{%page_text}}',
            'page_id'
        );

        $this->addForeignKey(
            'fk-page_text-page_id',
            '{{%page_text}}',
            'page_id',
            'page',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'idx-page_text-page_id',
            '{{%page_text}}'
        );

        $this->dropIndex(
            'fk-page_text-page_id',
            '{{%page_text}}'
        );
        $this->dropTable('{{%page_text}}');
    }
}
