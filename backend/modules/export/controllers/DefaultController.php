<?php

namespace backend\modules\export\controllers;

use backend\controllers\BackendController;
use common\models\ExportStatus;
use vova07\console\ConsoleRunner;
use yii\helpers\Json;

/**
 * Class DefaultController
 *
 * @package backend\modules\export\controllers
 */
class DefaultController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'get-export-status') {
            $this->enableCsrfValidation = false;
        }

        if (parent::beforeAction($action)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return ExportStatus::className();
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $view = 'default';
        $exportCols = \Yii::$app->request->post('exportCols');

        if ($exportCols) {
            ExportStatus::updateStatus(\Yii::$app->user->id, 0, '', $exportCols);
            $cr = new ConsoleRunner(['file' => '@app/../yii']);
            $cr->run('export/begin ' . \Yii::$app->getUser()->id);

            $view = 'export_progress';
        }

        return $this->render($view);
    }

    /**
     * @return string
     */
    public function actionGetExportStatus()
    {
        $status = ExportStatus::find()
            ->where('user_id=:uid', [':uid' => \Yii::$app->getUser()->id])
            ->one();

        if ($status) {
            return Json::encode(
                [
                    'percentage' => $status->status,
                    'isExported' => $status->is_exported ? true : false,
                    'fileHref' => $status->result_file_name
                ]
            );
        }
    }
}
