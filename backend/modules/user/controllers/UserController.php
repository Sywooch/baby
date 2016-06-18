<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\user\controllers;

use backend\controllers\BackendController;
use backend\modules\user\models\User;

/**
 * Class UserController
 *
 * @package backend\modules\user\controllers
 */
class UserController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return User::className();
    }

    public function actionExport()
    {
        $objPHPExcel = new \PHPExcel();
        $model = new User();

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
            /** @var User $row */
            $j = 0;
            foreach ($headers as $h) {
                $sheet->setCellValueExplicitByColumnAndRow($j, $rowNo, $row->{$h});
                $j++;
            }
            $rowNo++;
        }

        $objPHPExcel->setActiveSheetIndex(0);


        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=\"users.xlsx\"");
        header("Cache-Control: max-age=0");
        $writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save("php://output");
    }
}
