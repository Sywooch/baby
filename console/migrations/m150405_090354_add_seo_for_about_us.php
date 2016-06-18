<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150405_090354_add_seo_for_about_us*/
class m150405_090354_add_seo_for_about_us extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%page_seo}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->insert($this->tableName, [
            'description' => 'Страница "Про нас"'
        ]);
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->delete($this->tableName, 'description = :desc', [':desc' => 'Страница "Про нас"']);
    }
}
