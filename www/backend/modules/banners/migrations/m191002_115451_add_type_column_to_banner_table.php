<?php

namespace backend\modules\banners\migrations;

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%banner}}`.
 */
class m191002_115451_add_type_column_to_banner_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('banner', 'type', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('banner','type');
    }
}
