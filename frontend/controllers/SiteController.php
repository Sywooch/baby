<?php
namespace frontend\controllers;

use frontend\components\MetaTagRegistratorWithDefaults;
use frontend\components\UnusedParamsFilter;
use frontend\models\ProfileForm;
use frontend\modules\common\models\PageSeo;
use Yii;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends FrontController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'paramsFilter' => [
                'class' => UnusedParamsFilter::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $this->modelToFetchSeo = PageSeo::findOne(1);
        $this->pageForSeo = MetaTagRegistratorWithDefaults::PAGE_MAIN;

        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionDelivery()
    {
        $this->modelToFetchSeo = PageSeo::findOne(9);
        $this->pageForSeo = MetaTagRegistratorWithDefaults::PAGE_PAY_AND_DELIVERY;

        return $this->render('delivery');
    }

    /**
     * @return string
     */
    public function actionSitemap()
    {
        return $this->render('sitemap');
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }
}
