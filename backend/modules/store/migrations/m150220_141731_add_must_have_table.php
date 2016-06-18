<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150220_141731_add_must_have_table*/
class m150220_141731_add_must_have_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product_must_have}}';

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
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );


        $this->addForeignKey(
            'fk_must_have_product_id_to_store_product_id',
            $this->tableName,
            'product_id',
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
