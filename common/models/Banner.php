<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%banner}}".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $category_id
 * @property integer $banner_location
 * @property string $label
 * @property string $small_label
 * @property string $content
 * @property string $href
 * @property integer $image_id
 * @property integer $visible
 * @property integer $position
 *
 **/
class Banner extends \yii\db\ActiveRecord
{
    /**
     *
     */
    const TYPE_MAIN_PAGE = 1;

    /**
     *
     */
    const TYPE_CATEGORY = 2;

    /**
     *
     */
    const TYPE_NEW_PRODUCTS = 3;

    /**
     *
     */
    const TYPE_TOP_50 = 4;

    /**
     *
     */
    const TYPE_BLOG = 5;

    /**
     *
     */
    const TYPE_CATALOG = 6;

    /**
     *
     */
    const LOCATION_TOP = 1;

    /**
     *
     */
    const LOCATION_BOTTOM = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * @return array
     */
    public static function getTypeList()
    {
        return [
            static::TYPE_MAIN_PAGE => 'Главная страница',
            static::TYPE_CATEGORY => 'Категории',
        ];
    }

    /**
     * @param $typeId
     *
     * @return string|null
     */
    public static function getType($typeId)
    {
        $typeList = static::getTypeList();

        return isset($typeList[$typeId]) ? $typeList[$typeId] : null;
    }

    /**
     * @return array
     */
    public static function getLocationList()
    {
        return [
            static::LOCATION_TOP => 'Вверху страницы',
            static::LOCATION_BOTTOM => 'Внизу страницы'
        ];
    }

    /**
     * @param $locId
     *
     * @return string|null
     */
    public static function getLocation($locId)
    {
        $locList = static::getLocationList();

        return isset($locList[$locId]) ? $locList[$locId] : null;
    }
}
