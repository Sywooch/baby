<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\store\controllers;

use frontend\components\UnusedParamsFilter;
use frontend\controllers\FrontController;
use frontend\modules\store\forms\StoreProductSubscribeForm;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * Class ProductSubscribeController
 * @package frontend\modules\store\controllers
 */
class ProductSubscribeController extends FrontController
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
                    'subscribe' => ['id'],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'subscribe' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionSubscribe()
    {
        $form = new StoreProductSubscribeForm();
        $productId = \Yii::$app->request->get('id');
        if ($productId) {
            $form->productId = $productId;
        }

        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            $form->save();

            $data = [
                'what' => '.popup-w',
                'data' => $this->renderPartial('_thank_for_subscribe')
            ];
        } else {
            $data = [
                'what' => '.popup-w',
                'data' => $this->renderPartial('_subscribe', ['form' => $form])
            ];
        }

        return Json::encode([
            'content' => [
                $data
            ]
        ]);
    }
}
