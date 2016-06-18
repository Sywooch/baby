<?php

namespace backend\modules\common\models;

use Yii;

/**
 * This is the model class for table "{{%certificate_lang}}".
 *
 * @property integer $l_id
 * @property integer $model_id
 * @property string $lang_id
 * @property string $label
 */
class CertificateLang extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%certificate_lang}}';
    }
}
