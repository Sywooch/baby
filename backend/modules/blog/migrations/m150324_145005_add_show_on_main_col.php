<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150324_145005_add_show_on_main_col*/
class m150324_145005_add_show_on_main_col extends Migration
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
        $this->addColumn($this->tableName, 'show_on_main', Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT "Показывать на главной"');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'show_on_main');
    }
}
