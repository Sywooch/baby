<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m151016_120322_add_favorite_table*/
class m151016_120322_add_favorite_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%favorite}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer() . ' UNSIGNED NOT NULL',
                'product_id' => $this->integer() . ' UNSIGNED NOT NULL',
                'created' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Создано"',
                'modified' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Обновлено"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey('fk_favorite_user_id_to_user_id', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_favorite_product_id_to_product_id', $this->tableName, 'product_id', '{{%store_product}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
