<?php
/**
 * Author: Pavel Naumenko
 */
use metalguardian\fileProcessor\helpers\FPM;

$user = Yii::$app->user->identity;
?>
<?php

echo $user->getAvatarImage(['class' => 'user-foto user-photo-profile', 'id' => 'user-foto']);
echo skeeks\widget\simpleajaxuploader\Widget::widget(
    [
        "clientOptions" =>
            [
                'button' => 'user-foto',
                'name' => 'avatar',
                'url' => \frontend\models\DummyModel::getProfileUploadUrl(),
                'queue' => false,
                'debug' => true,
                'responseType' => 'json',
                'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif'],
                'onComplete' => new \yii\web\JsExpression("function(filename, response, uploadBtn) {
                      if (!response) {
                          alert(filename + 'upload failed');
                          return false;
                      }
                      parseResponse(response);
                      this.enable();
                      }
                      "),
                'onError' => new \yii\web\JsExpression("function( filename, errorType, status, statusText, response, uploadBtn ) {
                        alert('Ошибка при загрузке фото');
                }"),
                'onExtError' => new \yii\web\JsExpression("function( filename, extension ) {
                        alert('Некорректный формат');
                }")
            ]
    ]
);
