<?php

use yii\db\Migration;

/**
 * Handles the creation of table `seo_meta`.
 */
class m180613_064410_create_seo_meta_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('seo_meta', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer(11),
            'h1' => $this->string(255),
            'title' => $this->string(100),
            'keywords' => $this->text(),
            'description' => $this->text(),
            'seo_text' => $this->text(),
            'language' => $this->string(5),
            'parent_id' => $this->integer(11),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('seo_meta');
    }
}
