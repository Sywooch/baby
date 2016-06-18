<?php

namespace common\helpers;

use yii\helpers\VarDumper;

/**
 * Class Dump
 * @package common\helpers
 */
class Dump extends VarDumper
{
    /**
     * @inheritdoc
     */
    public static function dump($var, $exit = 1, $depth = 10, $highlight = true)
    {
        parent::dump($var, $depth, $highlight);
        if ($exit) {
            exit();
        }
    }
}
