<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m150219_101458_add_banner_tables_lang */
class m150219_101458_add_banner_tables_lang extends Migration
{
    /**
     * migration related table name
     */
    public $tableName = '{{%banner}}';

    /**
     * main table name, to make constraints
     */
    public $relatedTableName = '{{%banner_lang}}';

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
            // examples:
            'label' => Schema::TYPE_STRING. ' NOT NULL',
            'small_label' => Schema::TYPE_STRING. ' NOT NULL DEFAULT ""',
            'content' => Schema::TYPE_STRING. '(500) NOT NULL',
            'href' => Schema::TYPE_STRING. '(300) NULL DEFAULT NULL',

            'INDEX key_model_id_lang_id (model_id, lang_id)',
            'INDEX key_model_id (model_id)',
            'INDEX key_lang_id (lang_id)',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_banner_lang_model_id_to_main_model_id',
            $this->relatedTableName,
            'model_id',
            $this->tableName,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_banner_lang_lang_id_to_language_id',
            $this->relatedTableName,
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
        $this->dropTable($this->relatedTableName);
    }
}
