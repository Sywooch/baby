<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150617_091354_add_in_club_columnt_to_user*/
class m150617_091354_add_in_club_columnt_to_user extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%user}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'is_in_club', Schema::TYPE_SMALLINT.'(1) NOT NULL DEFAULT 0');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'is_in_club');
    }
}
