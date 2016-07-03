<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150713_085214_create_store_product_type_size_table*/
class m150713_085214_create_store_product_type_size_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product_type_size}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'product_type_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL COMMENT "Вид изделия"',
                'label' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Заголовок"',
                'height' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Рост"',
                'alias' => Schema::TYPE_STRING. ' DEFAULT NULL COMMENT "Алиас"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_size_to_product_type',
            $this->tableName,
            'product_type_id',
            '{{%store_product_type}}',
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
