<?php

use yii\i18n\DbMessageSource;

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'fileProcessor',
    ],
    'modules' => [
        'fileProcessor' => [
            'class' => '\metalguardian\fileProcessor\Module',
            'imageSections' => require(__DIR__ . '/image-resize-desktop-config.php')
        ],
    ],
    'components' => [
        'turbosms' => [
            'class' => 'avator\turbosms\Turbosms',
            'sender' => 'BABY',
            'login' => '',
            'password' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'i18n' => [
            'class' => Zelenin\yii\modules\I18n\components\I18N::className(),
            'languages' => ['ru'/*, 'uk'*/],
            'excludedCategories' => ['zelenin/modules/i18n', 'app'],
            'translations' => [
                '*' => [
                    'class' => DbMessageSource::className(),
                    'sourceMessageTable' => '{{%source_message}}',
                    'messageTable' => '{{%message}}',
                    'on missingTranslation' => ['Zelenin\yii\modules\I18n\Module', 'missingTranslation'],
                    'cache' => 'cache',
                    'cachingDuration' => 3600,
                    'enableCaching' => true
                ],
            ]
        ],
        'config' => [
            'class' => 'common\components\ConfigurationComponent'
        ],
        'postman' => [
            'class' => 'rmrevin\yii\postman\Component',
            'driver' => 'smtp',
            'default_from' => ['noreply@baby.com', 'baby'],
            'table' => '{{%email_queue}}',
            'view_path' => '/emails',
            'smtp_config' => [
                'host' => 'smtp.mandrillapp.com',
                'port' => 587,
                'auth' => true,
                'user' => '',
                'password' => '',
                'secure' => 'tls',
                'debug' => false,
            ]
        ],
    ],
    'params' => []
];
