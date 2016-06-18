<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\store\widgets\storeCatalogCategoryMenu;

use app\modules\store\models\StoreCategory;
use yii\helpers\Html;

/**
 * Class Widget
 * @package app\modules\store\widgets\storeCatalogCategoryMenu
 */
class Widget extends \yii\base\Widget
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $mainCategories = \Yii::$app->db->cache(
            function () {
                return StoreCategory::find()->where('level >= 2')->orderBy('lft')->all();
            },
            10
        );

        $items = $this->getItems($mainCategories);

        if (!empty($items)) {
            $output = Html::beginTag('div', ['class' => 'main-nav-wrapper']);
            $output .= \app\modules\store\widgets\nestedMenu\Widget::widget(
                [
                    'items' => $items,
                    'linkTemplate' => '<a href="{url}">{label}</a><i class="circle-yell"></i>',
                    'submenuTemplate' => "\n<ul class='subnav-item-col'>\n{items}\n</ul>\n",
                    'nestedItemsInOneColCount' => 6,
                    'nestedContainerClass' => 'subnav-item-drop',
                    'itemOptions' => [
                        'class' => 'main-nav-item'
                    ],
                    'options' => [
                        'class' => 'main-nav-drop'
                    ]
                ]
            );

            $output .= Html::endTag('div');

            return $output;
        }
    }

    /**
     * @param $categoryList
     *
     * @return array
     */
    protected function getItems($categoryList)
    {
        $items = [];

        $firstNestingLevel = 1;
        foreach ($categoryList as $i => $category) {
            if (!$i) {
                $firstNestingLevel = $category->level;
            }
            if ($firstNestingLevel == $category->level) {
                $itemNo = $i;
                $items[$itemNo] = [
                    'label' => $category->label,
                    'url' => StoreCategory::getCatalogRoute(['alias' => $category->alias]),
                ];
            }

            if ($category->level > $firstNestingLevel) {
                $items[$itemNo]['items'][] = [
                    'label' => $category->label,
                    'url' => StoreCategory::getCatalogRoute(['alias' => $category->alias]),
                    'template' => '<a href="{url}">{label}</a>'
                ];
            }
        }

        return $items;
    }
}
