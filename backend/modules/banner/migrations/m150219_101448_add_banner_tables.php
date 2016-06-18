<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150219_101448_add_banner_tables*/
class m150219_101448_add_banner_tables extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%banner}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'type' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 1',
                'category_id' => Schema::TYPE_INTEGER. ' UNSIGNED DEFAULT NULL',
                'banner_location' => Schema::TYPE_INTEGER. ' UNSIGNED DEFAULT NULL',
                'label' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Заголовок"',
                'small_label' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Подзаголовок" DEFAULT ""',
                'content' => Schema::TYPE_STRING. '(500) NOT NULL COMMENT "Текст"',
                'href' => Schema::TYPE_STRING. '(300) DEFAULT NULL COMMENT "Ссылка"',
                'image_id' => Schema::TYPE_INTEGER. ' DEFAULT NULL',
                'visible' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Отображать"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );


        $this->createIndex('key_type', $this->tableName, 'type');
        $this->createIndex('key_banner_location', $this->tableName, 'banner_location');

        $this->addForeignKey(
            'fk_banner_category_id_to_category_table',
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
