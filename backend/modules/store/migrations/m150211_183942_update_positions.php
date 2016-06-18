<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150211_183942_update_positions*/
class m150211_183942_update_positions extends Migration
{
    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        Yii::$app->db->createCommand()->update('{{%store_product}}', ['position' => new \yii\db\Expression('id')])->execute();
        Yii::$app->db->createCommand()->update('{{%store_product_type}}', ['position' => new \yii\db\Expression('id')])->execute();
        Yii::$app->db->createCommand()->update('{{%store_product_attribute}}', ['position' => new \yii\db\Expression('id')])->execute();
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
    }
}
