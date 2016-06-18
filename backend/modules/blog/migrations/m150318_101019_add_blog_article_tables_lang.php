<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m150318_101019_add_blog_article_tables_lang */
class m150318_101019_add_blog_article_tables_lang extends Migration
{
    /**
     * migration related table name
     */
    public $tableName = '{{%blog_article}}';
    public $tableName2 = '{{%blog_article_component}}';

    /**
     * main table name, to make constraints
     */
    public $relatedTableName = '{{%blog_article_lang}}';
    public $relatedTableName2 = '{{%blog_article_component_lang}}';

    /**
     * commands will be executed in transaction
     */
    public function safeUp()
    {
        $this->createTable(
            $this->relatedTableName,
            [
            'l_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'model_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'lang_id' => Schema::TYPE_STRING . '(5) NOT NULL',
            'label' => Schema::TYPE_STRING. ' NOT NULL',
            'description' => Schema::TYPE_TEXT. ' NULL DEFAULT NULL',

            'INDEX key_model_id_lang_id (model_id, lang_id)',
            'INDEX key_model_id (model_id)',
            'INDEX key_lang_id (lang_id)',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_blog_article_lang_model_id_to_main_model_id',
            $this->relatedTableName,
            'model_id',
            $this->tableName,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_blog_article_lang_lang_id_to_language_id',
            $this->relatedTableName,
            'lang_id',
            '{{%language}}',
            'locale',
            'CASCADE',
            'CASCADE'
        );


        $this->createTable(
            $this->relatedTableName2,
            [
                'l_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'model_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
                'lang_id' => Schema::TYPE_STRING . '(5) NOT NULL',
                'content' => Schema::TYPE_TEXT. ' NULL DEFAULT NULL',

                'INDEX key_model_id_lang_id (model_id, lang_id)',
                'INDEX key_model_id (model_id)',
                'INDEX key_lang_id (lang_id)',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_blog_article_component_lang_model_id_to_main_model_id',
            $this->relatedTableName2,
            'model_id',
            $this->tableName2,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_blog_article_component_lang_lang_id_to_language_id',
            $this->relatedTableName2,
            'lang_id',
            '{{%language}}',
            'locale',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * commands will be executed in transaction
     */
    public function safeDown()
    {
        $this->dropTable($this->relatedTableName2);
        $this->dropTable($this->relatedTableName);
    }
}
