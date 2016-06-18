<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\common\controllers;

use frontend\components\MetaTagRegistratorWithDefaults;
use frontend\components\UnusedParamsFilter;
use frontend\controllers\FrontController;
use frontend\modules\common\forms\GiftRequestForm;
use frontend\modules\common\models\PageSeo;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class GiftRequestController
 * @package frontend\modules\common\controllers
 */
class GiftRequestController extends FrontController
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
        ];
    }

    /**
     * @return string
     */
    public function actionSend()
    {
        $this->modelToFetchSeo = PageSeo::findOne(5);
        $this->pageForSeo = MetaTagRegistratorWithDefaults::PAGE_GET_GIFT;

        $form = new GiftRequestForm();

        if (\Yii::$app->request->isAjax && $form->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }

        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            $form->save();

            return $this->redirect(['thank']);
        }

        return $this->render('form', ['model' => $form]);
    }

    /**
     * @return string
     */
    public function actionThank()
    {
        $this->layout = '//simple';

        return $this->render('thank');
    }
}
