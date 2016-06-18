<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\modules\sales\widgets\profileRandomSale;

use frontend\modules\sales\models\Sales;

/**
 * Class Widget
 *
 * @package frontend\modules\sales\widgets\profileRandomSale
 */
class Widget extends \yii\base\Widget
{
    /**
     * @return string
     */
    public function run()
    {
        $model = Sales::find()
            ->where(['visible' => 1])
            ->orderBy('RAND()')
            ->one();

        return $this->render('default', compact('model'));
    }
}
