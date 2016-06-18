<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\store\widgets\pager;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class LinkPager
 * @package backend\modules\store\widgets\pager
 */
class LinkPager extends \yii\widgets\LinkPager
{
    public $options = [];
    public $prevPageCssClass = 'previous';
    public $disabledPageCssClass = 'no-active';
    public $activePageCssClass = 'selected';
    public $registerLinkTags = true;

    public $isBlogPage = true;


    public function init()
    {
        $this->nextPageLabel = \Yii::t('frontend', 'next page');
        $this->prevPageLabel = \Yii::t('frontend', 'prev page');
        if (IS_MOBILE) {
            $this->maxButtonCount = 8;
        }

        parent::init();
    }

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run()
    {
        if ($this->registerLinkTags && !\Yii::$app->request->isAjax) {
            $this->registerLinkTags();
        }
        echo $this->renderPageButtons();
    }

    protected function registerLinkTags()
    {
        $view = $this->getView();
        foreach ($this->pagination->getLinks(true) as $rel => $href) {
            $view->registerLinkTag(['rel' => $rel, 'href' => $href], $rel);
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $output = '';
        $buttons = [];
        $currentPage = $this->pagination->getPage();
        if ($this->isBlogPage) {
            $output .= Html::beginTag('div');
        }
        //Show more button
        if (($page = $currentPage + 1) <= $pageCount - 1) {
            $output .= Html::a(Html::tag('span', \Yii::t('frontend', 'Show more')), $this->pagination->createUrl($page, null, true), ['class' => 'btn-round']);
        }

        $output .= Html::beginTag('div', ['class' => 'catalog-pager hide']);

        // first page
        if ($this->firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($this->firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }

        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
        }

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        // last page
        if ($this->lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($this->lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        $output .= Html::tag('ul', implode("\n", $buttons), $this->options);

        $output .= Html::endTag('div');

        if ($this->isBlogPage) {
            $output .= Html::endTag('div');
        }

        return $output;
    }

    /**
     * @inheritdoc
     */
    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => $class === '' ? null : $class];

        $linkOptions = $this->linkOptions;

        $linkOptions['data-page'] = $page;

        if (!isset($options['class'])) {
            Html::addCssClass($options, 'page');
        } elseif (in_array($options['class'], [
                $this->prevPageCssClass,
                $this->nextPageCssClass,
            ])) {
            Html::addCssClass($linkOptions, 'btn-more-info');
        }

        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);

            return Html::tag('li', Html::tag('span', $label, ['class' => 'btn-more-info']), $options);
        }

        return Html::tag('li', Html::a(is_int($label) ? '' : $label, $this->pagination->createUrl($page, null, true), $linkOptions), $options);
    }
}
