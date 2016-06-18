<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150516_190059_add_new_positions_to_product_table*/
class m150516_190059_add_new_positions_to_product_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'new_position', Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция новинок"');
        $this->addColumn($this->tableName, 'top_position', Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция топ"');
        $this->addColumn($this->tableName, 'top_category_position', Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция топ категорий"');

        $this->createIndex('new_pos_index', $this->tableName, 'new_position');
        $this->createIndex('top_pos_index', $this->tableName, 'top_position');
        $this->createIndex('top_category_pos_index', $this->tableName, 'top_category_position');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'new_position');
        $this->dropColumn($this->tableName, 'top_position');
        $this->dropColumn($this->tableName, 'top_category_position');
    }
}
