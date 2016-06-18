<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\controllers;

use backend\controllers\BackendController;
use backend\modules\store\models\StoreProductNew;

/**
 * Class StoreProductController
 * @package backend\modules\store\controllers
 */
class StoreProductNewController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return StoreProductNew::className();
    }

    /**
     * @inheritdoc
     */
    public function actionRemove($id)
    {
        $model = $this->loadModel($id);

        $model->is_new = 0;
        $model->save(false);

        \Yii::$app->getSession()->setFlash('info', 'Запись #' . $model->id . ' убрана из новинок!');

        return $this->redirect(['index']);
    }
}
