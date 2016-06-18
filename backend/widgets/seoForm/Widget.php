<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\widgets\seoForm;

use yii\helpers\Html;

/**
 * Class Widget
 *
 * @package backend\widgets\seoForm
 */
class Widget extends \yii\base\Widget
{
    /**
     * @var \yii\db\ActiveRecord
     */
    public $model;

    /**
     * @return null|string
     */
    public function run()
    {
        /**
         * @var \notgosu\yii2\modules\metaTag\components\MetaTagBehavior $behavior
         */
        $behavior = $this->model->getBehavior('seo');
        if (!$behavior) {
            return null;
        }

        $languageList = $behavior->languages;
        $defaultLanguage = $behavior->defaultLanguage;

        return $this->render(
            'default',
            [
                'model' => $this->model,
                'languageList' => $languageList,
                'defaultLanguage' => $defaultLanguage
            ]
        );
    }

    /**
     * @param $seoTagName
     * @param $tagAttribute
     *
     * @return string
     * @throws \Exception
     */
    public function getInput($seoTagName, $tagAttribute)
    {
        switch ($seoTagName) {
            case 'title':
                return Html::activeTextInput($this->model, $tagAttribute, ['class' => 'form-control']);
                break;
            case 'description':
            case 'keywords':
                return Html::activeTextarea($this->model, $tagAttribute, ['class' => 'form-control']);
                break;
            default:
                return \backend\components\ImperaviEditor::widget(
                    [
                        'model' => $this->model,
                        'attribute' => $tagAttribute
                    ]
                );
        }
    }
}
