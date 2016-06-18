<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150223_211840_add_video_cols_for_product*/
class m150223_211840_add_video_cols_for_product extends Migration
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
        $this->addColumn($this->tableName, 'video_id', Schema::TYPE_STRING. ' DEFAULT NULL');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'video_id');
    }
}
