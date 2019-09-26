<?php

namespace backend\modules\import\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `parse_shop`.
 */
class m190910_102258_create_parse_shop_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('parse_shop', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'link' => $this->text(),
            'update_frequency' => $this->string(20),
            'currency' => $this->string(10)->notNull(),
            'currency_value' => $this->float()->notNull(),
            'date_create' => $this->dateTime(),
            'date_update' => $this->dateTime(),
            'date_to_update' => $this->dateTime(),
            'prod_process' => $this->integer()->defaultValue(0),
            'update_process' => $this->integer()->defaultValue(0),
            'edit_process' => $this->integer()->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('parse_shop');
    }

}
