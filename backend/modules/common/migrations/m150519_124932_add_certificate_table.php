<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150519_124932_add_certificate_table*/
class m150519_124932_add_certificate_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%certificate}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'label' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Название"',
                'price' => Schema::TYPE_DECIMAL. '(10,2) NOT NULL',
                'color' => Schema::TYPE_SMALLINT. '(1) NOT NULL DEFAULT 1 COMMENT "Цвет"',
                'visible' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Отображать"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
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
