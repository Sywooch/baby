<?php

use common\models\Configuration;
use yii\db\Migration;

/**
* Class m160625_104032_insert_into_configuration*/
class m160625_104032_insert_into_configuration extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%configuration}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $date = date('Y-m-d H:i:s');

        $this->insert($this->tableName, [
            'config_key' => 'contact_phone_1',
            'type' => Configuration::TYPE_STRING,
            'value' => '012 - 34 456 778',
            'description' => 'Телефон 1',
            'created' => $date,
            'modified' => $date
        ]);

        $this->insert($this->tableName, [
            'config_key' => 'contact_phone_2',
            'type' => Configuration::TYPE_STRING,
            'value' => '012 - 34 456 555',
            'description' => 'Телефон 2',
            'created' => $date,
            'modified' => $date
        ]);

        $this->insert($this->tableName, [
            'config_key' => 'admin_email',
            'type' => Configuration::TYPE_STRING,
            'value' => 'videoller@gmail.com',
            'description' => 'Email',
            'created' => $date,
            'modified' => $date
        ]);

        $this->insert($this->tableName, [
            'config_key' => 'address',
            'type' => Configuration::TYPE_TEXT,
            'value' => 'John Doe Street 1<br>
                        12345 Berlin<br>
                        Germany',
            'description' => 'Адрес магазина',
            'created' => $date,
            'modified' => $date
        ]);
    }

    /**
     * commands will be executed in transaction
     */
    public function safeDown()
    {
        $this->delete($this->tableName, 'config_key = "contact_phone_1"');
        $this->delete($this->tableName, 'config_key = "contact_phone_2"');
        $this->delete($this->tableName, 'config_key = "admin_email"');
        $this->delete($this->tableName, 'config_key = "address"');
    }
}
