<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150804_123440_add_is_paid_col_to_order_table*/
class m150804_123440_add_is_paid_col_to_order_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_order}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'payment_status', Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT "Статус оплаты"');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'payment_status');
    }
}
