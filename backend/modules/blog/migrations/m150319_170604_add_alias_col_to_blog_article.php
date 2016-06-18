<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150319_170604_add_alias_col_to_blog_article*/
class m150319_170604_add_alias_col_to_blog_article extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%blog_article}}';

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
