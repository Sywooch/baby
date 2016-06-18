<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150223_110510_add_store_filters_tables*/
class m150223_110510_add_store_filters_tables extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product_filter}}';
    public $tableName2 = '{{%store_product_filter_to_product}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'category_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL ',
                'label' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Название"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->createTable(
            $this->tableName2,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'product_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL ',
                'filter_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL ',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_store_product_filter_category_id_to_category_table',
            $this->tableName,
            'category_id',
            '{{%store_category}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_store_product_filter_product_id_to_product_table',
            $this->tableName2,
            'product_id',
            '{{%store_product}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_store_product_filter_filter_id_to_store_filter_table',
            $this->tableName2,
            'filter_id',
            '{{%store_product_filter}}',
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
        $this->dropTable($this->tableName2);
        $this->dropTable($this->tableName);
    }
}
