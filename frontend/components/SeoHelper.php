<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\components;

/**
 * Class SeoHelper
 *
 * @package frontend\components
 */
class SeoHelper
{
    /**
     * @param $productName
     *
     * @return string
     */
    public static function getCatalogImageAlt($productName)
    {
        return $productName . ' - catalog';
    }

    /**
     * @param $productName
     * @param $counter
     *
     * @return string
     */
    public static function getProductImagesAlt($productName, $counter)
    {
        return $productName . ' - фото№' . $counter;
    }
}
