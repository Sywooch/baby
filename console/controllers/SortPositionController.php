<?php
/**
 * Author: Pavel Naumenko
 */

namespace console\controllers;

use common\models\StoreProduct;
use yii\console\Controller;

/**
 * Class SortPositionController
 *
 * @package console\controllers
 */
class SortPositionController extends Controller
{
    public function actionSort()
    {
        $i = 0;
        foreach (StoreProduct::find()->each(10) as $product) {
            $productUpdated = false;

            if (!$product->new_position) {
                $productUpdated = true;
                $product->new_position = $product->position;
            }
            if (!$product->top_position) {
                $productUpdated = true;
                $product->top_position = $product->position;
            }
            if (!$product->top_category_position) {
                $productUpdated = true;
                $product->top_category_position = $product->position;
            }
            $product->save(false);


            if ($productUpdated) {
                $i++;
            }
        }

        echo "$i products was updated.";
    }
}
