<?php

namespace app\modules\common\models;

use app\modules\store\models\StoreCategory;
use common\models\Language;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%seo_footer_links}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $label
 * @property string $link
 * @property integer $position
 * @property string $created
 * @property string $modified
 *
 * @property StoreCategory $category
 * @property SeoFooterLinksLang[] $seoFooterLinksLangs
 */
class SeoFooterLinks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo_footer_links}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'position'], 'integer'],
            [['label', 'link', 'created', 'modified'], 'required'],
            [['created', 'modified'], 'safe'],
            [['label', 'link'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'label' => 'Название',
            'link' => 'Ссылка',
            'position' => 'Позиция',
            'created' => 'Создано',
            'modified' => 'Обновлено',
        ];
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
                    'tableName' => SeoFooterLinksLang::className(),
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
            'label', 'link'
        ];
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
    public function getSeoFooterLinksLangs()
    {
        return $this->hasMany(SeoFooterLinksLang::className(), ['model_id' => 'id']);
    }
}
