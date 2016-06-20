<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m160620_152557_add_column_city_to_store_order_table*/
class m160620_152557_add_column_city_to_store_order_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_order}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'city', Schema::TYPE_STRING. ' DEFAULT NULL COMMENT "Город"');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'city');
    }
}
