<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m151014_192310_add_user_social_auth_table*/
class m151014_192310_add_user_social_auth_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%social_auth}}';

    /**
     * commands will be executed in transaction
     */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer(). ' UNSIGNED NOT NULL' ,
                'source' => $this->string()->notNull(),
                'source_id' => $this->string()->notNull(),
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey('fk_social_auth_user_id_to_user_id', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * commands will be executed in transaction
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
