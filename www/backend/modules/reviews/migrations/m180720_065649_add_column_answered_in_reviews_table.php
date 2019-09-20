<?php

namespace backend\modules\reviews\migrations;

use yii\db\Migration;

/**
 * Class m180720_065649_add_column_answered_in_reviews_table
 */
class m180720_065649_add_column_answered_in_reviews_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('reviews', 'answer_id', $this->string());
         $this->addColumn('reviews', 'answered', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->dropColumn('reviews', 'answer_id');
         $this->dropColumn('reviews', 'answered');
    }
}
