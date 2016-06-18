<?php

namespace frontend\modules\common\models;

use Yii;

/**
 * This is the model class for table "{{%pay_and_delivery_lang}}".
 *
 * @property integer $l_id
 * @property integer $model_id
 * @property string $lang_id
 * @property string $name
 * @property string $price
 */
class PayAndDeliveryLang extends \frontend\components\FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay_and_delivery_lang}}';
    }
}
