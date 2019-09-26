<?php

namespace backend\modules\order\migrations;

use yii\db\Migration;

/**
 * Class m181207_154511_create_table_history_status_order
 */
class m181207_154511_create_table_history_status_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('history_status_order', [
            'status' => $this->integer(2),
            'order_id' => $this->integer(11),
            'date' => $this->dateTime()
        ]);

        $this->createIndex('ind_history_status_order_order_id','history_status_order','order_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('ind_history_status_order_order_id', 'history_status_order');
        $this->dropTable('history_status_order');
    }

}
