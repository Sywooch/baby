<?php
namespace backend\controllers;

use backend\modules\store\models\StoreOrder;
use common\models\Callback;
use common\models\GiftRequest;
use metalguardian\fileProcessor\helpers\FPM;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return ArrayHelper::merge(
            $behaviors,
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'logout' => ['post'],
                    ],
                ],
            ]
        );
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
        ];
    }

    /**
     * @param $id
     *
     * @return string
     */
    public function actionDeleteImage($id)
    {
        /**
         * @var \yii\db\ActiveRecord $model
         */
        $model = Yii::$app->request->post('model');
        $field = Yii::$app->request->post('field');

        if ($model && $field) {
            $record = $model::find()->where([$field => $id])->one();

            if ($record) {
                $record->{$field} = null;
                $record->save(false);
            }

            FPM::deleteFile($id);

            return Json::encode([
                'replaces' => [
                    [
                        'data' => '',
                        'what' => '#fpm_image_'.$id
                    ],
                    [
                        'data' => '',
                        'what' => '#image_delete_link_'.$id
                    ]
                ]
            ]);
        }
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render(
                'login',
                [
                    'model' => $model,
                ]
            );
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @return int|string
     */
    public function actionGetNewRequestsCount()
    {
        return \common\models\StoreOrder::getNewRequestCount();
    }
}
