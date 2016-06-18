<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\configuration\controllers;

use backend\controllers\BackendController;
use backend\modules\configuration\models\Configuration;
use common\models\Language;
use yii\helpers\Json;

/**
 * Class ConfigurationController
 * @package backend\modules\configuration\controllers
 */
class ConfigurationController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return Configuration::className();
    }

    /**
     * @return string
     */
    public function actionGetForm()
    {
        $model = new Configuration();
        $model->load(\Yii::$app->request->post());

        //For images and files clear value field for proper FPM work
        if (in_array(
            $model->type,
            [
                \common\models\Configuration::TYPE_FILE,
                \common\models\Configuration::TYPE_IMAGE,
            ]
        )
        ) {
            $model->value = null;
            foreach (Language::getLangList() as $key => $label) {
                if ($key != Language::getDefaultLang()->code) {
                    $model->{'value_'.$key} = null;
                }
            }
        }

        return Json::encode(
            [
                'replaces' => [
                    [
                        'what' => '.panel-body',
                        'data' => $this->renderPartial(
                                '//template/_form',
                                [
                                    'model' => $model,
                                    'action' => \Yii::$app->request->post('action', '')
                                ]
                            )
                    ]
                ]
            ]
        );
    }
}
