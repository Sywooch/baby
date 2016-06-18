<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=chicardi',
            'username' => 'chicardi',
            'password' => 'rpWiI',
            'charset' => 'utf8',
            'enableSchemaCache' => true
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
