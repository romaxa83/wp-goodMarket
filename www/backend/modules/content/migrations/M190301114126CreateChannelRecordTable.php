<?php

namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Class M190301114126CreateChanelRecordTable
 */
class M190301114126CreateChannelRecordTable extends Migration
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
            '{{%channel_record}}',
            [
                'id' => $this->primaryKey(),
                'channel_id' => $this->integer()->notNull(),
                'title' => $this->string()->notNull(),
                'seo_id' => $this->integer()->notNull(),
                'route_id' => $this->integer()->notNull(),
                'status' => $this->tinyInteger()->defaultValue(0),
                'cover_id' => $this->integer(),
                'created_at' => $this->date()->notNull(),
                'updated_at' => $this->date()->notNull()
            ],
            $tableOptions
        );

        $this->createIndex(
            'idx-channel_record-channel_id',
            '{{%channel_record}}',
            'channel_id'
        );

        $this->createIndex(
            'idx-channel_record-seo_id',
            '{{%channel_record}}',
            'seo_id'
        );

        $this->addForeignKey(
            'fk-channel_record-channel_id',
            '{{%channel_record}}',
            'channel_id',
            '{{%channel}}',
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
            'fk-channel_record-channel_id',
            '{{%channel_record}}'
        );

        $this->dropIndex(
            'idx-channel_record-channel_id',
            '{{%channel_record}}'
        );

        $this->dropIndex(
            'idx-channel_record-seo_id',
            '{{%channel_record}}'
        );

        $this->dropTable('{{%channel_record}}');
    }
}
