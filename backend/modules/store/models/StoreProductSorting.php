<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\models;

use backend\components\BackModel;
use common\models\EntityToFile;
//use common\models\StoreCategory;
use common\models\StoreProductType as CSPT;
use metalguardian\fileProcessor\helpers\FPM;
use Yii;
use yii\data\BaseDataProvider;
use yii\helpers\Html;

/**
 * Class StoreProductTop
 * @package backend\modules\store\models
 */
class StoreProductSorting extends BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'type_id',
                    'category_id',
                    'label',
                    'alias',
                    'announce',
                    'content',
                    'sku',
                    'price',
                    'visible',
                    'status',
                    'position',
                    'created',
                    'modified'
                ],
                'safe',
                'on' => 'search'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributes =  [
            'id' => Yii::t('app', 'ID'),
            'type_id' => Yii::t('app', 'Тип'),
            'category_id' => Yii::t('app', 'Категория'),
            'label' => Yii::t('app', 'Название'),
            'alias' => Yii::t('app', 'Ссылка'),
            'announce' => Yii::t('app', 'Краткое описание'),
            'content' => Yii::t('app', 'Описание'),
            'sku' => Yii::t('app', 'Артикул'),
            'price' => Yii::t('app', 'Цена'),
            'visible' => Yii::t('app', 'Отображать'),
            'position' => Yii::t('app', 'Позиция'),
            'created' => Yii::t('app', 'Создано'),
            'modified' => Yii::t('app', 'Обновлено'),
            'show_on_main_page' => Yii::t('app', 'Показывать на главной'),
            'is_new' => 'Новинка',
            'is_top_50' => 'ТОП-50',
            'is_top_50_category' => 'ТОП-50 категория',
            'video_id' => 'ID видео (Vimeo или Youtube)',
            'status' => 'Наличие'
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
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(StoreProductType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainImage()
    {
        return $this->hasOne(EntityToFile::className(), ['entity_model_id' => 'id'])
            ->where('entity_model_name = :emn', [':emn' => 'StoreProduct'])
            ->joinWith('file')
            ->orderBy('entity_to_file.position DESC');

    }

    public function getButtonsList(BaseDataProvider $dataProvider, BackModel $model)
    {
        return '';
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
                    'attribute' => 'type_id',
                    'value' => $this->type->label

                ],
                [
                    'attribute' => 'category_id',
                    'value' => $this->category->label

                ],
                'label',
                'alias',
                'announce',
                'content',
                'sku',
                'price',
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
                    'attribute' => 'type_id',
                    'filter' => CSPT::getProductTypes(),
                    'value' => function (self $data) {
                        return $data->type->label;
                    }

                ],
                [
                    'attribute' => 'category_id',
                    'filter' => \common\models\StoreCategory::getCategoriesList(),
                    'value' => function (self $data) {
                        return $data->category->label;
                    }

                ],
                [
                    'attribute' => 'label',
                    'format' => 'raw',
                    'value' => function (self $data) {
                        return Html::a($data->label, StoreProduct::getUpdateUrl(['id' => $data->id]));
                    }
                ],
                'alias',
                'sku',
                [
                    'attribute' => 'price',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                [
                    'attribute' => 'status',
                    'filter' => \common\models\StoreProduct::getStatusList(),
                    'value' => function (self $data) {
                        return \common\models\StoreProduct::getStatus($data->status);
                    }

                ],
                [
                    'label' => 'Изображение',
                    'format' => 'raw',
                    'value' => function (self $data) {
                        $mainImage = $data->mainImage;
                        return $mainImage
                            ? FPM::image($data->mainImage->file_id, 'product', 'smallPreview')
                            : null;
                    }
                ],
                [
                    'class' => \yii\grid\ActionColumn::className(),
                    'template' => '{remove}',
                    'buttons' => [
                        'remove' => function ($url) {
                            return Html::a(
                                Html::tag('span', null, ['class' => 'glyphicon glyphicon-remove']),
                                $url,
                                [
                                    'data-pjax' => 0
                                ]
                            );
                        }
                    ]
                ]
            ];
    }
}
