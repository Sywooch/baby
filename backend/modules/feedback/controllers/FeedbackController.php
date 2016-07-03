<?php

namespace backend\modules\feedback\controllers;

use backend\controllers\BackendController;
use backend\modules\feedback\models\Feedback;

class FeedbackController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return Feedback::className();
    }
}
