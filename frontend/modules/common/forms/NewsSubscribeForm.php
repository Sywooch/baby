<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\common\forms;

use common\models\NewsSubscribe;
use yii\base\Model;
use yii\helpers\Url;

/**
 * Class NewsSubscribeForm
 *
 * @package app\modules\common\forms
 */
class NewsSubscribeForm extends Model
{

    /**
     * @var string $email
     */
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            [
                ['email'],
                'unique',
                'targetClass' => NewsSubscribe::className(),
                'targetAttribute' => 'email',
                'message' => \Yii::t('frontend', 'You have already subscribed!')
            ]
        ];
    }

    public function save()
    {
        $model = new NewsSubscribe();
        $model->email = $this->email;
        $model->created = $model->modified = (new \DateTime())->format('Y-m-d H:i:s');
        $model->save(false);
        //Unset attributes
        $this->email = null;
    }

    /**
     * @return string
     */
    public static function getFormUrl()
    {
        return Url::toRoute('/common/news-subscribe/subscribe');
    }
}
