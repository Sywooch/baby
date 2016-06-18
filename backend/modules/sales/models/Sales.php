<?php

namespace backend\modules\sales\models;

use common\models\Language;
use metalguardian\fileProcessor\behaviors\UploadBehavior;
use metalguardian\fileProcessor\helpers\FPM;
use notgosu\yii2\modules\metaTag\components\MetaTagBehavior;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%sales}}".
 *
 * @property integer $id
 * @property integer $image_id
 * @property integer $image_bottom_id
 * @property string $label
 * @property string $alias
 * @property string $content
 * @property string $description
 * @property integer $type
 * @property integer $visible
 * @property integer $position
 * @property string $created
 * @property string $modified
 *
 * @property SalesLang[] $salesLangs
 */
class Sales extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sales}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'visible', 'position'], 'integer'],
            [['description'], 'required'],
            [['content', 'description'], 'string'],
            [['created', 'modified'], 'safe'],
            [['label', 'alias'], 'string', 'max' => 255],
            [
                ['id', 'image_id', 'label', 'content', 'type', 'visible', 'position', 'created', 'modified'],
                'safe',
                'on' => 'search'
            ]
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
                    'tableName' => SalesLang::className(),
                    'attributes' => $this->getLocalizedAttributes()
                ],
                'image' => [
                    'class' => UploadBehavior::className(),
                    'attribute' => 'image_id',
                    'image' => true,
                    'required' => false
                ],
                'image_bottom' => [
                    'class' => UploadBehavior::className(),
                    'attribute' => 'image_bottom_id',
                    'image' => true,
                    'required' => false
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
            'label',
            'content',
            'description'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributes = [
            'id' => 'ID',
            'image_id' => 'Image ID',
            'label' => 'Заголовок',
            'alias' => 'Ссылка',
            'description' => 'Краткое описание акции',
            'content' => 'Текст акции',
            'type' => 'Тип шаблона',
            'visible' => 'Отображать',
            'position' => 'Позиция',
            'created' => 'Создано',
            'modified' => 'Обновлено',
            'image_id' => 'Изображение вверху страницы',
            'image_bottom_id' => 'Изображение внизу страницы',
        ];

        return $this->prepareAttributeLabels($attributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesLangs()
    {
        return $this->hasMany(SalesLang::className(), ['model_id' => 'id']);
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = static::find();
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'sort' => [
                    'defaultOrder' => ['position' => SORT_DESC]
                ]
            ]
        );

        if (!empty($params)) {
            $this->load($params);
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['image_id' => $this->image_id]);
        $query->andFilterWhere(['like', 'label', $this->label]);
        $query->andFilterWhere(['content' => $this->content]);
        $query->andFilterWhere(['type' => $this->type]);
        $query->andFilterWhere(['visible' => $this->visible]);
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
                'image_id',
                'label',
                'content',
                'type',
                'visible:boolean',
                'position',
                'created',
                'modified',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                [
                    'attribute' => 'type',
                    'filter' => \common\models\Sales::getTypeList(),
                    'value' => function (self $data) {
                        return \common\models\Sales::getType($data->type);
                    }
                ],
                'label',
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
            'type' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \common\models\Sales::getTypeList()
            ],
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
            'description' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => ['rows' => 5],
                'hint' => 'Отображаеться на баннере акции'
            ],
            'content' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \vova07\imperavi\Widget::classname(),
                'options' => [
                    'model' => $this,
                    'attribute' => 'content',
                    'settings' => [
                        'buttons' => [
                            'formatting',
                            'bold',
                            'link',
                            'image',
                            'unorderedlist'
                        ],
                        'lang' => 'ru',
                        'minHeight' => 250,
                        'pastePlainText' => true,
                        'buttonSource' => true,
                        'imageUpload' => Url::to(['/sales/sales/image-upload']),
                    ]
                ],
                'hint' => 'Отображаеться на странице открытой акции, если не заполнить, то ссылки на страницу просмотра этой акции не будет'
            ],
            'imagePreview' => [
                'type' => Form::INPUT_RAW,
                'value' => function ($this) {
                    return $this->isNewRecord
                        ? null
                        : static::getImagePreview(
                            $this->image_id,
                            'sales',
                            'adminPreview',
                            Sales::className(),
                            'image_id'
                        );
                },
            ],
            'image_id' => [
                'type' => Form::INPUT_FILE,
            ],
            'imagePreviewBottom' => [
                'type' => Form::INPUT_RAW,
                'value' => function ($this) {
                    return $this->isNewRecord
                        ? null
                        : static::getImagePreview(
                            $this->image_bottom_id,
                            'sales',
                            'adminPreview',
                            Sales::className(),
                            'image_bottom_id'
                        );
                },
            ],
            'image_bottom_id' => [
                'type' => Form::INPUT_FILE,
            ],
            'visible' => [
                'type' => Form::INPUT_CHECKBOX,
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
        return 'Акции';
    }
}
