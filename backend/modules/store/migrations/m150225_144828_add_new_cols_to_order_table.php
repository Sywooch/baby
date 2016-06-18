<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150225_144828_add_new_cols_to_order_table*/
class m150225_144828_add_new_cols_to_order_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_order}}';
    public $tableName2 = '{{%store_order_product}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'sum', Schema::TYPE_DECIMAL.'(10, 2) NOT NULL');
        $this->addColumn($this->tableName2, 'qnt', Schema::TYPE_INTEGER.' NOT NULL');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'sum');
        $this->dropColumn($this->tableName2, 'qnt');
    }
}
