<?php

namespace backend\modules\product\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_lang}}`.
 */
class m190827_120623_create_product_lang_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%product_lang}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'lang_id' => $this->integer(),
            'name' => $this->string(255),
            'description' => $this->text(),
            'price' => $this->decimal(24, 13),
            'currency' => $this->string()
        ]);

        // creates index for column `lang_id`
        $this->createIndex(
                'idx-product_lang-lang_id', 'product_lang', 'lang_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        // drops index for column `lang_id`
        $this->dropIndex(
                'idx-product_lang-lang_id', 'product_lang'
        );
        $this->dropTable('{{%product_lang}}');
    }

}
