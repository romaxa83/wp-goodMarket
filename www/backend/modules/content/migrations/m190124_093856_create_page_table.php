<?php

namespace backend\modules\content\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `page`.
 */
class m190124_093856_create_page_table extends Migration
{
    /**
     * Create table page
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('page', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'slug_id' => $this->integer()->notNull(),
            'lang' => $this->string(),
            'status' => $this->tinyInteger()->defaultValue(0),
            'creation_date' => $this->date()->notNull(),
            'modification_date' => $this->date()->notNull()
        ], $tableOptions);
    }

    /**
     * Delete table page
     */
    public function down()
    {
        $this->dropTable('page');
    }
}
