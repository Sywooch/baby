<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150312_170653_add_configuration_tables*/
class m150312_170653_add_configuration_tables extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%configuration}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'config_key' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Ключ"',
                'value' =>  Schema::TYPE_TEXT. ' DEFAULT NULL COMMENT "Значение"',
                'description' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Описание"',
                'type' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Тип"',
                'created' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Создано"',
                'modified' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Обновлено"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->createIndex('unique_config_key', $this->tableName, 'config_key', true);
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
