<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m141219_111547_add_store_category_lang */
class m141219_111547_add_store_category_lang extends Migration
{
    /**
     * migration related table name
     */
    public $tableName = '{{%store_category}}';

    /**
     * main table name, to make constraints
     */
    public $relatedTableName = '{{%store_category_lang}}';

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
                'l_label' => Schema::TYPE_STRING. ' NOT NULL',
                'l_description' => Schema::TYPE_TEXT. ' DEFAULT NULL',

                'INDEX key_model_id_lang_id (model_id, lang_id)',
                'INDEX key_model_id (model_id)',
                'INDEX key_lang_id (lang_id)',
              ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_store_category_lang_model_id_to_main_model_id',
            $this->relatedTableName,
            'model_id',
            $this->tableName,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_store_category_lang_lang_id_to_language_id',
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
    }
}
