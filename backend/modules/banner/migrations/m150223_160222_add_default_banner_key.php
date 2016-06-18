<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m150223_160222_add_default_banner_key*/
class m150223_160222_add_default_banner_key extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%banner}}';

    /**
     * commands will be executed in transaction
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'is_default', Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0');
    }

    /**
     * commands will be executed in transaction
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'is_default');
    }
}
