<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\modules\common\widgets\seoText;

use common\models\Language;
use notgosu\yii2\modules\metaTag\models\MetaTagContent;
use yii\helpers\ArrayHelper;

/**
 * Class Widget
 * @package frontend\modules\common\widgets\seoText
 */
class Widget extends \yii\base\Widget
{

    /**
     * Model to fetch tags for
     *
     * @var \yii\db\ActiveRecord
     */
    public $model;

    /**
     * @return null|string
     */
    public function run()
    {
        $pageNum = \Yii::$app->request->get('page');

        if ($pageNum) {
            return null;
        }

        $seoText = [];

        if ($this->model) {
            $model = $this->model;
            $langCode = Language::getCurrent()->locale;

            $seoTextForModel = MetaTagContent::find()
                ->where([MetaTagContent::tableName().'.model_id' => $model->id])
                ->andWhere([MetaTagContent::tableName().'.model_name' => (new \ReflectionClass($model))->getShortName()])
                ->joinWith(['metaTag', 'metaTagContentLangs'])
                ->all();

            if (!empty($seoTextForModel)) {
                foreach ($seoTextForModel as $seo) {
                    if (!empty($langCode)) {
                        $langValues = ArrayHelper::map($seo->metaTagContentLangs, 'lang_id', 'meta_tag_content');
                        $content = isset($langValues[$langCode]) ? $langValues[$langCode] : '';
                    } else {
                        $content = $seo->meta_tag_content;
                    }

                    if (!empty($content)) {
                        $seoText[$seo->metaTag->meta_tag_name] = $content;
                    }
                }
            }
        }

        if (!empty($seoText)) {
            return $this->render('default', compact('seoText'));
        }
    }
}
