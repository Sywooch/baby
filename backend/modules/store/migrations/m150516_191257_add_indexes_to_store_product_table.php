<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150516_191257_add_indexes_to_store_product_table*/
class m150516_191257_add_indexes_to_store_product_table extends Migration
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
        $this->createIndex('type_index', $this->tableName, 'type_id');
        $this->createIndex('category_index', $this->tableName, 'category_id');
        $this->createIndex('alias_index', $this->tableName, 'alias');
        $this->createIndex('visible_index', $this->tableName, 'visible');
        $this->createIndex('created_index', $this->tableName, 'created');
        $this->createIndex('show_on_main_page_index', $this->tableName, 'show_on_main_page');
        $this->createIndex('is_new_index', $this->tableName, 'is_new');
        $this->createIndex('is_top_50_index', $this->tableName, 'is_top_50');
        $this->createIndex('is_top_50_category_index', $this->tableName, 'is_top_50_category');
        $this->createIndex('status_index', $this->tableName, 'status');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropIndex('type_index', $this->tableName);
        $this->dropIndex('category_index', $this->tableName);
        $this->dropIndex('alias_index', $this->tableName);
        $this->dropIndex('visible_index', $this->tableName);
        $this->dropIndex('created_index', $this->tableName);
        $this->dropIndex('show_on_main_page_index', $this->tableName);
        $this->dropIndex('is_new_index', $this->tableName);
        $this->dropIndex('is_top_50_index', $this->tableName);
        $this->dropIndex('is_top_50_category_index', $this->tableName);
        $this->dropIndex('status_index', $this->tableName);
    }
}
