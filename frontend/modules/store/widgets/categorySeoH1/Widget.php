<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\modules\store\widgets\categorySeoH1;

use app\modules\store\models\StoreCategory;
use frontend\components\MetaTagRegistratorWithDefaults;
use yii\helpers\Html;

/**
 * Class Widget
 *
 * @package frontend\modules\store\widgets\categorySeoH1
 */
class Widget extends \yii\base\Widget
{
    /**
     * @return null|string
     */
    public function run()
    {
        $page = \Yii::$app->request->get('page');
        $alias = \Yii::$app->request->get('alias');

        if ($page && $alias) {
            $title = StoreCategory::find()->where('alias = :alias', [':alias' => $alias])->one();
            $title = $title ? $title->label : '';
            $pageTag = Html::tag('span', $page, ['class' => 'seo-page-indicator']);

            return Html::tag(
                'div',
                Html::tag('h1', \Yii::t('frontend', 'page') . ' ' . $pageTag . ' - '. $title),
                ['class' => 'seo-title']
            );
        }

        return null;
    }
}
