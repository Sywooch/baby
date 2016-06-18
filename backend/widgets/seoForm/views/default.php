<?php
/**
 * Author: Pavel Naumenko
 * @var MetaTagContent[] $metaTagModelList
 * @var \yii\db\ActiveRecord $model
 * @var array $languageList
 * @var string $defaultLanguage
 */
use notgosu\yii2\modules\metaTag\models\MetaTagContent;
use yii\helpers\Html;

foreach ($model->seoData as $i => $data) {
    echo Html::beginTag('div', ['class' => 'row']);
    echo Html::beginTag('div', ['class' => 'col-sm-12']);
    echo Html::beginTag('div', ['class' => 'form-group']);

    echo Html::activeHiddenInput($model, 'seoData['.$i.'][meta_tag_id]');
    echo Html::activeHiddenInput($model, 'seoData['.$i.'][tagName]', ['value' => $model->seoData[$i]['tagName']]);
    if (!empty($languageList)) {
        foreach ($languageList as $lang) {
            if ($lang != $defaultLanguage) {
                echo Html::label($model->seoData[$i]['tagName'].'['.$lang.']', Html::getInputId($model, 'seoData['.$i.'][meta_tag_content_'.$lang.']'), ['class' => 'control-label']);
                echo $this->context->getInput($model->seoData[$i]['tagName'], 'seoData['.$i.'][meta_tag_content_'.$lang.']');
            } else {
                echo Html::label($model->seoData[$i]['tagName'].'['.$lang.']', Html::getInputId($model, 'seoData['.$i.'][meta_tag_content]'), ['class' => 'control-label']);
                echo $this->context->getInput($model->seoData[$i]['tagName'], 'seoData['.$i.'][meta_tag_content]');
            }
        }
    } else {
        echo Html::label($model->seoData[$i]['tagName'], Html::getInputId($model, 'seoData['.$i.'][meta_tag_content]'), ['class' => 'control-label']);
        echo $this->context->getInput($model->seoData[$i]['tagName'], 'seoData['.$i.'][meta_tag_content]');
    }

    echo Html::endTag('div');
    echo Html::endTag('div');
    echo Html::endTag('div');
}
