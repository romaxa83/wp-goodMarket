<?php

namespace backend\modules\product\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%characteristic}}`.
 */
class m190827_142022_create_characteristic_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%characteristic}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->integer(),
            'name' => $this->string(255),
            'type' => $this->string(255),
            'status' => $this->tinyInteger(),
            'attribute' => $this->string(255)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%characteristic}}');
    }

}
