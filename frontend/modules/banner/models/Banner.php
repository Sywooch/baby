<?php

namespace app\modules\banner\models;

use common\models\Language;
use frontend\components\FrontModel;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use yii\helpers\ArrayHelper;

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
 * @property StoreCategory $category
 * @property BannerLang[] $bannerLangs
 */
class Banner extends FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner}}';
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
                    'tableName' => BannerLang::className(),
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
        return ['label', 'small_label', 'content', 'href'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(StoreCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBannerLangs()
    {
        return $this->hasMany(BannerLang::className(), ['model_id' => 'id']);
    }
}
