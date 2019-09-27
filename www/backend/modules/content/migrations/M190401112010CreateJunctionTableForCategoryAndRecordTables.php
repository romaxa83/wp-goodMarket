<?php

namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Class M190401112010CreateJunctionTableForCategoryAndRecordTables
 */
class M190401112010CreateJunctionTableForCategoryAndRecordTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%channel_category_record}}',
            [
                'id' => $this->primaryKey()->unsigned(),
                'category_id' => $this->integer()->unsigned()->notNull(),
                'record_id' => $this->integer()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex(
            'idx-channel_category_record-category_id',
            '{{%channel_category_record}}',
            'category_id'
        );

        $this->createIndex(
            'idx-channel_category_record-record_id',
            '{{%channel_category_record}}',
            'record_id'
        );

        $this->addForeignKey(
            'fk-channel_category_record-category_id',
            '{{%channel_category_record}}',
            'category_id',
            '{{%channel_category}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-channel_category_record-record_id',
            '{{%channel_category_record}}',
            'record_id',
            '{{%channel_record}}',
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
            'fk-channel_category_record-record_id',
            '{{%channel_category_record}}'
        );

        $this->dropForeignKey(
            'fk-channel_category_record-category_id',
            '{{%channel_category_record}}'
        );

        $this->dropIndex(
            'idx-channel_category_record-record_id',
            '{{%channel_category_record}}'
        );

        $this->dropIndex(
            'idx-channel_category_record-category_id',
            '{{%channel_category_record}}'
        );

        $this->dropTable('{{%channel_category_record}}');
    }
}
