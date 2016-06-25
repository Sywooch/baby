<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m160625_120418_create_feedback_table*/
class m160625_120418_create_feedback_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%feedback}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'name' => Schema::TYPE_STRING. ' NULL DEFAULT NULL',
                'email' => Schema::TYPE_STRING. ' NULL DEFAULT NULL',
                'body' => Schema::TYPE_TEXT. ' NULL DEFAULT NULL',
                'created' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Создано"',
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
