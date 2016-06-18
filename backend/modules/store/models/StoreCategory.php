<?php

namespace backend\modules\store\models;

use backend\components\BackModel;
use backend\widgets\pageSize\Widget;
use common\models\Language;
use notgosu\yii2\modules\metaTag\components\MetaTagBehavior;
use omgdef\multilingual\MultilingualBehavior;
use sammaye\extensions\NestedSetBehavior;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\data\BaseDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
 *
 * @property StoreCategoryLang[] $storeCategoryLangs
 */
class StoreCategory extends \backend\components\BackModel
{
    /**
     * This parameter need to append newly created node to some parent node
     *
     * @var
     */
    public $parentCategory;

    /**
     * Category to compare after node updated
     *
     * @var
     */
    public $oldParentCategory;

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
            [['label', 'alias'], 'required'],
            ['alias', 'unique'],
            [['visible'], 'integer'],
            [['description'], 'string'],
            ['parentCategory', 'safe'],
            [['label', 'alias', 'label_parent_case'], 'string', 'max' => 255],
            [['id', 'label', 'alias', 'description', 'visible'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributes = [
            'id' => Yii::t('app', 'ID'),
            'lft' => Yii::t('app', 'Lft'),
            'rgt' => Yii::t('app', 'Rgt'),
            'level' => Yii::t('app', 'Level'),
            'parentCategory' => Yii::t('app', 'Родительская категория'),
            'label' => Yii::t('app', 'Название'),
            'alias' => Yii::t('app', 'Алиас'),
            'description' => Yii::t('app', 'Описание'),
            'visible' => Yii::t('app', 'Отображать'),
            'label_parent_case' => 'Название категории в родительском падеже'
        ];

        return $this->prepareAttributeLabels($attributes);
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = static::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['lft'=> SORT_ASC]]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'label', $this->label]);
        $query->andFilterWhere(['like', 'alias', $this->alias]);
        $query->andFilterWhere(['visible' => $this->visible]);

        return $dataProvider;
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
                [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => 'modified',
                    'updatedAtAttribute' => 'modified',
                    'value' => function () {
                        return date("Y-m-d H:i:s");
                    }
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
                'seo' => [
                    'class' => MetaTagBehavior::className(),
                    'defaultLanguage' => Language::getDefaultLang()->locale
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
            'label', 'description', 'label_parent_case'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function find($withRoot = false)
    {
        return $withRoot
            ? parent::find()->with(['translations'])
            : parent::find()->with(['translations'])->where(static::tableName().'.id > :id', [':id' => 1]);
    }

    public function afterFind()
    {
        parent::afterFind();

        if ($this->id > 1) {
            $this->parentCategory = $this->findParentCategory();
            $this->oldParentCategory = $this->parentCategory;
        }
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    protected function findParentCategory()
    {
        $parent = (new Query())
            ->select('id')
            ->from(static::tableName())
            ->where('id > :id', [':id' => 1])
            ->andWhere("[[$this->leftAttribute]] < :left", [':left' => $this->{$this->leftAttribute}])
            ->andWhere("[[$this->rightAttribute]] > :right", [':right' => $this->{$this->rightAttribute}])
            ->orderBy([$this->rightAttribute => SORT_ASC])
            ->one();

        return $parent ? $parent['id'] : 1;
    }

    /**
     * @inheritdoc
     */
    public function save($runValidation = true, $attrNames = [])
    {
        $root = static::find(true)->where('id=:id', [':id' => $this->parentCategory])->one();
        if ($this->isNewRecord) {
            return $this->appendTo($root);
        } else {
            $this->saveNode($runValidation, $attrNames);

            if ($this->isMadeChildOfTheRoot($root) || $this->isChildMoved($root)) {
                $this->moveAsLast($root);
            }

            return true;
        }
    }


    /**
     * @param $element
     *
     * @return bool
     */
    protected function isChildMoved($element)
    {

        if ($this->parentCategory != $this->oldParentCategory && $element->level != 1) {
            return true;
        }

        return false;
    }

    /**
     * @param $element
     *
     * @return bool
     */
    protected function isMadeChildOfTheRoot($element)
    {
        if ($this->parentCategory != $this->oldParentCategory && $element->level == 1) {
            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(StoreCategoryLang::className(), ['model_id' => 'id']);
    }

    public function getParentCategoryName()
    {
        if ($this->parentCategory == 1) {
            return null;
        } else {
            $parentCategory = self::find()->where('id=:id', [':id' => $this->parentCategory])->one();

            return $parentCategory ? $parentCategory->label : null;
        }
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
                'label',
                'alias',
                'description',
                [
                    'attribute' => 'parentCategory',
                    'value' => $this->getParentCategoryName()
                ],
                'visible:boolean',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                [
                    'attribute' => 'label',
                    'value' => function ($data) {
                            return $data->level > 2
                                ? str_repeat('--', $data->level).' '.$data->label
                                : $data->label;
                        }
                ],
                'alias',
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
                'options' => [
                    'class' => 's_name'
                ]
            ],
            'alias' => [
                'type' => Form::INPUT_TEXT,
                'options' => [
                    'class' => 's_alias'
                ]
            ],
            'parentCategory' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $this->getParentCategoriesList()
            ],
            'label_parent_case' => [
                'type' => Form::INPUT_TEXT,
                'hint' => 'Для SEO. Например "Блокнотов", "Аксессуаров"'
            ],
            'description' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => [
                    'class' => 'span6',
                    'rows' => 5
                ]
            ],
            'visible' => [
                'type' => Form::INPUT_CHECKBOX,
            ],

        ];
    }

    /**
     * @return array
     */
    public function getParentCategoriesList()
    {
        $categoryTree = self::find(true)->where(['level' => [1, 2]])->orderBy('lft')->all();
        $items = [];

        foreach ($categoryTree as $category) {
            if ($category->id != $this->id) {
                $items[$category->id] = $category->id == 1
                    ?'Выберите родительскую категорию'
                    : $category->label;
            }

        }

        return $items;
    }

    /**
     * @inheritdoc
     */
    public function getColCount()
    {
        return 2;
    }

    /**
     * @return string
     */
    public function getBreadCrumbRoot()
    {
        return 'Категории';
    }

    /**
     * @return array
     */
    public static function getCategorySortUrl()
    {
        return ['/store/store-category/sort-category'];
    }

    /**
     * @inheritdoc
     */
    public function getButtonsList(BaseDataProvider $dataProvider, BackModel $model)
    {
        $buttons = '';
        $buttons .= Widget::widget(['dataProvider' => &$dataProvider]);
        $buttons .= Html::a('Cоздать', ['create'], ['class' => 'create pull-left bottom-margin btn btn-success']);
        $buttons .= Html::a('Cортировать', ['list'], ['class' => 'sort pull-left bottom-margin left-margin btn btn-info']);

        return $buttons;
    }
}
