<?php

use yii\db\Migration;

/**
 * Class m190328_124008_add_column_status_setting
 */
class m190328_124008_add_column_status_setting extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('user', 'settings', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('user', 'settings');
    }

}
