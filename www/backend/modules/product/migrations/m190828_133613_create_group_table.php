<?php

namespace backend\modules\product\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%group}}`.
 */
class m190828_133613_create_group_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'status' => $this->tinyInteger()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%group}}');
    }

}
