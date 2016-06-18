<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150612_131809_add_address_to_store_order*/
class m150612_131809_add_address_to_store_order extends Migration
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
        $this->addColumn($this->tableName, 'address', Schema::TYPE_STRING. ' NOT NULL DEFAULT "" AFTER email');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'address');
    }
}
