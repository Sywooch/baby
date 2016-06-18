<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m150804_123305_add_payment_table*/
class m150804_123305_add_payment_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%payment}}';

    /**
     * commands will be executed in transaction
     */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'user_id' => Schema::TYPE_INTEGER . ' UNSIGNED DEFAULT NULL',
                'order_id' => Schema::TYPE_INTEGER . ' UNSIGNED DEFAULT NULL',
                'sum' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL COMMENT "Цена"',
                'sum_uah' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL COMMENT "Цена в гривне"',
                'comment' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT "Комментарий"',
                'status' => Schema::TYPE_SMALLINT . '(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT "Статус"',
                'created' => Schema::TYPE_DATETIME . ' NOT NULL COMMENT "Создано"',
                'modified' => Schema::TYPE_DATETIME . ' NOT NULL COMMENT "Обновлено"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_payment_user_id_to_user_table_id',
            $this->tableName,
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_payment_order_id_to_order_table_id',
            $this->tableName,
            'order_id',
            '{{%store_order}}',
            'id',
            'SET NULL',
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
