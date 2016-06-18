<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150111_130907_add_export_status_table*/
class m150111_130907_add_export_status_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%export_status}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'user_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'is_exported' => Schema::TYPE_SMALLINT. '(1) NOT NULL',
                'status' => Schema::TYPE_STRING. ' NOT NULL',
                'result_file_name' => Schema::TYPE_STRING. ' NOT NULL',
                'export_columns' => Schema::TYPE_TEXT. ' NOT NULL',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
