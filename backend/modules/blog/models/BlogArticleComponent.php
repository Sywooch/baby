<?php

namespace backend\modules\blog\models;

use metalguardian\fileProcessor\helpers\FPM;
use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%blog_article_component}}".
 *
 * @property integer $id
 * @property integer $blog_article_id
 * @property integer $type
 * @property string $content
 * @property integer $visible
 *
 * @property BlogArticle $blogArticle
 * @property BlogArticleComponentLang[] $blogArticleComponentLangs
 */
class BlogArticleComponent extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog_article_component}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['blog_article_id', 'type'], 'required'],
            [['blog_article_id', 'type', 'visible'], 'integer'],
            [['content'], 'string'],
            [['id', 'blog_article_id', 'type', 'content', 'visible'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'blog_article_id' => 'Запись',
            'type' => 'Тип виджета',
            'content' => 'Содержимое',
            'visible' => 'Отображать',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogArticle()
    {
        return $this->hasOne(BlogArticle::className(), ['id' => 'blog_article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogArticleComponentLangs()
    {
        return $this->hasMany(BlogArticleComponentLang::className(), ['model_id' => 'id']);
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
            $query->andFilterWhere(['blog_article_id' => $this->blog_article_id]);
            $query->andFilterWhere(['type' => $this->type]);
            $query->andFilterWhere(['content' => $this->content]);
            $query->andFilterWhere(['visible' => $this->visible]);
    
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
                'blog_article_id',
                'type',
                'content',
                'visible:boolean',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                'blog_article_id',
                'type',
                                
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
            
            'blog_article_id' => [
                'type' => Form::INPUT_TEXT,
            ],
            'type' => [
                'type' => Form::INPUT_TEXT,
            ],
            'content' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => ['rows' => 5]
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
        return 2;
    }

    /**
    * @return string
    */
    public function getBreadCrumbRoot()
    {
        return 'BlogArticleComponent';
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        parent::beforeDelete();
            if ($this->type == \common\models\BlogArticle::CONTENT_TYPE_IMAGE) {
                FPM::deleteFile($this->content);
            }

        return true;
    }
}
