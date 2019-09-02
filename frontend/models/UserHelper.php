<?php


namespace frontend\models;


use common\models\User;
use Yii;
use yii\db\ActiveRecord;

class UserHelper extends ActiveRecord
{

    public static function isReceivedGift()
    {
        $model = User::find()
            ->where(['id' => Yii::$app->user->getId()])
            ->one();
        return $model->getgift;
    }

    public static function receiveGift()
    {
        $model = User::find()
            ->where(['id' => Yii::$app->user->getId()])
            ->one();
        $model->getgift;
        $model->save();

    }


    public static function tableName()
    {
        return '{{user}}';
    }

}