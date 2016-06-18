<?php

namespace frontend\modules\common\models;

use common\models\Language;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%pay_and_delivery}}".
 *
 * @property integer $id
 * @property integer $type_id
 * @property string $name
 * @property string $price
 * @property integer $for_kiev
 * @property integer $for_regions
 * @property integer $position
 * @property string $created
 * @property string $modified
 *
 * @property PayAndDeliveryLang[] $payAndDeliveryLangs
 */
class PayAndDelivery extends \frontend\components\FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay_and_delivery}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'ml' => [
                    'class' => MultilingualBehavior::className(),
                    'languages' => Language::getLangList(),
                    'languageField' => 'lang_id',
                    'defaultLanguage' => Language::getDefaultLang()->code,
                    'langForeignKey' => 'model_id',
                    'tableName' => PayAndDeliveryLang::className(),
                    'attributes' => $this->getLocalizedAttributes()
                ]
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getLocalizedAttributes()
    {
        return [
            'name', 'price'
        ];
    }

    /**
     * @return string
     */
    public function getKievAndRegionsStatusForMob()
    {
        if ($this->for_kiev && !$this->for_regions) {
            return Yii::t('payAndDelivery', 'for_kyiv');
        } elseif ($this->for_regions && !$this->for_kiev) {
            return Yii::t('payAndDelivery', 'for_regions');
        } elseif ($this->for_kiev && $this->for_regions) {
            return Yii::t('payAndDelivery', 'for_Kyiv_and_regions');
        }
    }
}
