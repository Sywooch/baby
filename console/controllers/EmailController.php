<?php
/**
 * Author: Pavel Naumenko
 */

namespace console\controllers;

use rmrevin\yii\postman\models\LetterModel;
use yii\console\Controller;

/**
 * Class EmailController
 * @package console\controllers
 */
class EmailController extends Controller
{
    public function actionSend()
    {
        LetterModel::cron($num_letters_per_step = 10);
    }
}
