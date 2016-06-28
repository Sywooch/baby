<?php

namespace app\modules\store\models;

use common\models\Language;
use frontend\components\FrontModel;
use himiklab\sitemap\behaviors\SitemapBehavior;
use omgdef\multilingual\MultilingualBehavior;
use sammaye\extensions\NestedSetBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

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
 */
class StoreCategory extends FrontModel
{

    /**
     * This is used by CatalogController
     * @var null
     */
    public static $category = null;

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
    public function rules()
    {
        return [
            [['lft', 'rgt', 'level', 'label', 'alias'], 'required'],
            [['lft', 'rgt', 'level', 'visible'], 'integer'],
            [['description'], 'string'],
            [['label', 'alias'], 'string', 'max' => 255]
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
                'NestedSetBehavior' => [
                    'class' => NestedSetBehavior::className(),
                ],
                'ml' => [
                    'class' => MultilingualBehavior::className(),
                    'languages' => Language::getLangList(),
                    'languageField' => 'lang_id',
                    'defaultLanguage' => Language::getDefaultLang()->code,
                    'langForeignKey' => 'model_id',
                    'tableName' => StoreCategoryLang::className(),
                    'attributes' => $this->getLocalizedAttributes()
                ],
                'sitemap' => [
                    'class' => SitemapBehavior::className(),
                    'scope' => function ($model) {
                        /** @var \yii\db\ActiveQuery $model */
                        $model->select(['alias', 'modified']);
                        $model->andWhere(['visible' => 1]);
                        $model->andWhere('level >= 2')->orderBy('lft');
                    },
                    'dataClosure' => function ($model) {
                        /** @var self $model */
                        return [
                            'loc' => Url::to($model::getCatalogRoute(['alias' => $model->alias]), true),
                            'lastmod' => strtotime($model->modified),
                            'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        ];
                    }
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getLocalizedAttributes()
    {
        return [
            'label', 'label_parent_case'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'label' => Yii::t('app', 'Название'),
            'alias' => Yii::t('app', 'Алиас'),
            'description' => Yii::t('app', 'Описание'),
            'visible' => Yii::t('app', 'Отображать'),
        ];
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getCatalogRoute($params = [])
    {
        return ArrayHelper::merge(['/store/catalog/index'], $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function getCatalogUrl($params = [])
    {
        $params['alias'] = $this->alias;
        
        return Url::to(static::getCatalogRoute($params));
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getSearchUrl($params = [])
    {
        return Url::to(['/store/catalog/search', $params]);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getCatalogNewRoute($params = [])
    {
        return ArrayHelper::merge(['/store/catalog/new'], $params);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasMany(StoreProduct::className(), ['category_id' => 'id']);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getCatalogTopRoute($params = [])
    {
        return ArrayHelper::merge(['/store/catalog/top'], $params);
    }

    /**
     * @param $categoryAlias
     *
     * @return array|bool
     */
    public static function getCategoryWithChildIdList($categoryAlias)
    {
        $categoryIdList = [];

        $category = StoreCategory::find()->where('alias = :alias', [':alias' => $categoryAlias])->one();
        if ($category) {
            static::$category = $category;

            $categoryIdList = ArrayHelper::merge(
                [$category->id],
                ArrayHelper::getColumn($category->children()->select('id')->asArray()->all(), 'id')
            );
        }

        return empty($categoryIdList) ? false : $categoryIdList;
    }

    /**
     * @return null|string
     */
    public function getCategoryParent()
    {
        $parent = $this->parent()->one();

        return $parent
            ? $parent->label
            : null;
    }

    /**
     * @return string
     */
    public function getBreadCrumbs()
    {
        $output = Html::a(Yii::t('front', 'Home'), Url::home()) . ' » ';
        $ascestors = $this->ancestors()->all();

        foreach ($ascestors as $asc) {
            if ($asc->id > 1) {
                $output .= Html::a($asc->label, Url::to(StoreCategory::getCatalogRoute(['alias' => $asc->alias])));
                $output .= ' » ';
            }
        }
        $output .= Html::a($this->label, '#');

        return $output;
    }

    /**
     * @return static[]
     */
    public static function getParentCategories()
    {
        return static::find()
            ->where(['level' => 2, 'visible' => 1])
            ->orderBy('lft')
            ->all();
    }

    /**
     * @return static[] | []
     */
    public function getChildren()
    {
        return $this->children()->all();
    }
}
