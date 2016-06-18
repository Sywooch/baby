<?php

namespace backend\modules\common\models;

use backend\modules\store\models\StoreCategory;
use backend\modules\store\models\StoreProduct;
use common\models\Language;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
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
 */
class SeoFooterLinks extends \backend\components\BackModel
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
            [['label', 'link'], 'required'],
            ['link', 'url', 'defaultScheme' => 'http'],
            [['created', 'modified'], 'safe'],
            [['label', 'link'], 'string', 'max' => 255],
            [['id', 'category_id', 'label', 'link', 'position', 'created', 'modified'], 'safe', 'on' => 'search']
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
            'category_id' => 'Категория',
            'label' => 'Название',
            'link' => 'Ссылка',
            'position' => 'Позиция',
            'created' => 'Создано',
            'modified' => 'Обновлено',
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
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => 'created',
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
                    'tableName' => SeoFooterLinksLang::className(),
                    'attributes' => $this->getLocalizedAttributes()
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
            'label', 'link'
        ];
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
            'sort' => [
                'defaultOrder' => ['position' => SORT_DESC]
            ]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['category_id' => $this->category_id]);
            $query->andFilterWhere(['like', 'label', $this->label]);
            $query->andFilterWhere(['like', 'link', $this->link]);
            $query->andFilterWhere(['position' => $this->position]);
            $query->andFilterWhere(['created' => $this->created]);
            $query->andFilterWhere(['modified' => $this->modified]);
    
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
                [
                    'attribute' => 'category_id',
                    'value' => $this->category->label

                ],
                'label',
                'link',
//                'position',
                'created',
                'modified',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                [
                    'attribute' => 'category_id',
                    'filter' => StoreProduct::getCategoriesList(false),
                    'value' => function (self $data) {
                        return $data->category_id ? $data->category->label : 'Главная';
                    }

                ],
                'label',
                'link',
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
            'category_id' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => StoreProduct::getCategoriesList(false),
                'options' => [
                    'prompt' => 'Главная'
                ]
            ],
            'label' => [
                'type' => Form::INPUT_TEXT,
            ],
            'link' => [
                'type' => Form::INPUT_TEXT,
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
        return 'Ссылки перелинковки в футере';
    }
}
