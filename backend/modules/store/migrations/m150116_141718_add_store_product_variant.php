<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150116_141718_add_store_product_variant*/
class m150116_141718_add_store_product_variant extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product_variant}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'product_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'label' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Название"',
                'sku' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Артикул"',
                'price' => Schema::TYPE_DECIMAL. '(10, 2) NOT NULL DEFAULT 0.00 COMMENT "Цена"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',

            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_variants_product_id_to_store_product_table',
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
