<?php
namespace app\modules\store\widgets\sortItems;

use app\modules\store\models\StoreCategory;


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
        $sort = \Yii::$app->request->get('sort');

        return $this->render('default', [
            'sort' => $sort
        ]);
    }
}
