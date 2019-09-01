<?php
namespace console\models;




use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class UsersAccounts extends ActiveRecord

{
    const ACTIVE_USER=10;
    const DONT_RECEIVE_GIFT=0;

    public static function getAccounts($numbers){
        $users= static::find()
                 ->where(['status' => self::ACTIVE_USER,
                        'getgift'=>self::DONT_RECEIVE_GIFT
                     ])
                 ->andWhere(['not',['account' => null ]])
                 ->orderBy('id')
                 ->limit($numbers)
                 ->all();


      return  ArrayHelper::getColumn($users, 'account');

     }

    public static function findByAccountAndGetGift($account){
        $model=static::find()
            ->where(['account' =>$account ])
            ->one();
        if($model!=null) {
            $model->getgift = 1;
            $model->save();
        }
    }
    public static function tableName()
    {
        return '{{user}}';
    }

}