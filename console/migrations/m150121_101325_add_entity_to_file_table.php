<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150121_101325_add_entity_to_file_table*/
class m150121_101325_add_entity_to_file_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%entity_to_file}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'entity_model_name' => Schema::TYPE_STRING. ' NOT NULL',
                'entity_model_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'file_id' => Schema::TYPE_INTEGER. ' NOT NULL',
                'temp_sign' => Schema::TYPE_STRING. ' NOT NULL',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_entity_file_id_to_fpm_file_table',
            $this->tableName,
            'file_id',
            '{{%fpm_file}}',
            'id',
            'CASCADE',
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
