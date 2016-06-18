<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\widgets\additionalMenu;

use app\modules\certificate\models\Certificate;
use frontend\models\DummyModel;
use frontend\modules\sales\models\Sales;
use yii\widgets\Menu;

/**
 * Class Widget
 * @package frontend\widgets\additionalMenu
 */
class Widget extends \yii\base\Widget
{

    /**
     * @var bool
     */
    public $mobileVersion = false;

    public $menuClass = '';

    /**
     * @return string
     */
    public function run()
    {
        $items = [
            [
                'label' => \Yii::t('frontend', 'Gift certificates'),
                'url' => Certificate::getCertificateRoute(),
            ],
            [
                'label' => \Yii::t('frontend', 'Pay and delivery'),
                'url' => DummyModel::getDeliveryRoute(),
            ],
            [
                'label' => \Yii::t('frontend', 'Actions & discounts'),
                'url' => Sales::getViewAllSalesRoute(),
            ],
        ];

        return Menu::widget(
            [
                'items' => $items,
                'options' => [
                    'class' => $this->menuClass,
                ]
            ]
        );
    }
}
