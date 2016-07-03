<?php

namespace app\modules\store\models;

use common\models\Currency;
use common\models\EntityToFile;
use common\models\Language;
use common\models\StoreProductAttribute;
use common\models\StoreProductType;
use frontend\components\FrontModel;
use frontend\modules\store\forms\StoreProductSubscribeForm;
use frontend\modules\store\models\StoreProductSize;
use himiklab\sitemap\behaviors\SitemapBehavior;
use metalguardian\fileProcessor\helpers\FPM;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\Query;

/**
 * This is the model class for table "{{%store_product}}".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $category_id
 * @property string $label
 * @property string $alias
 * @property string $announce
 * @property string $content
 * @property string $sku
 * @property string $price
 * @property string $old_price
 * @property integer $visible
 * @property integer $position
 * @property string $video_id
 * @property string $created
 * @property string $modified
 * @property integer $status
 * @property integer $is_sale
 *
 * @property StoreCategory $category
 * @property StoreProductType $type
 * @property StoreProductEav[] $storeProductEavs
 * @property StoreProductLang[] $storeProductLangs
 * @property StoreProductVariant[] $storeProductVariants
 * @property EntityToFile $mainImage
 * @property EntityToFile[] $allImages
 * @property StoreProductSize[] $productSizes
 */
class StoreProduct extends FrontModel
{
    const WIDGET_LATEST = 'Latest Products';
    const WIDGET_SALE = 'Sale Products';
    const WIDGET_POPULAR = 'Popular Products';
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'category_id', 'label', 'alias', 'sku', 'created', 'modified'], 'required'],
            [['type_id', 'category_id', 'visible', 'position'], 'integer'],
            [['announce', 'content'], 'string'],
            [['price'], 'number'],
            [['created', 'modified'], 'safe'],
            [['label', 'alias', 'sku'], 'string', 'max' => 255],
            [['alias'], 'unique'],
            [['sku'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type_id' => Yii::t('app', 'Type ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'label' => Yii::t('app', 'Название'),
            'alias' => Yii::t('app', 'Ссылка'),
            'announce' => Yii::t('app', 'Краткое описание'),
            'content' => Yii::t('app', 'Полное описание'),
            'sku' => Yii::t('app', 'Sku'),
            'price' => Yii::t('app', 'Price'),
            'visible' => Yii::t('app', 'Отображать'),
            'position' => Yii::t('app', 'Позиция'),
            'created' => Yii::t('app', 'Создано'),
            'modified' => Yii::t('app', 'Обновлено'),
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
                    'tableName' => StoreProductLang::tableName(),
                    'attributes' => $this->getLocalizedAttributes()
                ],
                'sitemap' => [
                    'class' => SitemapBehavior::className(),
                    'scope' => function ($model) {
                        /** @var \yii\db\ActiveQuery $model */
                        $model->select(['alias', 'modified']);
                        $model->andWhere(['visible' => 1]);
                        $model->andWhere('status <> :status', ['status' => \common\models\StoreProduct::STATUS_ONLY_DIRECT]);
                    },
                    'dataClosure' => function ($model) {
                        /** @var self $model */
                        return [
                            'loc' => Url::to($model::getProductUrl(['alias' => $model->alias]), true),
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
            'label', 'content', 'announce'
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
    public function getMainImage()
    {
        return $this->hasOne(EntityToFile::className(), ['entity_model_id' => 'id'])
            ->where('entity_model_name = :emn', [':emn' => 'StoreProduct'])
            ->joinWith('file')
            ->orderBy(EntityToFile::tableName().'.position DESC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAllImages()
    {
        return $this->hasMany(EntityToFile::className(), ['entity_model_id' => 'id'])
            ->where('entity_model_name = :emn', [':emn' => 'StoreProduct'])
            ->joinWith('file')
            ->orderBy('position DESC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductSizes()
    {
        return $this->hasMany(StoreProductSize::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVariants()
    {
        return $this->hasMany(StoreProductVariant::className(), ['product_id' => 'id'])
            ->orderBy('position');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEAV()
    {
        return $this->hasMany(StoreProductEav::className(), ['product_id' => 'id'])
            ->localized()
            ->joinWith(
                [
                    'attributeRel' => function ($q) {
                            return $q->localized();
                        }
                ]
            );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreSimilarProducts()
    {
        return $this->hasMany(StoreSimilarProduct::className(), ['similar_product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreProductFilterToProducts()
    {
        return $this->hasMany(StoreProductFilterToProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilters()
    {
        return $this->hasMany(StoreProductFilter::className(), ['id' => 'filter_id'])
            ->viaTable(StoreProductFilterToProduct::tableName(), ['product_id' => 'id']);
    }

    /**
     * @return string|null
     */
    public function getFiltersLabels()
    {
        $filters = $this->getFilters()->all();

        if (!empty($filters)) {
            return ', '. join(', ', ArrayHelper::map($filters, 'id', 'label'));
        }

        return null;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getProductUrl($params = [])
    {
        return self::createUrl('/store/product/view', $params);
    }

    /**
     * @return string
     */
    public function getCategoryBreadCrumbs()
    {
        $output = Html::a(Yii::t('front', 'Home'), Url::home()) . ' » ';
        $ascestors = $this->category->ancestors()->all();

        foreach ($ascestors as $asc) {
            if ($asc->id > 1) {
                $output .= Html::a($asc->label, Url::to(StoreCategory::getCatalogRoute(['alias' => $asc->alias])));
                $output .= ' » ';
            }
        }
        $output .= Html::a($this->category->label, Url::to(StoreCategory::getCatalogRoute(['alias' => $this->category->alias])));

        return $output;
    }

    /**
     * @return string
     */
    public function getVariantsList()
    {
        $output = '';
        $variants = $this->variants;

        $output .= Html::beginTag('table');
        if (!empty($variants)) {
            $output .= Html::beginTag('tr');

            $output .= Html::beginTag('td');
            $output .= Yii::t('frontend', 'Choose').':';
            $output .= Html::endTag('td');
            $output .= Html::tag('td');
            $output .= Html::endTag('tr');

            $i = 0;
            foreach ($variants as $variant) {
                $output .= Html::beginTag('tr');

                $output .= Html::beginTag('td', ['class' => 'check-date', 'colspan' => 2]);
                $output .= Html::tag(
                    'div',
                    Html::tag('span', $variant->getPrice()). ' грн',
                    [
                        'class' => 'check-price',
                        'data-url' => StoreProductCartPosition::getCartAddUrl(['id' => $this->id, 'sku' => $variant->sku])
                    ]
                );
                $output .= Html::radio('variant-choose', false, ['id' => 'variant_'.$i]);
                $output .= Html::label(
                    Html::tag('span').$variant->label.' ('.
                    Html::tag('b', $variant->sku, ['class' => 'variant-strong']). ')',
                    'variant_'.$i
                );
                $output .= Html::endTag('td');
                $output .= Html::endTag('tr');

                $i++;
            }
        }

        $output .= Html::endTag('table');

        return $output;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        $output = '';

        if (!empty($this->announce)) {
            $output .= Html::tag('span', Yii::t('frontend', 'product description'), ['class' => 'product-desc-h2']);
            $output .= Html::tag('p', $this->announce);
        }

        if (!empty ($this->content)) {
            $output .= Html::tag('div', $this->content, ['class' => 'about-add']);
            $output .= Html::a('', '#', ['class' => 'btn-more']);
        }

        return $output;
    }

    /**
     * @param null|string $column
     *
     * @return array
     */
    public static function getProductOrder($column = null)
    {
        switch ($column){
            case 'price':
                return ['price' => SORT_DESC];
            case 'created':
                return 'created ASC';
            default:
                return ['position' => SORT_DESC];
        }
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return Currency::getPriceInCurrency($this->price) . ' ' . Currency::getDefaultCurrencyCode();
    }

    /**
     * @return float
     */
    public function getOldPrice()
    {
        return Currency::getPriceInCurrency($this->getMinOldPrice());
    }

    /**
     * @return bool|string
     */
    public function getMinPrice()
    {
        return (new Query())
            ->select(['MIN(price)'])
            ->from([StoreProductSize::tableName()])
            ->where(['product_id' => $this->id])
            ->groupBy(['product_id'])
            ->scalar();
    }

    /**
     * @return bool|string
     */
    public function getMinOldPrice()
    {
        return (new Query())
            ->select(['MIN(old_price)'])
            ->from([StoreProductSize::tableName()])
            ->where(['product_id' => $this->id])
            ->groupBy(['product_id'])
            ->scalar();
    }

    /**
     * @return string
     */
    public function getMinPriceWithCurrency()
    {
        $minPrice = $this->getMinPrice();
        if (!$minPrice) {
            return Yii::t('front', 'Clarify');
        }
        return Currency::getPriceInCurrency($this->getMinPrice()) . ' ' . Currency::getDefaultCurrencyCode();
    }

    /**
     * @return float|string
     */
    public function getAvailability()
    {
        switch ($this->status) {
            case \common\models\StoreProduct::STATUS_NOT_AVAILABLE:
            case \common\models\StoreProduct::STATUS_ONLY_DIRECT:
                $output = Yii::t('frontend', 'Oops, ended');
                break;
            case \common\models\StoreProduct::STATUS_WAIT_FOR:
                $output = Yii::t('frontend', 'Wait for supply');
                break;
            default:
                $output = 'Цена: ';
                $output .= $this->getPrice();
                $output .= ' грн';
        }

        return $output;
    }

    /**
     * @return null|string
     */
    public function getDeliveryInfo()
    {
        if ($this->getPrice() >= static::getFreeDeliveryPrice()) {
            return Html::tag('p', Yii::t('frontend', 'free delivery'), ['class' => 'free-shipping']);
        }

        return null;
    }

    /**
     * @return int
     */
    public static function getFreeDeliveryPrice()
    {
        $freeDeliveryPrice = Yii::$app->config->get('free_delivery_price');
        $freeDeliveryPrice = $freeDeliveryPrice ? $freeDeliveryPrice : 600;

        return $freeDeliveryPrice;
    }

    /**
     * @return int
     */
    public static function getCourierDeliveryPrice()
    {
        $price = Yii::$app->config->get('courier_deliver_price');

        return $price ? $price : 35;

    }

    /**
     * @return string
     */
    public function getAnnounceForSeo()
    {
        $announceLength = strlen($this->announce);
        if (!$announceLength) {
            return null;
        }

        return strlen($this->announce) <= 70
            ? $this->announce
            : mb_substr($this->announce, 0, 70, 'UTF-8');
    }

    /**
     * @return string
     */
    public function getPriceHtml()
    {
        $output = '';

        switch ($this->status){
            case \common\models\StoreProduct::STATUS_AVAILABLE:
                $text = $this->getDeliveryInfo();
                $class = 'product-price';
                $button = Html::a(
                    Html::tag('span', Yii::t('frontend', 'buy')),
                    StoreProductCartPosition::getCartAddUrl(
                        ['id' => $this->id]
                    ),
                    ['class' => 'btn-round btn-round__purp ajax-link g-a-buy']
                );
                break;
            case \common\models\StoreProduct::STATUS_NOT_AVAILABLE:
            case \common\models\StoreProduct::STATUS_ONLY_DIRECT:
            $text = Html::tag('p', Yii::t('frontend', 'Oops, ended'), ['class' => 'free-shipping']);
                $class = 'product-price no-prod';
                $button = Html::a(
                    Html::tag('span', Yii::t('frontend', 'See catalog for something')),
                    Url::to(StoreCategory::getCatalogRoute()),
                    ['class' => 'btn-round btn-round__yell ']
                );
                break;
            case \common\models\StoreProduct::STATUS_WAIT_FOR:
            $text = Html::tag('p', Yii::t('frontend', 'Wait for supply'), ['class' => 'free-shipping']);
                $class = 'product-price no-prod';
                $button = Html::a(
                    Html::tag('span', Yii::t('frontend', 'Inform me about availability')),
                    StoreProductSubscribeForm::getFormUrl(['id' => $this->id]),
                    ['class' => 'btn-round btn-round__yell ajax-popup-link']
                );
                break;
        }


        $output .= Html::beginTag('div', ['class' => 'product-price-w']);
        $output .= Html::beginTag('div', ['class' => $class]);
        $output .= Html::beginTag('p', ['class' => 'price']);
        $output .= 'Цена: ' . Html::tag('span', $this->getPrice()). ' грн';
        $output .= Html::endTag('p');
        $output .= $text;
        $output .= Html::endTag('div');
        $output .= $button;
        $output .= Html::endTag('div');

        return $output;
    }

    /**
     * @return string
     */
    public function getShortLabel()
    {
        if (strlen($this->label) > 50) {
            return mb_substr($this->label, 0, 50) . ' ...';
        }

        return $this->label;
    }

    /**
     * gets all level 2 categories and their items range
     * 
     * @return array
     */
    public static function getLevelTwo()
    {
        $query = (new Query())
            ->select('lft, rgt, label, alias')
            ->from('store_category')
            ->where('level = :level', [':level' => '2']);
        $category = $query->all();
        return $category;
    }

    /**
     * @return string
     */
    public function getProductType()
    {
        $query = (new Query())
            ->select('label')
            ->from('store_product_type')
            ->where('id = :id', [':id' => $this->type_id]);
        $product_type = $query->all();

        if (!isset($product_type[0]['label']))
            return 'None';

        if (strlen($product_type[0]['label']) > 40) {
            return mb_substr($product_type[0]['label'], 0, 40) . '...';
        }
        return $product_type[0]['label'];
    }

    /**
     * @return string
     */
    public function getProductCategory()
    {
        $uri             = $_SERVER['REQUEST_URI'];
        $reqest_category = substr($uri, strripos($uri, '/') + 1, strlen($uri) - 1);
        
        $query = (new Query())
            ->select('level, label, lft, rgt')
            ->from('store_category')
            ->where('alias = :alias', [':alias' => $reqest_category]);
        $category = $query->all();

        if (!isset($category[0]['level']) || !isset($category[0]['lft']) || !isset($category[0]['rgt']))
            return 'None';

        if ($category[0]['level'] == '2')
            return $category[0]['label'];

        $levels = $this->getLevelTwo();

        foreach ($levels as $level)
        {
            if ($level['lft'] < $category[0]['lft'] && $level['rgt'] > $category[0]['rgt'])
                return $level['label'];
        }

        return 'Nothing';
    }

    /**
     * @return string
     */
    public function getProductSubCategory()
    {
        if (strlen($this->category->label) > 40) {
            return mb_substr($this->category->label, 0, 40) . '...';
        }

        return $this->category->label;
    }

    /**
     * @return string
     */
    public function getProductCountry()
    {
        $query = (new Query())
            ->select('value')
            ->from('store_product_eav')
            ->where('attribute_id = :attribute_id AND product_id = :product_id', 
                [':attribute_id' => '7', 'product_id' => $this->id]);
        $product_country = $query->all();

        if (!isset($product_country[0]['value']))
            return 'None';

        if (strlen($product_country[0]['value']) > 40) {
            return mb_substr($product_country[0]['value'], 0, 40) . '...';
        }
        return $product_country[0]['value'];
    }

    /**
     * gets all categories sorted by lft
     *
     * @return array
     */
    public static function getAllCategories()
    {
        $levels = StoreProduct::getLevelTwo();
        sort($levels);
        foreach ($levels as $key => $level)
        {
            $query = (new Query())
                ->select('lft, rgt, label, alias')
                ->from('store_category')
                ->where('lft > :lft AND rgt < :rgt', 
                    [':lft' => $level['lft'], 'rgt' => $level['rgt']]);
            $sub_categories = $query->all();
            sort($sub_categories);
            $levels[$key]['sub_categories'] = $sub_categories;
        }
        return $levels;
    }

    /**
     * draws li list with all categories
     *
     * @return string
     */
    public function getCategoriesList()
    {
        $levels = StoreProduct::getAllCategories();
        $result = "";
        foreach ($levels as $level)
        {
            $result .= "<li>";
            $result .= "<a href='/catalog/$level[alias]'>$level[label]</a>";
            $result .= "<ul>";
            foreach ($level['sub_categories'] as $item)
            {
                $result .= "<li><a href='/catalog/$item[alias]'>$item[label]</a></li>";
            }
            $result .= "</ul>";
            $result .= "</li>";
        }
        return $result;
    }

    /**
     * gets current sub-category
     * 
     * @return string
     */
    public static function getCurrentSubCategory()
    {
        $uri             = $_SERVER['REQUEST_URI'];
        $reqest_category = substr($uri, strripos($uri, '/') + 1, strlen($uri) - 1);
        $query = (new Query())
            ->select('label')
            ->from('store_category')
            ->where('alias = :alias', [':alias' => $reqest_category]);
        $category = $query->all();

        if (!isset($category[0]['label']))
            return 'None';

        return $category[0]['label'];
    }

    /**
     * gets parent category of sub-category according to it's lft and rgt
     * 
     * @return string
     */
    public function getParentCategory()
    {
        $category = StoreProduct::getCurrentSubCategory();
        $levels   = StoreProduct::getLevelTwo();
        
        $query = (new Query())
            ->select('lft, rgt')
            ->from('store_category')
            ->where('label = :label', [':label' => $category]);
        $category = $query->all();

        if (!isset($category[0]['lft']) || !isset($category[0]['rgt']))
            return 'None';

        foreach ($levels as $level)
        {
            if ($level['lft'] < $category[0]['lft'] && $level['rgt'] > $category[0]['rgt'])
                return $level['label'];
        }

        return "Nothing";
    }

    /**
     * gets 4 random products from current category
     * 
     * @return array
     */
    public function getRandomProducts()
    {
        $sub_category = StoreProduct::getCurrentSubCategory();
        $levels   = StoreProduct::getLevelTwo();

        $is_parent_category = false;
        foreach ($levels as $level)
        {
            if ($level['label'] == $sub_category)
                $is_parent_category = true;
        }

        if ($is_parent_category)
        {
            $query = (new Query())
                ->select('lft, rgt')
                ->from('store_category')
                ->where('label = :label', [':label' => $sub_category]);
            $category = $query->all();

            if (!isset($category[0]['lft']) || !isset($category[0]['rgt']))
                return 'None';
            
            $query = (new Query())
                ->select('id')
                ->from('store_category')
                ->where('lft > :lft AND rgt < :rgt', [':lft' => $category[0]['lft'], ':rgt' => $category[0]['rgt']]);
            $all_ids = $query->all();

            foreach ($all_ids as $id)
            {
                $query = (new Query())
                    ->select('')
                    ->from('store_product')
                    ->where('category_id = :category_id', ['category_id' => $id['id']]);
                $all_products[] = $query->all();
            }
        }
        else
        {
            $query = (new Query())
                ->select('id')
                ->from('store_category')
                ->where('label = :label', ['label' => $sub_category]);
            $category_id = $query->all();

            if (!isset($category_id[0]['id']))
                return 'None';
            
            $query = (new Query())
                ->select('')
                ->from('store_product')
                ->where('category_id = :category_id', ['category_id' => $category_id[0]['id']]);
            $all_products[] = $query->all();
        }

        $random_numbers = array();
        for ($i = 0; $i < 4; $i++)
        {
            $random_products[$i] = $all_products[0][rand(0, count($all_products))]['label'];
        }

        return $random_products;
    }
}
