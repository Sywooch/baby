<?php

namespace frontend\modules\common\models;

use frontend\models\DummyModel;
use frontend\modules\blog\models\BlogArticle;
use frontend\modules\blog\models\BlogRubric;
use himiklab\sitemap\behaviors\SitemapBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%page_seo}}".
 *
 * @property integer $id
 * @property string $description
 */
class PageSeo extends \frontend\components\FrontModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_seo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Для какой страницы SEO',
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
                'sitemap' => [
                    'class' => SitemapBehavior::className(),
                    'scope' => function ($model) {
                        /** @var \yii\db\ActiveQuery $model */
                        $model->andWhere(['id' => 6]);//Blog model
                    },
                    'dataClosure' => function ($model) {
                        /** @var self $model */
                        return [
                            'loc' => Url::to(BlogRubric::getBlogRoute(), true),
                            'lastmod' => time(),
                            'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        ];
                    }
                ],
            ]
        );
    }
}
