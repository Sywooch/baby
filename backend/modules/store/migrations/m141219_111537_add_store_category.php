<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m141219_111537_add_store_category*/
class m141219_111537_add_store_category extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_category}}';

    /**
     * commands will be executed in transaction
     */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'lft' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'rgt' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'level' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'label' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Название"',
                'alias' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Алиас"',
                'description' => Schema::TYPE_TEXT. ' DEFAULT NULL COMMENT "Описание"',
                'visible' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Отображать"',
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
