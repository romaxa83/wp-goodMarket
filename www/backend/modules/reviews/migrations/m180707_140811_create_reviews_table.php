<?php

namespace backend\modules\reviews\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `reviews`.
 */
class m180707_140811_create_reviews_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('reviews', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(11),
            'user_id' => $this->integer(11),
            'date' => $this->dateTime(),
            'rating' => $this->float(),
            'text' => $this->text(),
            'publication' => $this->integer(),
            'answer_id' => $this->integer(11),
            'answered' => $this->integer(11)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('reviews');
    }
}
