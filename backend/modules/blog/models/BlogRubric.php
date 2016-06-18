<?php

namespace backend\modules\blog\models;

use common\models\Language;
use notgosu\yii2\modules\metaTag\components\MetaTagBehavior;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%blog_rubric}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $alias
 * @property integer $position
 * @property string $created
 * @property string $modified
 *
 * @property BlogRubricLang[] $blogRubricLangs
 */
class BlogRubric extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog_rubric}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'alias'], 'required'],
            [['position'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['label'], 'string', 'max' => 255],
            [['id', 'label', 'position', 'created', 'modified'], 'safe', 'on' => 'search']
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
            'alias' => 'Ссылка',
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
                    'tableName' => BlogRubricLang::className(),
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
            'label'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogRubricLangs()
    {
        return $this->hasMany(BlogRubricLang::className(), ['model_id' => 'id']);
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
        return 'Рубрики блога';
    }
}
