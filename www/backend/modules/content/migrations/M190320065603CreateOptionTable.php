<?php

namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Class M190320065603CreateOptionTable
 */
class M190320065603CreateOptionTable extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%content_options}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'value' => $this->string()->notNull(),
            ],
            $tableOptions
        );
    }

    public function down()
    {
        $this->dropTable('{{%content_options}}');
    }
}
