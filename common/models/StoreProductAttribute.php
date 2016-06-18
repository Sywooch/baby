<?php

namespace common\models;

use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%store_product_attribute}}".
 *
 * @property integer $id
 * @property string $label
 * @property integer $type
 * @property integer $show_in_filter
 * @property integer $is_required
 * @property integer $position
 */
class StoreProductAttribute extends \yii\db\ActiveRecord
{
    const TYPE_INPUT = 1;

    const TYPE_DROPDOWN = 2;

    const TYPE_MULTISELECT = 3;

    const TYPE_BOOLEAN = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product_attribute}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Название',
            'type' => 'Тип',
            'show_in_filter' => 'Показывать в блоке фильтров',
            'is_required' => 'Обязателен ли к заполнению',
            'position' => 'Позиция',
        ];
    }
}
