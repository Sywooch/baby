<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\store\controllers;

use app\modules\store\models\StoreProduct;
use frontend\components\MetaTagRegistratorWithDefaults;
use frontend\components\UnusedParamsFilter;
use frontend\controllers\FrontController;
use yii\web\NotFoundHttpException;

/**
 * Class ProductController
 * @package app\modules\store\controllers
 */
class ProductController extends FrontController
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
                    'view' => ['alias'],
                ]
            ],
        ];
    }

    /**
     * @param $alias
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($alias)
    {
        $product = StoreProduct::find()
            ->where('visible = 1')
            ->andWhere(StoreProduct::tableName().'.alias = :alias', [':alias' => $alias])
            ->joinWith(
                [
                    'allImages',
                ]
            )
            ->one();

        if (!$product) {
            throw new NotFoundHttpException(\Yii::t('app', 'Product not found'));
        }

        $this->modelToFetchSeo = $product;
        $this->pageForSeo = MetaTagRegistratorWithDefaults::PAGE_PRODUCT;

        return $this->render('view', ['model' => $product]);
    }
}
