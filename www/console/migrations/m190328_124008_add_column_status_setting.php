<?php

use yii\db\Migration;

/**
 * Class m190328_124008_add_column_status_setting
 */
class m190328_124008_add_column_status_setting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('settings', 'status', $this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('settings', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190328_124008_add_column_status_setting cannot be reverted.\n";

        return false;
    }
    */
}
