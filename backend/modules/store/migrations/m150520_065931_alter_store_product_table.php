<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150520_065931_alter_store_product_table*/
class m150520_065931_alter_store_product_table extends Migration
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
        $this->alterColumn($this->tableName, 'product_id', Schema::TYPE_INTEGER. ' UNSIGNED DEFAULT NULL');
        $this->addColumn($this->tableName, 'cert_id', Schema::TYPE_INTEGER. ' UNSIGNED DEFAULT NULL');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'cert_id');
    }
}
