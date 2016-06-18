<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150317_132335_add_news_subscribe_table*/
class m150317_132335_add_news_subscribe_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%news_subscribe}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'email' => Schema::TYPE_STRING. ' NOT NULL',
                'created' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Создано"',
                'modified' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Обновлено"',
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
