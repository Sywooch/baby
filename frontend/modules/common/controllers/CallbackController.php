<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\common\controllers;

use app\modules\common\forms\CallbackForm;
use frontend\components\UnusedParamsFilter;
use frontend\controllers\FrontController;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * Class CallbackController
 * @package frontend\modules\common\controllers
 */
class CallbackController extends FrontController
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
                    'callback' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionCallback()
    {
        $form = new CallbackForm();

        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            $form->save();

            $data = [
                'what' => '.popup-w',
                'data' => $this->renderPartial('_thank_for_callback')
            ];
        } else {
            $data = [
                'what' => '.popup-w',
                'data' => $this->renderPartial('_callback', ['form' => $form])
            ];
        }

        return Json::encode([
            'content' => [
                $data
            ]
        ]);
    }
}
