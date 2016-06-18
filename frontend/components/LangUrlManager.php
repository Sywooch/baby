<?php
/**
 * Author: Pavel Naumenko
 */
namespace frontend\components;

use common\models\Language;
use yii\web\UrlManager;

/**
 * Class LangUrlManager
 *
 * @package frontend\components
 */
class LangUrlManager extends UrlManager
{
    /**
     * @inheritdoc
     */
    public function createUrl($params)
    {
        if (isset($params['lang_id'])) {
            //Если указан идентификатор языка, то делаем попытку найти язык в БД,
            //иначе работаем с языком по умолчанию
            $lang = Language::findOne($params['lang_id']);
            if ($lang === null) {
                $lang = Language::getDefaultLang();
            }
            unset($params['lang_id']);
        } else {
            //Если не указан параметр языка, то работаем с текущим языком
            $lang = Language::getCurrent();
        }

        //Получаем сформированный URL(без префикса идентификатора языка)
        $url = parent::createUrl($params);

        $code = $lang->code;

        //Если текущий язык совпадает с языком по умолчанию, то не добавляем его в URL
        if ($code == Language::getDefaultLang()->code) {
            return $url;
        }

        //Добавляем к URL префикс - буквенный идентификатор языка
        if ($url == '/') {
            return '/'.$code;
        } else {
            return '/'.$code . $url;
        }
    }
}
