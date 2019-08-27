<?php

use yii\db\Migration;

/**
 * Class m190211_084949_add_prod_count_column_into_category_characteristic_table
 */
class m190211_084949_add_prod_count_column_into_category_characteristic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('category_characteristic', 'prod_count', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('category_characteristic', 'prod_count');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190211_084949_add_prod_count_column_into_category_characteristic_table cannot be reverted.\n";

        return false;
    }
    */
}
