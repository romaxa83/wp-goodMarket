<?php

namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Class M190304113633CreateChennelContentTable
 */
class M190304113633CreateChennelContentTable extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%channel_content}}',
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

    public function down()
    {
        $this->dropTable('{{%chanel_content}}');
    }
}
