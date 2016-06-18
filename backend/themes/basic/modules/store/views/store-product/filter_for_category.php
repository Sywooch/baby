<?php
/**
 * Author: Pavel Naumenko
 *
 * @var integer $categoryId
 */
?>
<?php
echo (new \backend\modules\store\models\StoreProduct())->getFilterHtml($categoryId);

