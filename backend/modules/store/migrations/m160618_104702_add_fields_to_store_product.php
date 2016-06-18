<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m160618_104702_add_fields_to_store_product*/
class m160618_104702_add_fields_to_store_product extends Migration
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
        $this->addColumn($this->tableName, 'old_price', Schema::TYPE_DECIMAL. '(10, 2) DEFAULT NULL');
        $this->addColumn($this->tableName, 'is_sale', Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT "Распродажа"');
        $this->addColumn($this->tableName, 'is_popular', Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT "Популярное"');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'old_price');
        $this->dropColumn($this->tableName, 'is_sale');
        $this->dropColumn($this->tableName, 'is_popular');
    }
}
