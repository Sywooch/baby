<?php

namespace backend\modules\banner\models;

use backend\modules\store\models\StoreCategory;
use common\models\Banner;
use common\models\Language;
use metalguardian\fileProcessor\behaviors\UploadBehavior;
use metalguardian\fileProcessor\helpers\FPM;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
 * @property integer $is_default
 *
 * @property StoreCategory $category
 * @property FpmFile $image
 * @property BannerLang[] $bannerLangs
 */
class CategoryBanner extends \backend\components\BackModel
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
    public function rules()
    {
        return [
            [['type', 'banner_location', 'is_default', 'category_id', 'visible', 'position'], 'integer'],
            ['type', 'default', 'value' => Banner::TYPE_CATEGORY],
            ['banner_location', 'default', 'value' => Banner::LOCATION_TOP],
            [['label', 'content'], 'required'],
            [['label', 'small_label'], 'string', 'max' => 255],
            [['content'], 'string', 'max' => 500],
            [['href'], 'string', 'max' => 300],
            ['href', 'url', 'defaultScheme' => 'http'],
            [['id', 'type', 'category_id', 'banner_location', 'label', 'small_label', 'content', 'href', 'image_id', 'visible', 'position'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return parent::find()->with(['translations']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributes = [
            'id' => 'ID',
            'type' => 'Тип',
            'category_id' => 'Категория',
            'banner_location' => 'Расположение',
            'label' => 'Заголовок',
            'small_label' => 'Подзаголовок',
            'content' => 'Текст',
            'href' => 'Ссылка',
            'image_id' => 'Изображение',
            'visible' => 'Отображать',
            'position' => 'Позиция',
            'is_default' => 'Дефолтный баннер'
        ];

        return $this->prepareAttributeLabels($attributes);

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
    public function getImage()
    {
        return $this->hasOne(FpmFile::className(), ['id' => 'image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBannerLangs()
    {
        return $this->hasMany(BannerLang::className(), ['model_id' => 'id']);
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
                    'tableName' => BannerLang::tableName(),
                    'attributes' => $this->getLocalizedAttributes()
                ],
                'image' => [
                    'class' => UploadBehavior::className(),
                    'attribute' => 'image_id',
                    'image' => true
                ]
            ]
        );
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        $this->type = Banner::TYPE_CATEGORY;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getLocalizedAttributes()
    {
        return ['label', 'small_label', 'content', 'href'];
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        parent::beforeDelete();

        FPM::deleteFile($this->image_id);

        return true;
    }

    /**
    * @param $params
    *
    * @return ActiveDataProvider
    */
    public function search($params)
    {
        $query = static::find()->where(['type' => Banner::TYPE_CATEGORY]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['category_id' => $this->category_id]);
            $query->andFilterWhere(['banner_location' => $this->banner_location]);
            $query->andFilterWhere(['like', 'label', $this->label]);
            $query->andFilterWhere(['like', 'small_label', $this->small_label]);
            $query->andFilterWhere(['like', 'content', $this->content]);
            $query->andFilterWhere(['like', 'href', $this->href]);
            $query->andFilterWhere(['visible' => $this->visible]);
            $query->andFilterWhere(['position' => $this->position]);
    
        return $dataProvider;
    }

    /**
    * @param bool $viewAction
    *
    * @return array
    */
    public function getViewColumns($viewAction = false)
    {
        return $viewAction
            ? [
                'id',
                'type',
                'category_id',
                'banner_location',
                'label',
                'small_label',
                'content',
                'href',
                'image_id',
                'visible:boolean',
                'position',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                'label',
                [
                    'attribute' => 'banner_location',
                    'filter' => Banner::getLocationList(),
                    'value' => function ($data) {
                            return Banner::getLocation($data->banner_location);
                        }
                ],
                [
                    'attribute' => 'category_id',
                    'filter' => $this->getCategoryList(),
                    'value' => function ($data) {
                            return $data->category->label;
                        }
                ],
                [
                    'attribute' => 'Изображение',
                    'format' => 'raw',
                    'value' => function ($data) {
                            return $data->image_id
                                ? Html::img(
                                    FPM::src(
                                        $data->image_id,
                                        'banner',
                                        $data->banner_location == Banner::LOCATION_TOP
                                        ? 'adminPreview'
                                        : 'categoryBottom'
                                    )
                                )
                                : null;
                        }
                ],

                [
                    'class' => \yii\grid\ActionColumn::className()
                ]
            ];
    }

    /**
    * @return array
    */
    public function getFormRows()
    {
        return [
            
            'label' => [
                'type' => Form::INPUT_TEXT,
            ],
            'small_label' => [
                'type' => Form::INPUT_TEXT,
                'hint' => 'Только для баннеров внизу страницы'
            ],
            'category_id' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => static::getCategoryList()
            ],
            'banner_location' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => Banner::getLocationList(),
            ],
            'content' => [
                'type' => Form::INPUT_TEXTAREA,
            ],
            'href' => [
                'type' => Form::INPUT_TEXT,
            ],
            'imagePreview' => [
                'type' => Form::INPUT_RAW,
                'value' => function ($this) {
                        return $this->isNewRecord
                            ? null
                            : Html::img(
                                FPM::src(
                                    $this->image_id,
                                    'banner',
                                    $this->banner_location == Banner::LOCATION_TOP
                                        ? 'adminPreview'
                                        : 'categoryBottom'
                                )
                            );
                },
            ],
            'image_id' => [
                'type' => Form::INPUT_FILE,
                'hint' => 'Для баннеров внизу страницы изображение будет показано слева от желтой рамки'
            ],
            'visible' => [
                'type' => Form::INPUT_CHECKBOX,
            ],
            'is_default' => [
                'type' => Form::INPUT_CHECKBOX,
                'hint' => 'Выводить этот баннер внизу страницы для всех категорий, в которых не задан баннер'
            ],
        ];
    }

    /**
    * @inheritdoc
    */
    public function getColCount()
    {
        return 1;
    }

    /**
    * @return string
    */
    public function getBreadCrumbRoot()
    {
        return 'Баннеры в категориях';
    }

    /**
     * @return array
     */
    public static function getCategoryList()
    {
        $categoryTree = StoreCategory::find(false)->orderBy('lft')->all();
        $items = [];

        foreach ($categoryTree as $category) {
            $items[$category->id] = $category->level > 2
                ? str_repeat('--', $category->level). $category->label
                : $category->label;
        }

        return $items;
    }
}
