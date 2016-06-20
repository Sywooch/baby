<?php

namespace backend\modules\staticPage\models;

use common\models\Language;
use notgosu\yii2\modules\metaTag\components\MetaTagBehavior;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "static_page".
 *
 * @property integer $id
 * @property string $label
 * @property string $alias
 * @property string $content
 * @property integer $visible
 * @property integer $position
 * @property string $created
 * @property string $modified
 */
class StaticPage extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'static_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'alias', 'content'], 'required'],
            [['content'], 'string'],
            [['visible', 'position'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['label', 'alias'], 'string', 'max' => 255],
            [['id', 'label', 'alias', 'content', 'visible', 'position', 'created', 'modified'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Заголовок',
            'alias' => 'Алиас',
            'content' => 'Содержимое',
            'visible' => 'Отображать',
            'position' => 'Позиция',
            'created' => 'Создано',
            'modified' => 'Обновлено',
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
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['like', 'label', $this->label]);
            $query->andFilterWhere(['like', 'alias', $this->alias]);
            $query->andFilterWhere(['content' => $this->content]);
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
                'alias',
                'content',
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
                'alias',
                                                [
                    'attribute' => 'position',
                    'headerOptions' => ['class' => 'col-sm-1']
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
            'content' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \vova07\imperavi\Widget::classname(),
                'options' => [
                    'model' => $this,
                    'attribute' => 'content',
                    'settings' => [
                        'lang' => 'ru',
                        'minHeight' => 250,
                        'pastePlainText' => true,
                        'buttonSource' => true,
                        'replaceDivs' => false,
                        'paragraphize' => false,
                        'imageManagerJson' => Url::to(['/store/store-product/images-get']),
                        'imageUpload' => Url::to(['/store/store-product/image-upload']),
                        'plugins' => [
//                                            'clips',
                            'imagemanager',
                            'fullscreen'
                        ]
                    ]
                ]
            ],
            'visible' => [
                'type' => Form::INPUT_CHECKBOX,
            ],
            'position' => [
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
        return 'Статические страницы';
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
                'seo' => [
                    'class' => MetaTagBehavior::className(),
                    'defaultLanguage' => Language::getDefaultLang()->locale
                ],
            ]
        );
    }
}
