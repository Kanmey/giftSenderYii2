<?php
namespace frontend\components;
use common\models\Gift;
use Yii;
use common\models\Money;
use yii\base\InvalidRouteException;
use yii\console\Exception;

class GiftSender
{
    const indexK = 3;//индекс конвертации денег в баллы

    public $session;
    private function sendMoney(){
        /**генерация случайного числа,
         * верхняя граница входит в значения случайного числа на счеру банка
         * для имитации ограничения суммы на счету компании, выделенных на подарки
         *
         */
        $summ=$this->getRandomMoney();
        if(Money::chekMoney($summ)){
            $this->session['gift'] = ['count' => $summ,'type' => 1];
            return $param=['text'=>"ПОЗДРАВЛЯЕМ! Вы получили деньги ".$summ." euro"];
        }else{
            return $this->sendBonuses($summ);
        }

    }
    public function sendBonuses($summDeneg){
        if($summDeneg!=null){
            $summ=static::convertMoneyToBonuses($summDeneg);
        }else $summ=Mt_rand(100,2000);

        $this->session['gift'] = ['count' => $summ,'type' => 2];
        return $param=['text'=>"ПОЗДРАВЛЯЕМ! Вы получили ".$summ." бонусных баллов"];
    }
    private function sendGift(){
        $giftStuff=Gift::getRandomGift();
        $this->session['gift'] = ['count' => 1,'type' => 3];
        $this->session['giftStuff'] = ['id' =>$giftStuff->id,'name' => $giftStuff->name];
        return $param=['text'=>"ПОЗДРАВЛЯЕМ! Вы получили подарок: ".$giftStuff->name];


    }
    public function sendRandomGift(){
        $this->session = Yii::$app->session;
        $random=Mt_rand(1,3);
        try {
            switch ($random){
                case 1:
                    return Yii::$app->runAction('/site/confirm-buttons', $this->sendBonuses(null));
                    break;
                case 2:
                    return Yii::$app->runAction('/site/confirm-buttons', $this->sendMoney());
                    break;
                case 3:
                    return Yii::$app->runAction('/site/confirm-buttons', $this->sendGift());
                    break;
            }
        } catch (InvalidRouteException $e) {
        } catch (Exception $e) {
        }


    }

    public static function convertMoneyToBonuses($money){

        return static::indexK*$money;
    }
    /**
     * @assert (100) == 300
     */
    public static function getRandomMoney(){
        return  Mt_rand(100,2000);
    }
}