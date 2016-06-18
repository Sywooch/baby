<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\store\widgets\storeCategoryMenu;

use app\modules\store\models\StoreCategory;
use yii\db\Connection;
use yii\helpers\Html;
use yii\widgets\Menu;

/**
 * Class Widget
 *
 * @package app\modules\store\widgets\storeCategoryMenu
 */
class Widget extends \yii\base\Widget
{
    public $showStaticImage = false;

    public $menuType = 'withAllChild';

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

        switch ($this->menuType) {
            case 'withAllChild':
                $items = $this->itemsWithAllChild($mainCategories);
                break;
            case 'withActiveCategoryChild':
                $items = $this->itemsWithActiveCategoryChild($mainCategories);
                break;
            default:
                $items = [];
        }

        if (!empty($items)) {
            $output = Html::beginTag('nav', ['class' => 'hide']);
            $output .= \app\modules\store\widgets\nestedMenu\Widget::widget(
                [
                    'items' => $items,
                    'linkTemplate' => '<a href="{url}">{label}</a><i class="circle-yell"></i>',
                    'submenuTemplate' => "\n<ul class='subnav-item-col'>\n{items}\n</ul>\n",
                    'nestedItemsInOneColCount' => 6,
                    'itemOptions' => [
                        'class' => 'main-nav-item'
                    ],
                    'options' => [
                        'class' => 'main-nav main-nav-desc'
                    ]
                ]
            );

            $output .= Html::tag('p', \Yii::t('frontend', 'Catalog'), ['class' => 'btn-submenu-open']);

            //Submenu for withActiveCategoryChild menu type
            if (isset($items['childs'])) {
                $output .= Html::beginTag('div', ['class' => 'submenu']);
                $output .= Menu::widget(
                    [
                        'items' => $items['childs'],
                    ]
                );
                $output .= Html::endTag('div');
            }

            if ($this->showStaticImage) {
                $output .= $this->getStaticImage();
            }
            $output .= Html::endTag('nav');

            return $output;
        }
    }

    /**
     * @param $categoryList
     *
     * @return array
     */
    protected function itemsWithAllChild($categoryList)
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

    /**
     * @param $categoryList
     *
     * @return array
     */
    protected function itemsWithActiveCategoryChild($categoryList)
    {
        $items = [];
        $childs = [];
        $activeCategoryNo = null;

        $activeCategoryAlias = \Yii::$app->request->get('alias');

        $firstNestingLevel = 1;
        $j = 0;
        foreach ($categoryList as $i => $category) {
            //Detect active category number
            if ($category->alias == $activeCategoryAlias) {
                $activeCategoryNo = $j;
            }
            //Detect first nesting level
            if (!$i) {
                $firstNestingLevel = $category->level;
            }
            //Put all first level nesting items to main menu
            if ($firstNestingLevel == $category->level) {
                $items[$j] = [
                    'label' => $category->label,
                    'url' => StoreCategory::getCatalogRoute(['alias' => $category->alias]),
                ];
                $j++;
            }

            //And childs to child menu
            if (($category->level > $firstNestingLevel)
            ) {
                $childs[count($items)-1][] = [
                    'label' => $category->label,
                    'url' => StoreCategory::getCatalogRoute(['alias' => $category->alias]),
                ];
            }
            //If child element is active
            if ($category->level > $firstNestingLevel && $category->alias == $activeCategoryAlias) {
                //Make parent active
                $key = count($items)-1;
                $items[$key]['active'] = true;
                //Overwrite active category number
                $activeCategoryNo = $key;
            }
        }

        if (!empty($childs)) {
            if (!is_null($activeCategoryNo) && isset($childs[$activeCategoryNo])) {
                $items['childs'] = $childs[$activeCategoryNo];
            }
        }

        return $items;
    }

    /**
     * @return string
     */
    protected function getStaticImage()
    {
        return Html::img('/img/demo/main_page_small_image/' . rand(1, 6) . '.jpg');
    }
}
