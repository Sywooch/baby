<?php

namespace backend\modules\import\controllers;

use backend\controllers\BackendController;
use backend\modules\import\models\UploadForm;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Class DefaultController
 *
 * @package backend\modules\import\controllers
 */
class DefaultController extends BackendController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $model = new UploadForm();

        if (\Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file && $model->validate()) {
                $model->file->saveAs(
                    \Yii::getAlias('@backendUploads').'/import/import_'  . time(). '.' . $model->file->extension
                );
            }
        }

        return $this->render('index', ['model' => $model]);
    }
}
