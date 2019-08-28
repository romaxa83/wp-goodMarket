<?php

namespace backend\modules\product\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vproduct}}`.
 */
class m190827_144319_create_vproduct_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%vproduct}}', [
            'id' => $this->primaryKey(),
            'stock_id' => $this->integer(),
            'product_id' => $this->integer(),
            'media_id' => $this->integer(),
            'amount' => $this->integer(),
            'price' => $this->decimal(24, 13),
            'publish' => $this->smallInteger(),
            'char_value' => $this->string(255)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%vproduct}}');
    }

}
