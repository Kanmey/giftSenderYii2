<?php


namespace common\models;


use yii\db\ActiveRecord;

class BankAccount extends ActiveRecord
{

    public function __construct()
    {
    }

    public static function getBankAccount()
    {
        return BankAccount::find()
            ->where(['activity' => 1])
            ->one();
    }

    public static function tableName()
    {
        return '{{%bank_account}}';
    }


}