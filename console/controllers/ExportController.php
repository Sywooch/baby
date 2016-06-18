<?php
/**
 * Author: Pavel Naumenko
 */

namespace console\controllers;

use common\models\ExportStatus;
use common\models\StoreProduct;
use SimpleExcel\SimpleExcel;
use SimpleExcel\Spreadsheet\Worksheet;
use yii\console\Controller;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/**
 * Class ExportController
 * @package console\controllers
 */
class ExportController extends Controller
{
    /**
     * @param $userId
     *
     * @return int
     */
    public function actionBegin($userId)
    {
        $headers = ExportStatus::getExportColumns($userId);
        if (empty($headers)) {
            $headers = StoreProduct::getExportHeaders();
        }

        $worksheet = new Worksheet();
        $worksheet->insertRecord($headers);

        $total = StoreProduct::find()->count();
        /**
         * @var $product \common\models\StoreProduct
         */
        $i = 1;
        foreach (StoreProduct::find()->each(10) as $product) {
            $worksheet->insertRecord($product->getExportRow($headers));
            $progress = round(($i/$total) * 100);
            ExportStatus::updateStatus($userId, $progress);
        }
        ExportStatus::updateStatus($userId, 99);

        $fileName = 'export_'.time().'.csv';
        $fullPath = \Yii::getAlias('@backendUploads').'/export/'.$fileName;

        $excel = new SimpleExcel();
        $excel->insertWorksheet($worksheet);
        $excel->exportFile($fullPath, 'CSV');

        sleep(3);
        ExportStatus::updateStatus($userId, 100, '/uploads/export/'.$fileName);

        return self::EXIT_CODE_NORMAL;
    }

}
