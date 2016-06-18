<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150202_145600_add_position_to_entity_to_file*/
class m150202_145600_add_position_to_entity_to_file extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%entity_to_file}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'position', Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'position');
    }
}
