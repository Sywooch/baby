<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\controllers;

use backend\controllers\BackendController;
use backend\modules\store\models\StoreProductCategorySort;

/**
 * Class StoreProductController
 * @package backend\modules\store\controllers
 */
class StoreProductCategorySortingController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return StoreProductCategorySort::className();
    }

    /**
     * @inheritdoc
     */
    public function actionRemove($id)
    {
        $model = $this->loadModel($id);

        $model->is_top_50_category = 0;
        $model->save(false);

        \Yii::$app->getSession()->setFlash('info', 'Запись #' . $model->id . ' убрана из топа категорий!');

        return $this->redirect(['index']);
    }
}
