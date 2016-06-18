<?php

namespace frontend\modules\common\models;

use common\models\Language;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%footer_link}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $column
 * @property integer $visible
 * @property integer $position
 * @property string $created
 * @property string $modified
 *
 * @property FooterLinkLang[] $footerLinkLangs
 */
class FooterLink extends \frontend\components\FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%footer_link}}';
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
                    'tableName' => FooterLinkLang::className(),
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
            'label', 'url'
        ];
    }
}
