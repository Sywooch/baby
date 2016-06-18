<?php

use yii\db\Schema;
use yii\db\Migration;

class m000000_000000_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%user}}',
            [
                'id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'username' => Schema::TYPE_STRING . ' NOT NULL',
                'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
                'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
                'password_reset_token' => Schema::TYPE_STRING,
                'email' => Schema::TYPE_STRING . ' NOT NULL',
                'role' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
                'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
                'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            ],
            $tableOptions
        );


        $this->insert(
            '{{%user}}',
            [
                'username' => 'admin',
                'auth_key' => Yii::$app->security->generateRandomString(),
                'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
                'password_reset_token' => Yii::$app->security->generateRandomString(). '_'. time(),
                'email' => 'admin@zim.dev',
                'role' => \common\models\User::ROLE_ADMIN,
                'status' => \common\models\User::STATUS_ACTIVE,
                'created_at' => time(),
                'updated_at' => time(),
            ]
        );
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
