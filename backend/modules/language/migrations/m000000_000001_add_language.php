<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m000000_000001_add_language
*/
class m000000_000001_add_language extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%language}}';

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
                'code' => Schema::TYPE_STRING. '(5) NOT NULL COMMENT "Код"',
                'locale' => Schema::TYPE_STRING. '(5) NOT NULL',

                'visible' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Отображать"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
                'created' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Создано"',
                'modified' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Обновлено"',

                'UNIQUE key_unique_code (code)',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->insert($this->tableName, [
                'label' => 'рус',
                'code' => 'ru',
                'locale' => 'ru',
            ]);
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
