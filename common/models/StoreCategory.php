<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%store_category}}".
 *
 * @property integer $id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $level
 * @property string $label
 * @property string $alias
 * @property string $description
 * @property integer $visible
 *
 * @property StoreCategoryLang[] $storeCategoryLangs
 * @property StoreProduct[] $storeProducts
 */
class StoreCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_category}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'level' => 'Level',
            'label' => 'Название',
            'alias' => 'Алиас',
            'description' => 'Описание',
            'visible' => 'Отображать',
        ];
    }

    /**
     * @return array
     */
    public static function getCategoriesList()
    {
        return ArrayHelper::map(StoreCategory::find()->all(), 'id', 'label');
    }
}
