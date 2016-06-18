<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m151019_112830_add_comment_table*/
class m151019_112830_add_comment_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%comment}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer(). ' UNSIGNED NOT NULL',
                'article_id' => $this->integer(). ' UNSIGNED NOT NULL',
                'content' => $this->text()->notNull(),
                'status' => $this->smallInteger(1)->defaultValue(1),
                'created' => $this->dateTime()->notNull(),
                'modified' => $this->dateTime()->notNull()
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );


        $this->addForeignKey('fk_comment_article_id_to_blog_article_id', $this->tableName, 'article_id', '{{%blog_article}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_comment_user_id_to_blog_article_id', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
