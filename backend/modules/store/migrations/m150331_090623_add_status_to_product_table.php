<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150331_090623_add_status_to_product_table*/
class m150331_090623_add_status_to_product_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'status', Schema::TYPE_SMALLINT.'(1) NOT NULL DEFAULT 1');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'status');
    }
}
