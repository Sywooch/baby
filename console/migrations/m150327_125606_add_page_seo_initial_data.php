<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150327_125606_add_page_seo_initial_data*/
class m150327_125606_add_page_seo_initial_data extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%meta_tag}}';

    public $seoPagTableName = '{{%page_seo}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $date = (new DateTime())->format('d-m-Y H:i:s');

        $data = [
            [
                'meta_tag_name' => 'bottom_seo_text_h1',
                'tag_description' => 'SEO внизу страницы, заглавие(h1)',
                'is_active' => 0,
                'position' => 4,
                'created' => $date,
                'modified' => $date,
            ],
            [
                'meta_tag_name' => 'bottom_seo_text_h2',
                'tag_description' => 'SEO внизу страницы, маленькое заглавие(h2)',
                'is_active' => 0,
                'position' => 5,
                'created' => $date,
                'modified' => $date,
            ],
            [
                'meta_tag_name' => 'bottom_seo_text_first_col_visible',
                'tag_description' => 'SEO внизу страницы, первый столбец(видимый)',
                'is_active' => 0,
                'position' => 6,
                'created' => $date,
                'modified' => $date,
            ],
            [
                'meta_tag_name' => 'bottom_seo_text_first_col_hidden',
                'tag_description' => 'SEO внизу страницы, первый столбец(скрытый)',
                'is_active' => 0,
                'position' => 7,
                'created' => $date,
                'modified' => $date,
            ],
            [
                'meta_tag_name' => 'bottom_seo_text_second_col_visible',
                'tag_description' => 'SEO внизу страницы, второй стобец(видимый)',
                'is_active' => 0,
                'position' => 8,
                'created' => $date,
                'modified' => $date,
            ],
            [
                'meta_tag_name' => 'bottom_seo_text_second_col_hidden',
                'tag_description' => 'SEO внизу страницы, второй стобец(скрытый)',
                'is_active' => 0,
                'position' => 9,
                'created' => $date,
                'modified' => $date,
            ],
        ];

        $dataPageSeo = [
            [
                'description' => 'Главная страница',
            ],
            [
                'description' => 'Страница каталога, без категорий',
            ],
            [
                'description' => 'Страница новинок',
            ],
            [
                'description' => 'Страница ТОП-50',
            ],
            [
                'description' => 'Страница "Подобрать подарок"',
            ],
            [
                'description' => 'Страница "Клуб chicardi(список записей блогов)"',
            ],
            [
                'description' => 'Страница "шоу-рум"',
            ],
            [
                'description' => 'Страница "Подарочные сертификаты"',
            ],
            [
                'description' => 'Страница "Оплата и доставка"',
            ],
            [
                'description' => 'Страница "Клуб chicardi(статическая)"',
            ],
            [
                'description' => 'Страница списка акций и скидок'
            ]
        ];

        $this->batchInsert($this->tableName, [
            'meta_tag_name',
            'tag_description',
            'is_active',
            'position',
            'created',
            'modified',
        ], $data);

        $this->batchInsert($this->seoPagTableName, [
            'description',
        ], $dataPageSeo);
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->truncateTable($this->seoPagTableName);

        $this->db->createCommand()
            ->delete($this->tableName, 'id > 3');
    }
}
