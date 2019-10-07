<?php

use yii\db\Migration;

/**
 * Class m191004_091225_add_default_lang
 */
class m191004_091025_add_currency_column_to_lang_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('lang', 'currency', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('lang', 'currency');
    }

}
