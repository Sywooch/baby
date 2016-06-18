<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150105_131853_add_store_product_eav*/
class m150105_131853_add_store_product_eav extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product_eav}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'product_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'attribute_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'value' => Schema::TYPE_STRING. '(500)  NOT NULL',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_eav_product_id_to_store_product_table',
            $this->tableName,
            'product_id',
            '{{%store_product}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_eav_attribute_id_to_store_product_attribute_table',
            $this->tableName,
            'attribute_id',
            '{{%store_product_attribute}}',
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
