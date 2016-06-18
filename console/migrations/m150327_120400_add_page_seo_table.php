<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150327_120400_add_page_seo_table*/
class m150327_120400_add_page_seo_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%page_seo}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'description' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Для какой страницы SEO"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
