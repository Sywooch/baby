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
    ],
    'params' => []
];
