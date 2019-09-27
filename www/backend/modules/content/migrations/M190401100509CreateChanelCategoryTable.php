<?php

namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Class M190401100509CreateChanelCategoryTable
 */
class M190401100509CreateChanelCategoryTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%channel_category}}',
            [
                'id' => $this->primaryKey()->unsigned(),
                'channel_id' => $this->integer()->notNull(),
                'title' => $this->string()->notNull(),
                'route_id' => $this->integer(),
                'seo_id' => $this->integer(),
                'status' => $this->tinyInteger()->notNull(),
                'created_at' => $this->timestamp()->defaultValue('2010-01-03 04:30:43'),
                'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            ],
            $tableOptions
        );

        $this->createIndex(
            'idx-channel_category-channel_id',
            '{{%channel_category}}',
            'channel_id'
        );

        $this->createIndex(
            'idx-channel_category-route_id',
            '{{%channel_category}}',
            'route_id'
        );

        $this->createIndex(
            'idx-channel_category-seo_id',
            '{{%channel_category}}',
            'seo_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-channel_category-seo_id',
            '{{%channel_category}}'
        );

        $this->dropIndex(
            'idx-channel_category-route_id',
            '{{%channel_category}}'
        );

        $this->dropIndex(
            'idx-channel_category-channel_id',
            '{{%channel_category}}'
        );

        $this->dropTable('{{%channel_category}}');
    }
}
