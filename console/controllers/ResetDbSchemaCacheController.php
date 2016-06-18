<?php
/**
 * Author: Pavel Naumenko
 */

namespace console\controllers;

use yii\console\Controller;

/**
 * Class ResestDBSchemaCacheController
 *
 * @package console\controllers
 */
class ResetDbSchemaCacheController extends Controller
{
    public function actionReset()
    {
        \Yii::$app->db->schema->refresh();
        echo "ok\n";
    }
}
