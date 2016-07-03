<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m160703_172327_add_column_size_id_to_order_product_table*/
class m160703_172327_add_column_size_id_to_order_product_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_order_product}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'size_id', Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL');
        $this->addForeignKey(
            'fk_order_product_size_id_to_main_table_id',
            $this->tableName,
            'size_id',
            '{{%store_product_size}}',
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
        $this->dropColumn($this->tableName, 'size_id');
    }
}
