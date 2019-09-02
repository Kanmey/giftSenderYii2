<?php


namespace common\models;

use yii\db\ActiveRecord;
use yii\db\Expression;

class Gift extends ActiveRecord
{

    public static function getRandomGift()
    {
        return static::find()
            ->where(['>', 'count', 1])
            ->orderBy(new Expression('rand()'))
            ->one();
    }

    public static function removeFromStok($id)
    {

        $model = static::find()
            ->where(['id' => $id])
            ->one();
        if ($model != null) {
            $model->count -= 1;
            $model->save();
        }
    }


    public static function tableName()
    {
        return '{{gifts_stock}}';
    }

}