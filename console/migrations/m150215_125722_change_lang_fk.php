<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150215_125722_change_lang_fk*/
class m150215_125722_change_lang_fk extends Migration
{
    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();

        //Unique for language locale
        $this->createIndex('key_unique_locale', '{{%language}}', 'locale', true);

        $this->dropForeignKey('fk_store_category_lang_lang_id_to_language_id', '{{%store_category_lang}}');
        $this->addForeignKey(
            'fk_store_category_lang_lang_id_to_language_id',
            '{{%store_category_lang}}',
            'lang_id',
            '{{%language}}',
            'locale',
            'CASCADE',
            'CASCADE'
        );

        $this->dropForeignKey('fk_store_product_type_lang_lang_id_to_language_id', '{{%store_product_type_lang}}');
        $this->addForeignKey(
            'fk_store_product_type_lang_lang_id_to_language_id',
            '{{%store_product_type_lang}}',
            'lang_id',
            '{{%language}}',
            'locale',
            'CASCADE',
            'CASCADE'
        );

        $this->dropForeignKey('fk_store_product_attribute_lang_lang_id_to_language_id', '{{%store_product_attribute_lang}}');
        $this->addForeignKey(
            'fk_store_product_attribute_lang_lang_id_to_language_id',
            '{{%store_product_attribute_lang}}',
            'lang_id',
            '{{%language}}',
            'locale',
            'CASCADE',
            'CASCADE'
        );

        $this->dropForeignKey('fk_store_product_attribute_option_lang_lang_id_to_language_id', '{{%store_product_attribute_option_lang}}');
        $this->addForeignKey(
            'fk_store_product_attribute_option_lang_lang_id_to_language_id',
            '{{%store_product_attribute_option_lang}}',
            'lang_id',
            '{{%language}}',
            'locale',
            'CASCADE',
            'CASCADE'
        );

        $this->dropForeignKey('fk_store_product_lang_lang_id_to_language_id', '{{%store_product_lang}}');
        $this->addForeignKey(
            'fk_store_product_lang_lang_id_to_language_id',
            '{{%store_product_lang}}',
            'lang_id',
            '{{%language}}',
            'locale',
            'CASCADE',
            'CASCADE'
        );

        $this->dropForeignKey('fk_store_product_variants_lang_lang_id_to_language_id', '{{%store_product_variant_lang}}');
        $this->addForeignKey(
            'fk_store_product_variants_lang_lang_id_to_language_id',
            '{{%store_product_variant_lang}}',
            'lang_id',
            '{{%language}}',
            'locale',
            'CASCADE',
            'CASCADE'
        );

        $this->dropForeignKey('fk_store_product_eav_lang_lang_id_to_language_id', '{{%store_product_eav_lang}}');
        $this->addForeignKey(
            'fk_store_product_eav_lang_lang_id_to_language_id',
            '{{%store_product_eav_lang}}',
            'lang_id',
            '{{%language}}',
            'locale',
            'CASCADE',
            'CASCADE'
        );

        $this->dropColumn('{{%language}}', 'url_code');

        $this->update('{{%language}}', ['code' => 'ua'], 'code = :code', [':code' => 'uk']);
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();

        $this->addColumn('{{%language}}', 'url_code', Schema::TYPE_STRING.'(5) NOT NULL AFTER `code`');

        $this->dropIndex('key_unique_locale', '{{%language}}');

        return true;
    }
}
