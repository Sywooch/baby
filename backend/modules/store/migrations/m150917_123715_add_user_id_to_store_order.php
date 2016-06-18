<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m150917_123715_add_user_id_to_store_order*/
class m150917_123715_add_user_id_to_store_order extends Migration
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
        $this->addColumn($this->tableName, 'user_id', Schema::TYPE_INTEGER . ' UNSIGNED AFTER name');

        $this->addForeignKey(
            'fk_store_order_user_id_to_user_id',
            $this->tableName,
            'user_id',
            '{{%user}}',
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
        $this->dropColumn($this->tableName, 'user_id');
    }
}
