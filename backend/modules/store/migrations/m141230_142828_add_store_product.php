<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m141230_142828_add_store_product*/
class m141230_142828_add_store_product extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'type_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'category_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'label' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Название"',
                'alias' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Ссылка"',
                'announce' => Schema::TYPE_TEXT. ' DEFAULT NULL COMMENT "Краткое описание"',
                'content' => Schema::TYPE_TEXT. ' DEFAULT NULL COMMENT "Полное описание"',
                'sku' => Schema::TYPE_STRING. ' NOT NULL',
                'price' => Schema::TYPE_DECIMAL. '(10, 2) NOT NULL DEFAULT 0.00',
                'visible' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Отображать"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
                'created' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Создано"',
                'modified' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Обновлено"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->createIndex('store_product_unique_alias', $this->tableName, 'alias', true);

        $this->addForeignKey(
            'fk_store_product_type_id_to_store_product_type',
            $this->tableName,
            'type_id',
            '{{%store_product_type}}',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk_store_product_category_id_to_store_category',
            $this->tableName,
            'category_id',
            '{{%store_category}}',
            'id',
            'RESTRICT'
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
