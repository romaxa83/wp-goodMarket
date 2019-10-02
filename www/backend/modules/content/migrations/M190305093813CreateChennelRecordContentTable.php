<?php

namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Class M190305093813CreateChennelRecordContentTable
 */
class M190305093813CreateChennelRecordContentTable extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%channel_record_content}}',
            [
                'id' => $this->primaryKey(),
                'channel_record_id' => $this->integer()->notNull(),
                'name' => $this->string()->notNull(),
                'label' => $this->string()->notNull(),
                'type' => $this->string()->notNull(),
                'content' => $this->text()
            ],
            $tableOptions
        );
    }

    public function down()
    {
        $this->dropTable('{{%channel_record_content}}');
    }
}
