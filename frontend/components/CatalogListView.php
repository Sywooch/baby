<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\components;

use yii\widgets\ListView;

/**
 * Class CatalogListView
 * @package frontend\components
 */
class CatalogListView extends ListView
{

    /**
     * @var string
     */
    public $emptyItemsClass = 'catalog-item tt-empty-empty';

    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{summary}':
                return $this->renderSummary();
            case '{items}':
                return $this->renderItems();
            case '{empty_items}':
                return $this->renderEmptyItems();
            case '{pager}':
                return $this->renderPager();
            case '{sorter}':
                return $this->renderSorter();
            default:
                return false;
        }
    }

    /**
     * @return string
     */
    protected function renderEmptyItems()
    {
        return $this->render(
            '@app/themes/basic/modules/store/views/catalog/_empty_items_for_animation_proper_work',
            [
                'pageSize' => $this->dataProvider->pagination->pageSize,
                'existItemsCount' => $this->dataProvider->getCount(),
                'emptyItemsClass' => $this->emptyItemsClass
            ]
        );
    }
}
