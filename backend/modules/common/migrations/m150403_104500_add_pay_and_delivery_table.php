<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150403_104500_add_pay_and_delivery_table*/
class m150403_104500_add_pay_and_delivery_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%pay_and_delivery}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'type_id' => Schema::TYPE_SMALLINT. '(1) NOT NULL DEFAULT 1',
                'name' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Тип доставки и оплаты"',
                'price' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Стоимость"',
                'for_kiev' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Для Киева"',
                'for_regions' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Для регионов"',
                'visible' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 1 COMMENT "Отображать"',
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
