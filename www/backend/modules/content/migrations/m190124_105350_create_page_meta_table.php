<?php

namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `page_meta`.
 */
class m190124_105350_create_page_meta_table extends Migration
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

        $this->createTable('page_meta', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->notNull(),
            'title' =>$this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'keywords' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-page_meta-page_id',
            'page_meta',
            'page_id'
        );

        $this->addForeignKey(
            'fk-page_meta-page_id',
            'page_meta',
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
            'idx-page_meta-page_id',
            'page_meta'
        );

        $this->dropIndex(
            'fk-page_meta-page_id',
            'page_meta'
        );
        $this->dropTable('page_meta');
    }
}
