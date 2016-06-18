<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150711_120948_add_created_modified_to_store_category*/
class m150711_120948_add_created_modified_to_store_category extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_category}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {

        $this->addColumn($this->tableName, 'modified', Schema::TYPE_TIMESTAMP.' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "Создано"');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'modified');
    }
}
