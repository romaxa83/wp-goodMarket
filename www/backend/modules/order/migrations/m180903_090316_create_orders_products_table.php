<?php

namespace backend\modules\order\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `orders_products`.
 */
class m180903_090316_create_orders_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('orders_products', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11),
            'product_id' => $this->string(),
            'vproduct_id' => $this->integer(11),
            'price'=> $this->decimal(24,13)->defaultValue(0),
            'product_price' => $this->decimal(24,13)->defaultValue(0),
            'currency' => $this->string(),
            'count' => $this->integer()
        ]);

        $this->createIndex('ind_orders_products_order_id','orders_products','order_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('ind_orders_products_order_id', 'orders_products');
        $this->dropTable('orders_products');
    }
}
