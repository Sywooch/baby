<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150616_200118_add_file_id_to_user_table*/
class m150616_200118_add_file_id_to_user_table extends Migration
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
        $this->addColumn($this->tableName, 'avatar_file_id', Schema::TYPE_INTEGER.' DEFAULT NULL');
        $this->addForeignKey(
            'fk_user_avatar_file_id_to_file_table_id',
            $this->tableName,
            'avatar_file_id',
            \metalguardian\fileProcessor\helpers\FPM::getTableName(),
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_avatar_file_id_to_file_table_id', $this->tableName);
        $this->dropColumn($this->tableName, 'file_id');
    }
}
