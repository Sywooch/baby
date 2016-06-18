<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150318_101009_add_blog_article_tables*/
class m150318_101009_add_blog_article_tables extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%blog_article}}';

    public $tableName2 = '{{%blog_article_component}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'label' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Заголовок"',
                'description' => Schema::TYPE_TEXT. ' DEFAULT NULL COMMENT "Краткое описание"',
                'blog_rubric_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL COMMENT "Рубрика"',
                'file_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL COMMENT "Изображение"',
                'visible' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Отображать"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
                'created' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Создано"',
                'modified' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Обновлено"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_blog_article_blog_rubric_id_to_blog_rubric_table_id',
            $this->tableName,
            'blog_rubric_id',
            '{{%blog_rubric}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable(
            $this->tableName2,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'blog_article_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL COMMENT "Запись"',
                'type' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL COMMENT "Тип виджета"',
                'content' => Schema::TYPE_TEXT. ' DEFAULT NULL COMMENT "Содержимое"',
                'visible' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Отображать"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_b_article_comp_blog_article_id_to_blog_article_table_id',
            $this->tableName2,
            'blog_article_id',
            '{{%blog_article}}',
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
        $this->dropTable($this->tableName2);
        $this->dropTable($this->tableName);
    }
}
