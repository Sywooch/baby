<?php
/**
 * @var $model ActiveRecord
 */

use yii\db\ActiveRecord;

foreach ($model->attributes() as $attribute){
    if (in_array($attribute, ['id', 'created', 'updated', 'model_name', 'model_id'])) {
        continue;
    }
    echo $model->getAttributeLabel($attribute) . ' : ' . $model->$attribute . '<br><br>';
}
