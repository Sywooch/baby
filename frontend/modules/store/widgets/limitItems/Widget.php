<?php
namespace app\modules\store\widgets\limitItems;


/**
 * Class Widget
 *
 * @package app\modules\store\widgets\storeCategoryMenu
 */
class Widget extends \yii\base\Widget
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $pageSize = (int) \Yii::$app->request->get('show');
        if (!$pageSize || $pageSize > 100) {
            $pageSize = 15;
        }
        $variants = [15, 25, 50, 75, 100];

        return $this->render('default', [
            'pageSize' => $pageSize, 
            'variants' => $variants,
        ]);
    }
}
