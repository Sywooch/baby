<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\controllers;

use backend\controllers\BackendController;
use backend\modules\store\models\StoreProductTop;

/**
 * Class StoreProductController
 * @package backend\modules\store\controllers
 */
class StoreProductTopController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return StoreProductTop::className();
    }

    /**
     * @inheritdoc
     */
    public function actionRemove($id)
    {
        $model = $this->loadModel($id);

        $model->is_top_50 = $model->is_top_50_category = 0;
        $model->save(false);

        \Yii::$app->getSession()->setFlash('info', 'Запись #' . $model->id . ' убрана из топа!');

        return $this->redirect(['index']);
    }
}
