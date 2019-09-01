<?php


namespace common\models;


use http\Client;

class HttpsServiceRequestBank
{
    public static function getRequestResponse($account,$moneyGift,$bankourAccont){
        /**   $client = new Client();
         @var  $response
      * какой  либо запрос к банку., если будут разные банки, то разделение на разные модели.
      *   return $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('post')
            ->setUrl('https://superBank.ru/api/v2/clean/address')

            ->setHeaders(['Content-Type' => 'application/json'])
            ->addHeaders(['Authorization' => ''])
            ->addHeaders(['X-Secret' => ''])

            ->setData(['toAccount' => $account, 'summ'=>$moneyGift, 'fromAccount'=>$bankourAccont])
            ->send();

      */
        return 1;
    }
}