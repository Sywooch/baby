<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150519_124932_add_certificate_table*/
class m150519_124933_add_certificate_table_lang extends Migration
{
    /**
     * migration related table name
     */
    public $tableName = '{{%certificate}}';

    /**
     * main table name, to make constraints
     */
    public $relatedTableName = '{{%certificate_lang}}';

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

                'INDEX key_model_id_lang_id (model_id, lang_id)',
                'INDEX key_model_id (model_id)',
                'INDEX key_lang_id (lang_id)',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_certificate_lang_model_id_to_main_model_id',
            $this->relatedTableName,
            'model_id',
            $this->tableName,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_certificate_lang_lang_id_to_language_id',
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
