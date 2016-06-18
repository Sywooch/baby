<?php

namespace backend\modules\common\models;

use common\models\Currency;
use common\models\Language;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%certificate}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $price
 * @property integer $color
 * @property integer $visible
 * @property integer $position
 * @property string $created
 * @property string $modified
 */
class Certificate extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%certificate}}';
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
    public function rules()
    {
        return [
            [['label', 'price'], 'required'],
            [['price'], 'number'],
            [['color', 'visible', 'position'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['label'], 'string', 'max' => 255],
            [['id', 'label', 'price', 'color', 'visible', 'position', 'created', 'modified'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributes = [
            'id' => 'ID',
            'label' => 'Название',
            'price' => 'Цена',
            'color' => 'Цвет',
            'visible' => 'Отображать',
            'position' => 'Позиция',
            'created' => 'Создано',
            'modified' => 'Обновлено',
        ];

        return $this->prepareAttributeLabels($attributes);
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
                    'tableName' => CertificateLang::className(),
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
            'label'
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
            'sort'=> ['defaultOrder' => ['position'=> SORT_DESC]]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['like', 'label', $this->label]);
            $query->andFilterWhere(['price' => $this->price]);
            $query->andFilterWhere(['color' => $this->color]);
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
                'label',
                'price',
                [
                    'attribute' => 'color',
                    'value' => \common\models\Certificate::getColor($this->color)
                ],
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
                'label',
                'price',
                [
                    'attribute' => 'color',
                    'filter' => \common\models\Certificate::getColors(),
                    'value' => function (self $data) {
                        return \common\models\Certificate::getColor($data->color);
                    }
                ],
                'created',
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
            'price' => [
                'type' => Form::INPUT_TEXT,
                'hint' => 'цена указана для текущей главной валюты "'.Currency::getDefaultCurrencyCode().'"'
            ],
            'color' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \common\models\Certificate::getColors()
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
        return 'Сертификаты';
    }
}
