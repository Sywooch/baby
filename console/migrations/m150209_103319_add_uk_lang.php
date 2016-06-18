<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150209_103319_add_uk_lang*/
class m150209_103319_add_uk_lang extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%language}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->insert($this->tableName, [
                'label' => 'укр',
                'code' => 'ua',
                'locale' => 'uk',
                'visible' => 1,
                'position' => 0,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ]);
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->delete($this->tableName, 'code = :code', [':code' => 'ua']);
    }
}
