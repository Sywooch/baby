<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\controllers;

use backend\controllers\BackendController;
use backend\modules\store\models\StoreOrderProduct;
use yii\base\Exception;
use yii\base\UserException;
use yii\helpers\Json;

/**
 * Class StoreOrderController
 *
 * @package backend\modules\store\controllers
 */
class StoreOrderProductController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return StoreOrderProduct::className();
    }

    /**
     * @inheritdoc
     */
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);

        try {
            $modelId = $model->id;
            $model->delete();
        } catch (Exception $e) {
            if ($e->errorInfo[1] == 1451) {
                throw new UserException('Удалите связанные данные сначала!');
            } else {
                throw new UserException('Ошибка при удалении: ' . $e->getMessage());
            }
        }

        if (\Yii::$app->request->isAjax) {
            return Json::encode(
                [
                    'replaces' => [
                        [
                            'what' => 'tr[data-key="' . $id . '"]',
                            'data' => ''
                        ]
                    ]
                ]
            );
        }


        \Yii::$app->getSession()->setFlash('success', 'Запись #'.$modelId.' удалена!');

        return $this->redirect(['index']);;
    }
}
