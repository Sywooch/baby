<?php

namespace backend\modules\common\models;

use common\models\Language;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%footer_link}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $column
 * @property integer $visible
 * @property integer $position
 * @property string $created
 * @property string $modified
 *
 * @property FooterLinkLang[] $footerLinkLangs
 */
class FooterLink extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%footer_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'url'], 'required'],
            ['url', 'url', 'defaultScheme' => 'http'],
            [['column', 'visible', 'position'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['label', 'url'], 'string', 'max' => 255],
            [['id', 'label', 'url', 'column', 'visible', 'position', 'created', 'modified'], 'safe', 'on' => 'search']
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
            'label' => 'Название',
            'url' => 'Ссылка',
            'column' => 'Столбец',
            'visible' => 'Отображать',
            'position' => 'Позиция',
            'created' => 'Создано',
            'modified' => 'Обновлено',
        ];

        return $this->prepareAttributeLabels($attributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFooterLinkLangs()
    {
        return $this->hasMany(FooterLinkLang::className(), ['model_id' => 'id']);
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
            $query->andFilterWhere(['like', 'label', $this->label]);
            $query->andFilterWhere(['like', 'url', $this->url]);
            $query->andFilterWhere(['column' => $this->column]);
            $query->andFilterWhere(['visible' => $this->visible]);
            $query->andFilterWhere(['position' => $this->position]);
            $query->andFilterWhere(['created' => $this->created]);
            $query->andFilterWhere(['modified' => $this->modified]);
    
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
                    'tableName' => FooterLinkLang::className(),
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
            'label', 'url'
        ];
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
                'url',
                'column',
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
                [
                    'attribute' => 'column',
                    'filter' => [1 => 'Первый', 2 => 'Второй'],
                    'value' => function (self $data) {
                        return $data->column == 1 ? 'Первый' : ($data->column == 2 ? 'Второй' : null);
                    }
                ],
                'url',
                [
                    'attribute' => 'visible',
                    'filter' => ['Да', 'Нет'],
                    'value' => function (self $data) {
                        return $data->visible == 0 ? 'Нет' : 'Да';
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
            'column' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => [1 => 'Первый', 2 => 'Второй']
            ],
            'label' => [
                'type' => Form::INPUT_TEXT,
            ],
            'url' => [
                'type' => Form::INPUT_TEXT,
            ],
            'visible' => [
                'type' => Form::INPUT_CHECKBOX,
            ]

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
        return 'Ссылки в футере';
    }
}
