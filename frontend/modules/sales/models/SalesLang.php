<?php

namespace frontend\modules\sales\models;

use Yii;

/**
 * This is the model class for table "{{%sales_lang}}".
 *
 * @property integer $l_id
 * @property integer $model_id
 * @property string $lang_id
 * @property string $label
 * @property string $href
 * @property string $content
 *
 * @property Language $lang
 * @property Sales $model
 */
class SalesLang extends \frontend\components\FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sales_lang}}';
    }
}
