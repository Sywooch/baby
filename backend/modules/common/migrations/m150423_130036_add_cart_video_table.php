<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150423_130036_add_cart_video_table*/
class m150423_130036_add_cart_video_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%cart_video}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'mp4_video_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'webm_video_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
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
