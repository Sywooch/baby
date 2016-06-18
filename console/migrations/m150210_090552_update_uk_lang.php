<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150210_090552_update_uk_lang*/
class m150210_090552_update_uk_lang extends Migration
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
        $this->update($this->tableName, ['url_code' => 'ua'], 'code = :code', [':code' => 'uk']);
        $this->update($this->tableName, ['url_code' => 'ua', 'code' => 'uk'], 'code = :code', [':code' => 'ua']);
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->update($this->tableName, ['url_code' => ''], 'code = :code', [':code' => 'uk']);
    }
}
