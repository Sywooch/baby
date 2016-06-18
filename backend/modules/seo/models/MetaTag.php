<?php

namespace backend\modules\seo\models;

use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%meta_tag}}".
 *
 * @property integer $id
 * @property string $meta_tag_name
 * @property string $meta_tag_http_equiv
 * @property string $meta_tag_default_value
 * @property integer $is_active
 * @property integer $position
 * @property string $created
 * @property string $modified
 *
 * @property MetaTagContent[] $metaTagContents
 */
class MetaTag extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%meta_tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['meta_tag_name'], 'required'],
            [['meta_tag_default_value'], 'string'],
            [['is_active', 'position'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['meta_tag_name', 'tag_description', 'meta_tag_http_equiv'], 'string', 'max' => 255],
            [['id', 'meta_tag_name', 'meta_tag_http_equiv', 'meta_tag_default_value', 'is_active', 'position', 'created', 'modified'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'meta_tag_name' => 'Название',
            'tag_description' => 'Описание тега',
            'meta_tag_http_equiv' => 'HTTP-заголовок для данного тега(http_equiv)',
            'meta_tag_default_value' => 'Значение по-умолчанию',
            'is_active' => 'Регистрировать автоматически',
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
                [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => 'created',
                    'updatedAtAttribute' => 'modified',
                    'value' => function () {
                        return date("Y-m-d H:i:s");
                    }
                ],
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaTagContents()
    {
        return $this->hasMany(MetaTagContent::className(), ['meta_tag_id' => 'id']);
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
                'defaultOrder' => [
                    'position' => SORT_DESC
                ]
            ]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['like', 'meta_tag_name', $this->meta_tag_name]);
            $query->andFilterWhere(['like', 'tag_description', $this->tag_description]);
            $query->andFilterWhere(['like', 'meta_tag_http_equiv', $this->meta_tag_http_equiv]);
            $query->andFilterWhere(['meta_tag_default_value' => $this->meta_tag_default_value]);
            $query->andFilterWhere(['is_active' => $this->is_active]);
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
                'meta_tag_name',
                'meta_tag_http_equiv',
                'meta_tag_default_value',
                'is_active',
                'position',
                'created',
                'modified',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                'meta_tag_name',
                'tag_description',
                [
                    'attribute' => 'is_active',
                    'format' => 'boolean',
                    'filter' => ['Нет', 'Да']
                ],
                [
                    'class' => \yii\grid\ActionColumn::className(),
                    'template' => '{view} {update} '
                ]
            ];
    }

    /**
    * @return array
    */
    public function getFormRows()
    {
        return [
            
            'meta_tag_name' => [
                'type' => Form::INPUT_TEXT,
            ],
            'meta_tag_http_equiv' => [
                'type' => Form::INPUT_TEXT,
            ],
            'meta_tag_default_value' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => ['rows' => 5]
            ],
            'tag_description' => [
                'type' => Form::INPUT_TEXTAREA,
                'hint' => 'Используеться только в админке'
            ],
            'is_active' => [
                'type' => Form::INPUT_CHECKBOX,
                'hint' => 'Включайте эту опцию только для SEO-тегов, которые автоматически должны регистрироваться в head'
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
        return 'Теги';
    }
}
