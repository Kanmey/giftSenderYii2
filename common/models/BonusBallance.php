<?php


namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class BonusBallance extends ActiveRecord
{

    public static function updateUserBallance($balance)
    {
        $model = User::find()
            ->where(['id' => Yii::$app->user->getId()])
            ->one();
        $model->bonusballance += $balance;
        $model->getgift = 1;
        $model->save();
    }


    public static function tableName()
    {
        return '{{user}}';
    }

}