<?php

namespace backend\modules\product\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m190827_104512_create_product_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'stock_id' => $this->integer(),
            'category_id' => $this->integer(),
            'media_id' => $this->integer(),
            'manufacturer_id' => $this->integer(),
            'group_id' => $this->integer(),
            'type' => $this->string(255),
            'vendor_code' => $this->string(255),
            'amount' => $this->integer(),
            'trade_price' => $this->decimal(24, 13),
            'rating' => $this->integer(),
            'gallery' => $this->text(),
            'is_variant' => $this->tinyInteger(),
            'publish' => $this->tinyInteger()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%product}}');
    }

}
