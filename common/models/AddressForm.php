<?php


namespace common\models;
use Yii;
use yii\base\Model;

class AddressForm extends Model
{
    public $address;
    public $firstName;
    public $lastName;
    public $phone;

    private $_user;

    /**
     * AddressForm constructor.
     * @param $_user
     */
    public function __construct()
    {   parent::__construct();
        $this->_user =Yii::$app->user;
        $model = User::find()
            ->where(['id' => Yii::$app->user->getId()])
            ->one();
        if($model->address!=null ){
            $this->address= $model->address;
        }
        $this->firstName= $model->firstName;
        $this->lastName= $model->lastName;
        $this->phone= $model->phone;


    }

    public function rules()
    {
        return [

            [['address', 'phone','lastName', 'firstName'], 'required'],

        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $model = User::find()
                ->where(['id' => Yii::$app->user->getId()])
                ->one();
            $model->address = $this->address;
            $model->firstName = $this->firstName;
            $model->lastName = $this->lastName;
            $model->phone = $this->phone;
            $model->getgift =1;
            $model->save();
        }
        return true;
    }
    public function checkAddress(){
        $model = User::find()
            ->where(['id' => Yii::$app->user->getId()])
            ->one();
        if($model->address!=null ){
           $this->address= $model->address;
        }

    }



}