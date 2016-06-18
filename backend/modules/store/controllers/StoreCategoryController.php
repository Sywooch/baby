<?php
/**
 * Author: Pavel Naumenko
 */
namespace backend\modules\store\controllers;

use backend\controllers\BackendController;
use backend\modules\store\models\StoreCategory;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

/**
 * Class StoreCategoryController
 * @package backend\modules\store\controllers
 */
class StoreCategoryController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return StoreCategory::className();
    }

    /**
     * @return string
     */
    public function actionList()
    {
        $class = $this->getModel();

        /**
         * @var $query \backend\components\StoreCategory
         */
        $query = $class::find(true)->where(['level' => 2])->orderBy('lft');

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        return $this->render(
            'list',
            [
                'dataProvider' => $dataProvider,
                'model' => new $class,
            ]
        );
    }

    public function actionSortCategory()
    {
        $items = \Yii::$app->request->post('items');

        if ($items) {
            $items = Json::decode($items, true);

            foreach ($items as $item) {
                \Yii::$app->db->createCommand()
                    ->update(
                        StoreCategory::tableName(),
                        [
                            'lft' => $item['left'],
                            'rgt' => $item['right'],
                            'level' => $item['depth'],
                        ],
                        'id = :id',
                        [':id' => $item['item_id']]
                    )->execute();
            }
        }
    }
}
