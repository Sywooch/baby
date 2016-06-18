<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150610_061618_add_additional_fields_to_user_table*/
class m150610_061618_add_additional_fields_to_user_table extends Migration
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
        $this->addColumn($this->tableName, 'name', Schema::TYPE_STRING. ' NOT NULL DEFAULT "" AFTER username');
        $this->addColumn($this->tableName, 'surname', Schema::TYPE_STRING. ' NOT NULL DEFAULT "" AFTER name');
        $this->addColumn($this->tableName, 'phone', Schema::TYPE_STRING. ' NOT NULL DEFAULT "" AFTER surname');
        $this->addColumn($this->tableName, 'city', Schema::TYPE_STRING. ' NOT NULL DEFAULT "" AFTER phone');
        $this->addColumn($this->tableName, 'address', Schema::TYPE_STRING. ' NOT NULL DEFAULT "" AFTER city');
        $this->addColumn($this->tableName, 'secondary_address', Schema::TYPE_STRING. ' NOT NULL DEFAULT "" AFTER address');
        $this->addColumn($this->tableName, 'discount_card', Schema::TYPE_STRING. ' NOT NULL DEFAULT "" AFTER secondary_address');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'name');
        $this->dropColumn($this->tableName, 'surname');
        $this->dropColumn($this->tableName, 'phone');
        $this->dropColumn($this->tableName, 'city');
        $this->dropColumn($this->tableName, 'address');
        $this->dropColumn($this->tableName, 'secondary_address');
        $this->dropColumn($this->tableName, 'discount_card');
    }
}
