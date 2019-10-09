<?php
namespace backend\modules\product\migrations;

use yii\db\Migration;

/**
 * Class m191008_090626_add_field_new
 */
class m191008_090626_add_field_new extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product', 'new', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'new');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191008_090626_add_field_new cannot be reverted.\n";

        return false;
    }
    */
}
