<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150125_124619_add_table_store_product_sku_list*/
class m150125_124619_add_table_store_product_sku_list extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product_sku_list}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->alterColumn('{{%store_product}}', 'sku', Schema::TYPE_STRING. ' NOT NULL UNIQUE');

        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'product_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'sku_prefix' => Schema::TYPE_STRING. ' NOT NULL',
                'sku' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->createIndex('ik_product_sku', $this->tableName, ['sku_prefix', 'sku'], true);
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
