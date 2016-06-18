<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\blog\controllers;
use backend\controllers\BackendController;
use backend\modules\blog\models\Comment;

/**
 * Class CommentController
 * @package backend\modules\blog\controllers
 */
class CommentController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return Comment::className();
    }
}
