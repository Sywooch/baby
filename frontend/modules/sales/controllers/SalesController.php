<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\sales\controllers;

use frontend\components\MetaTagRegistratorWithDefaults;
use frontend\components\UnusedParamsFilter;
use frontend\controllers\FrontController;
use frontend\modules\common\models\PageSeo;
use frontend\modules\sales\models\Sales;
use yii\web\NotFoundHttpException;

/**
 * Class SalesController
 * @package frontend\modules\sales\controllers
 */
class SalesController extends FrontController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'paramsFilter' => [
                'class' => UnusedParamsFilter::className(),
                'actions' => [
                    //action => ['param', 'param2']
                    'view' => ['alias']
                ]
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $sales = Sales::find()
            ->where(['visible' => 1])
            ->orderBy(['position' => SORT_DESC])
            ->all();

        $this->modelToFetchSeo = PageSeo::findOne(11);
        $this->pageForSeo = MetaTagRegistratorWithDefaults::PAGE_ACTIONS_AND_DISCOUNTS;

        return $this->render('index', [
            'sales' => $sales
        ]);
    }

    /**
     * @param $alias
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($alias)
    {
        $this->layoutDivClass = 'wrap product-wrapper article-black';

        $sale = Sales::find()
            ->where('visible = 1')
            ->andWhere('alias = :alias', [':alias' => $alias])
            ->one();

        if (!$sale) {
            throw new NotFoundHttpException(\Yii::t('frontend', 'Sale not found'));
        }

        $this->modelToFetchSeo = $sale;
        $this->pageForSeo = MetaTagRegistratorWithDefaults::PAGE_ONE_SALE_PAGE;

        return $this->render('view', ['model' => $sale]);
    }
}
