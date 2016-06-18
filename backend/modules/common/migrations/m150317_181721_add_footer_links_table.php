<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150317_181721_add_footer_links_table*/
class m150317_181721_add_footer_links_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%footer_link}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'label' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Название"',
                'url' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Ссылка"',
                'column' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Столбец"',
                'visible' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Отображать"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
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
