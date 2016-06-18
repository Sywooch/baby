<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\modules\common\widgets\footerLinks;

use app\modules\common\models\SeoFooterLinks;
use app\modules\store\models\StoreCategory;
use backend\modules\store\controllers\StoreProductController;
use frontend\modules\common\models\FooterLink;
use yii\helpers\Html;

/**
 * Class Widget
 *
 * @package frontend\modules\common\widgets\footerLinks
 */
class Widget extends \yii\base\Widget
{

    protected $_currentPath = null;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $column1 = $column2 = [];
        $output = '';

        $output .= $this->getCatalogMainItems();

        $links = FooterLink::find()
            ->where(['visible' => 1])
            ->orderBy(['position' => SORT_DESC])
            ->all();
        $seoLinks = $this->getSeoLinks();

        if (empty($links)) {
            return null;
        }

        foreach ($links as $link) {
            if ($link->column == 1) {
                $column1[] = $link;
            } elseif ($link->column == 2) {
                $column2[] = $link;
            } elseif ($link->column == 3) {
                $column3[] = $link;
            }
        }

        if (!empty($column1)) {
            $output .= Html::beginTag('div', ['class' => 'col']);
            $output .= Html::beginTag('ul');

            foreach ($column1 as $c1) {
                $output .= Html::tag(
                    'li',
                    Html::a($c1->label, $c1->url),
                    ['class' => $this->isCurrentLink($c1->url) ? 'active' : '']
                );
            }

            if ($_SERVER['REQUEST_URI'] == '/')
            {
                $output .= Html::beginTag('li', ['class' => 'col-breadcrumbs']);
                $output .= '
                <div xmlns:v="http://rdf.data-vocabulary.org/#">
                <span typeof="v:Breadcrumb">
                    <a href="/" rel="v:url" property="v:title">Интернет магазин оригинальных подарков</a> › </span>
                    <span typeof="v:Breadcrumb">
                        <a href="/#podarki" rel="v:url" property="v:title">♥Подарки для девушки♥</a>
                    </span>
                </div>';
                $output .= Html::endTag('li');
            }
            else if ((stristr($_SERVER['REQUEST_URI'], '/catalog') !== false) && (stristr($_SERVER['REQUEST_URI'], '/catalog/product') === false))
            {
                if ($_SERVER['REQUEST_URI'] == '/catalog')
                {
                    $uri = '/catalog';
                    $title = 'Каталог';
                }
                else
                {
                    $uri = $_SERVER['REQUEST_URI'];
                    $title = $this->getCurrentLink();
                }
                $output .= Html::beginTag('li', ['class' => 'col-breadcrumbs']);
                $output .= '
                <div xmlns:v="http://rdf.data-vocabulary.org/#">
                    <span typeof="v:Breadcrumb">
                        <a href="/" rel="v:url" property="v:title">Магазин оригинальных подарков</a> › 
                    </span>
                    <span typeof="v:Breadcrumb">
                        <a href="' . $uri . '" rel="v:url" property="v:title"> ♥' . $title . '♥</a>
                    </span>
                </div>';
                $output .= Html::endTag('li');
            }

            $output .= Html::endTag('ul');
            $output .= Html::endTag('div');
        }

        if (!empty($column2)) {
            $output .= Html::beginTag('div', ['class' => 'col']);
            $output .= Html::beginTag('ul');

            foreach ($column2 as $c2) {
                $output .= Html::tag(
                    'li',
                    Html::a($c2->label, $c2->url),
                    ['class' => $this->isCurrentLink($c2->url) ? 'active' : '']
                );
            }

            $output .= Html::endTag('ul');
            $output .= Html::endTag('div');
        }

        $output .= Html::beginTag('div', ['class' => 'col']);
        $output .= '<!-- links_block -->';
        $output .= Html::endTag('div');

        if (!empty($seoLinks)) {
            $output .= Html::beginTag('div', ['class' => 'col']);
            $output .= Html::beginTag('ul');

            foreach ($seoLinks as $sLink) {
                $output .= Html::tag(
                    'li',
                    Html::a($sLink->label, $sLink->link),
                    ['class' => $this->isCurrentLink($sLink->link) ? 'active' : '']
                );
            }

            $output .= Html::endTag('ul');
            $output .= Html::endTag('div');
        }

        return $output;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getCurrentLink()
    {
        $mainCategories = \Yii::$app->db->cache(
            function () {
                return StoreCategory::find()->where(['level' => 2])->orderBy('lft')->all();
            },
            10
        );

        if (!empty($mainCategories)) {
            foreach ($mainCategories as $mC) {
                if ($this->isCurrentLink($mC->alias) == 'active')
                {
                    return $mC->label;
                }
            }
        }

        return "No category";
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getCatalogMainItems()
    {
        $output = '';

        $mainCategories = \Yii::$app->db->cache(
            function () {
                return StoreCategory::find()->where(['level' => 2])->orderBy('lft')->all();
            },
            10
        );

        if (!empty($mainCategories)) {
            $output .= Html::beginTag('div', ['class' => 'col']);
            $output .= Html::beginTag('ul');

            foreach ($mainCategories as $mC) {
                $output .= Html::tag(
                    'li',
                    Html::a($mC->label, StoreCategory::getCatalogRoute(['alias' => $mC->alias])),
                    ['class' => $this->isCurrentLink($mC->alias) ? 'active' : '']
                );
            }

            $output .= Html::tag(
                'li',
                Html::a('Карта сайта', 'sitemap'),
                ['class' => (strripos($_SERVER['REQUEST_URI'], '/sitemap') !== FALSE) ? 'active' : '']
            );

            $output .= Html::endTag('ul');
            $output .= Html::endTag('div');
        }

        return $output;
    }

    /**
     * @param $link
     *
     * @return bool
     */
    protected function isCurrentLink($link)
    {
        if (!$this->_currentPath) {
            $this->_currentPath = \Yii::$app->request->getAbsoluteUrl().\Yii::$app->request->getPathInfo();
        }

        return (strpos($this->_currentPath, $link) !== false) ? true : false;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    protected function getSeoLinks()
    {
        $isMain = \Yii::$app->controller->id === 'site' && \Yii::$app->controller->action->id === 'index';
        $catAlias = \Yii::$app->request->get('alias');
        $category = $catAlias
            ? StoreCategory::find()->where('alias = :alias', [':alias' => $catAlias])->one()
            : null;
        $isCategory = \Yii::$app->controller->id === 'catalog' && \Yii::$app->controller->action->id === 'index' &&
            $category;


        if ($isMain) {
            return SeoFooterLinks::find()->where('category_id IS NULL')->all();
        } elseif ($isCategory) {
            return SeoFooterLinks::find()->where(['category_id' => $category->id])->all();
        } else {
            return [];
        }
    }
}
