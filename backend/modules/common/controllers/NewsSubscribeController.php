<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\common\controllers;

use backend\controllers\BackendController;
use backend\modules\common\models\NewsSubscribe;

/**
 * Class NewsSubscribeController
 * @package backend\modules\common\controllers
 */
class NewsSubscribeController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return NewsSubscribe::className();
    }

    public function actionExport()
    {
        $objPHPExcel = new \PHPExcel();
        $model = new NewsSubscribe();

        $headers = $model->getAttrsForExport();

        $labels = array();
        foreach ($headers as $header) {
            $labels[] = $model->getAttributeLabel($header);
        }

        $objPHPExcel->setActiveSheetIndex(0);

        $i = 0;
        foreach ($labels as $l) {
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($i, 1, $l);
            $i++;
        }

        $model->unsetAttributes();

        $rowNo = 2;
        $sheet = $objPHPExcel->getActiveSheet();
        foreach ($model::find()->each(20) as $row) {
            /** @var NewsSubscribe $row */
            $j = 0;
            foreach ($headers as $h) {
                $sheet->setCellValueExplicitByColumnAndRow($j, $rowNo, $row->{$h});
                $j++;
            }
            $rowNo++;
        }

        $objPHPExcel->setActiveSheetIndex(0);


        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=\"export.xlsx\"");
        header("Cache-Control: max-age=0");
        $writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save("php://output");
    }
}
