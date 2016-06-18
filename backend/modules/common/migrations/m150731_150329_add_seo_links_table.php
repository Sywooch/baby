<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m150731_150329_add_seo_links_table*/
class m150731_150329_add_seo_links_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%seo_footer_links}}';

    /**
     * commands will be executed in transaction
     */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'category_id' => Schema::TYPE_INTEGER . ' UNSIGNED DEFAULT NULL COMMENT "Категория"',
                'label' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Название"',
                'link' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Ссылка"',
                'position' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
                'created' => Schema::TYPE_DATETIME . ' NOT NULL COMMENT "Создано"',
                'modified' => Schema::TYPE_DATETIME . ' NOT NULL COMMENT "Обновлено"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_seo_footer_links_cat_id_to_cat_table',
            $this->tableName,
            'category_id',
            '{{%store_category}}',
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
        $this->dropTable($this->tableName);
    }
}
