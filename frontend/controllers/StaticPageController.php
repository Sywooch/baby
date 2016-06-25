<?php
namespace frontend\controllers;

use common\models\StaticPage;
use frontend\models\ContactForm;
use Yii;
use yii\web\NotFoundHttpException;


/**
 * Site controller
 */
class StaticPageController extends FrontController
{
    /**
     * @param $alias string
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($alias)
    {
        $model = StaticPage::find()
            ->where(['alias' => $alias, 'visible' => 1])
            ->one();
        if (!$model) {
            throw new NotFoundHttpException;
        }
        $this->modelToFetchSeo = $model;
        $contactForm = new ContactForm();
        if ($contactForm->load(Yii::$app->request->post()) && $contactForm->save()) {
            if ($contactForm->sendEmail()) {
                $message = Yii::t('front', 'Thank you for contacting us. We will respond to you as soon as possible.');
                Yii::$app->session->setFlash('contact-form-message', $message);
            } else {
                $message = Yii::t('front', 'There was an error sending email.');
                Yii::$app->session->setFlash('contact-form-message', $message);
            }

            return $this->refresh();
        }
            
        return $this->render('index', ['model' => $model, 'contactForm' => $contactForm]);
    }
}
