<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m150205_091700_update_lang_models*/
class m150205_091700_update_lang_models extends Migration
{
    /**
     * commands will be executed in transaction
     */
    public function safeUp()
    {
        //StoreProduct
        $this->dropColumn('{{%store_product_lang}}', 'l_label');
        $this->dropColumn('{{%store_product_lang}}', 'l_announce');
        $this->dropColumn('{{%store_product_lang}}', 'l_content');
        $this->addColumn('{{%store_product_lang}}', 'label', Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn('{{%store_product_lang}}', 'announce', Schema::TYPE_TEXT . ' NULL DEFAULT NULL');
        $this->addColumn('{{%store_product_lang}}', 'content', Schema::TYPE_TEXT . ' NULL DEFAULT NULL');

        //StoreCategory
        $this->dropColumn('{{%store_category_lang}}', 'l_label');
        $this->dropColumn('{{%store_category_lang}}', 'l_description');
        $this->addColumn('{{%store_category_lang}}', 'label', Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn('{{%store_category_lang}}', 'description', Schema::TYPE_TEXT . ' NULL DEFAULT NULL');

        //StoreProductType
        $this->dropColumn('{{%store_product_type_lang}}', 'l_label');
        $this->addColumn('{{%store_product_type_lang}}', 'label', Schema::TYPE_STRING . ' NOT NULL');

        //StoreProductAttribute
        $this->dropColumn('{{%store_product_attribute_lang}}', 'l_label');
        $this->addColumn('{{%store_product_attribute_lang}}', 'label', Schema::TYPE_STRING . ' NOT NULL');

        //StoreProductVariant
        $this->dropColumn('{{%store_product_variant_lang}}', 'l_label');
        $this->addColumn('{{%store_product_variant_lang}}', 'label', Schema::TYPE_STRING . ' NOT NULL');

        //StoreProductAttributeOption
        $this->dropColumn('{{%store_product_attribute_option_lang}}', 'l_label');
        $this->addColumn('{{%store_product_attribute_option_lang}}', 'label', Schema::TYPE_STRING . ' NOT NULL');
    }

    /**
     * commands will be executed in transaction
     */
    public function safeDown()
    {
        //StoreProduct
        $this->dropColumn('{{%store_product_lang}}', 'label');
        $this->dropColumn('{{%store_product_lang}}', 'announce');
        $this->dropColumn('{{%store_product_lang}}', 'content');
        $this->addColumn('{{%store_product_lang}}', 'l_label', Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn('{{%store_product_lang}}', 'l_announce', Schema::TYPE_TEXT . ' NULL DEFAULT NULL');
        $this->addColumn('{{%store_product_lang}}', 'l_content', Schema::TYPE_TEXT . ' NULL DEFAULT NULL');

        //StoreCategory
        $this->dropColumn('{{%store_category_lang}}', 'label');
        $this->dropColumn('{{%store_category_lang}}', 'description');
        $this->addColumn('{{%store_category_lang}}', 'l_label', Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn('{{%store_category_lang}}', 'l_description', Schema::TYPE_TEXT . ' NULL DEFAULT NULL');

        //StoreProductType
        $this->dropColumn('{{%store_product_type_lang}}', 'label');
        $this->addColumn('{{%store_product_type_lang}}', 'l_label', Schema::TYPE_STRING . ' NOT NULL');

        //StoreProductAttribute
        $this->dropColumn('{{%store_product_attribute_lang}}', 'label');
        $this->addColumn('{{%store_product_attribute_lang}}', 'l_label', Schema::TYPE_STRING . ' NOT NULL');

        //StoreProductVariant
        $this->dropColumn('{{%store_product_variant_lang}}', 'label');
        $this->addColumn('{{%store_product_variant_lang}}', 'l_label', Schema::TYPE_STRING . ' NOT NULL');

        //StoreProductAttributeOption
        $this->dropColumn('{{%store_product_attribute_option_lang}}', 'label');
        $this->addColumn('{{%store_product_attribute_option_lang}}', 'l_label', Schema::TYPE_STRING . ' NOT NULL');
    }
}
