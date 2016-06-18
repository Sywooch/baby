<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150210_082925_update_language_table*/
class m150210_082925_update_language_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%language}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'url_code', Schema::TYPE_STRING.'(5) NOT NULL AFTER `code`');
        $this->addColumn($this->tableName, 'is_default', Schema::TYPE_SMALLINT.'(1) NOT NULL DEFAULT 0');

        $this->update($this->tableName, ['is_default' => 1, 'url_code' => 'ru'], 'code = :code', [':code' => 'ru']);
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'url_code');
        $this->dropColumn($this->tableName, 'is_default');
    }
}
