<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'errorHandler' => [
            // handling uncaught PHP exceptions, execution and fatal errors
            'class' => 'ladamalina\yii2_rollbar\WebErrorHandler',
        ],
    ],
];
