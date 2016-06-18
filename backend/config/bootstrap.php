<?php
\Yii::$container->set(
    'notgosu\yii2\modules\metaTag\components\MetaTagBehavior',
    function ($container, $params, $config) {
        $config['languages'] = array_keys(\common\models\Language::getLangList());

        return new \notgosu\yii2\modules\metaTag\components\MetaTagBehavior($config);
    }
);
