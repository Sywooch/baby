<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\blog\controllers;

use backend\controllers\BackendController;
use backend\modules\blog\models\BlogArticle;
use yii\helpers\Json;

/**
 * Class BlogArticleController
 * @package backend\modules\blog\controllers
 */
class BlogArticleController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return BlogArticle::className();
    }

    /**
     * @return string
     */
    public function actionGetContentByType()
    {
        $typeId = \Yii::$app->request->post('type');

        if ($typeId) {
            return Json::encode([
                'append' => [
                    [
                        'what' => '.template-list',
                        'data' => $this->renderAjax('content', ['type' => $typeId])
                    ]
                ]
            ]);
        }
    }
}
