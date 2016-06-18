<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m141230_125425_add_store_product_type_to_attribute*/
class m141230_125425_add_store_product_type_to_attribute extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product_type_to_attribute}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'type_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'attribute_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addPrimaryKey('pk_type_id_attribute_id', $this->tableName, ['type_id', 'attribute_id']);

        $this->addForeignKey(
            'fk_type_id_to_store_product_type',
            $this->tableName,
            'type_id',
            '{{%store_product_type}}',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_attribute_id_to_store_product_attribute',
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
