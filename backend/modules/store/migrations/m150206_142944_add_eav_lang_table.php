<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150206_142944_add_eav_lang_table*/
class m150206_142944_add_eav_lang_table extends Migration
{
    /**
     * migration related table name
     */
    public $tableName = '{{%store_product_eav}}';

    /**
     * main table name, to make constraints
     */
    public $relatedTableName = '{{%store_product_eav_lang}}';

    /**
     * commands will be executed in transaction
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'id', Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');

        $this->createTable(
            $this->relatedTableName,
            [
                'l_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'model_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
                'lang_id' => Schema::TYPE_STRING . '(5) NOT NULL',
                // examples:
                'value' => Schema::TYPE_STRING. ' NOT NULL',
                //'l_announce' => Schema::TYPE_TEXT. ' NULL DEFAULT NULL',
                //'l_content' => Schema::TYPE_TEXT. ' NULL DEFAULT NULL',

                'INDEX key_model_id_lang_id (model_id, lang_id)',
                'INDEX key_model_id (model_id)',
                'INDEX key_lang_id (lang_id)',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_store_product_eav_lang_model_id_to_main_model_id',
            $this->relatedTableName,
            'model_id',
            $this->tableName,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_store_product_eav_lang_lang_id_to_language_id',
            $this->relatedTableName,
            'lang_id',
            '{{%language}}',
            'code',
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
        $this->dropColumn($this->tableName, 'id');
    }
}
