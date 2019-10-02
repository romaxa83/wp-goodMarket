<?php

namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Class M190402084042ChannelRecordsCommonField
 */
class M190402084042ChannelRecordsCommonField extends Migration
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
            '{{%channel_records_common_field}}',
            [
                'id' => $this->primaryKey(),
                'channel_id' => $this->integer()->notNull(),
                'name' => $this->string()->notNull(),
                'label' => $this->string()->notNull(),
                'type' => $this->string()->notNull(),
                'content' => $this->text()
            ],
            $tableOptions
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropTable('{{%channel_records_common_field}}');
    }
}
