<?php

/* @var $this yii\web\View */

$this->title = 'Раздатчик призов';

use yii\bootstrap\ActiveForm;
use yii\helpers\Html; ?>
<div class="site-index">

    <div class="jumbotron">
        <h1>ПОЗДРАВЛЯЕМ!</h1>

        <p class="lead">Вы выйграли приз.</p>


    </div>

    <div class="body-content">

        <div class="row">
            <p>Пожалуйста, введите логин и пароль для авторизации, чтобы забрать свой приз:</p>
            <div class="row">
                <div class="col-lg-5">
                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <div style="color:#999;margin:1em 0">
                        Если вы забыли пароль вы можете его <?= Html::a('сбросить', ['site/request-password-reset']) ?>.
                        <br>
                        Нет аккаунта? <?= Html::a('зарегистрируйся', ['site/signup']) ?>.
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>


        </div>

    </div>
</div>
