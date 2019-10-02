<?php

namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Class M190301094523CreateChanelTable
 */
class M190301094523CreateSeoDataTable extends Migration
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
            '{{%seo_data}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull(),
                'description' => $this->text()->notNull(),
                'keywords' => $this->string()
            ],
            $tableOptions
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%seo_data}}');
    }
}
