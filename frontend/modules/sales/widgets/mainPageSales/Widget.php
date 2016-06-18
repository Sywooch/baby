<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\modules\sales\widgets\mainPageSales;

use frontend\modules\sales\models\Sales;

/**
 * Class Widget
 * @package frontend\modules\sales\widgets\mainPageSales
 */
class Widget extends \yii\base\Widget
{
    /**
     * @return string
     */
    public function run()
    {
        $sales = Sales::find()
            ->where(['visible' => 1])
            ->orderBy(['position' => SORT_DESC])
            ->limit(2)
            ->all();

        return $this->render('default', ['models' => $sales]);
    }
}
