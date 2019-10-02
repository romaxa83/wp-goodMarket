<?php

namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Class M190506120548CreateChannelCategoryContentTable
 */
class M190506120548CreateChannelCategoryContentTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%channel_category_content}}',
            [
                'id' => $this->primaryKey(),
                'channel_id' => $this->integer()->notNull(),
                'name' => $this->string()->notNull(),
                'label' => $this->string()->notNull(),
                'type' => $this->string()->notNull(),
                'content' => $this->text()
            ],
            $tableOptions
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%channel_category_content}}');
    }
}
