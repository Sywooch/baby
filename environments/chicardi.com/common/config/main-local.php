<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=chicardi',
            'username' => 'root',
            'password' => 'sP#2p_31o!',
            'charset' => 'utf8',
            'enableSchemaCache' => true
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
        'rollbar' => [
            'environment' => 'chicardi.com', // you environment name
        ],
    ],
];
