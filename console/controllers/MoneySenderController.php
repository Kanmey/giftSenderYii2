<?php

namespace console\controllers;


use console\models\UsersAccounts;
use yii\console\Controller;
use common\models\BankRequest;

class MoneySenderController extends Controller
{
    public $numbers;

    public function actionSend()
    {

        echo "Вы действительно хотите отправить всем деньги?(yes/no)\n";
        $line = trim(fgets(STDIN));
        if ($line == "yes" || $line == "y") {
            $accounts = UsersAccounts::getAccounts($this->numbers);
            if ($accounts != null) {
                BankRequest::sendRequestForAll($accounts);

            } else  echo "Уже все пользователи получили свои призы";

        } else {
            echo "Ну и правильно";
        }

    }

    public function options($actionID)
    {
        return ['numbers'];
    }

    public function optionAliases()
    {
        return ['n' => 'numbers'];
    }

    public function actionIndex()
    {
        echo $this->numbers . "\n";
    }
}