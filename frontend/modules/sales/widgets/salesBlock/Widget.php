<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\modules\sales\widgets\salesBlock;

use frontend\modules\sales\models\Sales;

/**
 * Class Widget
 * @package frontend\modules\sales\widgets\salesBlock
 */
class Widget extends \yii\base\Widget
{
    /**
     * @var array|string
     */
    public $order = ['position' => SORT_DESC];

    /**
     * @var int
     */
    public $limit = 2;

    /**
     * @var string
     */
    public $view = 'default';

    /**
     * @var string|int
     */
    public $idNot = '';

    /**
     * @return string
     */
    public function run()
    {
        $sales = Sales::find()
            ->where(['visible' => 1])
            ->andWhere('id <> :id', [':id' => $this->idNot])
            ->orderBy($this->order)
            ->limit($this->limit)
            ->all();

        return $this->render($this->view, ['sales' => $sales]);
    }
}
