<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category_characteristic`.
 */
class m190124_083937_create_category_characteristic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category_characteristic', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(11),
            'characteristic_id' => $this->integer(11)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category_characteristic');
    }
}
