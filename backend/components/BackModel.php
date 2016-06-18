<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\components;

use backend\modules\banner\models\BlogBanner;
use backend\modules\blog\models\BlogRubric;
use backend\modules\common\models\Certificate;
use backend\modules\common\models\FooterLink;
use backend\modules\common\models\PayAndDelivery;
use backend\modules\sales\models\Sales;
use backend\modules\seo\models\MetaTag;
use backend\modules\store\models\Currency;
use backend\modules\store\models\StoreProduct;
use backend\modules\store\models\StoreProductAttribute;
use backend\modules\store\models\StoreProductCategorySort;
use backend\modules\store\models\StoreProductFilter;
use backend\modules\store\models\StoreProductMustHave;
use backend\modules\store\models\StoreProductNew;
use backend\modules\store\models\StoreProductTop;
use backend\modules\store\models\StoreProductType;
use backend\widgets\pageSize\Widget;
use common\models\Language;
use himiklab\sortablegrid\SortableGridBehavior;
use himiklab\sortablegrid\SortableGridView;
use kartik\builder\Form;
use metalguardian\fileProcessor\helpers\FPM;
use yii\data\ActiveDataProvider;
use yii\data\BaseDataProvider;
use yii\db\ActiveRecord;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class BackModel
 *
 * @package backend\components
 */
class BackModel extends ActiveRecord
{
    /**
     * @param $route
     * @param $params
     *
     * @return string
     */
    public static function createUrl($route, $params)
    {
        return Url::to(
            ArrayHelper::merge(
                [$route],
                $params
            )
        );
    }

    /**
     * @param null $names
     */
    public function unsetAttributes($names = null)
    {
        if ($names === null) {
            $names = $this->attributes();
        }
        foreach ($names as $name) {
            $this->$name = null;
        }
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

        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return in_array(static::className(), static::getSortableClasses())
            ? [
                'sort' => [
                    'class' => SortableGridBehavior::className(),
                    'sortableAttribute' => 'position'
                ],
            ]
            : [];
    }

    /**
     * @param bool $viewAction
     *
     * @return array
     */
    public function getViewColumns($viewAction = false)
    {
        return [];
    }

    /**
     * @return array
     */
    public function getFormRows()
    {
        return [];
    }

    /**
     * @return int
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
        return '';
    }

    /**
     * @return array
     */
    public function getLocalizedAttributes()
    {
        return [];
    }

    /**
     * @return array
     */
    public function prepareForm()
    {
        $return = [];
        $form = $this->getFormRows();
        $this->addSeoWidgetToFormConfig($form);

        if ($this->getBehavior('ml')) {
            if (isset($form['form-set'])) {
                foreach ($form['form-set'] as $formName => $rowSet) {
                    foreach ($rowSet as $field => $fieldConfig) {
                        if (in_array($field, $this->getLocalizedAttributes())) {
                            foreach (Language::getLangList() as $code => $label) {
                                $langField = Language::getDefaultLang()->code == $code
                                    ? $field
                                    : $field . '_' . $code;

                                $return['form-set'][$formName][$langField] = Language::getDefaultLang()->code == $code
                                    ? $fieldConfig
                                    : $this->prepareFormFieldConfig($fieldConfig);
                            }
                        } else {
                            $return['form-set'][$formName][$field] = $fieldConfig;
                        }
                    }
                }
            } else {
                foreach ($form as $field => $fieldConfig) {
                    if (in_array($field, $this->getLocalizedAttributes())) {
                        foreach (Language::getLangList() as $code => $label) {
                            $langField = Language::getDefaultLang()->code == $code
                                ? $field
                                : $field . '_' . $code;

                            $return[$langField] = Language::getDefaultLang()->code == $code
                                ? $fieldConfig
                                : $this->prepareFormFieldConfig($fieldConfig);
                        }
                    } else {
                        $return[$field] = $fieldConfig;
                    }
                }
            }
        } else {
            $return = $form;
        }


        return $return;
    }

    /**
     * @param $form
     *
     * @return mixed
     */
    protected function addSeoWidgetToFormConfig(&$form)
    {
        if ($this->getBehavior('seo')) {
            if (!isset($form['form-set'])) {
                $newForm['form-set']['Основные'] = $form;
                $form = $newForm;
            }

            $form['form-set']['SEO']['field'] = [
                'type' => Form::INPUT_RAW,
                'value' => function () {
                    return \backend\widgets\seoForm\Widget::widget(['model' => $this]);
                }
            ];
        }

        return $form;
    }

