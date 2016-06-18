<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150401_092041_add_store_products_subscribe_table*/
class m150401_092041_add_store_products_subscribe_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product_subscribe}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'product_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'user_id' => Schema::TYPE_INTEGER. ' UNSIGNED DEFAULT NULL',
                'user_name' => Schema::TYPE_STRING. ' NOT NULL',
                'email' => Schema::TYPE_STRING. ' NOT NULL',
                'phone' => Schema::TYPE_STRING. ' NOT NULL',
                'status' => Schema::TYPE_SMALLINT. '(1) NOT NULL DEFAULT 0',
                'created' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Создано"',
                'modified' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Обновлено"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_store_pro_subscr_product_id_to_product_table',
            $this->tableName,
            'product_id',
            '{{%store_product}}',
            'id',
            'CASCADE',
            'CASCADE'
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
