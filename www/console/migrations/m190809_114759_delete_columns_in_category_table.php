<?php

use yii\db\Migration;

/**
 * Class m190809_114759_delete_columns_in_category_table
 */
class m190809_114759_delete_columns_in_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('category', 'seo_id');
        $this->dropColumn('category', 'language');
        $this->dropColumn('category', 'language_parent_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
