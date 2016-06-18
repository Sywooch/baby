<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\store\widgets\catalogSort;

use app\modules\store\models\StoreCategory;
use app\modules\store\models\StoreProductFilter;
use yii\db\Query;
use yii\helpers\Url;

/**
 * Class Widget
 *
 * @package app\modules\store\widgets\catalogSort
 */
class Widget extends \yii\base\Widget
{
    public function run()
    {
        $defaultOrder = \Yii::t('frontend', 'sort_order');
        $selectedFilter = \Yii::$app->request->get('filter');

        $sort = \Yii::$app->request->get('sort');

        if (in_array($sort, ['created', '-created'])) {
            $defaultOrder = \Yii::t('frontend', 'date_sort');
        } elseif (in_array($sort, ['price', '-price'])) {
            $defaultOrder = \Yii::t('frontend', 'price_sort');
        }

        return IS_MOBILE
            ? $this->render(
                'mobile',
                [
                    'selectedFilter' => $selectedFilter,
                    'filters' => $this->getFilters()
                ]
            )
            : $this->render(
                'default',
                [
                    'default' => $defaultOrder,
                    'sort' => $this->getSort(),
                    'selectedFilter' => $selectedFilter,
                    'filters' => $this->getFilters()
                ]
            );
    }

    /**
     * @return array
     */
    protected function getSort()
    {
        $sort = [
            [
                'label' => \Yii::t('frontend', 'sort_order'),
                'url' => Url::current(['sort' => null])
            ],
            [
                'label' => \Yii::t('frontend', 'date_sort'),
                'url' => Url::current(['sort' => '-created'])
            ],
            [
                'label' => \Yii::t('frontend', 'price_sort'),
                'url' => Url::current(['sort' => 'price'])
            ],

        ];

        return $sort;
    }

    /**
     * @return array
     */
    protected function getFilters()
    {
        $filters = [];

        $categoryAlias = \Yii::$app->request->get('alias');

        if ($categoryAlias) {
            $categoryId = (new Query())
                ->select('id')
                ->from(StoreCategory::tableName())
                ->where('alias = :alias', [':alias' => $categoryAlias])
                ->scalar();

            if ($categoryId) {
                $filters = StoreProductFilter::getFiltersForCategory($categoryId);
            }
        }

        return $filters;
    }
}
