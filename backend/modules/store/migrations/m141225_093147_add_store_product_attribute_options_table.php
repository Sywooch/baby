<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m141225_093147_add_store_product_attribute_options_table*/
class m141225_093147_add_store_product_attribute_options_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product_attribute_option}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'attribute_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL COMMENT "Атрибут"',
                'label' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Значение"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_attribute_id_to_store_product_attribute_table',
            $this->tableName,
            'attribute_id',
            '{{%store_product_attribute}}',
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
