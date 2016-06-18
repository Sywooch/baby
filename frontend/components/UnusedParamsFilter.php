<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\components;

use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * Class UnusedParamsFilter
 *
 * @package frontend\components
 */
class UnusedParamsFilter extends Behavior
{
    /**
     * @var array this property defines the allowed params for each action.
     * If an action is not listed all params are considered disallowed.
     *
     * For example,
     *
     * ~~~
     * [
     *   'create' => ['myParam', 'mySecondParam'],
     *   'update' => ['testParam'],
     *   'delete' => ['mySecondParam'],
     * ]
     * ~~~
     */
    public $actions = [];

    /**
     * @var array list of params that allowed at any action. You can set manually value of this property
     */
    public $allowedParams = [
        'utm_',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'goal',
        'fb_action_ids',
        'fb_action_types',
        'gclid',
        'pokupon_cid',
        'pokupon_uid',
        '_dmp_mark'
    ];

    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param ActionEvent $event
     *
     * @return mixed
     * @throws HttpException
     */
    public function beforeAction($event)
    {
        $allowedParams = $this->allowedParams;
        $requestParams = array_keys(\Yii::$app->request->queryParams);
        $action = $event->action->id;
        if ($action === 'error') {
            return $event->isValid;
        }

        if (isset($this->actions[$action]) && is_array($this->actions[$action])) {
            $allowedParams = ArrayHelper::merge($allowedParams, $this->actions[$action]);
        }

        $unusedParams = array_diff($requestParams, $allowedParams);

        if (!empty($unusedParams)) {
            $event->isValid = false;
            throw new HttpException(404, \Yii::t('frontend', 'Unknown params') . ' ' . join(', ', $unusedParams));
        }

        return $event->isValid;
    }
}
