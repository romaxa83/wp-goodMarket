<?php

namespace backend\modules\product\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_characteristic}}`.
 */
class m190827_143147_create_product_characteristic_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%product_characteristic}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'group_id' => $this->integer(),
            'characteristic_id' => $this->integer(),
            'value' => $this->text(),
            'product_import_id' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%product_characteristic}}');
    }

}
