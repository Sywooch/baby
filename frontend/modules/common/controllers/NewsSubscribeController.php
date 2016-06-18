<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\common\controllers;

use app\modules\common\forms\NewsSubscribeForm;
use frontend\components\UnusedParamsFilter;
use frontend\controllers\FrontController;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * Class NewsSubscribeController
 *
 * @package frontend\modules\common\controllers
 */
class NewsSubscribeController extends FrontController
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
        $form = new NewsSubscribeForm();
        $success = false;

        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            $form->save();
            $success = true;
        }

        return Json::encode(
            [
                'replaces' => [
                    [
                        'what' => '.news-subscribe',
                        'data' => $this->renderPartial('_form', ['form' => $form, 'success' => $success])
                    ]
                ]
            ]
        );
    }
}
