<?php

namespace app\modules\banner\controllers;

use frontend\components\UnusedParamsFilter;
use yii\web\Controller;

class DefaultController extends Controller
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

    public function actionIndex()
    {
        return $this->render('index');
    }
}
