<?php

namespace backend\modules\banners\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%banner}}`.
 */
class m190830_083616_create_banner_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%banner}}', [
            'id' => $this->primaryKey(),
            'position' => $this->integer()->defaultValue(0),
            'status' => $this->boolean()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%banner}}');
    }

}
