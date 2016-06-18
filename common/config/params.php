<?php
return [
    'user.passwordResetTokenExpire' => 3600,
    'supportEmail' => 'videoller@gmail.com',
    "yii.migrations" => [
        '@backend/modules/user/migrations',
        '@backend/modules/language/migrations',
        '@backend/modules/store/migrations',
        '@backend/modules/export/migrations',
        '@backend/modules/banner/migrations',
        '@backend/modules/configuration/migrations',
        '@vendor/notgosu/yii2-meta-tag-module/migrations',
        '@backend/modules/callback/migrations',
        '@backend/modules/common/migrations',
        '@backend/modules/blog/migrations',
        '@backend/modules/sales/migrations',
        '@vendor/metalguardian/yii2-file-processor-module/src/migrations',
        '@vendor/rmrevin/yii2-postman/migrations/',
        '@vendor/avator/yii2-turbosms/migrations/',
        '@Zelenin/yii/modules/I18n/migrations'
    ],
];
