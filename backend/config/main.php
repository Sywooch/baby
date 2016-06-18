<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'chicardi-backend',
    'basePath' => dirname(__DIR__),
    'language' => 'ru',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'user' => [
            'class' => 'backend\modules\user\Module',
        ],
        'seo' => [
            'class' => 'backend\modules\seo\Module',
        ],
        'language' => [
            'class' => 'backend\modules\language\LanguageModule',
        ],
        'sales' => [
            'class' => 'backend\modules\sales\Module',
        ],
        'blog' => [
            'class' => 'backend\modules\blog\Module',
        ],
        'common' => [
            'class' => 'backend\modules\common\Module',
        ],
        'callback' => [
            'class' => 'backend\modules\callback\Callback',
        ],
        'store' => [
            'class' => 'backend\modules\store\StoreModule',
        ],
        'export' => [
            'class' => 'backend\modules\export\Export',
        ],
        'import' => [
            'class' => 'backend\modules\import\Import',
        ],
        'banner' => [
            'class' => 'app\modules\banner\Module',
        ],
        'configuration' => [
            'class' => 'backend\modules\configuration\Configuration',
        ],
        'i18n' => Zelenin\yii\modules\I18n\Module::className()
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/basic',
                'baseUrl' => '/themes/basic',
                'pathMap' => [
                    '@app/views' => ['@app/themes/basic'],
                    '@app/modules' => ['@app/themes/basic/modules']
                ]
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
