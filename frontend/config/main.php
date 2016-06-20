<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'chicardi-frontend',
    'basePath' => dirname(__DIR__),
    'language'=>'ru-RU',
    'bootstrap' => [
        'frontend\components\MobileDetectBootstrap',
        'log'
    ],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
        'sales' => [
            'class' => 'app\modules\sales\Module',
        ],
        'blog' => [
            'class' => 'app\modules\blog\Module',
        ],
        'common' => [
            'class' => 'app\modules\common\Module',
        ],
        'store' => [
            'class' => 'app\modules\store\Module',
        ],
        'banner' => [
            'class' => 'app\modules\banner\Module',
        ],
        'certificate' => [
            'class' => 'app\modules\certificate\Module',
        ],
        'favorite' => [
            'class' => 'app\modules\favorite\Module',
        ],
        'sitemap' => [
            'class' => 'himiklab\sitemap\Sitemap',
            'models' => [
                // your models
                'app\modules\store\models\StoreCategory',
                'app\modules\store\models\StoreProduct',
                'frontend\modules\common\models\PageSeo',
                'frontend\modules\blog\models\BlogArticle',
            ],
            'urls'=> [
                // your additional urls
                [
                    'loc' => '/',
                    'lastmod' => time(),
                    'changefreq' => \himiklab\sitemap\behaviors\SitemapBehavior::CHANGEFREQ_DAILY,
                    'priority' => 0.9
                ],
            ],
            'cacheKey' => 'sitemapCacheKey',
            'enableGzip' => false, // default is false
        ],
    ],
    'components' => [
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => ['js/jquery-1.7.1.min.js']
                ],
            ],
        ],
        'request' => [
            'class' => 'frontend\components\LangRequest'
        ],
        'response' => [
            'formatters' => [
                'html' => YII_ENV === 'chicardi.com'
                    ? 'app\components\SeoShieldFormatter'
                    : 'yii\web\HtmlResponseFormatter',
            ],
        ],
        'urlManager' => [
            'class' => 'frontend\components\LangUrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                ['pattern' => 'sitemap', 'route' => 'sitemap/default/index', 'suffix' => '.xml'],
                ['pattern' => '', 'route' => 'site/index'],
                ['pattern' => 'search', 'route' => 'store/catalog/search'],
                ['pattern' => 'showroom', 'route' => 'site/showroom'],

                ['pattern' => 'profile', 'route' => 'user/user/profile'],
                ['pattern' => 'profile/update', 'route' => 'user/user/profile-update'],
                ['pattern' => 'user/social-auth', 'route' => 'user/user/auth'],
                ['pattern' => 'user/full-fill-profile', 'route' => 'user/user/full-fill-profile'],
                ['pattern' => 'signup', 'route' => 'user/user/signup'],
                ['pattern' => 'user/upload', 'route' => 'user/user/upload'],
                ['pattern' => 'logout', 'route' => 'user/user/logout'],
                ['pattern' => 'login', 'route' => 'user/user/login'],
                ['pattern' => 'request-password-reset', 'route' => 'user/user/request-password-reset'],
                ['pattern' => 'reset-password', 'route' => 'user/user/reset-password'],
                ['pattern' => 'reset-password-done', 'route' => 'user/user/reset-password-done'],

                ['pattern' => 'favorite/add/<productId>', 'route' => '/favorite/favorite/add'],
                ['pattern' => 'favorite/remove/<productId>', 'route' => '/favorite/favorite/remove'],
                
                ['pattern' => 'delivery', 'route' => 'site/delivery'],
                ['pattern' => 'certificate', 'route' => 'certificate/certificate/index'],
                ['pattern' => 'gift-request', 'route' => 'common/gift-request/send'],
                ['pattern' => 'gift-request/thank', 'route' => 'common/gift-request/thank'],
                ['pattern' => 'callback', 'route' => 'common/callback/callback'],
                ['pattern' => 'product-subscribe', 'route' => 'store/product-subscribe/subscribe'],
                ['pattern' => 'subscribe', 'route' => 'common/news-subscribe/subscribe'],
                ['pattern' => 'sitemap', 'route' => 'site/sitemap'],
                ['pattern' => 'catalog/new', 'route' => 'store/catalog/new'],
                ['pattern' => 'cart/payment/<orderId>', 'route' => 'store/payment/pay'],
                ['pattern' => 'payment/validate', 'route' => 'store/payment/validate'],
                ['pattern' => 'payment/success', 'route' => 'store/payment/success-payment'],
                ['pattern' => 'payment/failed', 'route' => 'store/payment/failed-payment'],
                ['pattern' => 'catalog/top', 'route' => 'store/catalog/top'],
                ['pattern' => 'catalog/<alias>', 'route' => 'store/catalog/index'],
                ['pattern' => 'catalog', 'route' => 'store/catalog/index'],
                ['pattern' => 'catalog/product/<alias>', 'route' => 'store/product/view'],
                ['pattern' => 'cart/get/small', 'route' => 'store/cart/get-small-cart'],
                ['pattern' => 'cart/add/<id:\d+>', 'route' => 'store/cart/add'],
                ['pattern' => 'cart/remove/<id:\d+>', 'route' => 'store/cart/remove'],
                ['pattern' => 'cart/update/<id:\d+>', 'route' => 'store/cart/update'],
                ['pattern' => 'cart', 'route' => 'store/cart/show-cart'],
                ['pattern' => 'cart/checkout', 'route' => 'store/cart/checkout'],
                ['pattern' => 'cart/thank-for-the-order', 'route' => 'store/cart/order-done'],

                ['pattern' => 'blog/post-a-comment', 'route' => 'blog/blog/post-a-comment'],
                ['pattern' => 'blog/<rubric>', 'route' => 'blog/blog/index'],
                ['pattern' => 'blog', 'route' => 'blog/blog/index'],
                ['pattern' => 'blog/article/<alias>', 'route' => 'blog/blog/view'],

                ['pattern' => 'sales', 'route' => 'sales/sales/index'],
                ['pattern' => 'sales/view/<alias>', 'route' => 'sales/sales/view'],
                ['pattern' => '<alias>', 'route' => 'static-page/index'],
            ]
        ],
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/basic',
                'pathMap' => [
                    '@app/views' => ['@app/themes/basic'],
                    '@app/modules' => ['@app/themes/basic/modules']
                ]
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => '/login'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'cart' => [
            'class' => 'yz\shoppingcart\ShoppingCart',
            'cartId' => 'chicardiCart', //for session
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],

    'params' => $params,
];
