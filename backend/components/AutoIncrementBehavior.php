<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\components;
use yii\base\Behavior;
use yii\base\UserException;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * Class AutoIncrementBehavior
 * @package backend\components
 */
class AutoIncrementBehavior extends Behavior
{
    /**
     * @var array
     */
    public $fields = [];

    public function init()
    {
        parent::init();

        if (!is_array($this->fields)) {
            throw new UserException(\Yii::t('app', '`fields` should be an array!'));
        }

        if (empty($this->fields)) {
            throw new UserException(\Yii::t('app', '`fields` should have at least one element'));
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_VALIDATE => 'afterValidate',
        ];
    }

    /**
     * @param $event
     */
    public function afterValidate($event)
    {
        /**
         * @var ActiveRecord $owner
         */
        $owner = $this->owner;

        if ($owner->isNewRecord) {
            foreach ($this->fields as $field) {
                $owner->$field = $owner->$field
                    ? $owner->$field
                    : (new Query())
                        ->from($owner::tableName())
                        ->max($field) + 1;
            }
        }
    }
}
