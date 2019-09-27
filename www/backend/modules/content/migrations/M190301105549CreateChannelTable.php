<?php

namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Class M190301105549CreateChanelTable
 */
class M190301105549CreateChannelTable extends Migration
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
            '{{%channel}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull(),
                'route_id' => $this->integer()->notNull(),
                'seo_id' => $this->integer()->notNull(),
                'record_structure' => $this->text()->notNull(),
                'status' => $this->tinyInteger()->defaultValue(0),
                'created_at' => $this->date()->notNull(),
                'updated_at' => $this->date()->notNull()
            ],
            $tableOptions
        );

        $this->createIndex(
            'idx-channel-route_id',
            '{{%channel}}',
            'route_id'
        );

        $this->createIndex(
            'idx-channel-seo_id',
            '{{%channel}}',
            'seo_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-channel-route_id',
            '{{%channel}}'
        );

        $this->dropIndex(
            'idx-chanel-seo_id',
            '{{%channel}}'
        );

        $this->dropTable('{{%chanel}}');
    }
}
