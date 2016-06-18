<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150223_165841_add_top50_and_new_flags_to_product*/
class m150223_165841_add_top50_and_new_flags_to_product extends Migration
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
        $this->addColumn($this->tableName, 'is_new', Schema::TYPE_SMALLINT.'(1) NOT NULL DEFAULT 0');
        $this->addColumn($this->tableName, 'is_top_50', Schema::TYPE_SMALLINT.'(1) NOT NULL DEFAULT 0');
        $this->addColumn($this->tableName, 'is_top_50_category', Schema::TYPE_SMALLINT.'(1) NOT NULL DEFAULT 0');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'is_new');
        $this->dropColumn($this->tableName, 'is_top_50');
        $this->dropColumn($this->tableName, 'is_top_50_category');
    }
}
