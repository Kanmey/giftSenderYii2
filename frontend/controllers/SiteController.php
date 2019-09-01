<?php
namespace frontend\controllers;

use common\models\AddressForm;
use common\models\BankAccount;
use common\models\BankRequest;
use frontend\models\UserHelper;
use common\models\BonusBallance;
use common\models\Gift;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use frontend\components\GiftSender;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{



    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['getgift'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
     //return $this->render('index');

        if (!Yii::$app->user->isGuest) {


            if(UserHelper::isReceivedGift()==1){
                Yii::$app->session->setFlash('welcome', 'Вы уже получили подарок' );
                return $this->actionThanksAndWelcome();
            }else{
                return $this->render("getgift");
            }

        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }
    public function  actionGetgift(){

        if (Yii::$app->user->isGuest) {
            return $this->goHome();

        }

        return $this->render('getgift');
    }


    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {


        if (!Yii::$app->user->isGuest) {
            return $this->goHome();

        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();


        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    public function actionGifts(){
        $giftSender=new GiftSender();
        if (Yii::$app->request->isAjax) {
            return $giftSender->sendRandomGift();
        }
    }
    public function actionFormAdress(){
        $model=new AddressForm();
        $formData = Yii::$app->request->post();

        if($model->load($formData)&& $model->validate() && $model->save() ){
            $model->attributes = $formData;
            Yii::$app->session->setFlash('success', 'Подарок будет направлен по данному адресу, 
            наш менеджер вам перезвонит');
            $this->actionSendToManagetMail();
            Gift::removeFromStok(Yii::$app->session['giftStuff']['id']);
            return $this->actionThanksAndWelcome();

        }

        return $this->render('formAdress', [
            'model' => $model,
        ]);
    }
    public function actionFormCard(){
        $model=new CardForm();
        $formData = Yii::$app->request->post();
        if (Yii::$app->request->isPost) {

            $model->attributes = $formData;

            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Registered!');
            }
        }
        return $this->render('formAdress', [
            'model' => $model,
        ]);
    }
    public function actionThanksAndWelcome(){
        return $this->render('tnxAndWelcome');
    }

    public function actionGoToPay($sum) {

    }
    public function actionConfirmButtons($text){

        Yii::$app->view->params['message'] = $text;
        return $this->render('confirmButtons');
    }

    public function actionApplyGift(){
        $type = Yii::$app->session['gift']['type'];
        if($type==2){
            BonusBallance::updateUserBallance(Yii::$app->session['gift']['count']);
            UserHelper::receiveGift();
            Yii::$app->session->setFlash('welcome', 'Ваш бонусный балланс был увеличен на сумму.'.Yii::$app->session['gift']['count'] );
            return $this->actionThanksAndWelcome();
        }elseif($type==3){
            return $this->actionFormAdress();
        }elseif($type==1){
           // UserHelper::receiveGift();
            /**представим что мы уже знаем аккаунт пользователя на который перекинуть деньги.
             */
            try {
                $response =BankRequest::sendSingleRequest(Yii::$app->user->getIdentity()->account, Yii::$app->session['gift']['count'], BankAccount::getBankAccount()->account);
                if($response)
                Yii::$app->session->setFlash('welcome', 'Деньги были отправлены вам на счет' );
                else    Yii::$app->session->setFlash('welcome', 'Возникли неполадки во время отправки денег, мы обязательно  с этим разберемся' );
            } catch (\Throwable $e) {
                Yii::$app->session->setFlash('welcome', 'Мы отправим вам деньги как только узнаем ваш счет' );
            }
            return $this->actionThanksAndWelcome();
            // return $this->actionGoToPay();
        }

    }
    public function actionApplyConvertToBonus(){
        $bonusballance=GiftSender::convertMoneyToBonuses(Yii::$app->session['gift']['count']);
        BonusBallance::updateUserBallance($bonusballance);
        Yii::$app->session->setFlash('welcome', 'Ваш бонусный балланс был увеличен на сумму  '.$bonusballance );
        return $this->actionThanksAndWelcome();


    }
    public function actionDenyGift(){

        Yii::$app->session->setFlash('welcome', 'Вы отказались от подарка, но вы можете всегда вернуться и получить его' );
        return $this->actionThanksAndWelcome();


    }




    public function actionSendToManagetMail()
    {
        $result = Yii::$app->mailer->compose()
            ->setFrom('test.manager@gmail.com')
            ->setTo('test_manager@gmail.com')
            ->setSubject('Подарите пользователю подарок ')
            ->setTextBody('Подарите пользователю '.Yii::$app->user->getIdentity()->username." ".Yii::$app->session['giftStuff']['name'])
            ->setHtmlBody('<b>\'Подарите пользователю \'.Yii::$app->user->getIdentity()->username." ".Yii::$app->session[\'giftStuff\'][\'name\']</b>')
            ->send();


    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
