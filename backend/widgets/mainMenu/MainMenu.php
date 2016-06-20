<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\widgets\mainMenu;

use kartik\nav\NavX;
use yii\bootstrap\NavBar;
use yii\bootstrap\Widget;

/**
 * Class MainMenu
 * @package backend\widgets\mainMenu
 */
class MainMenu extends Widget
{
    public function run()
    {
        $menuItemsRightSide = $menuItems = [];

        NavBar::begin(
            [
                'brandLabel' => 'Главная',
                'brandUrl' => \Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]
        );
        if (\Yii::$app->user->isGuest) {
            $menuItemsRightSide[] = ['label' => 'Login', 'url' => ['/site/login']];
        } else {
            $menuItems[] = [
                'label' => 'Заказы',
                'url' => ['/store/store-order/index']
            ];

            $menuItems[] = [
                'label' => 'Каталог',
                'items' => [
                    [
                        'label' => 'Товары',
                        'url' => ['/store/store-product/index']
                    ],
                    [
                        'label' => 'Категории',
                        'url' => ['/store/store-category/index'],
                        /*'items' => [
                            [
                                'label' => 'Категории',
                                'url' => ['/store/store-category/index'],
                            ],
                            [
                                'label' => 'Баннеры для категорий',
                                'url' => ['/banner/category-banner/index'],
                            ],
                            [
                                'label' => 'Фильтры',
                                'url' => ['/store/store-product-filter/index'],
                            ]
                        ],*/
                    ],
                    /*[
                        'label' => 'Типы товаров',
                        'url' => ['/store/store-product-type/index']
                    ],
                    [
                        'label' => 'Атрибуты',
                        'url' => ['/store/store-product-attribute/index']
                    ],
                    [
                        'label' => 'Валюта',
                        'url' => ['/store/store-currency/index']
                    ],*/
                ]
            ];

           /* $menuItems[] = [
                'label' => 'Автоматизация',
                'items' => [
                    [
                        'label' => 'Экспорт',
                        'url' => ['/export/default/index']
                    ],
//                    [
//                        'label' => 'Импорт',
//                        'url' => '/import/default/index'
//                    ],
                ]
            ];*/

            $menuItems[] = [
                'label' => 'Сайт',
                'items' => [
                    [
                        'label' => 'Слайдер',
                        'url' => ['/banner/main-page-banner/index']
                    ],
                    [
                        'label' => 'Статические страницы',
                        'url' => ['/staticPage/default/index']
                    ],
                ]
            ];

            /*$menuItems[] = [
                'label' => 'Запросы/Подписки',
                'items' => [
                    [
                        'label' => 'Запросы обратного звонка',
                        'url' => ['/callback/callback/index']
                    ],
                    [
                        'label' => 'Запросы подбора подарка',
                        'url' => ['/common/gift-request/index']
                    ],
                    [
                        'label' => 'Подписки на на наличие товара',
                        'url' => ['/store/product-subscribe/index']
                    ],
                    [
                        'label' => 'Подписки на рассылку',
                        'url' => ['/common/news-subscribe/index']
                    ],
                ]
            ];*/

            $menuItems[] = [
                'label' => 'Модули',
                'items' => [
                    [
                        'label' => 'SEO',
                        'items' => [
                            [
                                'label' => 'Теги',
                                'url' => ['/seo/meta-tag/index']
                            ],
                            [
                                'label' => 'Заполнение тегов',
                                'url' => ['/seo/page-seo/index']
                            ],
                        ]
                    ],
                    /*[
                        'label' => 'Пользователи',
                        'url' => ['/user/user/index']

                    ],*/
                ]
            ];

            $menuItemsRightSide = [
                [
                    'label' => 'Переводы',
                    'url' => ['/i18n/default/index']
                ],
                [
                    'label' => 'Настройки',
                    'url' => ['/configuration/configuration/index'],
                ],
                [
                    'label' => 'Выйти (' . \Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ]
            ];

        }

        echo NavX::widget(
            [
                'activateParents' => true,
                'options' => [
                    'class' => 'navbar-nav'
                ],
                'items' => $menuItems,
            ]
        );

        echo NavX::widget(
            [
                'activateParents' => true,
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItemsRightSide,
            ]
        );
        NavBar::end();
    }
}
