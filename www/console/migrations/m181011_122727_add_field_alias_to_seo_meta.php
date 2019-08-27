<?php

use yii\db\Migration;

/**
 * Class m181011_122727_add_field_alias_to_seo_meta
 */
class m181011_122727_add_field_alias_to_seo_meta extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('seo_meta', 'alias', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('seo_meta','alias');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181011_122727_add_field_alias_to_seo_meta cannot be reverted.\n";

        return false;
    }
    */
}
