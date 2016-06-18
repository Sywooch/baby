<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150315_122958_add_currencies_tables*/
class m150315_122958_add_currencies_tables extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%currency}}';

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
                'code' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Код"',
                'rate_to_default' => Schema::TYPE_DECIMAL. '(6,2) NOT NULL COMMENT "Курс к главной валюте"',
                'is_default' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT "Главная валюта"',
                'visible' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Отображать"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
                'created' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Создано"',
                'modified' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Обновлено"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->insert($this->tableName, [
                'label' => 'Доллар',
                'code' => 'USD',
                'rate_to_default' => 1,
                'is_default' => 1,
                'position' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ]);

        $this->insert($this->tableName, [
                'label' => 'Гривна',
                'code' => 'UAH',
                'rate_to_default' => 22.55,
                'is_default' => 0,
                'position' => 2,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
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
