<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\modules\user\widgets\authChoice;

use yii\authclient\widgets\AuthChoice;
use yii\helpers\Html;

/**
 * Class Widget
 * @package frontend\modules\user\widgets\authChoice
 */
class Widget extends AuthChoice
{
    /**
     * @var string
     */
    public $preLabel;

    /**
     * @var string
     */
    public $postLabel;

    public function renderMainContent()
    {
        $preLabel = $this->preLabel ? $this->preLabel : \Yii::t('loginForm', 'enter_with_social');
        $postLabel = $this->postLabel ? $this->postLabel : \Yii::t('loginForm', 'or_enter_personal_credentials');

        echo Html::tag('p', $preLabel);

        echo Html::beginTag('div', ['class' => 'btns-w']);
        foreach ($this->getClients() as $externalService) {
            switch ($externalService->getName()) {
                case 'google':
                    echo Html::a(Html::tag('span', 'google+'), $this->createClientUrl($externalService), ['class' => 'btn-round btn-round__red']);
                    break;
                case 'facebook':
                    echo Html::a(Html::tag('span', 'facebook'), $this->createClientUrl($externalService), ['class' => 'btn-round btn-round__light-blue']);
                    break;
                case 'vkontakte':
                    echo Html::a(Html::tag('span', 'vkontakte'), $this->createClientUrl($externalService), ['class' => 'btn-round btn-round__blue-vk']);
                    break;
            }
        }
        echo Html::endTag('div');

        echo Html::tag('p', $postLabel);
    }
}
