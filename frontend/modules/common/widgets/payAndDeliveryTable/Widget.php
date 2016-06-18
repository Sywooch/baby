<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\modules\common\widgets\payAndDeliveryTable;

use frontend\modules\common\models\PayAndDelivery;

/**
 * Class Widget
 * @package frontend\modules\common\widgets\payAndDeliveryTable
 */
class Widget extends \yii\base\Widget
{
    /**
     * @return string
     */
    public function run()
    {
        if (IS_TABLET) {
            $view = 'desktop';
        } elseif (IS_MOBILE) {
            $view = 'mobile';
        } else {
            $view = 'desktop';
        }

        $data = [];

        $items = PayAndDelivery::find()
            ->where(['visible' => 1])
            ->orderBy(['position' => SORT_DESC])
            ->all();

        if (empty($items)) {
            return null;
        }

        foreach ($items as $item) {
            if ($item->type_id == \common\models\PayAndDelivery::TYPE_DELIVERY) {
                $data['delivery'][] = $item;
            } else {
                $data['pay'][] = $item;
            }
        }

        return $this->render($view, compact('data'));
    }
}
