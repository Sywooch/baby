<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150226_162123_add_sumilar_products_table*/
class m150226_162123_add_sumilar_products_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_similar_product}}';

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
                'similar_product_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_store_similar_product_product_id_to_store_product_id',
            $this->tableName,
            'product_id',
            '{{%store_product}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_store_similar_product_similar_product_id_to_store_product_id',
            $this->tableName,
            'similar_product_id',
            '{{%store_product}}',
            'id',
            'CASCADE',
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
