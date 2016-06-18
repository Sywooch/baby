<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150322_123457_add_sales_tables*/
class m150322_123457_add_sales_tables extends Migration
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
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'image_id' => Schema::TYPE_INTEGER. ' UNSIGNED DEFAULT NULL',
                'label' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Заголовок"',
                'href' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Ссылка"',
                'content' => Schema::TYPE_TEXT. ' DEFAULT NULL COMMENT "Текст акции"',
                'type' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Тип шаблона"',
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
