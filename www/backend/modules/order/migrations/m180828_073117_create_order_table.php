<?php

namespace backend\modules\order\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m180828_073117_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11),
            'guest_id' => $this->integer(11),
            'status' => $this->integer(),
            'delivary' => $this->integer(11)->notNull(),
            'payment_method' => $this->integer(),
            'city' => $this->string(100),
            'address' => $this->string(100),
            'comment' => $this->text(),
            'phone' => $this->string(),
            'date' => $this->dateTime(),
            'paid' => $this->boolean()->defaultValue(0),
            'sync' => $this->boolean()->defaultValue(0),
        ]);

        $this->createIndex('ind_order_user_id','order','user_id');
        $this->addForeignKey('fk_order_user_id','order','user_id','user','id');

        $this->createIndex('ind_order_guest_id','order','guest_id');
        $this->addForeignKey('fk_order_guest_id','order','guest_id','guest','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('ind_order_user_id', 'order');
        $this->dropIndex('ind_order_guest_id', 'order');
        $this->dropForeignKey('fk_order_guest_id','order');
        $this->dropForeignKey('fk_order_user_id','order');
        $this->dropTable('order');
    }
}
