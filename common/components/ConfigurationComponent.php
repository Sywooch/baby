<?php
/**
 * Author: Pavel Naumenko
 */

namespace common\components;

use common\models\Configuration;
use common\models\ConfigurationLang;
use common\models\Language;
use metalguardian\fileProcessor\helpers\FPM;
use yii\base\Component;
use yii\db\Query;

/**
 * Class ConfigurationComponent
 *
 * @package common\components
 */
class ConfigurationComponent extends Component
{
    /**
     *
     * @var string
     */
    public $cacheId = 'cache';

    /**
     * Cache expire time
     *
     * @var int
     */
    public $cacheExpire = 2592000; // 30 days

    /**
     * Config items
     *
     * @var array
     */
    protected $_configs = [];


    /**
     * Get config
     *
     * @param $key
     * @param bool $force do not use cache
     *
     * @return int|null|string
     */
    public function get($key, $force = false)
    {
        $langCurrentCode = Language::getCurrent()->code;

        $query = $langCurrentCode == Language::getDefaultLang()->code
            ? (new Query())
                ->from(Configuration::tableName())
                ->select(['config_key', 'value', 'type'])
                ->where(['config_key' => $key])
            : (new Query())
                ->from(Configuration::tableName())
                ->innerJoin(ConfigurationLang::tableName(), 'model_id = id')
                ->select(['config_key', ConfigurationLang::tableName().'.value AS value', 'type'])
                ->where(['config_key' => $key])
                ->andWhere([ConfigurationLang::tableName().'.lang_id' => Language::getCurrent()->locale]);


        $value = null;
        if ($force) {
            $config = $query->one();
            $value = $config ? $this->getValue($config) : null;
        } elseif (isset($this->_configs[$key])) {
            $value = $this->getValue($this->_configs[$key]);
        } else {
            //TODO Add cache
            $config = $query->one();
            $value = $config ? $this->getValue($config) : null;
        }

        return $value;
    }

    /**
     * Return value by type
     *
     * @param $array
     *
     * @return int|null|string
     */
    protected function getValue($array)
    {
        $value = null;
        switch ($array['type']) {
            case Configuration::TYPE_STRING:
            case Configuration::TYPE_HTML:
            case Configuration::TYPE_TEXT:
                $value = $array['value'];
                break;
            case Configuration::TYPE_INTEGER:
            case Configuration::TYPE_BOOLEAN:
                $value = (int)$array['value'];
                break;
            case Configuration::TYPE_FLOAT:
                $value = (float)$array['value'];
                break;
            case Configuration::TYPE_FILE:
            case Configuration::TYPE_IMAGE:
                $value = FPM::originalSrc((int)$array['value']);
                break;
        }
        return $value;
    }
}
