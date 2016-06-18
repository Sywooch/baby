<?php

namespace backend\modules\common\models;

use metalguardian\fileProcessor\behaviors\UploadBehavior;
use metalguardian\fileProcessor\helpers\FPM;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%cart_video}}".
 *
 * @property integer $id
 * @property integer $mp4_video_id
 * @property integer $webm_video_id
 * @property string $created
 * @property string $modified
 */
class CartVideo extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart_video}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mp4_video_id', 'webm_video_id'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['id', 'mp4_video_id', 'webm_video_id', 'created', 'modified'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mp4_video_id' => 'Mp4 Видео',
            'webm_video_id' => 'Webm Видео',
            'created' => 'Создано',
            'modified' => 'Обновлено',
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
                'mp4' => [
                    'class' => UploadBehavior::className(),
                    'attribute' => 'mp4_video_id',
                    'validator' => [
                        'extensions' => ['mp4'],
                        'skipOnEmpty' => false,
                    ]
                ],
                'webm' => [
                    'class' => UploadBehavior::className(),
                    'attribute' => 'webm_video_id',
                    'validator' => [
                        'extensions' => ['webm', 'ogg'],
                        'skipOnEmpty' => false,
                    ]
                ],
            ]
        );
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
            $query->andFilterWhere(['mp4_video_id' => $this->mp4_video_id]);
            $query->andFilterWhere(['webm_video_id' => $this->webm_video_id]);
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
                'mp4_video_id',
                'webm_video_id',
                'created',
                'modified',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                [
                    'attribute' => 'mp4_video_id',
                    'format' => 'raw',
                    'value' => function (self $data) {
                        $src = FPM::originalSrc($data->mp4_video_id);
                        return Html::a($src, $src);
                    },
                ],
                [
                    'attribute' => 'webm_video_id',
                    'format' => 'raw',
                    'value' => function (self $data) {
                        $src = FPM::originalSrc($data->webm_video_id);
                        return Html::a($src, $src);
                    },
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
            
            'mp4_video_id' => [
                'type' => Form::INPUT_FILE,
            ],
            'webm_video_id' => [
                'type' => Form::INPUT_FILE,
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
        return 'Видео в корзине';
    }
}
