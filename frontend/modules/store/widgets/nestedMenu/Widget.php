<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\store\widgets\nestedMenu;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Menu;

/**
 * Class Widget
 * @package app\modules\store\widgets\nestedMenu
 */
class Widget extends Menu
{
    /**
     * Max child items in one column
     *
     * @var int
     */
    public $nestedItemsInOneColCount = 10;

    /**
     * @var string
     */
    public $nestedContainerClass = 'subnav-item';

    public $isNestedChildRendering = false;

    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @return string the rendering result
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];
            if ($this->isNestedChildRendering) {
                $options['class'] = '';
            }
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }

            $menu = $this->renderItem($item);



            if (!empty($item['items'])) {
                //Container for nested childs
                $menu .= Html::beginTag('div', ['class' => $this->nestedContainerClass]);
                $this->isNestedChildRendering = true;

                $childsCount = count($item['items']);
                if ($childsCount > $this->nestedItemsInOneColCount) {
                    $childsChunks = array_chunk($item['items'], $this->nestedItemsInOneColCount);

                    foreach ($childsChunks as $childChunk) {
                        $menu .= strtr($this->submenuTemplate, [
                                '{items}' => $this->renderItems($childChunk),
                            ]);
                    }
                } else {
                    $menu .= strtr($this->submenuTemplate, [
                            '{items}' => $this->renderItems($item['items']),
                        ]);
                }

                $menu .= Html::endTag('div');
                $this->isNestedChildRendering = false;

            }
            $lines[] = Html::tag($tag, $menu, $options);
        }

        return implode("\n", $lines);
    }
}
