<?php

use yii\db\Migration;

/**
 * Class m190829_130247_add_settings_to_user_table
 */
class m190829_130247_add_settings_to_user_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('user', 'settings', $this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->addColumn('user', 'settings', $this->boolean()->defaultValue(true));
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190829_130247_add_settings_to_user_table cannot be reverted.\n";

      return false;
      }
     */
}
