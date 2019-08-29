<?php

namespace backend\modules\product\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%manufacturer}}`.
 */
class m190829_113933_create_manufacturer_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%manufacturer}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'status' => $this->tinyInteger(),
            'slug' => $this->string(255)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%manufacturer}}');
    }

}
