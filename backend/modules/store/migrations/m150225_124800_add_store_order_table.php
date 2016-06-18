<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150225_124800_add_store_order_table*/
class m150225_124800_add_store_order_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_order}}';
    public $tableName2 = '{{%store_order_product}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'name' => Schema::TYPE_STRING. ' NOT NULL',
                'phone' => Schema::TYPE_STRING. ' NOT NULL',
                'email' => Schema::TYPE_STRING. ' NULL DEFAULT NULL',
                'street' => Schema::TYPE_STRING. ' NULL DEFAULT NULL',
                'house' => Schema::TYPE_STRING. ' NULL DEFAULT NULL',
                'apartment' => Schema::TYPE_STRING. ' NULL DEFAULT NULL',
                'nova_poshta_storage' => Schema::TYPE_STRING. ' NULL DEFAULT NULL',
                'discount_card' => Schema::TYPE_STRING. ' NULL DEFAULT NULL',
                'promo_code' => Schema::TYPE_STRING. ' NULL DEFAULT NULL',
                'comment' => Schema::TYPE_TEXT. ' NULL DEFAULT NULL',
                'payment_type' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Тип оплаты"',
                'delivery_type' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Тип доставки"',
                'delivery_time' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT "Время доставки"',
                'status' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT "Cтатус"',
                'created' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Создано"',
                'modified' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Обновлено"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->createTable(
            $this->tableName2,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'order_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'product_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'sku' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Артикул товара"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );


        $this->addForeignKey(
            'fk_store_order_product_order_id_to_store_order_id',
            $this->tableName2,
            'order_id',
            $this->tableName,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_store_order_product_product_id_to_store_product_id',
            $this->tableName2,
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
        $this->dropTable($this->tableName2);
        $this->dropTable($this->tableName);
    }
}
