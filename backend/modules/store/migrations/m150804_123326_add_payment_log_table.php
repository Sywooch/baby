<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150804_123326_add_payment_log_table*/
class m150804_123326_add_payment_log_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%payment_log}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'text' => Schema::TYPE_TEXT. ' DEFAULT NULL COMMENT "Текст"',
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
