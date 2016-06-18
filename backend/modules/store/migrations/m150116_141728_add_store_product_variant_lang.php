<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m150116_141728_add_store_product_variant_lang */
class m150116_141728_add_store_product_variant_lang extends Migration
{
    /**
     * migration related table name
     */
    public $tableName = '{{%store_product_variant}}';

    /**
     * main table name, to make constraints
     */
    public $relatedTableName = '{{%store_product_variant_lang}}';

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
            //'l_announce' => Schema::TYPE_TEXT. ' NULL DEFAULT NULL',
            //'l_content' => Schema::TYPE_TEXT. ' NULL DEFAULT NULL',

            'INDEX key_model_id_lang_id (model_id, lang_id)',
            'INDEX key_model_id (model_id)',
            'INDEX key_lang_id (lang_id)',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_store_product_variants_lang_model_id_to_main_model_id',
            $this->relatedTableName,
            'model_id',
            $this->tableName,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_store_product_variants_lang_lang_id_to_language_id',
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
