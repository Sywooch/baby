<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%blog_article}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $description
 * @property integer $blog_rubric_id
 * @property integer $file_id
 * @property integer $visible
 * @property integer $position
 * @property string $created
 * @property string $modified
 *
 */
class BlogArticle extends ActiveRecord
{
    /**
     *
     */
    const CONTENT_TYPE_TEXT = 1;
    /**
     *
     */
    const CONTENT_TYPE_IMAGE = 2;
    /**
     *
     */
    const CONTENT_TYPE_IMAGE_BLOCK = 3;
    /**
     *
     */
    const CONTENT_TYPE_PRODUCT_BLOCK = 4;
    /**
     *
     */
    const CONTENT_TYPE_VIDEO_BLOCK = 5;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog_article}}';
    }

    /**
     * @return array
     */
    public static function getContentList()
    {
        return [
            static::CONTENT_TYPE_TEXT => 'Блок текста',
            static::CONTENT_TYPE_IMAGE => 'Изображение',
//            static::CONTENT_TYPE_IMAGE_BLOCK => 'Три изображения',
            static::CONTENT_TYPE_PRODUCT_BLOCK => 'Блок продуктов',
            static::CONTENT_TYPE_VIDEO_BLOCK => 'Видео'
        ];
    }

    /**
     * @param $contentId
     *
     * @return string|null
     */
    public static function getContent($contentId)
    {
        $content = static::getContentList();

        return isset($content[$contentId]) ? $content[$contentId] : null;
    }
}
