<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150713_125202_create_store_product_size_table*/
class m150713_125202_create_store_product_size_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product_size}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'product_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL COMMENT "ID изделия"',
                'product_type_size_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL COMMENT "ID стандрартного размера в составе вида изделия"',
                'existence' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Наличие"',
                'price' => Schema::TYPE_DECIMAL. '(10, 2) DEFAULT NULL COMMENT "Цена"',
                'old_price' => Schema::TYPE_DECIMAL. '(10, 2) DEFAULT NULL COMMENT "Старая цена"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_size_to_product',
            $this->tableName,
            'product_id',
            '{{%store_product}}',
            'id',
            'CASCADE'
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
