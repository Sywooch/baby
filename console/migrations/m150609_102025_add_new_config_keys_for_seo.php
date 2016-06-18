<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150609_102025_add_new_config_keys_for_seo*/
class m150609_102025_add_new_config_keys_for_seo extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%configuration}}';
    public $tableNameLang = '{{%configuration_lang}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $date = date('Y-m-d H:i:s');

        $this->insert($this->tableName, [
            'config_key' => 'default_meta_title_for_product_page',
            'type' => 2,
            'value' => 'купить в Киеве, цена выгодная - интернет магазин оригинальных подарков Chicardi',
            'description' => 'приставка для seo-тега title для страницы продукта по-умолчанию',
            'created' => $date,
            'modified' => $date
        ]);

        $this->insert($this->tableName, [
            'config_key' => 'default_meta_title_for_catalog_category',
            'type' => 2,
            'value' => 'Страница {{page}} раздела {{category}} - интернет магазин подарков Chicardi.com',
            'description' => 'приставка для seo-тега title для страницы категории каталога',
            'created' => $date,
            'modified' => $date
        ]);

        $this->insert($this->tableName, [
            'config_key' => 'default_meta_title_for_other_pages',
            'type' => 2,
            'value' => 'интернет магазин оригинальных подарков Chicardi',
            'description' => 'приставка для seo-тега title для непродвигаемых страниц',
            'created' => $date,
            'modified' => $date
        ]);

        $this->insert($this->tableName, [
            'config_key' => 'default_meta_description_for_catalog_category',
            'type' => 2,
            'value' => 'Лучшие цены на {{category}} в интернет магазине Chicardi.com. Огромный выбор {{category_many}} по доступным ценам. Мы осуществляем доставку {{category_many}} по всей Украине.',
            'description' => 'приставка для seo-тега description для категорий каталога',
            'created' => $date,
            'modified' => $date
        ]);
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->delete($this->tableName, 'config_key = "default_meta_title_for_product_page"');
        $this->delete($this->tableName, 'config_key = "default_meta_title_for_catalog_category"');
        $this->delete($this->tableName, 'config_key = "default_meta_title_for_other_pages"');
        $this->delete($this->tableName, 'config_key = "default_meta_description_for_catalog_category"');
    }
}
