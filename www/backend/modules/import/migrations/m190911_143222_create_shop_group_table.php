<?php

namespace backend\modules\import\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_group}}`.
 */
class m190911_143222_create_shop_group_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('shop_group', [
            'id' => $this->primaryKey(),
            'shop_id' => $this->integer(11),
            'group_id' => $this->integer(11)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('shop_group');
    }

}
