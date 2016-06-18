<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150610_051327_add_labe_parent_case_to_category*/
class m150610_051327_add_labe_parent_case_to_category extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_category}}';
    public $tableNameLang = '{{%store_category_lang}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'label_parent_case', Schema::TYPE_STRING .' NOT NULL DEFAULT "" AFTER `label`');
        $this->addColumn($this->tableNameLang, 'label_parent_case', Schema::TYPE_STRING .' NOT NULL DEFAULT "" AFTER `label`');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'label_parent_case');
        $this->dropColumn($this->tableNameLang, 'label_parent_case');
    }
}
