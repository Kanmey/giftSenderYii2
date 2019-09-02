<?php


namespace common\models;


use yii\base\Model;
use yii\httpclient\Client;

class Money extends Model
{
    private $_user;

    /**
     * Money constructor.
     */
    public function __construct()
    {

    }

    public static function chekMoney($money)
    {
        if ($money <= Money::getbalance()) {
            return 1;
        } else return 0;
    }

    public static function sendMoney()
    {
        //отправка денег на карту пользователя
        /**
         * запуск транзакции
         */
    }

    private static function getbalance()
    {

        $bankAccount = BankAccount::getBankAccount();


        /**
         * пример какого нибудь запроса к банку
         * $client = new Client();
         * $response = $client->createRequest()
         * ->setMethod('POST')
         * ->setUrl('https://bankdomain.com/api/1.0/getbalance')
         * ->setData(['api_key' => $bankAccount->api_key, 'account' => $bankAccount->api_key,'secret_key'=>$bankAccount->secret_key])
         * ->setOptions([
         * 'proxy' => 'tcp://proxy.example.com:5100',
         * 'timeout' => 15,
         * ])
         * ->send(); //тут должны быть реальные пути и переменные для обращения к реальному банку для запроса баланса счета
         */
        return rand(1000, 3000);//имитируем ответ банка, будто на счету какая то сумма
    }

}