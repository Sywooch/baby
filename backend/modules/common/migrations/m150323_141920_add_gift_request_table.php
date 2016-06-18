<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150323_141920_add_gift_request_table*/
class m150323_141920_add_gift_request_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%gift_request}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'sex' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Пол"',
                'name' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Имя отправителя"',
                'phone' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Телефон отправителя"',
                'email' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Email отправителя"',
                'receiver' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Кому подарок"',
                'about_receiver' => Schema::TYPE_TEXT. ' DEFAULT NULL COMMENT "Про получателя"',
                'about_gift' => Schema::TYPE_TEXT. ' DEFAULT NULL COMMENT "Какой должен быть ваш подарок"',
                'gift_reason' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Повод для подарка"',
                'gift_budget' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Бюджет"',
                'status' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT "Статус"',
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
