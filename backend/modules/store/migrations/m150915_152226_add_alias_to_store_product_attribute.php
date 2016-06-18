<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150915_152226_add_alias_to_store_product_attribute*/
class m150915_152226_add_alias_to_store_product_attribute extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%store_product_attribute}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'alias', $this->string()->notNull()->defaultValue(''). ' AFTER label');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'alias');
    }
}
