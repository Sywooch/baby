<?php
Yii::setAlias('commonModuleViews', '@frontend/themes/basic/modules/common/views');
Yii::setAlias('emailDir', '@frontend/emails');


\yii\base\Event::on(
    \yii\web\View::className(),
    \yii\web\View::EVENT_BEGIN_PAGE,
    function ($event) {
        $isAjax = Yii::$app->request->isAjax;
        if (!$isAjax && ($event->sender->context instanceof \frontend\controllers\FrontController)) {
            $event->sender->context->registerSeo();
        }
    }
);
