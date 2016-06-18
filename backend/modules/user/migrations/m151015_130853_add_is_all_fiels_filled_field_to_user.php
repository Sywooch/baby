<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m151015_130853_add_is_all_fiels_filled_field_to_user*/
class m151015_130853_add_is_all_fiels_filled_field_to_user extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%user}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'is_profile_filled', $this->smallInteger(1)->notNull()->defaultValue(1));
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'is_profile_filled');
    }
}
