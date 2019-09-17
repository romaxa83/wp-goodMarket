<?php

use yii\db\Migration;

/**
 * Class m190917_071533_add_personal_data_to_user_table
 */
class m190917_071533_add_personal_data_to_user_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('user', 'first_name', $this->string()->after('username'));
        $this->addColumn('user', 'last_name', $this->string()->after('first_name'));
        $this->addColumn('user', 'phone', $this->string(50)->unique()->after('last_name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('user', 'first_name');
        $this->dropColumn('user', 'last_name');
        $this->dropColumn('user', 'phone');
    }

}
