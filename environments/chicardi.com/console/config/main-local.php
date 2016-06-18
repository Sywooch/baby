<?php
return [
    'components' => [
        'errorHandler' => [
            // handling uncaught PHP exceptions, execution and fatal errors
            'class' => 'ladamalina\yii2_rollbar\ConsoleErrorHandler',
        ],
    ],
];
