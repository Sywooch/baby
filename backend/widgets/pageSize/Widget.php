<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\widgets\pageSize;

/**
 * Class Widget
 * @package backend\widgets\pageSize
 */
class Widget extends \yii\base\Widget
{
    public $dataProvider;

    public function run()
    {
        if (!$this->dataProvider) {
            return null;
        }

        $pageSize = \Yii::$app->request->get('pageSize');
        if (is_null($pageSize)) {
            $pageSize = 20;
        }

        $this->dataProvider->pagination->pageSize = $pageSize;

        return $this->render('default', ['pageSize' => $pageSize]);
    }
}
