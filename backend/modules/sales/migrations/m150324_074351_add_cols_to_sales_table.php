<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150324_074351_add_cols_to_sales_table*/
class m150324_074351_add_cols_to_sales_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%sales}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->dropColumn($this->tableName, 'href');
        $this->dropColumn('{{%sales_lang}}', 'href');
        $this->renameColumn($this->tableName, 'content', 'description');
        $this->addColumn('{{%sales_lang}}', 'description', Schema::TYPE_TEXT. ' NULL DEFAULT NULL');
        $this->addColumn($this->tableName, 'alias', Schema::TYPE_STRING. ' NOT NULL COMMENT "Ссылка" AFTER `label`');
        $this->addColumn($this->tableName, 'content', Schema::TYPE_TEXT. ' DEFAULT NULL COMMENT "Содержимое" AFTER `description`');
        $this->addColumn($this->tableName, 'image_bottom_id', Schema::TYPE_INTEGER. ' UNSIGNED DEFAULT NULL AFTER `image_id`');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->addColumn($this->tableName, 'href', Schema::TYPE_STRING. ' NOT NULL COMMENT "Ссылка"');
        $this->addColumn('{{%sales_lang}}', 'href', Schema::TYPE_STRING. ' NOT NULL');
        $this->dropColumn($this->tableName, 'alias');
        $this->dropColumn($this->tableName, 'content');
        $this->dropColumn($this->tableName, 'image_bottom_id');
        $this->dropColumn('{{%sales_lang}}', 'description');
        $this->renameColumn($this->tableName, 'description', 'content');
    }
}
