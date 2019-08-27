<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m180613_140115_create_category_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'stock_id' => $this->integer(),
            'seo_id' => $this->integer(),
            'media_id' => $this->integer(),
            'alias' => $this->string(255)->unique(),
            'position' => $this->integer(),
            'rating' => $this->integer(),
            'publish' => $this->smallInteger(),
            'language' => $this->string(5),
            'language_parent_id' => $this->integer()
        ],  'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('category');
    }

}
