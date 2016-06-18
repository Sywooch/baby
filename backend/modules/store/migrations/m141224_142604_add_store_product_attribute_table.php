<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m141224_142604_add_store_product_attribute_table*/
class m141224_142604_add_store_product_attribute_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product_attribute}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'label' => Schema::TYPE_STRING. ' UNIQUE NOT NULL COMMENT "Название"',
                'type' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Тип"',
                'show_in_filter' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Показывать в блоке фильтров"',
                'is_required' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Обязателен ли к заполнению"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
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
