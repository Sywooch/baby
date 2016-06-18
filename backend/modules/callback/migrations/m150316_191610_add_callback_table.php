<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150316_191610_add_callback_table*/
class m150316_191610_add_callback_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%callback}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'name' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Имя"',
                'phone' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Телефон"',
                'status' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT "Статус"',
                'created' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Создано"',
                'modified' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Обновлено"',
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
