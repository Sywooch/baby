<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\controllers;

use common\models\Language;
use common\models\User;
use frontend\components\MetaTagRegistratorWithDefaults;
use frontend\components\PokuponChecker;
use frontend\models\DummyModel;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\web\Controller;

/**
 * Class FrontController
 * @package frontend\controllers
 */
class FrontController extends Controller
{
    /**
     * Class of the main layout div. It may change from page to page
     *
     * @var string
     */
    public $layoutDivClass = 'wrap';

    /**
     * Indicates to show or not catalog dropout menu with categories
     *
     * @var bool
     */
    public $showCatalogDropOutMenu = true;

    /**
     * Menu for each page. Can be changed in any action
     *
     * @var string $pageMenu
     */
    public $mobileMenu;

    /**
     * Contact phone
     *
     * @var string
     */
    public $contactPhone = '';

    /**
     * @var null|\yii\db\ActiveRecord
     */
    public $modelToFetchSeo = null;

    /**
     * ID to identifies the page for the default SEO settings
     *
     * @var null|integer
     */
    public $pageForSeo = null;

    public function init()
    {
        parent::init();
        date_default_timezone_set('Europe/Kiev');
        //Catalog menu is default
        $this->contactPhone = \Yii::$app->config->get('contact_phone');

        $this->checkPageForNoFollowUrls();
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $url = \Yii::$app->getRequest()->getUrl();
            $cameCaseInUrl = preg_match('|[A-Z]|', $url);
            $cartUrl = strpos('ordercreaterequest', $url);
            if ($cameCaseInUrl && $cartUrl !== FALSE) {
                return $this->redirect(strtolower($url));
            }

            $this->saveReturnUrl($action, $url);
            $this->checkUserProfileFilled($action);

            return true;
        }

        return false;
    }

    public function registerSeo()
    {
        if ($this->modelToFetchSeo && ($this->modelToFetchSeo instanceof ActiveRecord)) {
            MetaTagRegistratorWithDefaults::register($this->modelToFetchSeo, Language::getCurrent()->locale, $this->pageForSeo);
        }
    }

    protected function checkPageForNoFollowUrls()
    {
        $url = \Yii::$app->request->url;
        $noFollowParams = [
            'utm',
            'gclid=',
            'UAH',
            'RUR',
            'WMZ',
            'USD',
            '?on_page',
            '?sort',
            '?dir'
        ];
        foreach ($noFollowParams as $param) {
            if (strpos($url, $param)) {
                \Yii::$app->view->registerMetaTag([
                    'name' => 'robots',
                    'content' => 'noindex,nofollow'
                ]);

                break;
            }
        }
    }

    /**
     * @return \yii\web\Response
     */
    protected function checkUserProfileFilled(Action $action)
    {
        if (!\Yii::$app->user->isGuest
            && !\Yii::$app->user->identity->is_profile_filled
            && !in_array($action->id, ['full-fill-profile', 'logout'])
            && !\Yii::$app->request->isAjax) {
            return $this->redirect(DummyModel::getFullFillProfileLink());
        }
    }

    /**
     * @param Action $action
     * @param $url
     */
    protected function saveReturnUrl(Action $action, $url) {
        if (\Yii::$app->user->isGuest
            && !in_array($action->id, ['error', 'auth', 'login', 'signup'])
            && !\Yii::$app->request->isAjax) {
            \Yii::$app->user->setReturnUrl($url);
        }
    }
}
