<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\controllers;

use backend\modules\store\models\StoreCategory;
use common\models\User;
use vova07\imperavi\actions\GetAction;
use vova07\imperavi\actions\UploadAction;
use himiklab\sortablegrid\SortableGridAction;
use yii\base\Exception;
use yii\base\UserException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class BackendController
 *
 * @package backend\controllers
 */
class BackendController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                                return !\Yii::$app->user->isGuest &&
                                \Yii::$app->user->identity->role == User::ROLE_ADMIN;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {

        return [
            'sort' => [
                'class' => SortableGridAction::className(),
                'modelName' => $this->getModel(),
            ],
            'images-get' => [
                'class' => GetAction::className(),
                'url' => '/uploads/redactor/', // Directory URL address, where files are stored.
                'path' => '@backendUploads/redactor',
                'type' => GetAction::TYPE_IMAGES,
                'options' => [
                    'except' => ['.gitkeep']
                ]

            ],
            'image-upload' => [
                'class' => UploadAction::className(),
                'url' => '/uploads/redactor/', // Directory URL address, where files are stored.
                'path' => '@backendUploads/redactor' // Or absolute path to directory where files are stored.
            ],

        ];
    }

    /**
     * @return \backend\components\BackModel
     * @throws \yii\base\Exception
     */
    public function getModel()
    {
        throw new Exception('Need to implement "getModel" method');
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $class = $this->getModel();

        /**
         * @var $model \backend\components\BackModel
         */
        $model = new $class();
        $model->setScenario('search');
        $model->unsetAttributes();

        $dataProvider = $model->search(\Yii::$app->request->queryParams);

        return $this->render(
            '//template/index',
            [
                'searchModel' => $model,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $class = $this->getModel();
        /**
         * @var $model \backend\components\BackModel
         */
        $model = new $class();
        $model->loadDefaultValues();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('success', 'Запись создана успешно!');

            return $this->redirect(['index']);
        } else {
            return $this->render(
                '//template/create',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * @param $id
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\UserException
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $isSaved = $model->load(\Yii::$app->request->post()) && $model->save();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            throw new UserException($e->getMessage());
        }

        if ($isSaved) {
            \Yii::$app->getSession()->setFlash('info', 'Запись #'.$model->id.' обновлена!');

            return $this->redirect(['index']);
        } else {
            return $this->render(
                '//template/update',
                [
                    'model' => $model,
                ]
            );
        }
    }


    /**
     * @param $id
     *
     * @return string
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);

        return $this->render(
            '//template/view',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * @param $id
     *
     * @throws \yii\base\UserException
     */
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);

        try {
            $modelId = $model->id;
            if ($model->className() == StoreCategory::className()) {
                $model->deleteNode();
            } else {
                $model->delete();
            }
        } catch (Exception $e) {
            if ($e->errorInfo[1] == 1451) {
                throw new UserException('Удалите связанные данные сначала!');
            } else {
                throw new UserException('Ошибка при удалении: ' . $e->getMessage());
            }
        }
        \Yii::$app->getSession()->setFlash('success', 'Запись #'.$modelId.' удалена!');


        $this->redirect(['index']);
    }

    /**
     * @param $id
     *
     * @return \backend\components\BackModel
     * @throws \yii\web\NotFoundHttpException
     */
    public function loadModel($id)
    {
        $class = $this->getModel();
        /**
         * @var $model \backend\components\BackModel
         */
        $model = new $class();
        $model = $model->findOne(['id' => (int)$id]);

        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException;
        }
    }
}
