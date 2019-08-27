<?php

use yii\db\Migration;

/**
 * Handles adding name_and_parent_id to table `{{%category}}`.
 */
class m190806_110305_add_parent_id_and_publish_status_columns_to_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('category', 'parent_id', $this->integer()->after('stock_id'));
        $this->addColumn('category', 'publish_status', $this->smallInteger()->after('publish'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('category', 'parent_id');
        $this->dropColumn('category', 'publish_status');
    }
}
