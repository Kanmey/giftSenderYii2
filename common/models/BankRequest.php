<?php


namespace common\models;



use console\models\UsersAccounts;
use frontend\components\GiftSender;

class BankRequest
{


    public static function sendRequestForAll($userAccounts){


      foreach ($userAccounts as $account){
          $moneyGift=GiftSender::getRandomMoney();
              echo self::sendSingleRequest($account,$moneyGift, BankAccount::getBankAccount()->account);
      }

    }

    public static function sendSingleRequest($account,$moneyGift,$fromAccount){
        $response= HttpsServiceRequestBank::getRequestResponse($account,$moneyGift,$fromAccount);

        if($response) {
            UsersAccounts::findByAccountAndGetGift($account);
            return "деньги: ".$moneyGift." euro успешно были переданы на счет ".$account. "\n ";

        }


        else {
            return "деньги: ".$moneyGift." euro не были переданы на счет ".$account. "\n ";
        }
    }
}