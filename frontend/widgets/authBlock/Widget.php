<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\widgets\authBlock;

/**
 * Class Widget
 *
 * @package frontend\widgets\authBlock
 */
class Widget extends \yii\base\Widget
{
    /**
     * @return string
     */
    public function run()
    {
        $view = \Yii::$app->user->isGuest ? 'guest' : 'authorized';

        return $this->render($view);
    }
}
