<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150319_134650_add_alias_col_to_blog_rubric*/
class m150319_134650_add_alias_col_to_blog_rubric extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%blog_rubric}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'alias', Schema::TYPE_STRING. ' NOT NULL DEFAULT ""');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'alias');
    }
}
