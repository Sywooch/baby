<?php

namespace frontend\modules\store\models;

use common\components\model\FrontModelForGuidTranslation;
use frontend\components\FrontModel;
use Yii;

/**
 * This is the model class for table "store_product_type_size".
 *
 * @property integer $id
 * @property integer $product_type_id
 * @property integer $size_id
 * @property string $label
 * @property string $height
 */
class StoreProductTypeSize extends FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_product_type_size';
    }

    /**
     * @return array
     */
    public function getLocalizedAttributes()
    {
        return ['label'];
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        if ($this->height) {
            return "$this->label ($this->height)";
        }
        
        return $this->label;
    }
}
