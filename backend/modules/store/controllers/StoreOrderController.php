<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\controllers;

use backend\controllers\BackendController;
use backend\modules\common\models\Certificate;
use backend\modules\store\models\StoreOrder;
use backend\modules\store\models\StoreOrderProduct;
use backend\modules\store\models\StoreProduct;
use yii\base\Exception;
use yii\base\UserException;
use yii\helpers\Json;

/**
 * Class StoreOrderController
 *
 * @package backend\modules\store\controllers
 */
class StoreOrderController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return StoreOrder::className();
    }

    /**
     * @param $id
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionPrint($id)
    {
        $this->layout = '@app/themes/basic/layouts/print';

        return $this->render('print', ['model' => $this->loadModel($id)]);
    }

    /**
     * @param null $search
     * @param null $id
     */
    public function actionGetProductList($search = null, $id = null)
    {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = StoreProduct::find()
                ->select(StoreProduct::tableName().'.id, label AS text')
                ->innerJoinWith(['mainImage'])
                ->where('label LIKE "%' . $search . '%"')
                ->limit(20)
                ->asArray()
                ->all();
            $certData = Certificate::find()
                ->select(['id', 'label AS text', 'id AS cert_id'])
                ->where('label LIKE "%' . $search . '%"')
                ->limit(20)
                ->asArray()
                ->all();

            $out['results'] = array_values(array_merge($data, $certData));

        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => StoreProduct::findOne($id)->label];
        } else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo Json::encode($out);
    }

    /**
     * @param $id
     * @param $orderId
     */
    public function actionAddProduct($id, $orderId)
    {
        $data = null;
        $isCert = \Yii::$app->request->get('is_cert');

        $data = $isCert
            ? $this->addNewCertificate($id, $orderId)
            : $this->addNewProduct($id, $orderId);

        echo Json::encode($data);
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
                'update',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * @param $id
     * @param $orderId
     *
     * @return array|null
     */
    protected function addNewProduct($id, $orderId)
    {
        $data = null;
        $product = StoreProduct::findOne($id);

        if ($product) {
            $model = new StoreOrderProduct();
            $model->product_id = $id;
            $model->order_id = $orderId;
            $model->sku = $product->sku;
            $model->qnt = 1;
            $model->save(false);

            $data = [
                'replaces' => [
                    [
                        'what' => '.grid-view',
                        'data' => $model->order->getItemsGrid()
                    ]
                ]
            ];
        }

        return $data;
    }

    /**
     * @param $id
     * @param $orderId
     *
     * @return array|null
     */
    protected function addNewCertificate($id, $orderId)
    {
        $data = null;

        $certificate = Certificate::findOne($id);

        if ($certificate) {
            $model = new StoreOrderProduct();
            $model->product_id = null;
            $model->order_id = $orderId;
            $model->cert_id = $certificate->id;
            $model->sku = $certificate->label;
            $model->qnt = 1;
            $model->save(false);

            $data = [
                'replaces' => [
                    [
                        'what' => '.grid-view',
                        'data' => $model->order->getItemsGrid()
                    ]
                ]
            ];
        }

        return $data;
    }
}
