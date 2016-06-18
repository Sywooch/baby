<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\widgets\lang;

use common\models\Language;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class LangToggler
 *
 * @package frontend\widgets\lang
 */
class LangToggler extends \yii\bootstrap\Widget
{
    public $pClass;

    public function run()
    {
        $languages = (new Query())
            ->from(Language::tableName())
            ->all();

        // sorting languages for correct links display order
        if ($languages[0]['code'] != Language::getCurrent()->code) 
        {
            rsort($languages);
        }

        echo Html::beginTag('p', ['class' => $this->pClass]);
        foreach ($languages as $lang) {
            $url = $lang['code'] == Language::getDefaultLang()->code
                ?  \Yii::$app->getRequest()->getLangUrl()
                : '/' . $lang['code'] . \Yii::$app->getRequest()->getLangUrl();

            if (!$url) {
                $url = '/';
            }

            // changing locale for shown links
            if ($lang['locale'] == 'ru')
                $lang['locale'] = 'ru-ru';
            if ($lang['locale'] == 'uk')
                $lang['locale'] = 'ua-ua';

            //Alternate links for google
            $this->view->registerLinkTag([
                'rel' => 'alternate',
                'hreflang' => $lang['locale'],
                'href' => Url::to($url, true)
            ]);

            echo Html::a(
                $lang['label'],
                $url,
                ['class' => $lang['code'] == Language::getCurrent()->code ? '' : 'active']
            );
        }
        echo Html::endTag('p');
    }
}
