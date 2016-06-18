<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150218_145922_add_show_in_main_flat_to_store_product*/
class m150218_145922_add_show_in_main_flat_to_store_product extends Migration
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
        $this->addColumn($this->tableName, 'show_on_main_page', Schema::TYPE_SMALLINT. '(1) NOT NULL DEFAULT 0 COMMENT "Отображать на главной"');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'show_on_main_page');
    }
}