    /**
     * Add multilingual labels to attribute labels list
     *
     * @param $attributes
     *
     * @return mixed
     */
    public function prepareAttributeLabels($attributes)
    {
        //Add multilingual attribute labels
        if ($this->getBehavior('ml')) {
            foreach ($attributes as $attr => $translate) {
                if (in_array($attr, $this->getLocalizedAttributes())) {
                    foreach (Language::getLangList() as $code => $label) {
                        if ($code == Language::getCurrent()->code) {
                            $attributes[$attr] = $translate.'['.$code.']';
                        } else {
                            $attributes[$attr.'_'.$code] = $translate.'['.$code.']';
                        }
                    }
                }
            }
        }

        return $attributes;
    }

    /**
     * @param array $config
     *
     * @return array
     */
    protected function prepareFormFieldConfig(array $config)
    {
        //Check for sluggable fields class and trim it for multilang fields
        if (isset($config['options']['class'])) {
            if (strpos($config['options']['class'], 's_name') !== false) {
                $config['options']['class'] = str_replace('s_name', '', $config['options']['class']);
            }

            if (strpos($config['options']['class'], 's_alias') !== false) {
                $config['options']['class'] = str_replace('s_alias', '', $config['options']['class']);
            }
        }

        return $config;
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
        ];
    }

    /**
     * @param BaseDataProvider $dataProvider
     * @param BackModel $model
     *
     * @return string
     */
    public function getButtonsList(BaseDataProvider $dataProvider, BackModel $model)
    {
        $buttons = '';
        $buttons .= Widget::widget(['dataProvider' => &$dataProvider]);
        $buttons .= Html::a('Cоздать', ['create'], ['class' => 'create pull-left bottom-margin btn btn-success']);

        return $buttons;
    }

    /**
     * @param BaseDataProvider $dataProvider
     * @param BackModel $model
     *
     * @return string
     */
    public function getIndexPageGridView(BaseDataProvider $dataProvider, BackModel $model)
    {
        if (in_array($model::className(), static::getSortableClasses())) {
            $columns = $model->getViewColumns();
            //Add sortable handle
            foreach ($columns as &$column) {
                if (is_array($column) && isset($column['attribute']) && $column['attribute'] == 'id') {
                    $column['format'] = 'raw';
                    $column['value'] = function ($data) {
                        return Html::tag('span', '<i class="glyphicon glyphicon-move"></i>', ['class' => 'badge']).' '.
                        $data->id;
                    };
                }
            }

            return SortableGridView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $model,
                    'columns' => $columns,
                ]
            );
        } else {
            return GridView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $model,
                    'columns' => $model->getViewColumns(),
                ]
            );
        }
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getDeleteImageUrl($params = [])
    {
        return static::createUrl('/site/delete-image', $params);
    }

    /**
     * @param $imageId
     * @param $module
     * @param $size
     * @param $model
     * @param $field
     *
     * @return string
     */
    public static function getImagePreview($imageId, $module, $size, $model, $field)
    {
        $image = FPM::image($imageId, $module, $size, ['id' => 'fpm_image_'.$imageId]);
        if ($image) {
            return  $image . Html::tag('br') .
            Html::a('Удалить', static::getDeleteImageUrl(['id' => $imageId]), [
                'id' => 'image_delete_link_'.$imageId,
                'class' => 'ajax-link red-link',
                'data-params' => 'model='.$model.'&field='.$field
            ]);
        }


    }

    /**
     * @return array
     */
    protected static function getSortableClasses()
    {
        return [
            StoreProduct::className(),
            StoreProductType::className(),
            StoreProductAttribute::className(),
            StoreProductAttribute::className(),
            StoreProductMustHave::className(),
            StoreProductFilter::className(),
            Currency::className(),
            FooterLink::className(),
            BlogRubric::className(),
            BlogBanner::className(),
            Sales::className(),
            MetaTag::className(),
            PayAndDelivery::className(),
            StoreProductTop::className(),
            StoreProductNew::className(),
            StoreProductCategorySort::className(),
            Certificate::className()
        ];
    }
}
